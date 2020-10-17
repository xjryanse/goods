<?php
namespace xjryanse\goods\service;

/**
 * 商品明细
 */
class GoodsService
{
    use \app\common\traits\InstTrait;
    use \app\common\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\goods\\model\\Goods';

}
