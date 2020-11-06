<?php
namespace xjryanse\goods\service;

/**
 * 商品价格模板
 */
class GoodsPrizeTplService
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\goods\\model\\GoodsPrizeTpl';

    /**
     * 销售类型和主key取价格key
     */
    public static function columnPrizeKeysBySaleTypeMainKey( $saleType, $mainKey ,$con=[] ){
        if( $saleType ){
            $con[] = [ 'sale_type','=',$saleType ];
        }
        if( $mainKey ){
            $con[] = [ 'main_key','=',$mainKey ];
        }
        return self::mainModel()->where( $con )->column('prize_key');
    }
    
    
}
