<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-7
 * Time: 17:17
 */

namespace app\admin\model;


use think\Db;
use think\Model;

class Login extends Model
{


    /*
     * 管理员登录验证
     */
    public function checkLogin($username, $password)
    {
        $passwords = md5($password);
        $res = $this->table('admin')->where('username', 'eq', $username)->find();
        if ($res) {
            $_password = $res['password'];
            if ($_password == $passwords) {
                if ($res['status'] == 1) {
                    return 4;//管理员被禁用
                }
                //根据ID查询上一次登录ip,时间
                //$sql = Db::table('admin_login_log')->where('admin_id', $res['id'])->order('create_time desc')->buildSql();
                //$result = Db::table($sql . 'a')->limit(1, 1)->find();
                $result = null;
                session('admin_login_log', $result); //session记录上次登录信息;
                session('admin_id', $res['id']);
                session('admin', $res);
                // 成功以后记录日志信息
                // 登录IP
                // 登录时间
                $data = [
                    'admin_id' => $res['id'],
                    'ip' => request()->ip(),
                    'create_time' => date('Y-m-d H:i:s',time())
                ];
                $this->table('admin_login_log')->insert($data);
                return 1;//密码正确可以登录
            } else {
                return 2;//密码出错
            }
        } else {
            return 3;//用户不存在
        }
    }
}