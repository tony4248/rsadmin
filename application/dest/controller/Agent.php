<?php

namespace app\dest\controller;

use app\dest\model\User;
use mptta\MPTTA;
use app\dest\model\Agent as Magent;
use think\Loader;

/**
 * 用户代理类
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-15
 * Time: 10:44
 */
class Agent extends Base
{

    /**
     *  渲染数据显示主页面
     * @param string $id
     * @return mixed
     */
    public function index($id = '')
    {
        $User = new Magent();
        if (empty($id)) {
            $id = session('agent_id');
        }
        $data = $User->getTress($id);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 数据添加页面
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * @param string $id //用户session_id
     * @param string $nickname //昵称
     * @param string $username //账号
     * @param string $password //密码
     * @param $phone //手机
     * @param $st
     */
    public function save($id = '', $nickname = '', $username = '', $password = '', $phone = '', $st = '')
    {
        if (empty($id)) {
            $id = session('agent_id');
        }
        $data = ['nickname' => $nickname, 'username' => $username, 'password' => $password, 'phone' => $phone];
        $validate = Loader::validate('User');
        if (!$validate->check($data)) {
            $this->error($validate->getError());
        }
        $User = new Magent();
        $result = $User->insertTres($id, $nickname, $username, $password, $phone, $st);
        if ($result) {
            $this->success('添加成功');
        } else {
            $this->error('添加失败');
        }
    }


    /**
     * 逻辑删除
     * @param string $id
     * @param string $aid 1用户0代理
     */
    public function del($id = '', $aid = '')
    {
        $Agent = new Magent();
        if ($aid === 0) {
            if ($id) {
                $result = $Agent->userLftRgtDelete($id);
                if (!$result) {
                    $this->error('删除失败');
                } else {
                    $this->success('删除成功');
                }
            } else {
                $this->error('数据异常请检查');
            }
        }
        if ($aid === 1) {
            $User = new User();
            $result = $User->userLftRgtDel($id);
            if ($result == 1) {
                $this->success('用户删除成功');
            } else {
                $this->error('用户删除失败');
            }
        }
    }

    /**
     *  显示转移页面
     * @param string $id
     * @param string $aid
     * @return mixed
     */
    public function transfer($id = '', $aid = '')
    {
        $this->assign('data', $id);
        $this->assign('aid', $aid);
        return $this->fetch();
    }

    /**
     *  用户代理移动
     * @param string $id
     * @param string $name
     * @param string $aid
     */
    public function moveNodes($id = '', $name = '', $aid = '')
    {
        $Agent = new Magent();
        $mptta = new MPTTA();
        $data = $Agent->where('id|nickname|username', 'like', $name)->field('id, lft, rgt')->find();
        if (!$data) {
            $this->error('此用户不存在');
        }
        $row = $data->toArray();

        $pid = $row['id'];
        if ($aid == 1) {
            $result = $Agent->userMoveNode($pid, $id);
            if ($result == 1) {
                $this->success('转移成功');
            } else {
                $this->error('转移失败');
            }
        } else {
            $ro = $Agent->where('id', $id)->field('lft,rgt')->find()->toArray();
            $res = $Agent->where('lft', 'between', [$ro['lft'], $ro['rgt']])->select()->toArray();
            $item = [];
            foreach ($res as $k => $v) {
                $item[] = $v['id'];
            }
            if (in_array($pid, $item) === true) {
                $this->error('不能转到自己的下级');
            }
            $result = $mptta->moveNode($pid, $id);
            if ($result == 1) {
                $this->success('转移成功');
            } else {
                $this->error('转移失败');
            }
        }


    }

    public function addUserYh()
    {
        return $this->fetch();
    }

    public function addUserYhInfo(int $id = 0, string $nickname = '', string $username = '', string $password = '', int $sex = 0, $phone = '', $st = 1)
    {

        $data = ['parent_id' => $id, 'username' => $username, 'create_ip' => request()->ip(), 'password' => $password, 'sex' => $sex, 'phone' => $phone, 'nickname' => $nickname];
        $Validate = Loader::validate('Promotion');
        if (!$Validate->check($data)) {
            $this->error($Validate->getError());
        }
        $Agent = new Magent();
        $res = $Agent->insertTres($id, $nickname, $username, $password, $phone, $st);
        if ($res) {
            $this->success('添加成功');
        } else {
            $this->error('添加失败');
        }
    }


}