<?php

namespace app\admin\model;

use app\common\model\MoneyLog;
use app\common\model\ScoreLog;
use think\Config;
use think\Db;
use think\Exception;
use think\Model;
use app\admin\model\Shop;

class Order extends Model
{

    // 表名
    protected $name = 'order';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'utime';
    // 追加属性
    protected $append = [

    ];

    public function getOriginData()
    {
        return $this->origin;
    }

    protected static function init()
    {

    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }

    public function group()
    {
        return $this->belongsTo('Admin', 'admin_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
    public function groups()
    {
        return $this->belongsTo('Shop', 'shop_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function shop()
    {
        return $this->belongsTo('Shop', 'shop_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function getAndUpdateOrderByOrderSN($order_sn, $is_command=false)
    {
        $order = $this->with('shop')->where(['order_sn'=>$order_sn])->find();
        $admin = $this->with('group')->where(['order_sn'=>$order_sn])->find();
        if (!$order){
            return ['status'=>-1,'data'=>[],'list'=>[]];
        }

        //unset($mobileClient);
        //$mobileClient = new MobileClient($order, $is_command);
        try {
            $result = $this->getOrderInfo($order->shop->token , $order['tb_order_sn']);
        } catch (Exception $e) {
            if (! $is_command) {
                return ['status'=>-1,'data'=>['msg'=>$e->getMessage()],'list'=>[]];
            }
            $result = ['status' => '交易已失败'];
        }
        $result = json_decode($result);
        if($result->Error_Code == 0 && $result->IsSuccess){
            $payTime = $result->Data->PayTime;
            if ($payTime != null) {
                $order->order_status = 1;
                $order->pay_time = strtotime($payTime);
                $order->pay_date = $payTime;
                $order->utime = time();
                $order->save();
                //exit('no null');
                if ($order->order_status = 1) {
                    if ($order->is_success == 0) {
                        Db::startTrans();
                        try {
                            $shopid = $order->shop_id;
                            $shop = Shop::get(['id' => $shopid]);
                            if($shop){
                                $shop->deal_num += $order->order_sum;
                                $shop->save();
                            }
                            $order->is_success = 1;
                            $result = $order->save();

                            if ($result) {
                                // 提交事务
                                Db::commit();
                                if (preg_match('/^https?:\/\//', $order->callback_url)) {
                                    $app_secret = $admin->group->app_secret;
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
                            } else {
                                \Log::record('订单已notify', 'error');
                                Db::rollback();
                            }

                        } catch (Exception $e) {
                            // 回滚事务
                            Db::rollback();
                            throw $e;
                        }
                    }
                }
            }
            //exit('null');
        }
        //print_r($admin->group->app_secret);exit();


        // 缓存 最新 的订单列表
        //return ['status'=> 1,'data'=>$result,'list'=>$order];
    }

    private function getOrderInfo($accessToken, $tid){
        $appSecret = Config::get('agiso')['appSecret'];
        $url = Config::get('agiso')['tradeUrl'];

        //业务参数
        $params = array();
        $params['tid'] = $tid;
        $params['timestamp'] = time();
        $params['sign'] = self::sign($params,$appSecret);

        //设置Header
        $headers[] = "Authorization: Bearer ".$accessToken;
        $headers[] = "ApiVersion: 1";

        return self::post($url, $params, $headers);
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

    private function post($url, $params, $headers='ApiVersion: 1'){
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        if (version_compare(phpversion(), '5.4.0', '<')) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 1);
        } else {
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 2);
        }
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
        curl_setopt($ci, CURLOPT_POST, TRUE);
        curl_setopt($ci, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ci, CURLOPT_URL,$url);
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
        $response = curl_exec($ci);
        curl_close ($ci);
        return $response;
    }


}
