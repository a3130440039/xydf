<?php

namespace app\common\model;

use think\Cache;
use think\Model;

/**
 * 訂單数据模型
 */
class Order extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    // 追加属性
    protected $append = [

    ];

    public function group()
    {
        return $this->belongsTo('Product', 'tb_order_sn', 'tb_order_sn', [], 'LEFT')->setEagerlyType(0);
    }
}
