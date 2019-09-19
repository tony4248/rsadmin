<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-6
 * Time: 9:20
 */

namespace app\admin\controller;

use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use think\Db;

/**
 * Class Group
 * name:用户组控制器
 * @package app\admin\controller
 */
class Group extends Base
{
    public function index()
    {
        $Group = new AuthGroup();
        $data = $Group->getGroupInfo();
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 空操作
     */
    public function _empty()
    {
        $this->redirect('group/index');
    }

    /**
     *  用户组添加
     * @param string $title
     * @param int $status
     * @return array|mixed
     */
    public function add($title = '', $status = 0)
    {
        if (request()->isAjax()) {
            $Group = new AuthGroup();
            $data = ['title' => $title, 'status' => $status];
            $validate = validate('authGroup');
            if (!$validate->scene('add')->check($data)) {
                return ['code' => 0, 'msg' => $validate->getError()];
            }
            $result = $Group->insertGroupData($data);
            if ($result) {
                return ['code' => 1, '用户组添加成功'];
            } else {
                return ['code' => 0, '用户组添加失败'];
            }
        }
        return $this->fetch();
    }

    /**
     *  用户组编辑
     * @param int $id
     * @param string $title
     * @param int $status
     * @return array|mixed
     */
    public function edit($id = 0, $title = '', $status = 0)
    {
        if (request()->isAjax()) {
            $dataS = ['id' => $id, 'title' => $title, 'status' => $status];
            if ($id == 1 || $status == 1) {
                return ['code' => 0, 'msg' => '超级管理员禁止禁用'];
            }
            //验证
            $validate = validate('auth_group');
            if (!$validate->scene('edit')->check($dataS)) {
                return ['code' => 0, 'msg' => $validate->getError()];
            }
            $res = new AuthGroup();
            $result = $res->editGroupUpdate($dataS);
            if ($result) {
                return ['code' => 1, 'msg' => '保存成功'];
            } else {
                return ['code' => 0, 'msg' => '保存失败'];
            }
        }
        $res = new AuthGroup();
        $result = $res->getEditUserInfo($id);
        $this->assign('data', $result);
        return $this->fetch();
    }


    /**
     * 用户组删除
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        $id = input('gid');
        $delAdmin = Db::table('user')->where(array('groupid' => $id))->delete();
        $del = Db::table('auth_group_access')->where('id', 'eq', $id)->delete();
        if ($del && $delAdmin) {
            return json(['code' => 0, 'msg' => '删除成功']);
        } else {
            return json(['code' => 1, 'msg' => '删除失败']);
        }
    }


    /**
     * 分配权限
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function power()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            $rules = implode(',', $data['rules']);
            $save = Db::table('auth_group')->where(['id' => $data['id']])->update(['rules' => $rules]);
            if ($save !== false) {
                return ['code'=>1,'msg'=>'分配权限成功'];
            } else {
                return ['code'=>0,'msg'=>'分配权限失败,请检查数据'];
            }
        }
        $AuthRule = new AuthRule();
        $data = $AuthRule->getChiledRen();
        $id = input('id');
        $authGroups = Db::table('auth_group')->find($id);
        $rules = explode(',', $authGroups['rules']);
        $this->assign([
            'authGroups' => $authGroups,
            'data' => $data,
            'rules' => $rules,
        ]);
        return $this->fetch();
    }

    /**
     *  查看分组下成员
     * @param $id
     * @return string
     */
    public function cha_kan($id)
    {
        $Admin = new \app\admin\model\Admin();
        $result = $Admin->where('groupid', $id)->select()->toArray();
        $arr = [];
        foreach ($result as $res) {
            $arr[] = $res['username'];
        }
        $data = implode(' , ', $arr);
        return $data;
    }
}