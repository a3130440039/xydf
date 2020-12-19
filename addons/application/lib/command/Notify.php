<?php
namespace app\lib\command;


use app\admin\model\Order;
use app\admin\model\Admin;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Notify extends Command
{
    protected function configure()
    {
        $this->setName('Notify')->setDescription('Here is Notify');
    }

    protected function execute(Input $input, Output $output)
    {
        $page = 1;
        while (true) {
          	unset($orderModel);
            $orderModel = new Order();
            $list = $orderModel->where(['order_status' => ['=',1]])->where('notify_number', '<', 6)->where(['notify_status'=>2])->order('id', 'asc')->page($page)->limit(50)->select();
            if (count($list) == 0) break;
            // 通知发送时间 1分钟 2分钟 3分钟 5分钟 10分钟
            foreach ($list as $order) {
                switch ( $order['notify_number'] ){
                    case 1:
                        if( $order['notify_time']+60 <= time() ){
                            $this->send($order);
                        }
                        break;
                    case 2:
                        if( $order['notify_time']+(60*2) <= time() ){
                            $this->send($order);
                        }
                        break;
                    case 3:
                        if( $order['notify_time']+(60*3) <= time() ){
                            $this->send($order);
                        }
                        break;
                    case 4:
                        if( $order['notify_time']+(60*5) <= time() ){
                            $this->send($order);
                        }
                        break;
                    case 5:
                        if( $order['notify_time']+(60*10) <= time() ){
                            $this->send($order);
                        }
                        break;
                }
            }

            //sleep(1);
            $page++;
        }
    }
	
  	public function sendOrder($order) {
        $this->send($order);
    }
  
    /**
     * 发送通知
     */
    private function send($order){
        $order = Order::get(['order_sn' => $order['order_sn']]);
        $admin_id = $order->admin_id;
        $admin = Admin::get(['id' => $admin_id]);
        $app_secret = $admin->app_secret;
        //file_put_contents("notify.txt","\nnotify通知：".date('Y-m-d H:i:s')."\n".var_export($order,true),FILE_APPEND);
        $rs = $this->notifyUrl($order->callback_url,
            [
                'callbacks' => 'CODE_SUCCESS',
                'total' => $order->order_sum,
                'paytime' => $order->pay_time,
                'order_sn' => $order->part_sn,
            ],$app_secret);
        if (trim($rs) == 'success') {
            $order->notify_status = 1;
            $order->notify_time = time();
            $order->notify_number += 1;
            $order->save();
        }
    }

    private function notifyUrl($callback, $params, $app_secret){
        //print_r($callback);print_r($params);exit();
        $params['sign'] = $this->sign($params, $app_secret);

        return self::notifyPost($callback, $params);
    }

    // 簽名方法
    private function sign($args,$client_secret)
    {
        ksort($args);
        $str='';
        foreach ($args as $key => $value)
        {
            $str .= ($key . $value);
        }
        //头尾加入AppSecret
        $str = $client_secret . $str . $client_secret;
        $encodeStr = md5($str);
        return $encodeStr;
    }

    private function notifyPost($url, $params, $headers=[], $cookieJar='')
    {
        //print_r($url);
        //print_r($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        if ($cookieJar) curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        $result = curl_exec($ch);
        curl_close($ch);
        /*echo '返回结果:';
        print_r($result);
        echo '--结束';*/
        return $result;

    }

}