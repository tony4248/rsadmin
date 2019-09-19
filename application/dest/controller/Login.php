<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-17
 * Time: 11:44
 */

namespace app\dest\controller;

use app\dest\model\Login as LoginModel;

use think\Controller;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    /**
     *  登录数据验证
     * @param string $username
     * @param string $password
     * @param string $code
     */
    public function doLogin($username = '', $password = '', $code = '')
    {
        if (request()->isPost()) {
            if (!captcha_check($code)) {
                $this->error('验证码错误,请重新输入！');
            }
            $check = new LoginModel();
            $loginStatus = $check->checkLogin($username, $password);
            if ($loginStatus == 1) {
                $this->success('登录成功', 'index/index');
            } elseif ($loginStatus == 2) {
                $this->error('密码错误！');
            } elseif ($loginStatus == 3) {
                $this->error('用户不存在！');
            } elseif ($loginStatus == 4) {
                $this->error('账号已被禁用！');
            } elseif ($loginStatus == 6 || $loginStatus == 7){
                $this->error('ip地址已被禁用');
            }
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        session('user_id',null);
        $this->success('退出成功', 'dest/login/index');
    }


}