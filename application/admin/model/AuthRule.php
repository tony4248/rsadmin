<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-6
 * Time: 14:37
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class AuthRule extends Model
{

    public function getRuleInfo()
    {
        $data = $this->select();
        return $data;
    }


    public function getChiledRen()
    {
        $data = $this->where(['pid' => 0])->select()->toArray();
        foreach ($data as $k => $v) {
            $data[$k]['children'] = db('authRule')->where(['pid' => $v['id']])->select();
            foreach ($data[$k]['children'] as $k1 => $v1) {
                $data[$k]['children'][$k1]['children'] = $this->where(['pid' => $v1['id']])->select();
            }
        }
        return $data;
    }

    //规则无限极树
    public function ruleTree($ruleRes)
    {
        return $this->sort($ruleRes);
    }

    // 进行排序
    public function sort($ruleRes, $pid = 0, $level = 0)
    {

        static $arr = [];
        foreach ($ruleRes as $k => $v) {
            if ($v['pid'] == $pid) {
                $v['level'] = $level;
                $arr[] = $v;
                $this->sort($ruleRes, $v['id'], $level + 1);
            }
        }

        return $arr;
    }

    /**
     * @param $datas
     * @return $this
     * 规则编辑
     */
    public function ruleEdit($datas)
    {
        $data = $this->where('id', 'eq', $datas['id'])->update($datas);
        return $data;
    }


    /**
     * @param $ruleid
     * @return array
     * 获取子栏目
     */
    public function childRenIds($ruleid)
    {
        $data = $this->field('id,pid')->select();
        return $this->_childRenRds($data, $ruleid);
    }

    /**
     * @param $data
     * @param $ruleid
     * @return array
     * 找儿子
     */
    private function _childRenRds($data, $ruleid)
    {
        static $arr = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $ruleid) {
                $arr[] = $v['id'];
                $this->_childRenRds($data, $v['id']);
            }
        }
        return $arr;
    }

}