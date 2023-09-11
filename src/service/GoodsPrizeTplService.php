<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\logic\Cachex;

/**
 * 商品价格模板
 */
class GoodsPrizeTplService {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsPrizeTpl';

    /**
     * 
     * @param type $saleType
     * @param type $companyId   有命令行执行的，故需保留companyId
     */
    public static function saleTypeList($saleType, $companyId) {
        return Cachex::funcGet(__CLASS__ . '_' . __METHOD__ . $saleType . $companyId, function() use ($saleType, $companyId) {
                    $prizeCon[] = ["sale_type", "=", $saleType];
                    $prizeCon[] = ["company_id", "=", $companyId];
                    return GoodsPrizeTplService::mainModel()->where($prizeCon)->cache(86400)->select();
                });
    }

    /**
     * 20220619：获取前序价格key
     * 用于关联前序订单付款
     */
    public static function getPreKey($saleType, $prizeKey) {
        $con[] = ['main_key', '=', $prizeKey];
        $con[] = ['sale_type', '=', $saleType];
        $con[] = ['belong_role', '=', 'seller'];
        $info = self::staticConFind($con);
        return $info ? $info['prize_key'] : '';
    }

    /**
     * 20220619：获取后续价格key
     * 用于关联后序订单收款
     */
    public static function getAfterKey($saleType, $prizeKey) {
        $con[] = ['prize_key', '=', $prizeKey];
        $con[] = ['sale_type', '=', $saleType];
        $con[] = ['belong_role', '=', 'seller'];
        $info = self::staticConFind($con);
        return $info ? $info['main_key'] : '';
    }

    /**
     * 【逐步弃用】GoodsPrizeKeyService同名方法替代
     * 如果在mainKey中，为买家；
     * 不在mainKey中，根据 prize_key 查 belong_role
     */
    public static function keyBelongRole($key) {
        return false;
        $con1[] = ['main_key', '=', $key];
        if (self::count($con1)) {
            return 'buyer';
        }
        $con2[] = ['prize_key', '=', $key];
        $info = self::find($con2);
        return Arrays::value($info, 'belong_role');
    }

    /**
     * 获取始祖key，顶级无父key的key
     */
    public static function getAncestorKey($saleType, $belongRole) {
        $con[] = ['sale_type', '=', $saleType];
        $con[] = ['belong_role', '=', $belongRole];
        $con[] = ['p_key', '=', ''];
        $info = self::find($con);
        return $info ? $info['prize_key'] : '';
    }

    /**
     * 价格key取父价格key
     */
    public static function prizeKeyGetPKey($prizeKey) {
        return Cachex::funcGet(__CLASS__ . '_' . __METHOD__ . $prizeKey, function() use ($prizeKey) {
                    $con[] = ['prize_key', '=', $prizeKey];
                    $info = self::find($con);
                    return $info ? $info['p_key'] : '';
                }, true);
    }

    /**
     * 销售类型和主key取价格key
     */
    public static function columnPrizeKeysBySaleTypeMainKey($saleType, $mainKey, $con = []) {
        if ($saleType) {
            $con[] = ['sale_type', '=', $saleType];
        }
        if ($mainKey) {
            $con[] = ['main_key', '=', $mainKey];
        }

        return self::staticConColumn('prize_key', $con);
    }

    /**
     * 根据销售类型取价格key
     * @param type $saleType
     * @return type
     */
    public static function columnPrizeKeysBySaleType($saleType) {
        return Cachex::funcGet(__CLASS__ . '_' . __METHOD__ . $saleType, function() use ($saleType) {
                    $con[] = ['sale_type', '=', $saleType];
                    return self::mainModel()->where($con)->column('prize_key');
                });
    }

    /**
     * 销售类型取主key
     * @param type $saleType
     * @param type $con
     * @return type
     */
    public static function columnMainKeysBySaleType($saleType, $con = []) {
        $con[] = ['sale_type', '=', $saleType];
        return self::column('main_key', $con);
    }

    /**
     * 获取顶级价格key：
     * 用于计算总价
     */
    public static function getFinalKeys($saleType, $belongRole = '') {
        return Cachex::funcGet(__CLASS__ . '_' . __METHOD__ . $saleType . $belongRole, function() use ($saleType, $belongRole) {
                    $con[] = ['sale_type', '=', $saleType];
                    $con[] = ['p_key', '=', ''];
                    if ($belongRole) {
                        $con[] = ['belong_role', 'in', $belongRole];
                    }
                    return self::mainModel()->where($con)->column('prize_key');
                });
    }

    /*
     * 是否最终价格
     */

    public static function isMainKeyFinal($mainKey) {
        return Cachex::funcGet(__CLASS__ . '_' . __METHOD__ . $mainKey, function() use ($mainKey) {
                    $con[] = ['main_key', 'in', $mainKey];
                    return self::isFinal($con);
                }, true);
    }

    public static function isPrizeKeyFinal($mainKey) {
        return Cachex::funcGet(__CLASS__ . '_' . __METHOD__ . $mainKey, function() use ($mainKey) {
                    $con[] = ['prize_key', 'in', $mainKey];
                    return self::isFinal($con);
                }, true);
    }

    /**
     * 是否最终价格
     */
    protected static function isFinal($con) {
        //价格
        $lists = self::lists($con);
        $isFinal = true;
        foreach ($lists as $value) {
            if ($value['p_key']) {
                $isFinal = false;
            }
        }
        return $isFinal;
    }

    /**
     * 根据销售类型，取归属角色列表
     */
    public static function columnBelongRolesBySaleType($saleType) {
        return Cachex::funcGet(__CLASS__ . '_' . __METHOD__ . $saleType, function() use ($saleType) {
                    $con[] = ['sale_type', '=', $saleType];
                    return self::mainModel()->where($con)->column('distinct belong_role');
                });
    }

    /**
     *
     */
    public function fId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fAppId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 父费用key
     */
    public function fPKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 费用key
     */
    public function fPrizeKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 费用名称
     */
    public function fPrizeName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 主费用key
     */
    public function fMainKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 主名
     */
    public function fMainName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 销售类型
     */
    public function fSaleType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 归属角色
     */
    public function fBelongRole() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 默认金额
     */
    public function fDefaultMoney() {
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
     * 有使用(0否,1是)
     */
    public function fHasUsed() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未锁，1：已锁）
     */
    public function fIsLock() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 锁定（0：未删，1：已删）
     */
    public function fIsDelete() {
        return $this->getFFieldValue(__FUNCTION__);
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
