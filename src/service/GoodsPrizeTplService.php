<?php

namespace xjryanse\goods\service;

/**
 * 商品价格模板
 */
class GoodsPrizeTplService {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsPrizeTpl';
    
    /**
     * 获取始祖key，顶级无父key的key
     */
    public static function getAncestorKey( $saleType, $belongRole )
    {
        $con[] = ['sale_type','=',$saleType ];
        $con[] = ['belong_role','=',$belongRole ];
        $con[] = ['p_key','=','' ];
        $info = self::find( $con );
        return $info ? $info['prize_key'] : '';
    }
    /**
     * 价格key取父价格key
     */
    public static function prizeKeyGetPKey( $prizeKey )
    {
        $con[] = ['prize_key','=',$prizeKey];
        $info = self::find($con); 
        return $info ? $info['p_key'] : '';
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
        return self::mainModel()->where($con)->column('prize_key');
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
    public static function getFinalKeys( $saleType, $belongRole = '')
    {
        $con[] = ['sale_type','=',$saleType];
        $con[] = ['p_key','=',''];
        if($belongRole){
            $con[] = ['belong_role','in',$belongRole];
        }
        return self::mainModel()->where( $con )->column('prize_key');
    }
    /**
     * 根据销售类型，取归属角色列表
     */
    public static function columnBelongRolesBySaleType( $saleType )
    {
        $con[] = ['sale_type','=',$saleType];
        return self::mainModel()->where( $con )->column('distinct belong_role');
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
