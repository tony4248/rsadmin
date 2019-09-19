<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-20
 * Time: 14:55
 */

namespace app\dest\validate;


use think\Validate;

class IpSegment extends Validate
{
    protected $rule = [
        'ip_start' => 'require|ip|unique:ip_segment',
        'ip_end' => 'require|ip|unique:ip_segment',
    ];

    protected $message = [
        'ip_start.require' => 'IP段开始不能为空',
        'ip_start.ip' => '请输入正确的开始IP段',
        'ip_start.unique' => 'IP段开始已存在',

        'ip_end.require' => 'IP段结束不能为空',
        'ip_end.ip' => '请输入正确的结束IP段',
        'ip_end.unique' => 'IP段结束已存在',
    ];
}