<?php

namespace app\api\controller;

use app\admin\model\Product;
use app\common\controller\Api;
use Exception;
use think\Config;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\cache;

/**
 * 订单接口
 */
class Order extends Api
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
    protected $admin = null;

    protected function _initialize()
    {
        parent::_initialize();

        $this->pro = model('Product');
        $this->order = model('Order');
        $this->shop = model('Shop');
        $this->admin = model('Admin');

    }


    /**
     * 獲取支付二維碼
     *
     */
    public function index()
    {
        $params = $this->request->param();
		if (empty($params)) {
            exit('参数不合法!');
        }
        $id = $params['id'];
        $adminInfo = $this->admin->where('id' ,$id)->field('app_secret')->find();
        if($adminInfo){
            //簽名
            parent::checkSign($this->request, $adminInfo['app_secret']);

            $this ->setOrder($params);

            $this->success('返回成功', ['action' => 'index']);
        }else{
            $this->error('获取店铺app_secret失败！');
        }

    }

    private function setOrder($params){
        if ($params) {
            $params = $this->preExcludeFields($params);
            $money =$params['money'];
            $part_sn =$params['part_sn'];
            $notify = $params['notify'];
            $id = $params['id'];

            if (!$money || !$part_sn || !$notify || !$id) {
                $this->error(__('Invalid parameters'));
            }

            $expireTime = Config::get('expireTime');

            $cache = cache::get($id.$part_sn.$money);
            if($cache){

                $this->success('获取成功！', $cache);
            }else{

                $orderInfo = $this->order
                    ->with('group')
                    ->where('order_sum', $money)
                    ->where('order.admin_id', $id)
                    ->where('order.expire_time','GT',time())
                    ->where('order_status', 0)
                    ->where('part_sn',$part_sn)
                    ->find();

                if($orderInfo){
                    $data = array(
                        'order_sn' =>$orderInfo['order_sn'],
                        'h5_url' => $this->request->domain().'/index.php/index/qr?order_sn='.$orderInfo['order_sn']
                    );
                    $this->success('获取成功！', $data);
                }
            }

            $result = false;
            $data = array();
            //查找pro 在shop_no
            $promode = new Product();
            $pro = $promode->match($money, $id);
            Db::startTrans();
            try {
                //生成系統訂單
                if(!empty($pro)){
                    //商铺是否关闭
                    $shopInfo = $this->shop->where('id',$pro['shop_id'])->find();
                    //print_r($shopInfo);exit();
                    if($shopInfo['shop_status'] == 1){
                        //日限额
                        $today_sum = $this->order->where('pay_date', date('Y-m-d'))->sum('order_sum');
                        //print_r($shopInfo);exit();
                        if($today_sum < $shopInfo['day_limit'] || $shopInfo['day_limit'] == null){
                            $order_data = array();
                            $order_sn = creat_order_sn();
                            $order_data['tb_order_sn'] = $pro['tb_order_sn'];
                            $order_data['shop_id'] = $pro['shop_id'];
                            $order_data['order_sum'] = $pro['sum'];
                            $order_data['admin_id'] = $pro['admin_id'];
                            $order_data['from'] = '';
                            $order_data['callback_url'] = $notify;
                            $order_data['expire_time'] = time()+$expireTime;
                            $order_data['ctime'] = time();
                            $order_data['order_sn'] = $order_sn;
                            $order_data['part_sn'] = $part_sn;

                            //更新pro表
                            $pro['is_lock']  = 1;
                            $pro->allowField(true)->save($pro);

                            //系统订单
                            $result = $this->order
                                ->insert($order_data);

                            //更新shop表day_num

                            $this->shop->where('id',$pro['shop_id'])->setInc('day_num', $pro['sum']);
                            $this->shop->where('id',$pro['shop_id'])->update(['day'=>date('Y-m-d',time())]);



                            $data = array(
                                'order_sn' =>$order_sn,
                                'h5_url' => $this->request->domain().'/index.php/index/qr?order_sn='.$order_sn
                            );
                            //缓存数据
                            cache::set($id.$part_sn.$money, $data, $expireTime);
                        }
                    }
                }
                Db::commit();
            } catch (ValidateException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result !== false) {
                $this->success('获取成功！', $data);
            } else {
                $this->error(__('获取失败！'));
            }
        }
        $this->error(__('Parameter %s can not be empty', ''));
    }


}
