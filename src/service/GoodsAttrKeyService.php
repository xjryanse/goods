<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;

/**
 * 商品明细
 */
class GoodsAttrKeyService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsAttrKey';

    public static function extraPreUpdate(&$data, $uuid) {
        $attrValues = Arrays::value($data, 'attrValues');
        //入参：一维数组
        if ($attrValues && is_array($attrValues) && $uuid) {
            //数组保存
            GoodsAttrValueService::keyIdValueSave($uuid, $attrValues);
        }
    }

    public static function extraPreSave(&$data, $uuid) {
        $attrValues = Arrays::value($data, 'attrValues');
        //入参：一维数组
        if ($attrValues && is_array($attrValues) && $uuid) {
            //数组保存
            GoodsAttrValueService::keyIdValueSave($uuid, $attrValues);
        }
    }

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    $cond[] = ['is_delete', '=', 0];
                    //sku查询数组
                    $valueArr = GoodsAttrValueService::groupBatchCount('key_id', $ids, $cond);
                    //值数组
                    $valueListArr = GoodsAttrValueService::groupBatchSelect('key_id', $ids);
                    $goodsAttrCountArr = GoodsAttrService::groupBatchCount('attr_name', $ids);
                    foreach ($lists as &$v) {
                        // 属性数
                        $v['valueCounts'] = Arrays::value($valueArr, $v['id'], 0);

                        // 拼接值字符串数组
                        $v['valueStr'] = implode(',', array_column(Arrays::value($valueListArr, $v['id'], []), 'attr_value'));
                        // 商品属性数
                        $v['goodsAttrCounts'] = Arrays::value($goodsAttrCountArr, $v['id'], 0);
                    }
                    return $lists;
                });
    }

    /*
     * 商品分类id，取属性（含值）
     */

    public static function listWithValue($cateId) {
        $con[] = ['cate_id', '=', $cateId];
        $attrKeys = self::lists($con, 'id', 'id,attr_name as name');
        $attrKeys = $attrKeys ? $attrKeys->toArray() : [];
        foreach ($attrKeys as &$value) {
            $cond = [];
            $cond[] = ['key_id', '=', $value['id']];
            $value['values'] = GoodsAttrValueService::lists($cond, '', 'id,attr_value as name');
        }
        return $attrKeys;
    }

    /**
     * 商品分类取属性
     * @param type $cateId
     * @return type
     */
    public static function getCateAttr($cateId) {
        $con[] = ['cate_id', '=', $cateId];
        $cateAttr = self::mainModel()->where($con)->field('id,cate_id,attr_name')->select();
        return $cateAttr;
    }

    /**
     * 逐步替代getCateAttr方法
     * @param type $cateIds
     * @return type
     */
    public static function getCateAttrs($cateIds) {
        $con[] = ['cate_id', 'in', $cateIds];
        $cateAttr = self::mainModel()->where($con)->field('id,cate_id,attr_name')->select();
        $data = [];
        foreach ($cateAttr as &$v) {
            $data[$v['cate_id']][] = $v;
        }
        return $data;
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
    public function fAttrName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 
     */
    public function fCateId() {
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
