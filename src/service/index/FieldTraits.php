<?php

namespace xjryanse\goods\service\index;

/**
 * 分页复用列表
 */
trait FieldTraits{
   
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

    public function fSpuId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fCompanyId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fCustomerId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 商品图片
     */
    public function fGoodsPic() {
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
    public function fGoodsDesc() {
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
     * 库存量
     */
    public function fSellerUserId() {
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

    public function fUnit() {
        return $this->getFFieldValue(__FUNCTION__);
    }

}
