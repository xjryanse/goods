<?php
namespace xjryanse\goods\service;

/**
 * 商品价格设置
 */
class GoodsPrizeService
{
    use \app\common\traits\InstTrait;
    use \app\common\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\goods\\model\\GoodsPrize';

}
