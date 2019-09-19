<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-12
 * Time: 11:28
 */

namespace app\admin\validate;


use think\Validate;

class Agent extends Validate
{
    protected $rule = [
        'pid' => 'require|length:6',
        'uid' => 'require|length:6',
    ];

    protected $message = [
        'pid.require' => '上级代理Id不能为空！',
        'pid.length:6' => 'Id是六位数',
        'uid.require' => '用户Id不能为空！',
        'uid.length:6' => 'Id是六位数',
    ];

    protected $scene = [
        'add'  =>  ['pid','uid'],
    ];
}