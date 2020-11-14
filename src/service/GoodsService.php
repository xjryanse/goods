<?php
namespace xjryanse\goods\service;

/**
 * 商品明细
 */
class GoodsService
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\goods\\model\\Goods';
    /**
     * 适用于1个商品多种卖法(如商标：授权，租用，购买)
     * @param type $goodsTableId    商品来源表id
     * @param type $saleType        销售类型
     * @param type $con             其他查询条件
     */
    public static function getBySaleType( $goodsTableId, $saleType ,$con = [])
    {
        $con[] = ["goods_table_id","=",$goodsTableId ];
        $con[] = ["sale_type","=",$saleType ];
        return self::find( $con );
    }
}
