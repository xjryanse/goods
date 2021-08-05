<?php
namespace xjryanse\goods\model;

/**
 * 商品SPU
 */
class GoodsSpu extends Base
{
    use \xjryanse\traits\ModelTrait;
    /**
     * 商品图标
     * @param type $value
     * @return type
     */
    public function getMainPicAttr($value) {
        return self::getImgVal($value);
    }
    /**
     * 图片修改器，图片带id只取id
     * @param type $value
     * @throws \Exception
     */
    public function setMainPicAttr($value) {
        $valueRes = self::setImgVal($value);
        return $valueRes;
    }
    public function setSubPicsAttr($value) {
        return self::setImgVal($value);
    }
    public function getSubPicsAttr($value) {
        return self::getImgVal($value,true);
    }
}