<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-18
 * Time: 9:10
 */

namespace app\dest\controller;


use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {
        if (!session('agent_id')) {
            $this->error('未登录,请先登录', 'login/index');
        }
        $menu=db('agent_setting')->where('name','menu_tree')->find()['value'];
        $menu_tree = json_decode($menu,true);
        $this->assign('menu_tree', $menu_tree);
    }
}