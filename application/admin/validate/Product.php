<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-21
 * Time: 13:04
 */

namespace app\admin\validate;


use think\Validate;

class Product extends Validate
{
    protected $rule = [
        'name' => 'require',
        'description' => 'require',
        'image' => 'require',
        'qty' => 'require|number',
        'price' => 'require|number'
    ];

    protected $message = [
        'name.require' => '名称不能为空',
        'description.require' => '描述不能为空',
        'image.require' => '图片名称不能为空',
        'qty.require' => '数量不能为空',
        'qty.number' => '数量必须是数字',
        'price.require' => '价格不能为空',
        'price.number' => '价格必须是数字'
    ];

    protected $scene = [
        'add'  =>  ['name','description','image','qty','price'],
    ];
}