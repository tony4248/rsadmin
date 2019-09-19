<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-9-28
 * Time: 17:01
 */

namespace app\dest\validate;


use think\Validate;

class Promotion extends Validate
{

    protected $rule = [
        'nickname' => 'require|min:2|max:6',
        'username' => 'require|checkIsUser',
        'password' => 'require',
        'phone' => 'require|checkIsMobile'
    ];

    protected $message = [
        'nickname.require' => '昵称不能为空',
        'nickname.min' => '昵称不能小于2个字',
        'nickname.max' => '昵称不能超过6个字',
        'username.require' => '账号不能为空',
        'username.alpha' => '账号必须是字母',
        'password' => '密码不能为空',
        'phone.require' => '手机号码不能为空',
        'phone.checkIsMobile' => '手机号码不正确'
    ];

    /**
     *  手机号码正则验证
     * @param $value
     * @return bool
     */
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

    /**
     *  用户账号正则验证
     * @param $value
     * @return bool
     */
    public function checkIsUser($value)
    {
        $rule='/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/';
        $result = preg_match($rule,$value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}