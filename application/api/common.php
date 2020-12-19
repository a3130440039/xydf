<?php

use think\Config;

//post
function post($url, $params, $headers='ApiVersion: 1'){
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

//生成系統訂單號
function creat_order_sn(){
    $dt = date('YmdHis');
    $str = $dt . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 6);
    return $str;
}


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