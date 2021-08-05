<?php
namespace xjryanse\goods\model;

/**
 * 商品分类
 */
class GoodsCate extends Base
{
    use \xjryanse\traits\ModelTrait;
    
    public function getCatePicAttr($value) {
        return self::getImgVal($value);
    }

    public function setCatePicAttr($value) {
        return self::setImgVal($value);
    }

}