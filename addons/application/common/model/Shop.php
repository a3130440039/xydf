<?php

namespace app\common\model;

use think\Cache;
use think\Model;

/**
 * 商鋪数据模型
 */
class Shop extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    // 追加属性
    protected $append = [

    ];


}
