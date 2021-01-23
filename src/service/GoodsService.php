<?php

namespace xjryanse\goods\service;

use xjryanse\logic\DbOperate;
/**
 * 商品明细
 */
class GoodsService {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\SubServiceTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\Goods';

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
        $data['goods_table']    = $goodsTable;
        $data['goods_table_id'] = $goodsTableId;
        $data['sale_type']      = $saleType;
//        dump($goodsTable);
//        dump($goodsTableId);
//        dump($goodsInfo);
//        dump('---保存商品--');
//        dump($data);
        
        $res = GoodsService::saveGetId($data);
//        dump($res);
        return $res;
    }
    
    public static function save( $data )
    {
        self::checkTransaction();
        //①商品保存
        $res = self::commSave( $data );
//        dump('-----商品保存地导弹----');
//        dump($data);
//        dump($res);
        //②写入商品子表
        if($data['sale_type']){
            $subService = self::getSubService( $data['sale_type'] );
            if( class_exists($subService) ){
                $subService::save( $res && is_object($res) ? $res->toArray() : $res );
            }
        }
        return $res;
    }
    
    /**
     * 额外详情信息
     */
    protected static function extraDetail( &$item ,$uuid )
    {
        //①添加商品来源表数据
        $subService = DbOperate::getService( $item['goods_table'] );
        self::addSubServiceData($item, $subService, $item['goods_table_id']);
        //②添加商品销售分表数据:按类型提取分表服务类
        self::addSubData($item, $item['sale_type']);
        //③添加价格数据
        $prize          = GoodsPrizeService::getGoodsPrizeSumByBelongRole( $uuid );
        $prizeTableName = GoodsPrizeService::mainModel()->getTable();
        foreach( $prize as $key=>$value){
            $item[$prizeTableName.'.'.$key] = $value;
        }
        return $item;
    }
    
    /**
     * 额外输入信息
     */
    public static function extraAfterSave( &$data, $uuid ){
        //商品价格冗余记录
        self::getInstance($uuid)->goodsIsOnSync();
    }
    /**
     * 上下架状态同步记录（写入来源表）
     */
    public function goodsIsOnSync() {
        //更新价格
        $saleType       = $this->fSaleType();
        $goodsTableName = $this->fGoodsTable();
        $goodsTableId   = $this->fGoodsTableId();
        $isOn           = $this->fIsOn();
        
        $field          = camelize( 'is_' .$saleType. '_on' );
        
        $service = DbOperate::getService($goodsTableName);
        if ($service::mainModel()->hasField($field)) {
            return $service::mainModel()->update(['id'=>$goodsTableId,$field => $isOn]);
        }
        return false;
    }    
    
    /**
     * 适用于1个商品多种卖法(如商标：授权，租用，购买)
     * @param type $goodsTableId    商品来源表id
     * @param type $saleType        销售类型
     * @param type $con             其他查询条件
     */
    public static function getBySaleType($goodsTableId, $saleType, $con = []) {
        $con[] = ["goods_table_id", "=", $goodsTableId];
        $con[] = ["sale_type", "=", $saleType];
        return self::find($con);
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
