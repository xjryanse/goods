<?php
namespace xjryanse\goods\service;

/**
 * 商品价格设置
 */
class GoodsPrizeService
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\goods\\model\\GoodsPrize';

}
