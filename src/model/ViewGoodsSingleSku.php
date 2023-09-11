<?php
namespace xjryanse\goods\model;

/**
 * 单sku的商品，方便维护
 */
class ViewGoodsSingleSku extends Base
{
    public static $picFields = ['main_pic'];

    public function getMainPicAttr($value) {
        return self::getImgVal($value);
    }

    public function setMainPicAttr($value) {
        return self::setImgVal($value);
    }
}