<?php

$apiurl = 'http://www.xydaifu.com/api/order';            // API下单地址
$client_secret = '19405ab7e62642595654ac2e18028582';    // 店铺app_secret
$data = array(
    'money' => '30',                                // 金额
    'part_sn' => 'ceshi_P01202002251511214050024',  // 发起平台订单号
    'notify' => 'http://www.xxx.com',               // 异步回调地址
	'id' => '2',                               //码商ID
);
$data['sign'] = sign($data,$client_secret); // 生成签名
//print_r($data);
//exit();
$res = sendRequest($apiurl,$data); // 下单
print_r($res);
//返回信息 成功
/*
{
    "code":1, //成功
    "msg":"获取成功！",
    "time":"1585560041",
    "data":{
    "order_sn":"20200330171822101995", //返回订单
    "h5_url":"http:\/\/www.xydaifu.com\/index.php\/index\/qr?order_sn=20200330171822101995"} //返回订单支付页面
   }
*/

//返回信息 失败
/*
{
    "code":0, //失败
    "msg":"签名错误!",
    "time":"1585560120",
    "data":null
   }
*/


// 簽名方法
function sign($args,$client_secret)
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



/**
 * CURL发送Request请求,含POST和REQUEST
 * @param string $url 请求的链接
 * @param mixed $params 传递的参数
 * @param string $method 请求的方法
 * @param mixed $options CURL的参数
 * @return array
 */
function sendRequest($url, $params = [], $method = 'POST', $options = []) {
    $method = strtoupper($method);
    $protocol = substr($url, 0, 5);
    $query_string = is_array($params) ? http_build_query($params) : $params;

    $ch = curl_init();
    $defaults = [];
    if ('GET' == $method) {
        $geturl = $query_string ? $url . (stripos($url, "?") !== FALSE ? "&" : "?") . $query_string : $url;
        $defaults[CURLOPT_URL] = $geturl;
    } else {
        $defaults[CURLOPT_URL] = $url;
        if ($method == 'POST') {
            $defaults[CURLOPT_POST] = 1;
        } else {
            $defaults[CURLOPT_CUSTOMREQUEST] = $method;
        }
        $defaults[CURLOPT_POSTFIELDS] = $query_string;
    }

    $defaults[CURLOPT_HEADER] = FALSE;
    $defaults[CURLOPT_USERAGENT] = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.98 Safari/537.36";
    $defaults[CURLOPT_FOLLOWLOCATION] = TRUE;
    $defaults[CURLOPT_RETURNTRANSFER] = TRUE;
    $defaults[CURLOPT_CONNECTTIMEOUT] = 3;
    $defaults[CURLOPT_TIMEOUT] = 3;

    // disable 100-continue
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

    if ('https' == $protocol) {
        $defaults[CURLOPT_SSL_VERIFYPEER] = FALSE;
        $defaults[CURLOPT_SSL_VERIFYHOST] = FALSE;
    }

    curl_setopt_array($ch, (array)$options + $defaults);

    $ret = curl_exec($ch);
    $err = curl_error($ch);


    curl_close($ch);
    return  $ret;
}