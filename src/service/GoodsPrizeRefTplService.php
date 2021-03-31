<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
use xjryanse\finance\service\FinanceStatementOrderService;
use xjryanse\goods\service\GoodsPrizeService;
use xjryanse\goods\service\GoodsPrizeKeyService;
use xjryanse\order\service\OrderService;
/**
 * 退款规则模板
 */
class GoodsPrizeRefTplService {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsPrizeRefTpl';
    
    protected static function refKeyGetRefs( $refKey ,$cancelBy = "")
    {
        $con[] = ['ref_key','=',$refKey];
        if( $cancelBy ){
            $con[] = ['cancel_by','=',$cancelBy ];
        }
        return self::lists( $con );
    }
    /**
     * 获取不退金额（扣罚，用于制作合同）
     */
    public static function orderGetNoRef( $orderId, $refKey, $cancelBy = '' )
    {
        //退款规则
        $rules      = self::refKeyGetRefs( $refKey ,$cancelBy );
        if(!$rules){
            return 0;
        }
        $noRefMoney = 0;    //不退金额
        //订单已付金额
        $goodsId    = OrderService::getInstance( $orderId )->fGoodsId();
        foreach( $rules as $value ){
            $goodsPrize = GoodsPrizeService::getByGoodsAndPrizeKey($goodsId, $value['prize_key']);
            $tmpMoney   = floatval( Arrays::value($goodsPrize,'prize') ) * $value['rate'];
            Debug::debug( "商品".$goodsId.'收取金额'.$value['prize_key'] , $tmpMoney );
            $noRefMoney += $tmpMoney;
            Debug::debug( "商品".$goodsId.'总收取金额',$noRefMoney);
        }
        return $noRefMoney;
    }
    
    /**
     * 订单id和价格，提取应退金额
     * @param type $orderId
     * @param type $refKey
     * @param type $cancelBy
     */
    public static function orderGetRef( $orderId, $refKey, $cancelBy = '' )
    {
        $refType = GoodsPrizeKeyService::keyBelongRole($refKey);
        //客户已付金额
        $con1[]     = ['order_id','=',$orderId];
        $con1[]     = ['has_settle','=',1];
        $con1[]     = ['statement_cate','=','buyer'];
        $buyerPay   = FinanceStatementOrderService::mainModel()->where( $con1 )->sum('need_pay_prize');    
        Debug::debug('orderGetRef,客户已付',$buyerPay);
        //已付供应商金额
        $con2[] = [ 'order_id','=',$orderId ];
        $con2[] = [ 'has_settle','=',1 ];
        $con2[] = [ 'statement_cate','=','seller' ];
        $paySeller  = FinanceStatementOrderService::mainModel()->where( $con2 )->sum('need_pay_prize'); 
        Debug::debug('orderGetRef,已付供应商',$paySeller);
        //获取不退的金额
        $noRefMoney = self::orderGetNoRef($orderId, $refKey,$cancelBy);
        //TODO 20210319
        //超出金额才退款
        if($refType == "buyer" && abs($buyerPay) > abs($noRefMoney)){
            Debug::debug( "buyer" ,$buyerPay);
            Debug::debug( $buyerPay ,$noRefMoney);
            return abs($buyerPay) - $noRefMoney;
        }
        //TODO 20210319
        //超出金额才退款
        if($refType == "seller"  && abs($paySeller) > abs($noRefMoney)){
            Debug::debug( "seller" ,$paySeller);
            Debug::debug( $paySeller ,$noRefMoney);
            return abs($paySeller) - $noRefMoney;
        }
        return false;
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
     * 谁取消
     */
    public function fCancelBy() {
        return $this->getFFieldValue(__FUNCTION__);
    }
    /**
     * 流程key
     */
    public function fFlowNodeKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 费用名称
     */
    public function fGrade() {
        return $this->getFFieldValue(__FUNCTION__);
    }
    /**
     * 退款角色
     */
    public function fBelongRole() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 1应收(供应商退)，2应付(退客户)
     */
    public function fChangeType() {
        return $this->getFFieldValue(__FUNCTION__);
    }
    /**
     * 退款key：tmAuthSellerRef
     */
    public function fRefKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }
    /**
     * 归属角色
     */
    public function fPrizeKey() {
        return $this->getFFieldValue(__FUNCTION__);
    }
    /**
     * 退款比率：0.6表示退60%
     */
    public function fRate() {
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