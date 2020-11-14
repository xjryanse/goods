<?php
namespace xjryanse\goods\model;

/**
 * 商品明细
 */
class Goods extends Base
{
    /**
     * 上下架状态
     * @param type $value
     */
    public function setIsOnAttr( $value )
    {
        //非布尔值时转布尔值
        return is_numeric($value) ? $value : booleanToNumber($value);
    }
}