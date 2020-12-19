<?php

namespace app\admin\controller\demo;


use app\common\controller\Backend;

/**
 * 测试
 * @internal
 */
class Demo extends Backend
{

    /**
     * @var \app\admin\model\Shop
     */
    protected $model = null;
    protected $adminId = null;
    protected $dataLimitField = 'admin_id'; //数据关联字段,当前控制器对应的模型表中必须存在该字段
    protected $dataLimit = 'personal'; //表示仅显示当前自己的数据

    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     *
     */
    public function index()
    {
        return $this->view->fetch();
    }

    /**
     *
     */
    public function qr()
    {
        $params = $this->request->post("row/a");
        if ($params) {
            $params = $this->preExcludeFields($params);
            if(!$params['money'] || !$params['part_sn'] ||!$params['notify'] ||!$params['id'] || !$params['app_secret']){
                $this ->error('參數錯誤');
            }
        }

        $url = $this->request->domain().'/index.php/api/order';
        $app_secret = $params['app_secret'] ;
        unset($params['app_secret']);
        $params['sign'] = $this->sign($params,$app_secret);
        $headers[] = "ApiVersion: 1";
        $rs = $this->post($url, $params ,$headers);

        $rs = json_decode($rs);
         if(!$rs||$rs->code == 0){
             $this ->error('未獲取到二維碼');
         }
        $this-> assign('rs', $rs);
        return $this->view->fetch();
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
//post
    private function post($url, $params, $headers=''){
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
