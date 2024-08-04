<?php
namespace xjryanse\goods\service\index;

/**
 * 
 */
trait DimTraits{
    /*
     * 提取销售类型，有效数据(status=1)
     */
    public static function dimListBySaleTypeEffect($saleType, $con = []){
        $con[] = ['sale_type','=',$saleType];
        $con[] = ['status','=',1];
        return self::staticConList($con);
    }

}
