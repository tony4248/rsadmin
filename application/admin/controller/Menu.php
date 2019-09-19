<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-18
 * Time: 14:33
 */

namespace app\admin\controller;

use app\admin\model\Menu as MenuModel;

class Menu extends Base
{
    public function index()
    {
        //$menu_tree = db('setting')->where('name','menu_tree')->find()['value'];
        $menu_tree = db('setting')->find()['menu_tree'];
        $menu_tree = json_decode($menu_tree, true);
        return $this->fetch('index', ['title' => '后台菜单管理', 'menu_tree' => $menu_tree]);
    }

    public function add()
    {
        return $this->fetch();
    }

    public function title_save($title = '')
    {
        if(!$title){
            return json(['code'=>0,'msg'=>'标题不能为空']);
        }
        $MenuModel = new MenuModel();
        $data = [
            [
                'title' => '菜单'
            ]
        ];
        $a = json_encode($data,true);
        $MenuModel->where('id',1)->insert(['title'=>$title]);
    }
}