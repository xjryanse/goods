<?php

namespace xjryanse\goods\service;

/**
 * 商品价格设置
 */
class GoodsTypePrizeKeyService {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsTypePrizeKey';

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    foreach($lists as &$v){
                        $v['goodsTypeId'] = GoodsTypeService::saleTypeToId($v['sale_type']);
                    }
                    return $lists;
                },true);
    }
    
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
        // 20220814优化
        $con[] = ['sale_type', '=', $saleType];
        return array_unique(self::staticConColumn('prize_key', $con));
    }

}
