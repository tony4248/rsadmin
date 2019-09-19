<?php

namespace app\dest\validate;

use think\Validate;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-17
 * Time: 9:30
 */
class User extends Validate
{
    protected $rule = [
        'nickname' => 'require|min:2',
        'username' => 'require|min:2',
        'password' => 'require|min:6',
        'phone' => 'require|checkIsMobile',
    ];

    protected $message = [
        'nickname.require'  => '昵称不能为空',
        'nickname.min'  => '昵称不正确',
        'username.require' => '用户名不能为空',
        'username.min' => '用户名字数不够',
        'password.require' => '密码不能为空',
        'password.min' => '密码最低6位数',
        'phone.require' => '手机不能为空',
        'phone.checkIsMobile' => '请输入正确的手机号'
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
        'account_edit' => ['nickname','phone']
    ];
}