<?php

namespace app\api\controller;

use app\admin\model\Order;
use app\common\controller\Api;
use think\Config;
use think\Exception;
use think\Log;


/**
 * 定时接口
 */
class Crontab extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['*'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    protected $pro = null;
    protected $order = null;
    protected $shop = null;

    protected function _initialize()
    {
        parent::_initialize();
        $this->pro = model('Product');
        $this->order = model('Order');
        $this->shop = model('Shop');
        $this->admin = model('Admin');
    }

    /**
     * 查询支付状态
     * @param $params
     */
    public function checkOrderStatus(){
        //查询未支付，未过期，已鎖定的產品
        $page = 1;
        $end = time() - 1 * 10;
        $start = time() - 60 * 20;
print_r($end);
        $list = Order::where(['order_status' => 1])
            ->where('ctime', '>', $start)
            ->where('ctime', '<', $end)
            ->order('id', 'desc')->page($page)
            ->limit(50)->select();
        print_r($list);exit();
        $index = 0;
        $page_size = 10;
       do{
           $proList = $this->pro
               ->where('is_lock',1)
               ->where('expire_time','GT',time())
               ->where('is_payed', 0)
               ->limit($index*$page_size,$page_size)
               ->select();
           if(!empty($proList)){
               foreach ($proList as $v){
                   try{
                       $tid = $v['tb_order_sn'];
                       $shop_id = $v['shop_id'];
                       $admin_id = $v['admin_id'];

                       $shop = $this->shop
                           ->where('id',$shop_id)
                           ->lock(true)
                           ->find();
                       $admin = $this->admin
                           ->where('id',$admin_id)
                           ->lock(true)
                           ->find();

                       $token = $shop['token'];
                       $app_secret = $admin['app_secret'];

                       if(!$shop||empty($shop['token'])){
                           break;
                       }
                       $tbOrder = $this->getOrderInfo($token, $tid);
                       $tbOrder = json_decode($tbOrder);

                       /*成功信息*/
                       if($tbOrder->Error_Code ==0&&$tbOrder->IsSuccess){

                           $payTime = $tbOrder->Data->PayTime;

                           //-----------------未支付--------------------
                           if($payTime == null){
                               $orderInfo = $this->order
                                   ->where('tb_order_sn', $tid)
                                   ->order('ctime DESC')
                                   ->find();
                               //解除pro鎖定
                               if($orderInfo['expire_time']<time()){
                                   $this->pro->where('id' ,$v['id'])->update(['is_lock'=>0]);
                               }
                               break;
                           }

                           /*//-----------------已支付 但支付時間在訂單創建之前------------
                           if($payTime != null&&$payTime>$v['ctime']){
                               //pro表已支付
                               $this->pro->where('id' ,$v['id'])->update(['is_payed'=>1]);

                               //查找上一个支付之前下单的订单
                               $orderInfo = $this->order->where('ctime','GT', $payTime)->where('tb_order_sn', $tid)->order('ctime DESC')->find();
                               $this->pro->where('id', $orderInfo['id'])->update(['order_status'=>1]);

                              $this->changeShop($shop, $orderInfo);

                               //回調通知
                               $this->notifyUrl($orderInfo['callback_url'],$orderInfo['order_sn'], $payTime);
                               break;
                           }*/

                           //-----------------已支付--------------------------------
                           if($payTime != null){
                               //pro表已支付
                               $v['is_payed']  = 1;

                               $this->pro->where('id', $v['id'])->save($v);

                               //更新訂單
                               $orderInfo = $this->order
                                   ->where('tb_order_sn', $tid)
                                   ->order('ctime DESC')
                                   ->find();
                               $this->order->where('id', $orderInfo['id'])->update(['order_status'=>1, 'pay_time'=>$payTime, 'pay_date' =>date('Y-m-d',$payTime)]);

                               //回調通知
                               $rs = $this->notifyUrl($orderInfo['callback_url'],
                                   [
                                       'callbacks' => 'CODE_SUCCESS',
                                       'total' => $orderInfo['order_sum'],
                                       'paytime' => $payTime,
                                       'order_sn' => $orderInfo['part_sn'],
                                   ],
                                   $app_secret);
                               if(trim($rs) == 'success'){
                                   $this->order->where('id', $orderInfo['id'])->update([ 'is_success' =>1]);
                               }
                               $this->changeShop($shop, $orderInfo);

                           }

                           /*商家token過期 禁用商家 商品下架*/
                       }else if($tbOrder->Error_Code ==1){
                           /* $shop['status'] = 1;
                            $this->shop
                                ->allowField(true)->save($shop);

                            $this->pro
                                ->where('shop_id', $shop_id)
                                ->where('is_payed', 0)
                                ->update(['is_sale'=>1]);*/
                           Log::info('商家id:'.$shop_id.'token過期');

                           /*其他情況订单過期時間延長*/
                       }else{
                           //更新訂單
                           $orderInfo = $this->order
                               ->where('tb_order_sn', $tid)
                               ->order('ctime DESC')
                               ->find();
                           $expire_time  = $orderInfo['expire_time']+Config::get('expireTime');
                           $this->order->where('id', $orderInfo['id'])->update(['expire_time'=>$expire_time]);
                           Log::error('咸鱼单号：'.$tid.'||'.$tbOrder->Error_Code.'||'.$tbOrder->Error_Msg);
                       }
                   }catch (Exception $e){
                       Log::error('咸鱼单号：'.$tid.'出现严重错误！');
                   };
               }
           }

           $index++;

       }while(!empty($proList));

        $this->success('執行成功');
    }

    //回調訂單信息给四方
    private function notifyUrl($callback, $params, $app_secret){
        //业务参数
        $params['sign'] = sign($params, $app_secret);

        return self::notifyPost($callback, $params);
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

    //獲取淘寶訂單信息
    private function getOrderInfo($accessToken, $tid){
        $appSecret = Config::get('agiso')['appSecret'];
        $url = Config::get('agiso')['tradeUrl'];

        //业务参数
        $params = array();
        $params['tid'] = $tid;
        $params['timestamp'] = time();
        $params['sign'] = sign($params,$appSecret);

        //设置Header
        $headers[] = "Authorization: Bearer ".$accessToken;
        $headers[] = "ApiVersion: 1";

       return post($url, $params, $headers);
    }

    //更新shop表
    private function changeShop($shop, $orderInfo){
        //更新shop表 deal_num
        if($shop['day'] == date('Y-m-d')){
            $this->shop->where('id',$shop['shop_id'])->setInc('deal_num', $orderInfo['order_sum']);
        }else{
            $this->shop->where('id',$shop['shop_id'])->update(['day_num'=>$orderInfo['order_sum'], 'deal_num' =>$orderInfo['order_sum'], 'day'=>date('Y-m-d', time())]);
        }
    }


}
