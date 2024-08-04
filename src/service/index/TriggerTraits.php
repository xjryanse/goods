<?php

namespace xjryanse\goods\service\index;

use xjryanse\goods\service\GoodsSpuService;
use xjryanse\logic\Arrays;
use Exception;
/**
 * 触发动作
 */
trait TriggerTraits{
   
    /**
     * 钩子-保存前
     */
    public static function extraPreSave(&$data, $uuid) {
    }

    /**
     * 钩子-保存后
     */
    public static function extraAfterSave(&$data, $uuid) {
        
    }

    /**
     * 钩子-更新前
     */
    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    /**
     * 钩子-更新后
     */
    public static function extraAfterUpdate(&$data, $uuid) {
        
    }

    /**
     * 钩子-删除前
     */
    public function extraPreDelete() {
        self::stopUse(__METHOD__);
    }

    /**
     * 钩子-删除后
     */
    public function extraAfterDelete() {
        
    }
    
    
    /**
     * 20220701
     * @param type $data
     * @param type $uuid
     * @return type
     * @throws Exception
     */
    public static function ramPreSave(&$data, $uuid) {
//        DataCheck::must($data, ['goods_name', 'spu_id']);
//        
//        //20210731谁发谁卖
//        $data['seller_user_id'] = Arrays::value($data, 'seller_user_id') ?: session(SESSION_USER_ID);
//        $spuId              = Arrays::value($data, 'spu_id');
//        $data['cate_id']    = GoodsSpuService::getInstance($spuId)->fCateId();
//        $data['sale_type']  = GoodsSpuService::getInstance($spuId)->fSaleType();
//
//        if (!Arrays::value($data, "goods_table") && !Arrays::value($data, "goods_table_id")) {
//            $data['goods_table'] = self::mainModel()->getTable();
//            $data['goods_table_id'] = $uuid;
//        }
//        $prizeKeys = GoodsPrizeTplService::saleTypeList($data['sale_type'], session(SESSION_COMPANY_ID));
//        if (!$prizeKeys) {
//            throw new Exception('销售类型' . $data['sale_type'] . '未配置费用信息，请联系开发人员设置');
//        }
//
//        return $data;
        self::redunFields($data, $uuid);
        // 商品表冗余
        if (!Arrays::value($data, "goods_table") && !Arrays::value($data, "goods_table_id")) {
            $data['goods_table']    = self::mainModel()->getTable();
            $data['goods_table_id'] = $uuid;
        }
    }

    /**
     * 20220701
     * @param type $data
     * @param type $uuid
     */
    public static function ramPreUpdate(&$data, $uuid) {
        self::redunFields($data, $uuid);

//        $info = self::getInstance($uuid)->get();
//        if (isset($data['sellerGoodsPrize']) || isset($data['plateGoodsPrize'])) {
//            $sellerPrize    = isset($data['sellerGoodsPrize']) ? $data['sellerGoodsPrize'] : $info['sellerGoodsPrize'];
//            $platePrize     = isset($data['plateGoodsPrize']) ? $data['plateGoodsPrize'] : $info['plateGoodsPrize'];
//            $data['goodsPrize'] = $sellerPrize + $platePrize;
//        }
    }

    

 /**
     * 20220701
     * @param type $data
     * @param type $uuid
     */
    public static function ramAfterSave(&$data, $uuid) {
//        $info = self::getInstance($uuid)->get();
//        $info['goodsPrize'] = Arrays::value($info, 'sellerGoodsPrize', 0) + Arrays::value($info, 'plateGoodsPrize', 0);
//        //商品价格冗余记录
//        self::getInstance($uuid)->goodsIsOnSyncRam();
//        //一口价写入价格表
        self::getInstance($uuid)->setGoodsPrizeArrRam();
//        //更新spu的价格
//        $spuId = self::getInstance($uuid)->fSpuId();
//        // GoodsSpuService::getInstance( $spuId )->updatePrize();
//        // 20220701
//        GoodsSpuService::getInstance($spuId)->objAttrsPush('goods', $info);
//        GoodsSpuService::getInstance($spuId)->dataSyncRam();
    }

    /**
     * 20220701
     * @param type $data
     * @param type $uuid
     */
    public static function ramAfterUpdate(&$data, $uuid) {
//        Debug::debug('ramAfterUpdate', $data);
//        //商品价格冗余记录
//        self::getInstance($uuid)->goodsIsOnSyncRam();
//        //一口价写入价格表
//        self::getInstance($uuid)->setGoodsPrizeArrRam();
//        //更新spu的价格
//        $spuId = self::getInstance($uuid)->fSpuId();
//        //20220701：更新属性
//        GoodsSpuService::getInstance($spuId)->objAttrsUpdate('goods', $uuid, $data);
//        GoodsSpuService::getInstance($spuId)->dataSyncRam();
    }
    /**
     * 钩子-删除前
     */
    public function ramPreDelete() {

    }

    /**
     * 钩子-删除后
     */
    public function ramAfterDelete() {
        
    }

    protected static function redunFields(&$data, $uuid){
        $data['sellerGoodsPrize']   = self::getInstance($uuid)->calSellerGoodsPrize();
        $data['plateGoodsPrize']    = self::getInstance($uuid)->calPlateGoodsPrize();
        $data['goodsPrize']         = $data['sellerGoodsPrize'] + $data['plateGoodsPrize'];
        if(Arrays::value($data, 'spu_id')){
            $spuId              = Arrays::value($data, 'spu_id');
            $data['cate_id']    = GoodsSpuService::getInstance($spuId)->fCateId();
            $data['sale_type']  = GoodsSpuService::getInstance($spuId)->fSaleType();
        }
        
        return $data;
    }
}
