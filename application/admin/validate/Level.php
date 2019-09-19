<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-21
 * Time: 13:04
 */

namespace app\admin\validate;


use think\Validate;

class Level extends Validate
{
    protected $rule = [
        'level_name' => 'require',
        'exp' => 'require|number'
    ];

    protected $message = [
        'level_name.require' => '名称不能为空',
        'exp.require' => '经验值不为空',
        'exp.number' => '经验值必须是数字'
    ];
}