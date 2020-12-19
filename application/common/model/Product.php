<?php

namespace app\common\model;

use Exception;
use think\Cache;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Model;

/**
 * 商品数据模型
 */
class Product extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    // 追加属性
    protected $append = [
      
    ];

}
