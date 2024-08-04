<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\store\service\StoreChangeDtlService;
use xjryanse\order\service\OrderService;
use xjryanse\order\service\OrderGoodsService;
use xjryanse\order\service\OrderShoppingCartService;
use xjryanse\logic\Debug;
use xjryanse\goods\service\GoodsSpuService;
use xjryanse\goods\service\GoodsService;

/**
 * 用户收藏商品
 */
class ViewGoodsSingleSkuService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;


    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\ViewGoodsSingleSku';

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) {
                    $goodsIds = array_column($lists, 'goods_id');
                    $cond[] = ['is_delete', '=', 0];
                    //sku查询数组
                    $attrArr = GoodsAttrService::groupBatchCount('goods_id', $goodsIds);
                    //订单数
                    $orderArr = OrderService::groupBatchCount('goods_id', $goodsIds, $cond);
                    //订单商品数
                    $orderGoodsArr = OrderGoodsService::groupBatchCount('goods_id', $goodsIds);
                    //购物车商品数
                    $orderShoppingCartArr = OrderShoppingCartService::groupBatchCount('goods_id', $goodsIds);
                    //仓库出入明细数
                    $storeChangeDtlArr = StoreChangeDtlService::groupBatchCount('goods_id', $goodsIds);

                    $conIncome[] = ['change_type', '=', 1];
                    $incomeDtlCounts = StoreChangeDtlService::groupBatchCount('goods_id', $goodsIds, $conIncome);
                    $conOutcome[] = ['change_type', '=', 2];
                    $outcomeDtlCounts = StoreChangeDtlService::groupBatchCount('goods_id', $goodsIds, $conOutcome);
                    $conRef[] = ['change_type', '=', 3];
                    $refDtlCounts = StoreChangeDtlService::groupBatchCount('goods_id', $goodsIds, $conRef);

                    foreach ($lists as &$v) {
                        $iGoodsId = $v['goods_id'];
                        //订单数
                        $v['orderCounts'] = Arrays::value($orderArr, $iGoodsId, 0);
                        //属性数
                        $v['attrCounts'] = Arrays::value($attrArr, $iGoodsId, 0);
                        //订单商品数
                        $v['orderGoodsCounts'] = Arrays::value($orderGoodsArr, $iGoodsId, 0);
                        //购物车商品数
                        $v['orderShoppingCartCounts'] = Arrays::value($orderShoppingCartArr, $iGoodsId, 0);
                        //仓库出入明细数
                        $v['storeChangeDtlCounts'] = Arrays::value($storeChangeDtlArr, $iGoodsId, 0);
                        // 入库流水数
                        $v['incomeDtlCounts'] = Arrays::value($incomeDtlCounts, $iGoodsId, 0);
                        // 出库流水数
                        $v['outcomeDtlCounts'] = Arrays::value($outcomeDtlCounts, $iGoodsId, 0);
                        // 退库流水数
                        $v['refDtlCounts'] = Arrays::value($refDtlCounts, $iGoodsId, 0);
                    }
                    return $lists;
                });
    }

    /**
     * 2022-12-09：改写原方法
     * @param type $param
     */
    public static function saveGetIdRam($param) {
        $param['single_sku'] = 1;   //单sku模式
        //spu保存
        Debug::debug('GoodsSpuService的保存数据', $param);
        $id = GoodsSpuService::saveGetIdRam($param);
        $info = GoodsSpuService::getInstance($id)->get(0);

        // Debug::debug('----------------',$info);
        // Debug::debug('GoodsSpuService的保存之后的数据',$info);
        $keys = ['name' => 'goods_name', 'main_pic' => 'goods_pic', 'goods_desc' => 'goods_desc','rd_code' => 'rd_code','goods_spc'=>'goods_spc'];
        $infoArr = is_object($info) ? $info->toArray() : $info;
        $data = Arrays::keyReplace($infoArr, $keys);
        $data['sellerGoodsPrize'] = Arrays::value($param, 'sellerGoodsPrize', 0);
        $data['plateGoodsPrize'] = Arrays::value($param, 'plateGoodsPrize', 0);
        // 2022-12-08
        if (Arrays::value($param, 'unit')) {
            $data['unit'] = $param['unit'];
        }
        $data['spu_id'] = $id;
        $data['goods_table'] = GoodsSpuService::mainModel()->getTable();
        $data['goods_table_id'] = $id;
        $data['sale_type'] = Arrays::value($param, 'sale_type', 'normal'); //TODO
        $cond[] = ['spu_id', '=', $id];
        $skuId = GoodsService::mainModel()->where($cond)->value('id');
        if ($skuId) {
            $data['id'] = $skuId;
        }
        // Debug::debug('GoodsService的保存数据',$data);
        //sku保存
        $skuIdSave = GoodsService::saveGetIdRam($data);
        return $skuIdSave;
    }

    /**
     * 备注
     */
    public function fRemark() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建者，user表
     */
    public function fCreater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新者，user表
     */
    public function fUpdater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建时间
     */
    public function fCreateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新时间
     */
    public function fUpdateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

}
