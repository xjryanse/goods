<?php

namespace xjryanse\goods\service\spu;

use xjryanse\goods\service\GoodsService;
use xjryanse\goods\service\GoodsCateService;
use Exception;
use xjryanse\logic\DataCheck;
/**
 * 分页复用列表
 */
trait TriggerTraits{
    /**
     * 钩子-保存前
     */
    public static function extraPreSave(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    /**
     * 钩子-更新前
     */
    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    /**
     * 钩子-删除前
     */
    public function extraPreDelete() {
        self::stopUse(__METHOD__);
    }

    /**
     * 钩子-保存前
     */
    public static function ramPreSave(&$data, $uuid) {
        $keys = ['cate_id','name'];
        DataCheck::must($data,$keys);
        self::redunFields($data, $uuid);
    }

    /**
     * 钩子-保存后
     */
    public static function ramAfterSave(&$data, $uuid) {
        
    }

    /**
     * 钩子-更新前
     */
    public static function ramPreUpdate(&$data, $uuid) {
        self::redunFields($data, $uuid);
        
    }

    /**
     * 钩子-更新后
     */
    public static function ramAfterUpdate(&$data, $uuid) {
        
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
        $data['sale_type']  = GoodsCateService::getInstance($data['cate_id'])->fGroup();
        $data['min_prize']  = self::getInstance($uuid)->calMinPrize();
        $data['max_prize']  = self::getInstance($uuid)->calMaxPrize();
        return $data;
    }

}
