<?php

namespace xjryanse\goods\service;

use xjryanse\logic\DbOperate;
use xjryanse\logic\DataCheck;
use xjryanse\logic\Arrays;
use xjryanse\logic\Cachex;
use xjryanse\logic\Debug;
use xjryanse\logic\Sql;
use xjryanse\store\service\StoreChangeDtlService;
use xjryanse\order\service\OrderService;
use xjryanse\order\service\OrderGoodsService;
use xjryanse\order\service\OrderShoppingCartService;
use Exception;

/**
 * 商品明细
 */
class GoodsService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\StaticModelTrait;
    use \xjryanse\traits\SubServiceTrait;
    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\Goods';
    
    //直接执行后续触发动作
    protected static $directAfter = true;
    
    protected static $fixedFields = ['company_id', 'creater', 'create_time', 'sale_type'];
    // 商品的价格列表
    protected $prizeList = [];
    protected $hasPrizeListQuery = false;
    ///从ObjectAttrTrait中来
    // 定义对象的属性
    protected $objAttrs = [];
    // 定义对象是否查询过的属性
    protected $hasObjAttrQuery = [];
    // 定义对象属性的配置数组
    protected static $objAttrConf = [
        //20220701 商品属性
        'goodsAttr' => [
            'class' => '\\xjryanse\\goods\\service\\GoodsAttrService',
            'keyField' => 'goods_id',
            'master' => true
        ],
//        'goodsPrize' => [
//            'class' => '\\xjryanse\\goods\\service\\GoodsPrizeService',
//            'keyField' => 'goods_id',
//            'master' => true
//        ]
    ];
    
    use \xjryanse\goods\service\index\FieldTraits;
    use \xjryanse\goods\service\index\CalTraits;
    use \xjryanse\goods\service\index\TriggerTraits;
    use \xjryanse\goods\service\index\DimTraits;
    /**
     * 获取商品价格数组
     */
    public function getPrizeList() {
        Debug::debug('获取前', $this->prizeList);
        if (!$this->prizeList && !$this->hasPrizeListQuery) {
            $cond[] = ['goods_id', '=', $this->uuid];
            $lists = GoodsPrizeService::listSetUudata($cond);
            $listsArr = $lists ? (is_array($lists) ? $lists : $lists->toArray()) : [];
            $this->prizeList = $listsArr;
            //已经有查过了就不再查了，即使为空
            $this->hasPrizeListQuery = true;
        }
        return $this->prizeList;
    }

    /**
     * 设定商品价格数组
     * @param type $data
     */
    public function setPrizeList($data) {
        $this->prizeList = $data;
        $this->hasPrizeListQuery = true;
    }

    /**
     * 弃用，根据spuId，获取id和属性
     */
    public static function listsWithAttrBySpuId($spuId) {
        $con[] = ['spu_id', '=', $spuId];
        $lists = self::lists($con, '', 'id,cate_id,goodsPrize,goods_desc,goods_name,goods_pic,spu_id');
        foreach ($lists as &$value) {
            $cond = [];
            $cond[] = ['goods_id', '=', $value['id']];
            $value['attrs'] = GoodsAttrService::mainModel()->where($cond)->column('attr_value', 'attr_name');
            $value['stock'] = StoreChangeDtlService::getStockByGoodsId($value['id']);
        }
        return $lists;
    }

    /**
     * 适用于一个类型只有一个商品
     */
    public static function getBySaleType($saleType) {
        $cacheKey = __CLASS__ . __FUNCTION__ . $saleType;
        return Cachex::funcGet($cacheKey, function() use ($saleType) {
                    $con[] = ['sale_type', '=', $saleType];
                    return self::find($con);
                }, true);
    }

    /**
     * 适用于一单一价
     */
    public static function saleTypeNewGoods($saleType) {
        
    }

    /**
     * 根据spuId，获取id和属性
     */
    public static function listsWithAttrBySpuIds($spuIds) {
        $con[] = ['spu_id', 'in', $spuIds];
        //20220410，兼容内部领料注释
        //$con[] = ['sellerGoodsPrize','>',0];
//        $listsRaw = self::lists($con,'','id,cate_id,goodsPrize,goods_desc,goods_name,goods_pic,spu_id,stock');
//        $lists = $listsRaw ? $listsRaw->toArray() : [];
        $lists = self::selectX($con, '', 'id,cate_id,goodsPrize,goods_desc,goods_name,goods_pic,spu_id,stock');
        // 获取商品属性
        $goodsIds = array_column($lists, 'id');
        $goodsAttrs = GoodsAttrService::getGoodsAttr($goodsIds);
        foreach ($lists as &$value) {
            $value['attrs'] = Arrays::value($goodsAttrs, $value['id'], []);
            //库存直接拿冗余值
            //$value['stock']    = StoreChangeDtlService::getStockByGoodsId($value['id']);
        }

        $data = [];
        foreach ($lists as &$v) {
            $data[$v['spu_id']][] = $v;
        }
        return $data;
    }

    /**
     * 商品来源表id，全部商品上架
     */
    public static function setOnSaleByGoodsTableId($goodsTableId, $goodsTable) {
        if ($goodsTable) {
            //库存设为1
            $con[] = ['goods_table', '=', $goodsTable];
        }
        $con[] = ['goods_table_id', '=', $goodsTableId];
        self::mainModel()->where($con)->update(['stock' => 1, 'is_on' => 1, 'goods_status' => GOODS_ONSALE]);
        $service = DbOperate::getService($goodsTable);
        $service::getInstance($goodsTableId)->update(['goods_status' => GOODS_ONSALE]);
    }

    /**
     * 根据订单，更新商品状态
     * @param type $preStatus
     * @param type $afterStatus
     * @return type
     */
    public function updateGoodsStatus($preStatus, $afterStatus) {
        $con[] = ['id', '=', $this->uuid];
        $data['goods_status'] = $afterStatus;
        //过滤数据
        $updData = DbOperate::dataFilter(self::mainModel()->getTable(), $data);
        $res = self::mainModel()->where($con)->update($updData);
        //更新商标表或网店表的状态
        $info = $this->get();
        if ($info) {
            $service = DbOperate::getService($info['goods_table']);
            $service::getInstance($info['goods_table_id'])->update($data);
        }
        return $res;
    }

    /**
     * 保存商品取id
     * @param type $goodsTable
     * @param type $goodsTableId    商品表id
     * @param type $saleType        销售类型    
     * @param type $data            保存数据
     * @return type
     */
    public static function saveGoodsGetId($goodsTable, $goodsTableId, $saleType, $data) {
        //删id
        if (isset($data['id'])) {
            unset($data['id']);
        }
        $con = [];
        $con[] = ['goods_table_id', '=', $goodsTableId];
        $con[] = ['sale_type', '=', $saleType];
        $goodsInfo = GoodsService::find($con);
        if ($goodsInfo) {
            $data['id'] = $goodsInfo['id'];
        }
        $data['goods_table'] = $goodsTable;
        $data['goods_table_id'] = $goodsTableId;
        $data['sale_type'] = $saleType;
        self::debug('---保存商品的数据---', $data);
        $res = GoodsService::commSaveGetId($data);

        return $res;
    }

    public function extraPreDelete() {
        self::checkTransaction();
        $con[] = ['goods_id', '=', $this->uuid];
        $count['orderCounts'] = OrderService::count($con);
        $count['orderGoodsCounts'] = OrderGoodsService::count($con);
        $count['storeDtlCounts'] = StoreChangeDtlService::count($con);

        $exception['orderCounts'] = '该商品有订单记录';
        $exception['orderGoodsCounts'] = '该商品有下单记录';
        $exception['storeDtlCounts'] = '该商品有出入库记录';

        foreach ($count as $k => &$v) {
            if ($v) {
                throw new Exception($exception[$k]);
            }
        }
    }

    /**
     * 删除价格数据
     */
    public function extraAfterDelete() {
        self::checkTransaction();
        //删商品
        $con[] = ['goods_id', '=', $this->uuid];
        $lists = GoodsPrizeService::lists($con);
        foreach ($lists as $value) {
            GoodsPrizeService::getInstance($value['id'])->delete();
        }
    }

    /**
     * 额外详情信息(逐步废弃)
     */
    protected static function extraDetail(&$item, $uuid) {
        if (!$item) {
            return false;
        }
        if (Arrays::value($item, 'sale_type')) {
            self::addSubData($item, $item['sale_type']);
        }
        return $item;
    }

    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function($lists) use ($ids) {
                    $cond[] = ['is_delete', '=', 0];
                    //sku查询数组
                    $attrArr = GoodsAttrService::groupBatchCount('goods_id', $ids);
                    //订单数
                    $orderArr = OrderService::groupBatchCount('goods_id', $ids, $cond);
                    //订单商品数
                    $orderGoodsArr = OrderGoodsService::groupBatchCount('goods_id', $ids);
                    //购物车商品数
                    $orderShoppingCartArr = OrderShoppingCartService::groupBatchCount('goods_id', $ids);
                    //仓库出入明细数
                    $storeChangeDtlArr = StoreChangeDtlService::groupBatchCount('goods_id', $ids);

                    $conIncome[] = ['change_type', '=', 1];
                    $incomeDtlCounts = StoreChangeDtlService::groupBatchCount('goods_id', $ids, $conIncome);
                    $conOutcome[] = ['change_type', '=', 2];
                    $outcomeDtlCounts = StoreChangeDtlService::groupBatchCount('goods_id', $ids, $conOutcome);
                    $conRef[] = ['change_type', '=', 3];
                    $refDtlCounts = StoreChangeDtlService::groupBatchCount('goods_id', $ids, $conRef);
                    // 价格数
                    $prizeCounts = GoodsPrizeService::groupBatchCount('goods_id', $ids);

                    foreach ($lists as &$v) {
                        // 价格数
                        $v['prizeCount'] = Arrays::value($prizeCounts, $v['id'], 0);
                        //订单数
                        $v['orderCounts'] = Arrays::value($orderArr, $v['id'], 0);
                        //属性数
                        $v['attrCounts'] = Arrays::value($attrArr, $v['id'], 0);
                        //订单商品数
                        $v['orderGoodsCounts'] = Arrays::value($orderGoodsArr, $v['id'], 0);
                        //购物车商品数
                        $v['orderShoppingCartCounts'] = Arrays::value($orderShoppingCartArr, $v['id'], 0);
                        //仓库出入明细数
                        $v['storeChangeDtlCounts'] = Arrays::value($storeChangeDtlArr, $v['id'], 0);

                        // 入库流水数
                        $v['incomeDtlCounts'] = Arrays::value($incomeDtlCounts, $v['id'], 0);
                        // 出库流水数
                        $v['outcomeDtlCounts'] = Arrays::value($outcomeDtlCounts, $v['id'], 0);
                        // 退库流水数
                        $v['refDtlCounts'] = Arrays::value($refDtlCounts, $v['id'], 0);
                    }
                    return $lists;
                });
    }

    /**
     * 额外输入信息
     */
    public static function extraPreSave(&$data, $uuid) {
        $notices['goods_name'] = '商品名称必须';
        // $notices['goods_pic']   = '商品主图必须';
        $notices['spu_id'] = 'spu_id必须';
        $notices['cate_id'] = 'cate_id必须';
        //20210731谁发谁卖
        $data['seller_user_id'] = Arrays::value($data, 'seller_user_id') ?: session(SESSION_USER_ID);
        $spuId = Arrays::value($data, 'spu_id');
        if ($spuId) {
            $data['cate_id'] = GoodsSpuService::getInstance($spuId)->fCateId();
            $data['sale_type'] = GoodsSpuService::getInstance($spuId)->fSaleType();
        }
        //'goods_pic',只有normal时，goods_pic必须
        DataCheck::must($data, ['goods_name', 'spu_id'], $notices);

        if (!Arrays::value($data, "goods_table") && !Arrays::value($data, "goods_table_id")) {
            $data['goods_table'] = self::mainModel()->getTable();
            $data['goods_table_id'] = $uuid;
        }
        $prizeKeys = GoodsPrizeTplService::saleTypeList($data['sale_type'], session(SESSION_COMPANY_ID));
        if (!$prizeKeys) {
            throw new Exception('销售类型' . $data['sale_type'] . '未配置费用信息，请联系开发人员设置');
        }

        return $data;
    }



    /**
     * 额外输入信息
     */
    public static function extraAfterSave(&$data, $uuid) {
        //商品价格冗余记录
        self::getInstance($uuid)->goodsIsOnSync();
        //一口价写入价格表
        self::getInstance($uuid)->setGoodsPrizeArr();
        //更新spu的价格
        $spuId = self::getInstance($uuid)->fSpuId();
        GoodsSpuService::getInstance($spuId)->updatePrize();
    }

   

    /**
     * 额外输入信息
     */
    public static function extraAfterUpdate(&$data, $uuid) {
        //商品价格冗余记录
        self::getInstance($uuid)->goodsIsOnSync();
        //一口价写入价格表
        self::getInstance($uuid)->setGoodsPrizeArr();
        //更新spu的价格
        $spuId = self::getInstance($uuid)->fSpuId();
        GoodsSpuService::getInstance($spuId)->updatePrize();
    }

    /**
     * 上下架状态同步记录（写入来源表）
     */
    public function goodsIsOnSync() {
        //更新价格
        $saleType = $this->fSaleType();
        $goodsTableName = $this->fGoodsTable();
        $goodsTableId = $this->fGoodsTableId();
        $isOn = $this->fIsOn();

        $field = camelize('is_' . $saleType . '_on');
        $service = DbOperate::getService($goodsTableName);
        if ($service && $service::mainModel()->hasField($field)) {
            return $service::mainModel()->update(['id' => $goodsTableId, $field => $isOn]);
        }
        return false;
    }

    /**
     * 20220701
     * @return boolean
     */
    public function goodsIsOnSyncRam() {
        //更新价格
        $saleType = $this->fSaleType();
        $goodsTableName = $this->fGoodsTable();
        $goodsTableId = $this->fGoodsTableId();
        $isOn = $this->fIsOn();

        $field = camelize('is_' . $saleType . '_on');
        $service = DbOperate::getService($goodsTableName);
        if ($service && $service::mainModel()->hasField($field)) {
            return $service::mainModel()->updateRam(['id' => $goodsTableId, $field => $isOn]);
        }
        return false;
    }

    /**
     * 适用于一口价，设定商品价格
     */
    public function setGoodsPrizeArr() {
        $info = $this->get(0);
        $prizeKeys = GoodsPrizeTplService::saleTypeList($info['sale_type'], $info['company_id']);
        Debug::debug('setGoodsPrizeArr 的 $prizeKeys', $prizeKeys);
        foreach ($prizeKeys as $key) {
            $con = [];
            $con[] = ['goods_id', '=', $this->uuid];
            $con[] = ['prize_key', '=', $key['prize_key']];
            $prizeId = GoodsPrizeService::mainModel()->where($con)->value('id');
            $goodsPrizeArr = [
                "id" => $prizeId ?: self::mainModel()->newId(),
                "goods_id" => $this->uuid,
                "company_id" => Arrays::value($info, 'company_id'),
                "prize_key" => $key['prize_key'],
                "prize_name" => $key['prize_name'],
                "belong_role" => $key['belong_role'],
                "prize" => Arrays::value($info, $key['prize_key']),
                "creater" => Arrays::value($info, 'creater'),
            ];
            Debug::debug('setGoodsPrizeArr 的 价格更新', $goodsPrizeArr);
            if ($prizeId) {
                //更新
                GoodsPrizeService::mainModel()->where('id', $prizeId)->update($goodsPrizeArr);
            } else {
                //新增
                GoodsPrizeService::mainModel()->insert($goodsPrizeArr);
            }
        }
    }

    /**
     * 20220701
     */
    public function setGoodsPrizeArrRam() {
        $info = $this->get(0);
        $prizeKeys = GoodsPrizeTplService::saleTypeList($info['sale_type'], $info['company_id']);
        Debug::debug('setGoodsPrizeArr 的 $prizeKeys', $prizeKeys);
        foreach ($prizeKeys as $key) {
            $con = [];
            $con[] = ['goods_id', '=', $this->uuid];
            $con[] = ['prize_key', '=', $key['prize_key']];
            $prizeId = GoodsPrizeService::mainModel()->where($con)->value('id');
            $goodsPrizeArr = [
                "id"            => $prizeId ?: self::mainModel()->newId(),
                "goods_id"      => $this->uuid,
                "company_id"    => Arrays::value($info, 'company_id'),
                "prize_key"     => $key['prize_key'],
                "prize_name"    => $key['prize_name'],
                "belong_role"   => $key['belong_role'],
                "prize" => Arrays::value($info, $key['prize_key']),
                "creater" => Arrays::value($info, 'creater'),
            ];
            Debug::debug('setGoodsPrizeArr 的 价格更新', $goodsPrizeArr);
            if ($prizeId) {
                GoodsPrizeService::getInstance($prizeId)->updateRam($goodsPrizeArr);
            } else {
                GoodsPrizeService::saveRam($goodsPrizeArr);
            }
        }
    }

    /**
     * 适用于1个商品多种卖法(如商标：授权，租用，购买)
     * @param type $goodsTableId    商品来源表id
     * @param type $saleType        销售类型
     * @param type $con             其他查询条件
     */
    public static function getByGoodsSaleType($goodsTableId, $saleType, $con = []) {
        $con[] = ["goods_table_id", "=", $goodsTableId];
        $con[] = ["sale_type", "=", $saleType];
        return self::find($con);
    }

    /**
     * 20220627
     * @global array $glSqlQuery
     * @return boolean
     */
    public function updateStockRam() {
        global $glSqlQuery;
        $mainTable = self::getTable();
        $mainField = "stock";
        $dtlTable = StoreChangeDtlService::getTable();
        $dtlStaticField = "amount";
        $dtlUniField = "goods_id";
        $dtlCon[] = ['main.id', '=', $this->uuid];
        $sql = Sql::staticUpdate($mainTable, $mainField, $dtlTable, $dtlStaticField, $dtlUniField, $dtlCon);
        //扔一条sql到全局变量，方法执行结束后执行
        $glSqlQuery[] = $sql;
        return true;
    }

    /**
     * 20220522：将前端传回的数组中，数量为空的数据剔除。不入库/不下单
     * @param type $goodsArr
     * @return type
     */
    public static function unsetEmptyGoods(&$goodsArr) {
        foreach ($goodsArr as $k => &$v) {
            if (!$v['amount']) {
                unset($goodsArr[$k]);
            }
        }
        return $goodsArr;
    }

    /**
     * 更新商品金额
     * @createTime 20231027
     * @return type
     */
    public function goodsPrizeUpdateRam() {
        $data['sellerGoodsPrize']   = $this->calSellerGoodsPrize();
        $data['plateGoodsPrize']    = $this->calPlateGoodsPrize();
        $data['goodsPrize']         = $data['sellerGoodsPrize'] + $data['plateGoodsPrize'];

        return $this->doUpdateRamClearCache($data);
    }

}
