<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
/**
 * 商品明细
 */
class GoodsSpuService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsSpu';

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
    public function extraPreDelete()
    {

    }
    /**
     * 钩子-删除后
     */
    public function extraAfterDelete()
    {

    }
    /**
     * 更新商品价格
     * 查到最大最小
     */
    public function updatePrize(){
        if(!$this->uuid){
            return false;
        }
        $con[]      = ['spu_id','=',$this->uuid];
        $prizeArr   = GoodsService::mainModel()->where($con)->order('goodsPrize')->column('goodsPrize');
        $data['min_prize']    = Arrays::value($prizeArr, 0, 0);
        $data['max_prize']    = Arrays::value(array_reverse($prizeArr), 0, 0);
        self::mainModel()->where('id',$this->uuid)->update($data);
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
    
    public function fCustomerId() {
        return $this->getFFieldValue(__FUNCTION__);
    }    

    /**
     * 
     */
    public function fName() {
        return $this->getFFieldValue(__FUNCTION__);
    }
    //分类id
    public function fCateId() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fMainPic() {
        return $this->getFFieldValue(__FUNCTION__);
    }
    /**
     * 
     */
    public function fSubPics() {
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
