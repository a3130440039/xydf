<?php
namespace app\lib\command;


use app\admin\model\Order;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class OnPayTwoMinute extends Command
{
    protected function configure()
    {
        $this->setName('OnPayTwoMinute')->setDescription('Here is OnPayTwoMinute');
    }

    public function execute(Input $input, Output $output)
    {

        $page = 1;
        while (true) {
            //1分钟以前 2分钟内的订单
            $end = time() - 55 * 1;
            $start = time() - 60 * 2;

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