<?php
namespace xjryanse\goods\model;

use xjryanse\logic\DbOperate;
/**
 * 商品明细
 */
class Goods extends Base
{
    use \xjryanse\traits\ModelTrait;
    /**
     * 上下架状态
     * @param type $value
     */
    public function setIsOnAttr( $value )
    {
        //非布尔值时转布尔值
        return is_numeric($value) ? $value : booleanToNumber($value);
    }

    /**
     * 根据商品表，设定模型sql
     * @param int $goodsTable
     * @return string
     */
    public function setTableByGoodsTable( $goodsTable ,$tableFields = [],$con = [])
    {
        $tableArr[] = ['table'=> $goodsTable  ,'mainField'=>'goods_table_id'  ,'tableField'=>'id']; 
        return $this->setTable($tableArr, $con);
    }
    /**
     * 根据销售类型，设定模型sql
     * @param type $saleType
     */
    public function setTableBySaleType( $saleType, $tableFields = [] ,$con = [])
    {
        $table      = $this->baseTableName( );
        $subTable   = $table.'_'.$saleType;
        if(!DbOperate::isTableExist( $subTable )){
            return false;
        }

        $tableArr[] = ['table'=> $subTable  ,'mainField'=>'id'  ,'tableField'=>'id']; 
        $res = $this->setTable($tableArr, $con);

        return $res;
    }
    
    /**
     * 商品图标
     * @param type $value
     * @return type
     */
    public function getGoodsPicAttr($value) {
        return self::getImgVal($value);
    }

    /**
     * 图片修改器，图片带id只取id
     * @param type $value
     * @throws \Exception
     */
    public function setGoodsPicAttr($value) {
        return self::setImgVal($value);
    }
    public function setSubPicsAttr($value) {
        return self::setImgVal($value);
    }
    public function getSubPicsAttr($value) {
        return self::getImgVal($value,true);
    }
}