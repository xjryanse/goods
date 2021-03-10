<?php

namespace xjryanse\goods\service;

use xjryanse\logic\DbOperate;


/**
 * 商品明细
 */
class GoodsService {

    use \xjryanse\traits\DebugTrait;
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\SubServiceTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\goods\\model\\Goods';

    /**
     * 商品来源表id，全部商品上架
     */
    public static function setOnSaleByGoodsTableId( $goodsTableId ,$goodsTable )
    {
        if( $goodsTable ){
            //库存设为1
            $con[] = ['goods_table','=', $goodsTable ];
        }
        $con[] = ['goods_table_id','=', $goodsTableId ];
        self::mainModel()->where( $con )->update(['stock'=>1,'is_on'=>1,'goods_status'=> GOODS_ONSALE ]);
        $service = DbOperate::getService($goodsTable);
        $service::getInstance( $goodsTableId )->update(['goods_status'=>GOODS_ONSALE]);
    }
    /**
     * 根据订单，更新商品状态
     * @param type $preStatus
     * @param type $afterStatus
     * @return type
     */
    public function updateGoodsStatus( $preStatus, $afterStatus )
    {
        $con[] = ['id','=',$this->uuid ];
        $data[ 'goods_status' ] = $afterStatus;
        //过滤数据
        $updData = DbOperate::dataFilter( self::mainModel()->getTable(),$data);
        $res = self::mainModel()->where( $con )->update( $updData );
        //更新商标表或网店表的状态
        $info = $this->get();
        if( $info ){
            $service = DbOperate::getService( $info['goods_table'] );
            $service::getInstance( $info['goods_table_id'] )->update( $data );
        }
        return $res ;
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
        $data['goods_table']    = $goodsTable;
        $data['goods_table_id'] = $goodsTableId;
        $data['sale_type']      = $saleType;
//        dump($goodsTable);
//        dump($goodsTableId);
//        dump($goodsInfo);
//        dump('---保存商品--');
//        dump($data);
        self::debug('---保存商品的数据---',$data);
        $res = GoodsService::commSaveGetId($data);
//        dump($res);
        return $res;
    }

    public function extraPreDelete()
    {
        self::checkTransaction();
    }
    /**
     * 删除价格数据
     */
    public function extraAfterDelete()
    {
        self::checkTransaction();
        //删商品
        $con[] = ['goods_id','=',$this->uuid];
        $lists = GoodsPrizeService::lists( $con );
        foreach( $lists as $value){
            GoodsPrizeService::getInstance( $value['id'] )->delete();
        }
    }
    
    /**
     * 额外详情信息
     */
    protected static function extraDetail( &$item ,$uuid )
    {
        if(!$item){ return false;}
        //①添加商品来源表数据
//        $subService = DbOperate::getService( $item['goods_table'] );
//        self::addSubServiceData($item, $subService, $item['goods_table_id']);
        //②添加商品销售分表数据:按类型提取分表服务类
        self::addSubData($item, $item['sale_type']);
        //③添加价格数据
//        $prize          = GoodsPrizeService::getGoodsPrizeSumByBelongRole( $uuid );
//        $prizeTableName = GoodsPrizeService::mainModel()->getTable();
//        foreach( $prize as $key=>$value){
//            $item[$prizeTableName.'.'.$key] = $value;
//        }
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
     * 额外输入信息
     */
    public static function extraAfterUpdate( &$data, $uuid ){
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
        if ($service && $service::mainModel()->hasField($field)) {
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
