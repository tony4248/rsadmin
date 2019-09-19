<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-17
 * Time: 11:53
 */

namespace app\dest\model;

use think\Model;

class Login extends Model
{
    /**
     * 用户登录验证
     * @param $username
     * @param $password
     * @return int
     */
    public function checkLogin($username, $password)
    {
        $passwords = md5($password);
        $res = $this->table('agent')->where('username', 'eq', $username)->find();
        if ($res) {
            $_password = $res['password'];
            if ($_password == $passwords) {
                if ($res['lock'] === 1) {
                    return 4;//用户被禁用
                }
                $ip = request()->ip(); //当前登录ip
                //判断当前IP是否被禁用
                if($ip_whitelist = db('ip_whitelist')->where('ip',$ip)->find()){
                    return 6;  //ip黑名单里面
                }
                $IpSegment = new IpSegment();
                $row = $IpSegment->field('ip_start,ip_end')->select()->toArray();
                $IpSeg = ipAuth($ip,$row);
                if($IpSeg === true){
                    return 7;
                }
                session('agent_id', $res['id']);
                session('agent_info', $res);
                // 成功以后记录日志信息
                // 登录IP
                // 登录时间
                $data = [
                    'agent_id' => $res['id'],
                    'ip' => $ip,
                    'create_time' => now()
                ];
                $ip = request()->ip();
                $this->table('agent')->where('id', $res['id'])->update(['active_ip' => $ip]);
                $this->table('agent_login_log')->insert($data);
                return 1;//密码正确可以登录
            } else {
                return 2;//密码出错
            }
        } else {
            return 3;//用户不存在
        }
    }


}