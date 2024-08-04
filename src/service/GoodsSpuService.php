<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
use Exception;

/**
 * 商品明细
 */
class GoodsSpuService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsSpu';
    ///从ObjectAttrTrait中来
    // 定义对象的属性
    protected $objAttrs = [];
    // 定义对象是否查询过的属性
    protected $hasObjAttrQuery = [];
    // 定义对象属性的配置数组
    protected static $objAttrConf = [
        //20220701 sku
        'goods' => [
            'class' => '\\xjryanse\\goods\\service\\GoodsService',
            'keyField' => 'spu_id',
            'master' => true
        ]
    ];

    use \xjryanse\goods\service\spu\TriggerTraits;
    use \xjryanse\goods\service\spu\FieldTraits;
    use \xjryanse\goods\service\spu\CalTraits;
    
    /**
     * 销售类型取spuid，适用于会员充值场景
     * @param type $saleType
     */
    public static function getSpuIdBySaleType($saleType) {
        $con[] = ['sale_type', '=', $saleType];
        $con[] = ['company_id', '=', session(SESSION_COMPANY_ID)];
        $con[] = ['is_delete', '=', 0];
        $con[] = ['status', '=', 1];
        return self::mainModel()->where($con)->value('id');
    }

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    //商品分类id
                    $cateIds = array_unique(array_column($lists, 'cate_id'));
                    $cateInfos = GoodsAttrKeyService::getCateAttrs($cateIds);
                    $cateValues = GoodsAttrValueService::cateIdValues($cateIds);
                    $skuLists = GoodsService::listsWithAttrBySpuIds($ids);
                    //sku查询数组
                    $skuArr = GoodsService::groupBatchCount('spu_id', $ids);

                    foreach ($lists as &$goodsInfo) {
                        $goodsInfo['attrKeys'] = Arrays::value($cateInfos, $goodsInfo['cate_id'], []);
                        $goodsInfo['attrs'] = Arrays::value($cateValues, $goodsInfo['cate_id'], []);
                        $goodsInfo['currentUserId'] = session(SESSION_USER_ID);
                        // 销量
                        $goodsInfo['saleCount'] = '500+';
                        // 浏览量
                        $goodsInfo['scanCount'] = '2000+';
                        $goodsInfo['skuList'] = isset($skuLists[$goodsInfo['id']]) ? $skuLists[$goodsInfo['id']] : [];
                        //订单数
                        $goodsInfo['skuCounts'] = Arrays::value($skuArr, $goodsInfo['id'], 0);
                    }
                    return $lists;
                });
    }

    /**
     * 以属性为键的商品信息。
     * 可一次性编辑同一个spu下多个商品
     * @return type
     */
    public function skuAttrKeyList() {
        $info = $this->get();
        $cateId = Arrays::value($info, 'cate_id');
        // spuId取全部skuId
        $skuIds = $this->skuIds();
        $skuInfos = GoodsService::batchGet($skuIds);
        $array = [];
        foreach ($skuIds as $skuId) {
            $key = GoodsAttrService::goodsGetAttrKey($skuId);
            $array[$key] = $skuInfos[$skuId];
        }
        //属性key
        $goodsAttrKeys = GoodsCateService::getInstance($cateId)->attrCombineKeys();

        $dataArr = array_fill_keys($goodsAttrKeys, (new \stdClass()));
        return array_merge($dataArr, $array);
    }

    public function skuIds() {
        $con[] = ['spu_id', '=', $this->uuid];
        return GoodsService::mainModel()->where($con)->column('id');
    }

    /**
     * 20220701：数据同步
     */
    public function dataSyncRam() {
        $data['min_prize'] = $this->calMinPrize();
        $data['max_prize'] = $this->calMaxPrize();
        Debug::debug('dataSyncRam', $data);
        return $this->doUpdateRam($data);
    }

    

    /**
     * 更新商品价格
     * 查到最大最小
     * 逐步弃用,使用同名Ram方法替代
     */
    public function updatePrize() {
        if (!$this->uuid) {
            return false;
        }
        $con[] = ['spu_id', '=', $this->uuid];
        $prizeArr = GoodsService::mainModel()->where($con)->order('goodsPrize')->column('goodsPrize');
        $data['min_prize'] = Arrays::value($prizeArr, 0, 0);
        $data['max_prize'] = Arrays::value(array_reverse($prizeArr), 0, 0);
        self::mainModel()->where('id', $this->uuid)->update($data);
    }

    
    /**
     * 更新商品金额
     * @createTime 20231027
     * @return type
     */
    public function updatePrizeRam() {
//        $data['sellerGoodsPrize']   = $this->calSellerGoodsPrize();
//        $data['plateGoodsPrize']    = $this->calPlateGoodsPrize();
//        $data['goodsPrize']         = $data['sellerGoodsPrize'] + $data['plateGoodsPrize'];
//
//        return $this->doUpdateRamClearCache($data);
    }
    
}
