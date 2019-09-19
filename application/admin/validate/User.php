<?php

namespace app\admin\validate;

use \think\Validate;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-6
 * Time: 9:37
 */
class User extends Validate
{
    protected $rule = [
        'name' => 'require|chsDash|unique:user',
        'nickName' => 'require|length:2,16',
        'password' => 'require',
        'mobile' => 'require|checkIsMobile'
    ];

    protected $message = [
        'nickName.require' => '昵称不能为空！',
        'name.require' => '账号不能为空!',
        'password.require' => '密码不能为空',
        'password.length' => '密码6-12位！',
        'mobile.require' => '手机号码不能为空',
        'mobile.checkIsMobile' => '手机号码不正确',
        'nickName.length' => '长度不正确',
        'name.chsDash' => '只能是汉字、字母、数字和下划线_及破折号-',
        'name.unique' => '用户账号已存在'
    ];


    protected function checkIsMobile($value)
    {
        $rule = '/^0?(13|14|15|17|18)[0-9]{9}$/';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    protected $scene = [
        'robot_add' => ['name' => 'require|min:4|max:12', 'nickName' => 'require|length:2,16', 'password'],
        'robot_edit_save' => ['nickName', 'mobile'],
        'designated_user_save' => ['nickName', 'name', 'password', 'id' => 'require'],
    ];
}