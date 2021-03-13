<?php
namespace xjryanse\goods\logic;

use xjryanse\goods\service\GoodsPrizeService;
use xjryanse\goods\service\GoodsPrizeTplService;
use xjryanse\logic\Arrays;
use xjryanse\logic\Debug;

/**
 * 价格逻辑
 */
class PrizeLogic
{
    /**
     * 
     * @param type $saleType    销售类型
     * @param type $data        原始数据，包含价格key
     * @param type $id          商品id
     */
    public static function savePrize( $saleType, $data, $id )
    {
        $prizeKeys  = GoodsPrizeTplService::columnPrizeKeysBySaleType( $saleType );
        Debug::debug( '$prizeKeys' ,$prizeKeys );

        foreach( $prizeKeys as $prizeKey){
            if(!isset($data[$prizeKey])){
                continue;
            }
            $saveData['app_id'] = Arrays::value($data, 'app_id');
            $saveData['company_id'] = Arrays::value($data, 'company_id');
            Debug::debug( '$id' ,$id );
            //价格保存
            GoodsPrizeService::saveByKey( $id , $prizeKey, $data[$prizeKey],$saveData);
        }
    }
}
