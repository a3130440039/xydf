<?php
/**
 * Created by PhpStorm.
 * User: yin
 * Date: 2019/5/6
 * Time: 3:09 AM
 */

namespace app\lib\command;


use app\lib\pinduoduo\Tools;
use app\system\model\Orders;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\Command;
use think\console\Input;

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
            $start = date('Y-m-d H:i:s', time() - 60 * 1);
			
            $list = Orders::where(['status' => Orders::UNPAY])->where('ctime', '>', $start)->order('id', 'asc')->page($page)->limit(50)->select();
            if($list->isEmpty()) break;
           
        	foreach ($list as $order) {
                unset($orderModel);
                $orderModel = new Orders();
                $result = $orderModel->getAndUpdateOrderByOrderSN($order->order_sn, true);
                //file_put_contents("one_minute.txt","\nOnPayOneMinute1分钟通知：".date('Y-m-d H:i:s')."\n".var_export($order,true),FILE_APPEND);
                //file_put_contents("one_minute.txt","\nOnPayOneMinute1分钟通知返回通知：".date('Y-m-d H:i:s')."\n".var_export($result,true),FILE_APPEND);
            }
            //sleep(1);
            $page++;
            	
            

            
        }
    }
}