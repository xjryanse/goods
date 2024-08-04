<?php

namespace xjryanse\goods\service\spu;

use xjryanse\logic\Arrays2d;
/**
 * 分页复用列表
 */
trait CalTraits{
    /**
     * 20220701最低价格
     * @return type
     */
    public function calMinPrize() {
        $lists      = $this->objAttrsList('goods');
        $cone       = [['status','=',1]];
        $effList    = Arrays2d::listFilter($lists, $cone);

        return $effList ? min(array_column($effList, 'goodsPrize')) : 0;
    }

    /**
     * 20220701最高价格
     * @return type
     */
    public function calMaxPrize() {
        $lists = $this->objAttrsList('goods');
        $cone       = [['status','=',1]];
        $effList    = Arrays2d::listFilter($lists, $cone);
        return $effList ? max(array_column($effList, 'goodsPrize')) : 0;
    }
}
