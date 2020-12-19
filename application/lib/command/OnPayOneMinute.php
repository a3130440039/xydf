<?php
namespace app\lib\command;


use app\admin\model\Order;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class OnPayOneMinute extends Command
{
    protected function configure()
    {
        $this->setName('OnPayOneMinute')->setDescription('Here is OnPayOneMinute');
    }

    public function execute(Input $input, Output $output)
    {

        $page = 1;
        while (true) {
            //1分钟以内的订单
            $start = time() - 60 * 1;
			
            $list = Order::where(['order_status' => 0])->where('ctime', '>', $start)->order('id', 'asc')->page($page)->limit(50)->select();
            print_r($list);
            if (count($list) == 0) break;
           
        	foreach ($list as $order) {
                unset($orderModel);
                $orderModel = new Order();
                $result = $orderModel->getAndUpdateOrderByOrderSN($order->order_sn, true);
                //file_put_contents("one_minute.txt","\nOnPayOneMinute1分钟通知：".date('Y-m-d H:i:s')."\n".var_export($order,true),FILE_APPEND);
                //file_put_contents("one_minute.txt","\nOnPayOneMinute1分钟通知返回通知：".date('Y-m-d H:i:s')."\n".var_export($result,true),FILE_APPEND);
            }
            //sleep(1);
            $page++;
            	
            

            
        }
    }
}