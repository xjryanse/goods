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
     * 主key取价格key
     */
    public static function columnPrizeKeysByMainKey( $mainKey ,$con=[] ){
        $con[] = [ 'main_key','=',$mainKey ];
        return self::mainModel()->where( $con )->column('prize_key');
    }
    
    
}
