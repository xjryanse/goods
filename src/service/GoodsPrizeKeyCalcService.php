<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\system\service\SystemMathService;
use xjryanse\logic\Debug;
use Exception;

/**
 * 商品价格计算式（适用于配送费计算）
 */
class GoodsPrizeKeyCalcService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsPrizeKeyCalc';

    /**
     * keyId计算结果
     * @param type $prizeKeyId
     * @param type $data
     */
    public static function prizeKeyIdCalc($prizeKeyId, $data) {
        $scopeField = GoodsPrizeKeyService::getInstance($prizeKeyId)->fScopeField();
        if (!$scopeField) {
            throw new Exception('分段函数分段字段不能为空，请联系开发' . $prizeKeyId);
        }
        $value = Arrays::value($data, $scopeField, 0);
        $con[] = ['prize_key_id', '=', $prizeKeyId];
        $lists = self::lists($con);
        //$lists  = self::staticConList($con);
        Debug::debug('prizeKeyIdCalc的分段函数列表', $lists);
        foreach ($lists as $v) {
            if (($v['min_val'] < $value || ($v['min_val'] == $value && $v['min_contain'])) && ($v['max_val'] > $value || ($v['max_val'] == $value && $v['max_contain']))) {
                $key = $v['math_key'];
                // 固定参数
                $fixedData = json_decode($v['fixed_data'], JSON_UNESCAPED_UNICODE);
                // 带入计算
                Debug::debug('prizeKeyIdCalc的计算key', $key);
                Debug::debug('prizeKeyIdCalc的计算数据', array_merge($data, $fixedData));
                return SystemMathService::calByKey($key, array_merge($data, $fixedData));
            }
        }
        //没有匹配到，返回0;
        return 0;
    }

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
        
    }

    /**
     * 钩子-删除后
     */
    public function extraAfterDelete() {
        
    }

    /**
     *
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建时间
     */
    public function fCreateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 创建者，user表
     */
    public function fCreater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 参数(带入计算)；json格式
     */
    public function fFixedData() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 有使用(0否,1是)
     */
    public function fHasUsed() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未删，1：已删）
     */
    public function fIsDelete() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未锁，1：已锁）
     */
    public function fIsLock() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 价格key，system_math表
     */
    public function fMathKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 最大包含？< ? <=
     */
    public function fMaxContain() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分段止
     */
    public function fMaxVal() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 最小包含？> ? >=
     */
    public function fMinContain() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分段起
     */
    public function fMinVal() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 价格key
     */
    public function fPrizeKeyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 备注
     */
    public function fRemark() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 排序
     */
    public function fSort() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 状态(0禁用,1启用)
     */
    public function fStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新时间
     */
    public function fUpdateTime() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 更新者，user表
     */
    public function fUpdater() {
        return $this->getFFieldValue(__FUNCTION__);
    }

}
