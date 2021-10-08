<?php
namespace xjryanse\goods\model;

/**
 * 商品分组，页面展示
 */
class GoodsGroup extends Base
{
    use \xjryanse\traits\ModelTrait;
    
    public function getGroupPicAttr($value) {
        return self::getImgVal($value);
    }

    public function setGroupPicAttr($value) {
        return self::setImgVal($value);
    }

}