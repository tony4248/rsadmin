<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-9-30
 * Time: 14:26
 */

namespace app\admin\validate;


use think\Validate;

class UserYh extends Validate
{
    protected $rule = [
        'nickName' => 'require',
        'moblie' => 'require|checkIsMobile'
    ];

    protected $message = [
        'nickName.require' => '昵称不能是空',
        'moblie' => '手机号不能是空',
        'moblie.checkIsMobile' => '手机号格式不正确'
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