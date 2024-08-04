<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
use xjryanse\order\service\OrderService;
use xjryanse\order\service\OrderGoodsService;
use xjryanse\finance\service\FinanceStatementOrderService;
use xjryanse\logic\Cachex;
use Exception;

/**
 * 商品价格设置
 */
class GoodsPrizeKeyService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\MainModelComCateLevelQueryTrait;

// 静态模型：配置式数据表
    use \xjryanse\traits\StaticModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsPrizeKey';

    /**
     * 额外详情
     * @param type $ids
     * @return type
     */
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    //剩余未收
                    $prizeKeys = array_column($lists, 'prize_key');
                    $saleTypeCounts = GoodsTypePrizeKeyService::groupBatchCount('prize_key', $prizeKeys);
                    $statementOrderCounts = FinanceStatementOrderService::groupBatchCount('statement_type', $prizeKeys);
                    foreach ($lists as &$v) {
                        // 销售类型数
                        $v['saleTypeCount'] = Arrays::value($saleTypeCounts, $v['prize_key'], 0);
                        // 账单订单数
                        $v['statementOrderCount'] = Arrays::value($statementOrderCounts, $v['prize_key'], 0);
                        // 应结金额
                        // 已结金额
                        // 未结金额
                        // 应结笔数
                        // 已结笔数
                        // 未结笔数
                    }

                    return $lists;
                });
    }

    /**
     * 获取全部子key
     * @param type $prizeKey    价格key
     * @param type $isDeep      是否深度递归
     * @return type
     */
    public static function getChildKeys($prizeKey, $isDeep = false) {
        $con[] = ['p_key', '=', $prizeKey];
        $lists = self::staticConList($con);
        $prizeKeys = array_column($lists, 'prize_key');     // self::mainModel()->where( $con )->column('prize_key');
        if ($isDeep && $prizeKeys) {
            $finalKeys = $prizeKeys;
            foreach ($prizeKeys as $key) {
                $tmpKeys = self::getChildKeys($key, $isDeep);
                if ($tmpKeys) {
                    $finalKeys = array_merge($finalKeys, $tmpKeys);
                }
            }
            $prizeKeys = $finalKeys;
        }

        return $prizeKeys;
    }

    /**
     * 订单价格key取价格信息
     * @param type $orderId
     * @param type $prizeKey
     * @return int
     */
    public static function orderPrizeKeyGetPrize($orderId, $prizeKey) {
        Debug::debug('orderPrizeKeyGetPrize，调用', $orderId . '_' . $prizeKey);
        $inst = OrderService::getInstance($orderId)->orderSaleTypeInst();
        if (!$inst->hasPrizeKey($prizeKey)) {
            // 销售类型没有这个价格，返回0；
            return 0;
        }

        $info = self::getByPrizeKey($prizeKey);
        // Debug::dump($info);
        Debug::debug('orderPrizeKeyGetPrize，的getByPrizeKey_' . $prizeKey, $info);
        // 费用群组：商品；订单
        $keyGroup = Arrays::value($info, 'key_group');
        // 费用类型：付款；退款
        $type = Arrays::value($info, 'type');

        //费用群组：商品
        if ($keyGroup == 'goods') {
            if ($type == 'ref') {
                //【退款】 ref
                //todo,优化合并，在退款规则中再找一找：20210319
                Debug::debug('orderPrizeKeyGetPrize，从退款获得的$prizeKey', $prizeKey);
                // 订单被谁(哪个角色)取消
                $cancelBy = OrderService::getInstance($orderId)->fCancelBy();
                return GoodsPrizeRefTplService::orderGetRef($orderId, $prizeKey, $cancelBy);
            } else {
                //【付款】 pay
                Debug::debug('orderPrizeKeyGetPrize，从退款获得的$prizeKey', $prizeKey);
                return OrderService::getInstance($orderId)->prizeKeyGetPrize($prizeKey);
            }
        }
        //费用群组：订单：配送费：todo
        if ($keyGroup == 'order') {
            $prizeKeyInfo = self::getByPrizeKey($prizeKey);
            if (!$prizeKeyInfo) {
                throw new Exception('价格' . $prizeKey . '不存在，请联系开发');
            }
            // 取订单总价
            // $data['goodsPrize'] = OrderGoodsService::orderGoodsPrize($orderId);
            $data['goodsPrize'] = OrderService::getInstance($orderId)->orderGoodsPrize();
            // 计算价格key
            return GoodsPrizeKeyCalcService::prizeKeyIdCalc($prizeKeyInfo['id'], $data);
        }
        //其他非法群组，返回0
        return 0;
    }

    /**
     * 订单总价；包含配送费等
     * @param type $orderId
     */
    public static function orderPrize($orderId) {
        $inst = OrderService::getInstance($orderId)->orderSaleTypeInst();
        Debug::debug(__CLASS__ . __FUNCTION__ . '$inst', $inst);
        $buyerPayPrizeKeys = $inst->buyerPayPrizeKey();
        $prize = 0;
        foreach ($buyerPayPrizeKeys as $prizeKey) {
            $prize += GoodsPrizeKeyService::orderPrizeKeyGetPrize($orderId, $prizeKey);
        }
        return $prize;
    }

    /**
     * 获取某个价格key的依赖key
     * （通常用于中介平台的加价）
     */
    public static function getRelyKey($prizeKey) {
        $con[] = ['prize_key', '=', $prizeKey];
        $info = self::find($con);
        return Arrays::value($info, 'rely_key');
    }

    /**
     * 价格key取信息
     * @param type $prizeKey
     * @return type
     */
    public static function getByPrizeKey($prizeKey) {
        $con[] = ['prize_key', '=', $prizeKey];
        $res = self::staticConFind($con);
        if(!$res){
            // 20231208:增加通用配置
            $res = self::comCateLevelFind($con);
        }
        return $res;
    }

    /**
     * 如果在mainKey中，为买家；
     * 不在mainKey中，根据 prize_key 查 belong_role
     */
    public static function keyBelongRole($key) {
        $con1[] = ['prize_key', '=', $key];
        $info = self::staticConFind($con1);
        Debug::debug('keyBelongRole的信息', $info);
        if (!$info) {
            // 20231208
            $info = self::comCateLevelFind($con1);
            if(!$info){
                throw new Exception('价格' . $key . '不存在');
            }
        }
        if ($info['from_role'] == $info['to_role']) {
            return $info['from_role'];
        } else {
            return str_replace("plate", "", $info['from_role'] . $info['to_role']);
        }
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
     * goods:表示价格可拆到具体商品：如商品单价；
     * order:表示价格是整单的价，不可拆分：如配送费；包装费；
     * @return type
     */
    public function fKeyGroup() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 归属价格
     */
    public function fPid() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 商品id
     */
    public function fGoodsId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 费用类型:次
     */
    public function fPrizeType() {
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
     * 归属角色
     */
    public function fBelongRole() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 归属角色
     */
    public function fBelongUserId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 报价
     */
    public function fPrize() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 分段字段
     * @return type
     */
    public function fScopeField() {
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
