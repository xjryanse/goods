<?php
namespace xjryanse\goods\service;

/**
 * 商品价格设置
 */
class GoodsPrizeService
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\goods\\model\\GoodsPrize';

    /*
     * 用商品id查询，并绑定键
     */
    public static function selectByGoodsIdBindKey( $goodsId )
    {
        return self::mainModel()->where('goods_id',$goodsId)->column('*','prize_key');
    }
    
}
