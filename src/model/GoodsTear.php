<?php
namespace xjryanse\goods\model;

/**
 * 商品SPU
 */
class GoodsTear extends Base
{
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'goods_id',
            // 去除prefix的表名
            'uni_name'  =>'goods',
            'uni_field' =>'id',
            'del_check' => true,
        ],
        [
            'field'     =>'tear_goods_id',
            // 去除prefix的表名
            'uni_name'  =>'goods',
            'uni_field' =>'id',
            'del_check' => true,
        ]
    ];
}