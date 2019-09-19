<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-10-11
 * Time: 9:43
 */

namespace app\admin\controller;


use think\Controller;

/**
 *  空控制器
 * Class Error
 * @package app\admin\controller
 */
class Error extends Controller
{
    public function index()
    {
        $this->error('控制器不存在,自动跳转首页', 'admin/index/index');
    }
}