<?php

namespace app\admin\model;

use think\Model;
use app\admin\model\Shop;

class Product extends Model
{

    // 表名
    protected $name = 'product';
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


    public function getExpireTimeAttr($value){
        return date('Y-m-d H:i:s', $value);
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }

    public function group()
    {
        return $this->belongsTo('Shop', 'shop_id', 'id', [], 'LEFT')->field('tb_name')->setEagerlyType(0);
    }

    public function shop()
    {
        //return $this->belongsTo('Shop', 'shop_id', 'id', [], 'LEFT')->setEagerlyType(0);
        return $this->belongsTo('Shop');
    }

    /**
     * 匹配码
     */
    public function match($money, $admin_id = 0, $user = [])
    {
        $where = [
            'shop_status' => 1,
        ];
        if ($admin_id) {
            $where['admin_id'] = $admin_id;
        }
        $shop_list =  Shop::where($where)->order('last_use_time', 'asc')->select();
        if (count($shop_list) <= 0) {
            return [];
        }
        $prowhere = [
            'admin_id'  => $admin_id,
            'sum'       => $money,
            'is_lock'   => 0,
            'is_payed'  => 0,
            'is_sale'   => 1

        ];
        foreach ($shop_list as $shop)
        {

            //查询码
            $prowhere['shop_id'] = $shop['id'];
            $product = $this->where($prowhere)->where('expire_time', 'GT', time())->order('last_use_time', 'asc')->find();
            if ($product) {
                $shopmod = Shop::get($shop['id']);
                $shopmod->last_use_time = time();
                $shopmod->save();
                $product->last_use_time = time();
                $product->save();
                return $product;
            } else {
                continue;
            }

        }
        return [];
        die;
    }
}
