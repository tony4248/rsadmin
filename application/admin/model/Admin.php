<?php

namespace app\admin\model;

use think\Db;
use think\Model;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-5
 * Time: 17:32
 */
class Admin extends Model
{
    protected $autoWriteTimestamp = true;
    Protected $pk = 'id';

    /**
     * @param $data
     * @return int|string
     * 保存用户信息:未用
     */
    public function saveUserInfo($data)
    {
        $data['password'] = md5($data['password']);
        $res = Db::table('user')->insert($data);
        return $res;
    }

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     * 根据id获取用户信息
     */
    public function getUserInfoOne($id)
    {
        $result = $this->where('id', 'eq', $id)->find();
        return $result;
    }


    /**
     *  编辑用户信息
     * @param $id
     * @param $username
     * @param $mobile
     * @param $email
     * @param $groupid
     * @param $note
     * @return $this
     */
    public function editUserInfo($id, $username, $mobile, $email, $groupid, $note)
    {
        $data = [
            'username' => $username,
            'mobile' => $mobile,
            'email' => $email,
            'groupid' => $groupid,
            'note' => $note,
            'update_time' => date('Y-m-d H:i:s',time())
        ];
        $save = self::table('admin')->where('id', $id)->update($data);
        self::table('auth_group_access')->where(['uid' => $id])->update(['group_id' => $groupid]);
        return $save;
    }

    /**
     * @return false|\PDOStatement|string|\think\Collection
     * 获取管理员登录日志
     */
    public function getAdminLoginLog()
    {
        $result = self::with('admin_login_log')->select();
        return $result;
    }

    public function adminLoginLog()
    {
        return $this->hasMany('admin_login_log', 'admin_id', 'id')->field('username');
    }

    /**
     *  修改管理员密码操作
     * @param $id
     * @param $passwords
     * @return $this
     */
    public function changeAdminPassword($id, $passwords)
    {
        $result = $this->where('id', $id)->update(['password' => $passwords]);
        return $result;
    }


}