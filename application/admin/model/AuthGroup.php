<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-6
 * Time: 10:00
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class AuthGroup extends Model
{
    public function getGroupInfo()
    {
        $data = $this->select();
        return $data;
    }

    public function getEditUserInfo($id)
    {
        $result = $this->find($id);
        return $result;
    }

    public function editGroupUpdate($datas)
    {
        $id = $datas['id'];
        $result = $this->where('id', 'eq', $id)->update($datas);
        return $result;
    }

    public function insertGroupData($data)
    {
        $res = $this->insert($data);
        return $res;
    }

    public function getRuleTree()
    {
        $menu_list = self::table('auth_rule')->where(array('status' => 0))->select();
        $menus = $this->getTreeItems($menu_list);
        $results = array();
        foreach ($menus as $value) {
            $value['pid'] = isset($value['pid']) ? $this->formatMenus($value['pid']) : false;
            $results[] = $value;
        }
        return $results;
    }

    public function getTreeItems($menu_list, $pid = 0)
    {
        $tree = '';
        foreach ($menu_list as $k => $v) {
            if ($v['pid'] == $pid) {
                $v['pid'] = $this->getTreeItems($menu_list, $v['id']);
                $tree[] = $v;

            }
        }
        return $tree;
    }


    private function formatMenus($items, &$res = array())
    {
        foreach ($items as $item) {
            if (!isset($item['children'])) {
                $res[] = $item;
            } else {
                $tem = $item['children'];
                unset($item['children']);
                $res[] = $item;
                $this->formatMenus($tem, $res);
            }
        }
        return $res;
    }

    public function getChaKanAdminAttr($value, $data)
    {
        $Admin = new Admin();
        $result = $Admin->where('groupid', $data['id'])->select()->toArray();
        $arr = [];
        foreach ($result as $res) {
            $arr[] = $res['username'];
        }
        $data = implode(' , ', $arr);
        return $data;

    }
}