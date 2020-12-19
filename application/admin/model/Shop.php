<?php

namespace app\admin\model;

use app\common\model\MoneyLog;
use app\common\model\ScoreLog;
use think\Model;

class Shop extends Model
{

    // 表名
    protected $name = 'shop';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'utime';
    // 追加属性
    protected $append = [

    ];

    public function getOriginData()
    {
        return $this->origin;
    }

    protected static function init()
    {

    }



    public function getStatusList()
    {
        return ['0' => __('Normal'), '1' => __('Hidden')];
    }

    public static function getShopList($aid)
    {
        $shopList = self::where('admin_id',$aid)->field('id , tb_name')->select();
        return $shopList;
    }

    public function group(){
        return $this->belongsTo('Admin', 'admin_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
