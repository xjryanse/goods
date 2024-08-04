<?php
namespace xjryanse\goods\model;

/**
 * 商品分类
 */
class GoodsCate extends Base
{
    use \xjryanse\traits\ModelTrait;
    
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        [
            'field'     =>'group',
            // 去除prefix的表名
            'uni_name'  =>'goods_type',
            'uni_field' =>'sale_type',
            'del_check' => true,
        ]
    ];
    
    
    /**
     * 图片字段
     * @var type 
     */
    public static $picFields = ['cate_pic'];
    
    public function getCatePicAttr($value) {
        return self::getImgVal($value);
    }

    public function setCatePicAttr($value) {
        return self::setImgVal($value);
    }

}