<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-10
 * Time: 13:47
 */

namespace app\admin\validate;


use think\Validate;

class ArticleCate extends Validate
{
    protected $rule = [
        'catename' => 'require|min:2'
    ];

    protected $message = [
        'catename.require' => '分类名称不能为空',
        'catename.min' => '分类名称不能低于2位',

    ];
}