<?php

namespace xjryanse\goods\service;

use xjryanse\logic\Arrays;
use xjryanse\logic\Arrays2d;
use xjryanse\logic\Debug;
use xjryanse\order\service\OrderService;
use xjryanse\order\service\OrderFlowNodeTplService;
use xjryanse\order\service\OrderGoodsService;
use xjryanse\goods\service\GoodsService;
use xjryanse\goods\service\GoodsPrizeTplService;
use xjryanse\logic\Cachex;
use Exception;

/**
 * 商品价格设置
 */
class GoodsTypeService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\GoodsType';

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    $saleTypes = array_column($lists, 'sale_type');
                    $prizeKeyArr = GoodsTypePrizeKeyService::groupBatchCount('sale_type', $saleTypes);
                    //商品数
                    $goodsSkuArr = GoodsService::groupBatchCount('sale_type', $saleTypes);
                    $goodsSpuArr = GoodsSpuService::groupBatchCount('sale_type', $saleTypes);
                    //订单数
                    $orderArr = OrderService::groupBatchCount('order_type', $saleTypes);
                    //流程模板
                    $nodeTplArr = OrderFlowNodeTplService::groupBatchCount('sale_type', $saleTypes);
                    //价格模板
                    $prizeTplArr = GoodsPrizeTplService::groupBatchCount('sale_type', $saleTypes);

                    foreach ($lists as &$v) {
                        $v['prizeKeyCount'] = Arrays::value($prizeKeyArr, $v['sale_type'], 0);
                        $v['goodsCount'] = Arrays::value($goodsSkuArr, $v['sale_type'], 0);
                        $v['goodsSpuCount'] = Arrays::value($goodsSpuArr, $v['sale_type'], 0);
                        $v['orderCount'] = Arrays::value($orderArr, $v['sale_type'], 0);
                        // 流程模板数
                        $v['nodeTplCount'] = Arrays::value($nodeTplArr, $v['sale_type'], 0);
                        // 价格模板数
                        $v['prizeTplCount'] = Arrays::value($prizeTplArr, $v['sale_type'], 0);
                    }

                    return $lists;
                });
    }

    /**
     * 销售类型取商品id
     * @param type $saleType
     */
    public static function getGoodsId($saleType) {
        return Cachex::funcGet(__CLASS__ . __FUNCTION__ . $saleType, function() use ($saleType) {
                    $con[] = ['sale_type', '=', $saleType];
                    $info = self::find($con);
                    Debug::debug('信息', $info);
                    $goodsCate = $info['goods_cate'];
                    if (!in_array($goodsCate, ['single', 'fixed'])) {
                        throw new Exception('商品为多个，需要前端传参');
                    }
                    if ($goodsCate == 'fixed') {
                        $cond[] = ['sale_type', '=', $saleType];
                        $goodsIds = GoodsService::column('id', $cond);
                        return $goodsIds ? $goodsIds[0] : '';
                    } else {
                        return 'todo';
                    }
                }, true);
    }

    /**
     *
     */
    public function fId() {
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
