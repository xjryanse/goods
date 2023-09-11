<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Cachex;

/**
 * 商品价格设置
 */
class GoodsTypePrizeKeyService {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsTypePrizeKey';

    /**
     * 20220814优 销售类型
     * @param type $prizeKey
     * @return type
     */
    public static function getSaleTypes($prizeKey) {
        $con[] = ['prize_key', '=', $prizeKey];
        // return self::mainModel()->where($con)->column('distinct sale_type');
        // 20220814
        return array_unique(self::staticConColumn('sale_type', $con));
    }

    /**
     * 20220814优 价格key
     * @param type $saleType
     * @return type
     */
    public static function getPrizeKeys($saleType) {
        //带缓存
//        return Cachex::funcGet('GoodsTypePrizeKeyService_getPrizeKeys'.$saleType, function() use ($saleType){
//            $con[] = ['sale_type','=',$saleType];
//            return self::mainModel()->where($con)->column('distinct prize_key');
//        });
        // 20220814优化
        $con[] = ['sale_type', '=', $saleType];
        return array_unique(self::staticConColumn('prize_key', $con));
    }

}
