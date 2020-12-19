<?php

namespace app\admin\controller\data;

use app\common\controller\Backend;
use Exception;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Session;

/**
 * 商户统计
 *
 * @icon fa fa-users
 */
class Shopdata extends Backend
{

    /**
     * @var \app\admin\model\Shop
     */
    protected $model = null;
    protected $order = null;
    protected $adminId = null;
    protected $dataLimitField = 'admin_id'; //数据关联字段,当前控制器对应的模型表中必须存在该字段
    protected $dataLimit = 'personal'; //表示仅显示当前自己的数据

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Admin');
        $this->order = model('Order');
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
            $where_all = '';
            $where_deal = '';
            $dur = '';

            $filter = $this->request->request('filter');
            $filter = json_decode($filter);
            if(isset($filter->dur)){
                $dates = explode(",",$filter->dur);
                $dur = $filter->dur;

                if(!empty($dates[0])){
                    $stime= strtotime($dates[0]);
                    $where_all.='ctime>'.$stime;
                    $where_deal.='pay_time>'.$stime;
                }
                if(!empty($dates[1])){
                    $etime= strtotime($dates[1])+3600*24-1;
                    $where_all.=' and ctime<'.$etime;
                    $where_deal.=' and pay_time<'.$etime;
                }
            }
            $total = $this->model
                //->where($where)
                ->count();

            $list = $this->model
                //->where($where)
                ->limit($offset, $limit)
                ->select();
            if(!empty($list)){
                foreach ($list as $k=>$v){
                    $all = $this->order->where($where_all)->where('admin_id',$v['id'])->sum('order_sum');
                    $deal = $this->order->where($where_deal)->where('admin_id',$v['id'])->where('order_status', 1)->sum('order_sum');
                    $list[$k]['all'] = $all;
                    $list[$k]['deal'] = $deal;
                    $all!=0?($list[$k]['rate'] = floor($deal/$all*100).'%'):($list[$k]['rate'] = '0%');
                    $list[$k]['dur'] = $dur;
                }
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


}
