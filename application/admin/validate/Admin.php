<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-11
 * Time: 16:06
 */

namespace app\admin\validate;


use think\Validate;

class Admin extends Validate
{
    protected $rule  =  [
        'username'  =>  'require',
        'password'  =>  'require|min:6',
        'mobile'  =>  'require|checkIsMobile',
        'email'  =>  'email'
    ];

    protected $message = [
        'username.require'  =>   '账号不能为空!',
        'password.require'  =>  '密码不能为空',
        'password.min'  =>  '管理员密码不能少于6位！',
        'mobile.require'  =>  '手机号码不能为空',
        'mobile.checkIsMobile'  =>  '手机格式不正确',
        'email'  =>  '邮箱不正确'
    ];

    protected $scene = [
        'edit'  =>  ['username','mobile','email'],
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
}