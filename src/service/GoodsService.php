<?php
namespace xjryanse\goods\service;

/**
 * 商品明细
 */
class GoodsService
{
    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;

    protected static $mainModel;
    protected static $mainModelClass    = '\\xjryanse\\goods\\model\\Goods';

}
