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

    /*
     * 用商品id查询，并绑定键
     */
    public static function selectByGoodsIdBindKey( $goodsId )
    {
        return self::mainModel()->where('goods_id',$goodsId)->column('*','prize_key');
    }
    
    public static function saveByKey( $goodsId, $key, $prize,$data = [] )
    {
        $con[] = ['goods_id','=',$goodsId];
        $con[] = ['prize_key','=',$key];
        //价格信息保存
        $data['prize']      = $prize;
        $data['goods_id']   = $goodsId;
        $data['prize_key']  = $key;

        $info = GoodsPrizeService::find( $con );
        if($info){
            $res = GoodsPrizeService::getInstance($info['id'])->update( $data );
        } else {
            $res = GoodsPrizeService::save( $data );
        }
        return $res;
    }
    
}
