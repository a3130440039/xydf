<?php
namespace app\lib\command;


use app\admin\model\Order;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class OnPayThreeMinute extends Command
{
    protected function configure()
    {
        $this->setName('OnPayThreeMinute')->setDescription('Here is OnPayThreeMinute');
    }

    public function execute(Input $input, Output $output)
    {

        $page = 1;
        while (true) {
            //3分钟以前 4分钟内的订单
            $end = time() - 50 * 3;
            $start = time() - 60 * 4;

            $list = Order::where(['order_status' => 0])
                ->where('ctime', '>', $start)
                ->where('ctime', '<', $end)
                ->order('id', 'desc')->page($page)
                ->limit(50)->select();

            if (count($list) == 0) break;

            foreach ($list as $order) {
            	unset($orderModel);
                $orderModel = new Order();
                $orderModel->getAndUpdateOrderByOrderSN($order->order_sn, true);

            }

            //sleep(1);
            $page++;
        }
    }
}