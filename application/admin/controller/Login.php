<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-7
 * Time: 16:26
 */

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Login as LoginModel;

/**
 * Class Login
 * @package app\admin\controller
 * 登录验证处理类
 */
class Login extends Controller
{

    /**
     * @return mixed
     * 登录页面
     */
    public function index()
    {
        if (session('id')) {
            $this->error('您已经登录成功，请勿重复登录！', 'Index/index');
        }
        return $this->fetch();
    }

    /**
     * 空操作
     */
    public function _empty()
    {
        $this->redirect('login/index');
    }

    /**
     *  登录验证
     * @param string $username
     * @param string $password
     * @param string $code
     * @return array
     */
    public function doLogin($username = '', $password = '', $code = '')
    {
        if(empty($username)){
            return ['code'=>0,'msg'=>'账号不能为空'];
        }
        if(empty($password)){
            return ['code'=>0,'msg'=>'密码不能为空'];
        }
        if(empty($code)){
            return ['code'=>0,'msg'=>'验证码不能为空'];
        }
        // if (!captcha_check($code)) {
        //     $this->error('验证码错误,请重新输入！');
        // }
        $check = new LoginModel();
        $loginStatus = $check->checkLogin($username, $password);
        if ($loginStatus == 1) {
            return ['code'=>1,'msg'=>'登录成功'];
        } elseif ($loginStatus == 2) {
            return ['code'=>2,'msg'=>'密码错误'];
        } elseif ($loginStatus == 3) {
            return ['code'=>3,'msg'=>'用户不存在'];
        } elseif ($loginStatus == 4) {
            return ['code'=>4,'msg'=>'账号已被禁用'];
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        session('admin_id', null);
        return ['code'=>1,'msg'=>'退出成功'];
    }

}