<?php

namespace app\admin\controller\shop;

use app\common\controller\Backend;
use app\admin\model\Shop as ShopModel;
use Exception;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Session;

/**
 * 订单管理
 *
 * @icon fa fa-users
 */
class Order extends Backend
{

    /**
     * @var \app\admin\model\Product
     */
    protected $model = null;
    protected $shop = null;
    protected $adminId = null;
    protected $dataLimitField = 'order.admin_id'; //数据关联字段,当前控制器对应的模型表中必须存在该字段
    protected $dataLimit = 'personal'; //表示仅显示当前自己的数据

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Order');
        $this->shop = model('Shop');
        $this->admin = model('Admin');
        $this->view->assign("statusList", $this->model->getStatusList());

        $this->adminId = Session::get('admin')['id'];

    }

    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->with(['group', 'groups'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['group', 'groups'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $all = $this->model
                ->with(['group', 'groups'])
                ->where($where)
                ->order($sort, $order)
                ->sum('order_sum');
            $deal = $this->model
                ->with(['group', 'groups'])
                ->where($where)
                ->where('order_status', 1)
                ->order($sort, $order)
                ->sum('order_sum');

            $data = array(
                'all' =>$all,
                'deal' =>$deal,
                'rate' =>$all == 0?'0%':floor(100*$deal/$all).'%'
            );
            $result = array("total" => $total, "rows" => $list, 'tj' =>$data);
            return json($result);
        }

        return $this->view->fetch();
    }



    public function add()
    {
        return parent::add();
    }

    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        return parent::edit($ids);
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();

            if (is_array($adminIds)) {
                $this->model->where('admin_id', 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    //支付回调
    public function callback($ids = null){
        if ($this->request->isAjax()) {
            $result = false;

            Db::startTrans();
            try{
                $row = $this->model->get($ids);
                if ($row['admin_id'] >= 2 && $row['callback_url']) {
                    $admin = $this->admin
                        ->where('id',$row['admin_id'])
                        ->lock(true)
                        ->find();
                    $app_secret = $admin['app_secret'];

                    $rs = $this->notifyUrl($row['callback_url'],
                        [
                            'callbacks' => 'CODE_SUCCESS',
                            'total' => $row['order_sum'],
                            'paytime' => time(),
                            'order_sn' => $row['part_sn'],
                        ],$app_secret);
                    //$this->error(__('回调失败！'.$rs));
                    if(trim($rs) == 'success') {
                        $now = time();
                        $row['pay_time'] = $now;
                        $row['notify_time'] = $now;
                        $row['pay_date'] = date('Y-m-d h:i:s', $now);
                        $row['notify_number'] += 1;
                        $row['hand_notify'] = 1;
                        $row['order_status'] = 1;
                        $row['is_success'] = 1;
                        // 店铺增加成功
                        $shop = $this->shop->where(['id' => $row['shop_id']])->find();
                        $shop->deal_num += $row['order_sum'];
                        $shop->save();

                        $result = $row->allowField(true)->save($row);
                        Db::commit();
                    } else {
                        $this->error(__('回调失败！'));
                    }
                } else {
                    $this->error(__('回调失败！'));
                }

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
                $this->success(__('回调成功！'));
            } else {
                $this->error(__('回调失败！'));
            }

        }
        return $this->view->fetch();
    }



    //回調訂單信息
    private function notifyUrl($callback, $params, $app_secret){
        //业务参数

        $params['sign'] = self::sign($params, $app_secret);

        return self::post($callback, $params);
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

    private function post($url, $params, $headers=[], $cookieJar='')
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
