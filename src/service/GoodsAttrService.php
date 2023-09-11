<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Debug;
use think\Db;
use Exception;

/**
 * 商品明细
 */
class GoodsAttrService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsAttr';

    public static function getGoodsAttr($goodsIds) {
        $con[] = ['goods_id', 'in', $goodsIds];
        $lists = self::mainModel()->where($con)->field('id,goods_id,attr_name,attr_value')->cache(86400)->select();
        //按cate_id，聚合为数组
        $data = [];
        foreach ($lists as &$v) {
            $data[$v['goods_id']][$v['attr_name']] = $v['attr_value'];
        }
        return $data;
    }

    public static function goodsGetAttrKey($goodsId) {
        $goodsTable = GoodsService::getTable();
        $attrKeyTable = GoodsAttrKeyService::getTable();
        $attrTable = GoodsAttrService::getTable();
        //order by解决多个属性生成的key错乱问题
        $sql = 'select bb.attr_value from '
                . '(select b.* from ' . $goodsTable . ' as a inner join ' . $attrKeyTable . ' as b on a.cate_id = b.cate_id where a.id="' . $goodsId . '") as aa '
                . 'left join (select * from ' . $attrTable . ' where goods_id = "' . $goodsId . '") as bb on aa.id = bb.attr_name order by bb.attr_name';

        Debug::debug('goodsGetAttrKey的$sql', $sql);
        $arr = Db::query($sql);
        return implode('_', array_column($arr, 'attr_value'));
    }

    public static function skuUpdateAttr($skuId, $attrValues) {
        //删，写
        if (!$skuId) {
            throw new Exception('$skuId必须');
        }
        //键值对转化为保存的数组
        $con[] = ['goods_id', '=', $skuId];
        //删
        self::mainModel()->where($con)->delete();
        //写
        self::saveAll($attrValues, ['goods_id' => $skuId]);
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
    public function fAttrName() {
        return $this->getFFieldValue(__FUNCTION__);
    }

    /**
     * 商品详情表id
     */
    public function fAttrValue() {
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
