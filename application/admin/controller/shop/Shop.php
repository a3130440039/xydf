<?php

namespace app\admin\controller\shop;

use app\common\controller\Backend;
use Exception;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Session;

/**
 * 商户管理
 *
 * @icon fa fa-users
 */
class Shop extends Backend
{

    /**
     * @var \app\admin\model\Shop
     */
    protected $model = null;
    protected $adminId = null;
    protected $dataLimitField = 'admin_id'; //数据关联字段,当前控制器对应的模型表中必须存在该字段
    protected $dataLimit = 'personal'; //表示仅显示当前自己的数据
    protected $multiFields = 'shop_status';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Shop');
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
                ->with('group')
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with('group')
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $day_num = $this->model
                ->with('group')
                ->where($where)
                ->order($sort, $order)
                ->sum('day_num');

            $deal_num = $this->model
                ->with('group')
                ->where($where)
                ->order($sort, $order)
                ->sum('deal_num');

            if(!empty($list)){
                foreach ($list as $k=>$v){
                    /*if($v['day'] == date('Y-m-d',time())){*/
                        if($v['day_num']!=0){
                            $list[$k]['rate'] = floor($v['deal_num']/$v['day_num']*100).'%';
                        }else{

                            $list[$k]['rate'] = '0%';
                        }
                    /*}else{
                        $list[$k]['day_num'] = 0;
                        $list[$k]['deal_num'] = 0;
                        $list[$k]['rate'] = '0%';
                    }*/

                }
            }
            $result = array("total" => $total, "rows" => $list, "day_num" => $day_num, "deal_num"=> $deal_num);
            /*$this->view->assign("day_num", $day_num);
            $this->view->assign("deal_num", $deal_num);*/
            return json($result);
        }
        return $this->view->fetch();
    }


    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = $this->model->where('tb_name',$params['tb_name'])->find();
                !empty($result)&& $this->error(__('已存在该店铺名称', ''));
            }

            if ($params) {
                $params = $this->preExcludeFields($params);
                $params['admin_id'] = $this->adminId;
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
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
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        return parent::add();
    }

    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $result = false;
                $params = $this->preExcludeFields($params);
                $params['admin_id'] = $this->adminId;
                $rs = $this->model->where('tb_name',$params['tb_name'])->where('id','NEQ', $ids)->find();
                !empty($rs)&& $this->error(__('已存在该店铺名称', ''));
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
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
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

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


    //清除当前数据
    public function clear($ids=''){
        if ($ids !='index.php') {
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
                    $count += $v->save(['day_num'=>0, 'deal_num'=>0]);
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
            }
        } else {
            $result = false;
            Db::startTrans();
            try{
                $result = $this->model->where('admin_id',$this->adminId)
                    ->update(['day_num'=>0, 'deal_num'=>0]);
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
                $this->success();
            } else {
                $this->error(__('No rows were updated'));
            }
        }
    }

}
