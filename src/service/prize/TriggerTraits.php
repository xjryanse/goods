<?php

namespace xjryanse\goods\service\prize;

use xjryanse\goods\service\GoodsService;
use Exception;
/**
 * 触发动作
 */
trait TriggerTraits{

    /**
     * 20220701
     * @param type $data
     * @param type $uuid
     * @return type
     * @throws Exception
     */
    public static function ramPreSave(&$data, $uuid) {

    }

    /**
     * 20220701
     * @param type $data
     * @param type $uuid
     */
    public static function ramPreUpdate(&$data, $uuid) {
        // dump($data);

    }

 /**
     * 20220701
     * @param type $data
     * @param type $uuid
     */
    public static function ramAfterSave(&$data, $uuid) {
        $info = self::getInstance($uuid)->get();
        if ($info['goods_id']) {
            GoodsService::getInstance($info['goods_id'])->goodsPrizeUpdateRam();
        }
    }

    /**
     * 20220701
     * @param type $data
     * @param type $uuid
     */
    public static function ramAfterUpdate(&$data, $uuid) {
        $info = self::getInstance($uuid)->get();
        if ($info['goods_id']) {
            GoodsService::getInstance($info['goods_id'])->goodsPrizeUpdateRam();
        }
    }
    /**
     * 钩子-删除前
     */
    public function ramPreDelete() {
    }

    /**
     * 钩子-删除后
     */
    public function ramAfterDelete($info) {
        if ($info['goods_id']) {
            GoodsService::getInstance($info['goods_id'])->goodsPrizeUpdateRam();
        }
    }

}
