<?php
namespace app\lib\command;


use app\admin\model\Order;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class OnPayFiveMinute extends Command
{
    protected function configure()
    {
        $this->setName('OnPayFiveMinute')->setDescription('Here is OnPayFiveMinute');
    }

    public function execute(Input $input, Output $output)
    {

        $page = 1;
        while (true) {
            //五分钟以前 10分钟以内的订单
            $end = time() - 55 * 4;
            $start = time() - 60 * 10;

            $list = Order::where(['order_status' => 0])
                ->where('ctime', '>', $start)
                ->where('ctime', '<', $end)
                ->order('id', 'desc')->page($page)
                ->limit(50)->select();

            if (count($list) == 0) break;

            foreach ($list as $order) {
            	unset($orderModel);
                $orderModel = new Order();
                $result = $orderModel->getAndUpdateOrderByOrderSN($order->order_sn, true);
                //file_put_contents("five_minute.txt","\nOnPayFiveMinute5分钟通知：".date('Y-m-d H:i:s')."\n".var_export($order,true),FILE_APPEND);
                //file_put_contents("five_minute.txt","\nOnPayFiveMinute5分钟通知返回通知：".date('Y-m-d H:i:s')."\n".var_export($result,true),FILE_APPEND);

            }

            sleep(1);
            $page++;
        }
    }
}