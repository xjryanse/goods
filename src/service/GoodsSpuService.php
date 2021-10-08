<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use xjryanse\store\service\StoreChangeDtlService;
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
     * 销售类型取spuid，适用于会员充值场景
     * @param type $saleType
     */
    public static function getSpuIdBySaleType($saleType){
        $con[] = ['sale_type','=',$saleType];
        $con[] = ['company_id','=',session(SESSION_COMPANY_ID)];
        $con[] = ['is_delete','=',0];
        $con[] = ['status','=',1];
        return self::mainModel()->where($con)->value('id');
    }
    /**
     * 比较优化的extraDetail方法
     * @param type $ids
     */
    public static function extraDetails( $ids ){
        //数组返回多个，非数组返回一个
        $isMulti = is_array($ids);
        if(!is_array($ids)){
            $ids = [$ids];
        }
        $con[] = ['id','in',$ids];
        $listRaw = self::mainModel()->where($con)->select();
        $lists = $listRaw ? $listRaw->toArray() : [];
        //商品分类id
        $cateIds = array_unique(array_column($lists, 'cate_id'));
        $cateInfos  = GoodsAttrKeyService::getCateAttrs($cateIds);
        $cateValues = GoodsAttrValueService::cateIdValues($cateIds);
        $skuLists   = GoodsService::listsWithAttrBySpuIds($ids);
        
        foreach($lists as &$goodsInfo){
            $goodsInfo['attrKeys']  = Arrays::value($cateInfos, $goodsInfo['cate_id'],[]);
            $goodsInfo['attrs']     = Arrays::value($cateValues, $goodsInfo['cate_id'],[]);
            $goodsInfo['currentUserId'] = session(SESSION_USER_ID);
            // 销量
            $goodsInfo['saleCount']     = '500+';
            // 浏览量
            $goodsInfo['scanCount']     = '2000+';
            $goodsInfo['skuList']       = $skuLists[$goodsInfo['id']];
        }
        
        return $isMulti ? $lists : $lists[0];
        // return $isMulti ? Arrays2d::fieldSetKey($lists, 'id') : $lists[0];
    }
    /**
     * 以属性为键的商品信息。
     * 可一次性编辑同一个spu下多个商品
     * @return type
     */
    public function skuAttrKeyList(){
        $info = $this->get();
        $cateId = Arrays::value($info, 'cate_id');
        // spuId取全部skuId
        $skuIds = $this->skuIds();
        $skuInfos = GoodsService::batchGet($skuIds);
        $array = [];
        foreach($skuIds as $skuId){
            $key = GoodsAttrService::goodsGetAttrKey($skuId);
            $array[$key] = $skuInfos[$skuId];
        }
        //属性key
        $goodsAttrKeys = GoodsCateService::getInstance($cateId)->attrCombineKeys();
        
        $dataArr = array_fill_keys($goodsAttrKeys, (new \stdClass()));
        return array_merge($dataArr, $array);
    }
    
    public function skuIds(){
        $con[] = ['spu_id','=',$this->uuid];
        return GoodsService::mainModel()->where($con)->column('id');
    }
    
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
    public function fSaleType() {
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
