<?php
namespace xjryanse\goods\service;

/**
 * 商品价格设置
 */
class GoodsPrizeKeyService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsPrizeKey';

    public static function getByPrizeKey( $prizeKey )
    {
        $con[] = ['prize_key','=',$prizeKey];
        return self::find( $con );
    }
    /**
     * 如果在mainKey中，为买家；
     * 不在mainKey中，根据 prize_key 查 belong_role
     */
    public static function keyBelongRole( $key )
    {
        $con1[] = ['prize_key','=',$key];
        $info   = self::find( $con1 );
        if(!$info){
            throw new Exception('价格'.$key.'不存在');
        }
        if( $info['from_role'] == $info['to_role'] ){
            return $info['from_role'];
        } else {
            return str_replace("plate","",$info['from_role'].$info['to_role']);
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
