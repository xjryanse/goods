<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;
use Exception;

/**
 * 商品明细
 */
class GoodsCateService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsCate';

    /**
     * 钩子-保存前
     */
    public static function extraPreSave(&$data, $uuid) {
        $attrKeys = Arrays::value($data, 'attrKeys');
        if ($attrKeys && is_array($attrKeys)) {
            //批量保存
            foreach ($attrKeys as $value) {
                $value['attr_name'] = $value['name'];   //兼容前端
                $value['cate_id'] = $uuid;
                $keyId = GoodsAttrKeyService::saveGetId($value);
                if (isset($value['values'])) {
                    foreach ($value['values'] as $attrValue) {
                        $attrValue['attr_value'] = $attrValue['name'];   //兼容前端
                        $attrValue['key_id'] = $keyId;   //兼容前端
                        GoodsAttrValueService::saveGetId($attrValue);
                    }
                }
            }
        }
    }

    /**
     * 组合key成为数组
     * array(8) {
      [0] => string(13) "小份_加热"
      [1] => string(16) "小份_不加热"
      }
     * @return type
     */
    public function attrCombineKeys() {
        $mainArray = $this->combineArray();
        Debug::debug('attrCombineKeys' . $mainArray);
        // 数组转字符串
        foreach ($mainArray as &$v) {
            $v = implode('_', $v);
        }
        return $mainArray;
    }

    /**
     * array(2) {
      [0] => array(2) {
      [0] => string(6) "小份"
      [1] => string(6) "加热"
      }
      [1] => array(2) {
      [0] => string(6) "小份"
      [1] => string(9) "不加热"
      }
      }
     * @return type
     */
    public function combineArray() {
        $lists = GoodsAttrKeyService::listWithValue($this->uuid);
        Debug::debug('combineArray的lists', $lists);
        if (!$lists) {
            return [];
        }
        $mainArray = [''];
        foreach ($lists as $value) {
            $subArray = array_column($value['values']->toArray(), 'name');
            $mainArray = Arrays::combineArray($mainArray, $subArray);
        }
        return $mainArray;
    }

    /**
     * 组合key，取出原始组合数组
     */
    public function combineKeyGetAttrArr($combineKey) {
        // key字段
        $mainArray = $this->combineArray();
        // 写入数组
        $mainArrayData = $this->attrCombineKeysArr();

        $keyAll = [];
        foreach ($mainArray as $k2 => $v2) {
            $temp = [];
            foreach ($v2 as $k3 => $v3) {
                $temp[] = $mainArrayData[$k3][$v3];
            }
            $keyAll[implode('_', $v2)] = $temp;
        }

        return $keyAll[$combineKey];
    }

    public function attrCombineKeysArr() {
        $lists = GoodsAttrKeyService::listWithValue($this->uuid);
        $mainArrayData = [];
        foreach ($lists as $key => $val) {
            $temp = [];
            foreach ($val['values'] as $subValue) {
                $eeArr = [];
                $eeArr['attr_name'] = $val['id'];
                $eeArr['attr_value'] = $subValue['name'];
                $temp[$subValue['name']] = $eeArr;
                //$arr[] = $value ? $value.'_'.$subValue['name'] : $subValue['name'];
            }
            $mainArrayData[] = $temp;
        }

        return $mainArrayData;
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
        self::extraPreSave($data, $uuid);
    }

    /**
     * 钩子-更新后
     */
    public static function extraAfterUpdate(&$data, $uuid) {
        
    }

    /**
     * 钩子-删除前
     */
    public function extraPreDelete() {
        self::checkTransaction();
        $con[] = ['cate_id', '=', $this->uuid];
        $res = GoodsSpuService::mainModel()->master()->where($con)->count(1);
        if ($res) {
            throw new Exception('该分类有spu记录，不可删除');
        }
        $sku = GoodsService::mainModel()->master()->where($con)->count(1);
        if ($sku) {
            throw new Exception('该分类有商品记录，不可删除');
        }
    }

    /**
     * 钩子-删除后
     */
    public function extraAfterDelete() {
        
    }

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    //子级数
                    $pidsArr = self::groupBatchCount('pid', $ids);
                    //sku查询数组
                    $skuArr = GoodsService::groupBatchCount('cate_id', $ids);
                    //spu查询数组
                    $spuArr = GoodsSpuService::groupBatchCount('cate_id', $ids);
                    //属性key数组
                    $attrKeyArr = GoodsAttrKeyService::groupBatchCount('cate_id', $ids);
                    foreach ($lists as &$v) {
                        //子级数
                        $v['childCounts'] = Arrays::value($pidsArr, $v['id'], 0);
                        //sku数
                        $v['skuCounts'] = Arrays::value($skuArr, $v['id'], 0);
                        //spu数
                        $v['spuCounts'] = Arrays::value($spuArr, $v['id'], 0);
                        //attrKey数
                        $v['attrKeyCounts'] = Arrays::value($attrKeyArr, $v['id'], 0);
                    }
                    return $lists;
                });
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

}
