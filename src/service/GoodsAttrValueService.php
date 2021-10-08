<?php

namespace xjryanse\goods\service;

/**
 * 商品明细
 */
class GoodsAttrValueService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsAttrValue';

    public static function cateIdValues( $cateIds ){
        $con[] = ['b.cate_id','in',$cateIds];
        $lists = self::mainModel()->field('a.id,a.key_id,a.attr_value,b.cate_id')->alias('a')
                ->join('w_goods_attr_key b','a.key_id=b.id')->where($con)->select();
        //按cate_id，聚合为数组
        $data = [];
        foreach($lists as &$v){
            $data[$v['cate_id']][] = $v;
        }
        return $data;
    }
    
    /**
     * 键id保存值
     * @param String $keyId     字符串
     * @param Array $attrValues   一维数组
     */
    public static function keyIdValueSave( $keyId, $attrValues){
        self::checkTransaction();
        $cond   = [];
        $cond[] = ['key_id','=',$keyId];
        //先删
        self::mainModel()->where($cond)->delete();
        $attrValueData = [];
        foreach($attrValues as $value){
            $attrValueData[] = ['key_id'=>$keyId,'attr_value'=>$value];
        }
        //再加
        self::saveAll($attrValueData);
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
    public function fAttrValue() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fKeyId() {
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
