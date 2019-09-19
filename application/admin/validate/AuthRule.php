<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-7
 * Time: 13:48
 */

namespace app\admin\validate;


use think\Validate;

class AuthRule extends Validate
{
    protected $rule=[
        'title'=>'require|unique:auth_rule',
        'name'=>'require',
        'pid'=>'require|number',
        'status'=>'require|number',
    ];

    protected $message=[
        'title.require'=>'规则名称不得为空！',
        'title.unique'=>'规则名称不得重复！',
        'name.require'=>'规则值不得为空！',
        'name.unique'=>'规则值不得重复！',
        'pid.number'=>'上级规则值必须是数字！',
        'pid.require'=>'上级规则值不得为空！',
        'status.number'=>'规则状态值必须是数字！',
        'status.require'=>'规则状态值不得为空！',
    ];

    protected $scene = [
        'add'  =>  ['title','name','pid','status'],
        'edit'  =>  ['title','name','pid','status'],
    ];
}