<?php

namespace xjryanse\goods\service;

use xjryanse\logic\DbOperate;
/**
 * 商品明细
 */
class GoodsService {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\SubServiceTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\Goods';

    /**
     * 额外详情信息
     */
    protected static function extraDetail( &$item ,$uuid )
    {
        //①添加商品来源表数据
        $subService = DbOperate::getService( $item['goods_table'] );
        self::addSubServiceData($item, $subService, $item['goods_table_id']);
        //②添加商品销售分表数据:按类型提取分表服务类
        self::addSubData($item, $item['sale_type']);
        //③添加价格数据
        $prize          = GoodsPrizeService::getGoodsPrizeSumByBelongRole( $uuid );
        $prizeTableName = GoodsPrizeService::mainModel()->getTable();
        foreach( $prize as $key=>$value){
            $item[$prizeTableName.'.'.$key] = $value;
        }
        return $item;
    }
    
    /**
     * 适用于1个商品多种卖法(如商标：授权，租用，购买)
     * @param type $goodsTableId    商品来源表id
     * @param type $saleType        销售类型
     * @param type $con             其他查询条件
     */
    public static function getBySaleType($goodsTableId, $saleType, $con = []) {
        $con[] = ["goods_table_id", "=", $goodsTableId];
        $con[] = ["sale_type", "=", $saleType];
        return self::find($con);
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
     * 商品详情表
     */
    public function fGoodsTable() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 商品详情表id
     */
    public function fGoodsTableId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 商品名称
     */
    public function fGoodsName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 归属店铺
     */
    public function fShopId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 销售类型：商标授权、商标租用、购买商标、购买网店
     */
    public function fSaleType() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 是否上架：0否，1是
     */
    public function fIsOn() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 库存量
     */
    public function fStock() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 审核状态：待审核，已同意，已拒绝
     */
    public function fAuditStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 商品状态：
      offsale下架
      onsale上架
      authorize:授权中
      buying:购买中
      renting:租赁中
      transferd:已过户(相当于失效)
     */
    public function fGoodsStatus() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 审核用户
     */
    public function fAuditUserId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 审核意见
     */
    public function fAuditDescribe() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 浏览人次
     */
    public function fScanTimes() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 浏览人数
     */
    public function fScanUsers() {
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
