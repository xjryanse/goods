<?php
namespace xjryanse\goods\service\cate;

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
    public function fPid() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     *
     */
    public function fGroup() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    public function fCate() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 商品详情表
     */
    public function fCatePic() {
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
