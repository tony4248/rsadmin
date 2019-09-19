<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-8
 * Time: 14:08
 */

namespace app\admin\validate;


use think\Validate;

class ArticleList extends Validate
{
    protected $rule = [
        'title' => 'require',
    ];

    protected $message = [
        'title.require' => '标题不能为空',
    ];

}