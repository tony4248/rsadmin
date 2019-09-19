<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-21
 * Time: 16:09
 */

namespace app\dest\controller;


class Menu extends Base
{
    public function index()
    {
        $menu_tree = db('agent_setting')->where('name','menu_tree')->find()['value'];
        $menu_tree = json_decode($menu_tree, true);
        return $this->fetch('index', ['title' => '后台菜单管理', 'menu_tree' => $menu_tree]);
    }
}