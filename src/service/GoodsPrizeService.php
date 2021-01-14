<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;

/**
 * 商品价格设置
 */
class GoodsPrizeService {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsPrize';

    public static function save( $data ){
        $pKey   = GoodsPrizeTplService::prizeKeyGetPKey($data['prize_key']);
        $pInfo  = self::getByGoodsAndPrizeKey( $data['goods_id'], $pKey ); 
        $data['pid']    = $pInfo ? $pInfo['id'] : '';
        $res    = self::commSave($data);
        return $res;
    }
    
    public function update( $data ){
        $info = $this->get();
        $prizeKey = isset($data['prize_key'])   ? $data['prize_key']    : $info['prize_key'];
        $goodsId  = isset($data['goods_id'])    ? $data['goods_id']     : $info['goods_id'];

        $pKey   = GoodsPrizeTplService::prizeKeyGetPKey( $prizeKey );
        $pInfo  = self::getByGoodsAndPrizeKey( $goodsId, $pKey ); 
        $data['pid']    = $pInfo ? $pInfo['id'] : '';
        $res    = $this->commUpdate($data);
        return $res;
    }
    
    /*
     * 用商品id查询，并绑定键
     */

    public static function selectByGoodsIdBindKey($goodsId) {
        return self::mainModel()->where('goods_id', $goodsId)->column('*', 'prize_key');
    }

    public static function saveByKey($goodsId, $key, $prize, $data = []) {
        $con[] = ['goods_id', '=', $goodsId];
        $con[] = ['prize_key', '=', $key];
        //价格信息保存
        $data['prize'] = $prize;
        $data['goods_id'] = $goodsId;
        $data['prize_key'] = $key;

        $info = GoodsPrizeService::find($con);
        if ($info) {
            $res = GoodsPrizeService::getInstance($info['id'])->update($data);
        } else {
            Arrays::unset($data, ['id']);
            $res = GoodsPrizeService::save($data);
        }
        return $res;
    }

    /**
     * 根据商品id和价格key，取价格信息（一般用于分润）
     * @param type $goodsId
     * @param type $prizeKey
     */
    public static function getByGoodsAndPrizeKey($goodsId, $prizeKey) {
        $con[] = ['goods_id', '=', $goodsId];
        $con[] = ['prize_key', '=', $prizeKey];
        return self::find($con);
    }
    /**
     * 获取商品价格
     * @param type $goodsId     商品id
     * @param type $prizeKeys   过滤条件
     */
    public static function sumGoodsPrizeByPrizeKeys( $goodsId ,$prizeKeys )
    {
        $con[] = ['goods_id','=',$goodsId];
        $con[] = ['prize_key','in',$prizeKeys];
        return self::sum($con, 'prize');
    }
    
    /*
     * 根据归属角色，获取商品总价格
     */
    public static function getGoodsPrizeSumByBelongRole( $goodsId )
    {
        $saleType       = GoodsService::getInstance( $goodsId )->fSaleType();
        $belongRoles    = GoodsPrizeTplService::columnBelongRolesBySaleType( $saleType );

        $prize = [];
        //各角色价格
        foreach( $belongRoles as $belongRole ){
            $finalKeys          = GoodsPrizeTplService::getFinalKeys ( $saleType, $belongRole );
            $prize[$belongRole] = self:: sumGoodsPrizeByPrizeKeys( $goodsId, $finalKeys );
        }
        //最终合并价格
        $finalKeys      = GoodsPrizeTplService::getFinalKeys( $saleType, $belongRoles );
        $prize['total'] = self:: sumGoodsPrizeByPrizeKeys( $goodsId, $finalKeys );

        return $prize;
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
