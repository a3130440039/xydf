<?php

namespace app\admin\controller\shop;

use app\common\controller\Backend;
use app\admin\model\Shop as ShopModel;
use Exception;
use think\Config;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Session;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

/**
 * 商品管理
 *
 * @icon fa fa-users
 */
class Product extends Backend
{

    /**
     * @var \app\admin\model\Product
     */
    protected $adminId = null;
    protected $shop = null;
    protected $model = null;
    protected $dataLimitField = 'admin_id'; //数据关联字段,当前控制器对应的模型表中必须存在该字段
    protected $dataLimit = 'personal'; //表示仅显示当前自己的数据
    protected $multiFields = 'is_sale';


    public function _initialize()
    {
        parent::_initialize();
        $this ->adminId = Session::get('admin')['id'];
        $this->model = model('Product');
        $this->view->assign("statusList", $this->model->getStatusList());
        $shopList = ShopModel::getShopList($this->adminId);
        $this->view->assign("shopList", $shopList);
        $this->shop = model('Shop');

    }

    public function index()
    {
        $total = 0;
        $list = array();
        //过期状态设置
       $this->model
            ->where('expire_time', 'LT', time())
            ->where('is_expire', 1)
            ->update(['is_expire' =>0]);

        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->where($where)
                //->where(['is_del' => 0])
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with('shop')
                ->where($where)
                //->where(['is_del' => 0])
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $result = array("total" => $total, "rows" => $list);

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
                //闲鱼单号是否重复
                $pro = $this->model->where('tb_order_sn', $params['tb_order_sn'])->find();
                if(!empty($pro)){
                    $this->error(__('已存在该闲鱼订单！'));
                }

                $shopInfo = $this->shop->where('id', $params['shop_id'])->field('tb_name')->find();
                $params['tb_name'] = $shopInfo['tb_name'];
                $params['admin_id'] = $this->adminId;
                $params['expire_time'] = strtotime('+1 day')-3600;
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
        return $this->view->fetch();
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
                $params = $this->preExcludeFields($params);
                $shopInfo = $this->shop->where('id', $params['shop_id'])->field('tb_name')->find();
                $params['tb_name'] = $shopInfo['tb_name'];
                $params['admin_id'] = $this->adminId;
                $result = false;
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
                    $count += $v->save(['is_del' => 1, 'is_lock' => 1, 'is_expire' => 0]);
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

    /**
     * 导入
     */
    public function import()
    {
        $file = $this->request->request('file');
        if (!$file) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        $filePath = ROOT_PATH . DS . 'public' . DS . $file;
        if (!is_file($filePath)) {
            $this->error(__('No results were found'));
        }
        //实例化reader
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            $this->error(__('Unknown data format'));
        }
        if ($ext === 'csv') {
            $file = fopen($filePath, 'r');
            $filePath = tempnam(sys_get_temp_dir(), 'import_csv');
            $fp = fopen($filePath, "w");
            $n = 0;
            while ($line = fgets($file)) {
                $line = rtrim($line, "\n\r\0");
                $encoding = mb_detect_encoding($line, ['utf-8', 'gbk', 'latin1', 'big5']);
                if ($encoding != 'utf-8') {
                    $line = mb_convert_encoding($line, 'utf-8', $encoding);
                }
                if ($n == 0 || preg_match('/^".*"$/', $line)) {
                    fwrite($fp, $line . "\n");
                } else {
                    fwrite($fp, '"' . str_replace(['"', ','], ['""', '","'], $line) . "\"\n");
                }
                $n++;
            }
            fclose($file) || fclose($fp);

            $reader = new Csv();
        } elseif ($ext === 'xls') {
            $reader = new Xls();
        } else {
            $reader = new Xlsx();
        }

        //导入文件首行类型,默认是注释,如果需要使用字段名称请使用name
        $importHeadType = isset($this->importHeadType) ? $this->importHeadType : 'comment';


        //加载文件
        $insert = [];
        $shopError = false;
        $orderError = false;
        $orderinfoError = false;
        try {
            if (!$PHPExcel = $reader->load($filePath)) {
                $this->error(__('Unknown data format'));
            }
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);

            $fields = array('tb_name', 'tb_order_sn', 'tb_qr_url');

            for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
                $values = [];
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    if ($currentColumn == 1) {
                        $cur1 = explode('-', $val);
                        $values[] =  $cur1[0];
                        $values[] =  substr($cur1[1],0, strrpos($cur1[1], '.'));
                    } else{
                        $values[] = is_null($val) ? '' : $val;
                    }

                }

                $row = [];
                $temp = array_combine($fields, $values);

                foreach ($temp as $k => $v) {
                        $row[$k] = $v;

                }
                if ($row) {
                    unset($shopInfo);
                    $shopInfo = $this->shop->where('tb_name', $row['tb_name'])->find();
                    //店铺不存在
                    if (!$shopInfo) {
                        $shopError = true;
                        $shopnameError = $row['tb_name'];
                        break;
                    }

                    $shop_id = $shopInfo['id'];
                    $shop_token = $shopInfo['token'];
                    $shop_tb_name = $shopInfo['tb_name'];
                    $tb_order_sn = $row['tb_order_sn'];
                    // 查看订单是否存在
                    $productinfo = $this->model->where('tb_order_sn', $row['tb_order_sn'])->find();
                    if ($productinfo) {
                        $orderinfoError = true;
                        $orderinfoError = $tb_order_sn;
                        $orderinfoErrorShop = $shop_tb_name;
                        $Error_Msg = '导入订单号已存在!订单号为:' . $tb_order_sn;
                        break;
                    }
                    //验证订单
                    $tbOrder = $this->getOrderInfo($shop_token, $tb_order_sn);
                    $tbOrder = json_decode($tbOrder);
                    if ($tbOrder->Error_Code != 0) {
                        $orderError = true;
                        $ordersnError = $tb_order_sn;
                        $orderErrorShop = $shop_tb_name;
                        $Error_Msg = $tbOrder->Error_Msg;
                        break;
                    }
                    if($tbOrder->Data->SellerNick != $shop_tb_name) {
                        $orderinfoError = true;
                        $orderinfoError = $tb_order_sn;
                        $orderinfoErrorShop = $shop_tb_name;
                        $Error_Msg = '订单店铺与实际订单返回数据不一致!返回店铺名:' . $tbOrder->Data->SellerNick;
                        break;
                    }
                    /*if(round($tbOrder->Data->Payment) != $order_sum) {
                        $orderinfoError = true;
                        $orderinfoError = $tb_order_sn;
                        $orderinfoErrorShop = $shop_tb_name;
                        $Error_Msg = '订单店铺与实际订单返回数据不一致!导入的金额是:' . $order_sum .' 实际订单金额是:' . round($tbOrder->Data->Payment);
                        break;
                    }*/

                    $row['sum'] = round($tbOrder->Data->Payment) ;
                    $row['shop_id'] = $shop_id;
                    $row['admin_id'] = $this ->adminId;
                    $row['expire_time'] = strtotime('+1 day')-3600;
                    $insert[] = $row;
                }
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }

        if ($orderinfoError) {
            $this->error('01-导入的店铺与订单异常!数据表中第'. $currentRow . '行,查看 店铺名:' . $orderinfoErrorShop . '订单号:' .$orderinfoError . '返回错误信息:' . $Error_Msg);
        }
        if ($orderError) {
            $this->error('02-导入的店铺与订单不符或店铺异常!数据表中第'. $currentRow . '行,查看 店铺名:' . $orderErrorShop . '订单号:' .$ordersnError . '返回错误信息:' . $Error_Msg);
        }
        if ($shopError) {
            $this->error('03-导入的店铺不存在!数据表中第'. $currentRow . '行,查看:'. $shopnameError);
        }
        if (!$insert) {
            $this->error(__('No rows were updated'));
        }
        try {
            $this->model->saveAll($insert);
        } catch (PDOException $exception) {
            $msg = $exception->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                $msg = "导入失败，包含【{$matches[1]}】的记录已存在";
            };
            $this->error($msg);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success();
    }

    private function getOrderInfo($accessToken, $tid){
        $appSecret = Config::get('agiso')['appSecret'];
        $url = Config::get('agiso')['tradeUrl'];

        //业务参数
        $params = array();
        $params['tid'] = $tid;
        $params['timestamp'] = time();
        $params['sign'] = $this->sign($params,$appSecret);

        //设置Header
        $headers[] = "Authorization: Bearer ".$accessToken;
        $headers[] = "ApiVersion: 1";

        return $this->post($url, $params, $headers);
    }

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
