<?php
namespace xjryanse\goods\service;

use xjryanse\logic\Cachex;
/**
 * 商品价格设置
 */
class GoodsTypePrizeKeyService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsTypePrizeKey';
    /**
     * 销售类型
     * @param type $prizeKey
     * @return type
     */
    public static function getSaleTypes($prizeKey){
        $con[] = ['prize_key','=',$prizeKey];
        return self::mainModel()->where($con)->column('distinct sale_type');
    }
    /**
     * 价格key
     * @param type $saleType
     * @return type
     */
    public static function getPrizeKeys($saleType){
        //带缓存
        return Cachex::funcGet('GoodsTypePrizeKeyService_getPrizeKeys'.$saleType, function() use ($saleType){
            $con[] = ['sale_type','=',$saleType];
            return self::mainModel()->where($con)->column('distinct prize_key');
        });
    }
}
