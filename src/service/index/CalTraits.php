<?php

namespace xjryanse\goods\service\index;

use xjryanse\logic\Arrays2d;
/**
 * 分页复用列表
 */
trait CalTraits{

    public function calSellerGoodsPrize(){
        $lists = $this->objAttrsList('goodsPrize');
        $con    = [];
        $con[]  = ['prize_key','=','sellerGoodsPrize'];
        $arr = Arrays2d::listFilter($lists, $con);
        return Arrays2d::sum($arr, 'prize');
    }

    public function calPlateGoodsPrize(){
        $lists = $this->objAttrsList('goodsPrize');

        $con    = [];
        $con[]  = ['prize_key','=','plateGoodsPrize'];
        $arr = Arrays2d::listFilter($lists, $con);
        return Arrays2d::sum($arr, 'prize');
    }
}
