<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-20
 * Time: 10:33
 */

namespace app\dest\validate;


use think\Validate;

class IpWhitelist extends Validate
{
    protected $rule = [
        'ip'=>'require|ip|unique:ip_whitelist'
    ];

    protected $message = [
        'ip.require' => 'IP不能为空',
        'ip.ip'=>'请输入正确IP',
    ];
}