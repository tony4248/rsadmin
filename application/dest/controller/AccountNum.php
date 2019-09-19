<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-24
 * Time: 16:40
 */

namespace app\dest\controller;

use app\admin\model\Bank;
use app\dest\model\Agent as AgentModel;
use app\dest\model\User;
use think\Config;
use think\Loader;

class AccountNum extends Base
{
    /**
     *  代理账号
     * @return mixed
     */
    public function account_agent()
    {
        $Agent = new AgentModel();
        $id = session('agent_id');
        $data = $Agent->where('parent_id', $id)->paginate(Config::get('myConfig.agent_page_num'), false, ['query' => $_GET]);
        $page = $data->render();
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     *  用户账号
     * @return mixed
     */
    public function account_user()
    {
        $User = new User();
        $id = session('agent_id');
        $data = $User->where('parent_id',$id)->paginate(Config::get('myConfig.agent_page_num'), false, ['query' => $_GET]);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    /**
     * 代理编辑渲染页面
     * @param int $id
     * @return mixed
     */
    public function edit($id = 0)
    {

        $Agent = new AgentModel();
        $data = $Agent->where('id', $id)->find();
        $this->assign('data', $data);
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     *  代理编辑
     * @param int $id
     * @param string $nickname
     * @param int $phone
     * @param int $status
     * @param int $lock
     * @return \think\response\Json
     */
    public function edit_update($id = 0, $nickname = '', $phone = 0, $status = 0, $lock = 0)
    {
        $validate = Loader::validate('User');
        if (!$validate->scene('account_edit')->check(['nickname' => $nickname, 'phone' => $phone])) {
            return json(['code' => 0, 'msg' => $validate->getError()]);
        }
        $data = ['nickname' => $nickname, 'phone' => $phone, 'status' => $status, 'lock' => $lock];
        $Agent = new AgentModel();
        $result = $Agent->save($data, ['id' => $id]);
        if ($result) {
            return json(['code' => 1, 'msg' => '编辑成功']);
        } else {
            return json(['code' => 0, 'msg' => '编辑失败']);
        }
    }

    /**
     *  代理取款
     * @param int $id
     * @return mixed
     */
    public function qukuan($id = 0)
    {
        $Bank = new \app\dest\model\Bank();
        $bank_coin = $Bank->where('user_id',$id)->find();
        if($bank_coin === null){
            $Bank->insert(['user_id'=>$id,'coin'=>0]);
        }
        $this->assign('bank_coin',$bank_coin->coin);
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     *  取款数据处理
     * @param $id
     * @param $coin
     * @return \think\response\Json
     */
    public function qukuan_save($id,$coin)
    {
        if(!$id){
            return json(['code'=>0,'msg'=>'数据有误']);
        }
        $Bank = new \app\dest\model\Bank();
        if(!$bank = $Bank->where('user_id',$id)->find()){
            $Bank->insert(['user_id'=>$id,'coin'=>0]);
            return json(['code'=>0,'银行号余额不足无法取款']);
        }
        if($coin > $bank->coin){
            return json(['code'=>0,'msg'=>'银行余额不足无法充值']);
        }
        $Agent = new AgentModel();
        $Agent->where('id',$id)->setInc('coin',$coin);
        $Bank->where('user_id',$id)->setDec('coin',$coin);
        return json(['code'=>1,'msg'=>'取款成功']);

    }

    /**
     *  代理充值
     * @param int $id
     * @return mixed
     */
    public function charge($id = 0)
    {
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     *  代理充值保存
     * @param int $id
     * @param int $coin
     * @return \think\response\Json
     */
    public function charge_save($id = 0, $coin = 0)
    {
        if ($bank_coin = \app\dest\model\Bank::get(['user_id' => $id]) === null) {
            db('bank')->insert(['user_id' => $id, 'coin' => 0]);
        }

        if (!is_numeric($coin)) {
            return json(['code' => 0, 'msg' => '输入有误,请重新输入']);
        }
        if (!$Agent = AgentModel::get($id)) {
            return json(['code' => 0, 'msg' => '代理不存在,请刷新页面']);
        }
        $Agent->coin += $coin;
        $result = $Agent->save();

        if ($result) {
            return json(['code' => 1, 'msg' => '充值成功']);
        } else {
            return json(['code' => 0, 'msg' => '充值失败']);
        }
    }

    /**
     *  密码修改
     * @param int $id
     * @return mixed
     */
    public function mima($id = 0)
    {
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     *  密码编辑保存
     * @param int $id
     * @param string $password
     * @return \think\response\Json
     */
    public function mima_save($id = 0, $password = '')
    {
        $Agent = new AgentModel();
        if (mb_strlen($password) < 6 || mb_strlen($password) > 12) {
            return json(['code' => 0, 'msg' => '密码长度必须大于6位数小于12位']);
        }
        $passwords = md5($password);
        $password_com = $Agent->where('id', $id)->field('password')->find();
        if ($passwords === $password_com->password) {
            return json(['code' => 0, 'msg' => '新密码不能和旧密码相同']);
        }
        $result = $Agent->changeAdminPassword($id, $passwords);
        if ($result) {
            return json(['code' => 1, 'msg' => '密码更新成功']);
        } else {
            return json(['code' => 0, 'msg' => '密码更新失败']);
        }
    }

    /**
     * 用户编辑渲染页面
     * @param int $id
     * @return mixed
     */
    public function user_edit($id = 0)
    {
        $User = new User();
        $data = $User->where('id', $id)->find();
        $this->assign('data', $data);
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     *  用户编辑保存
     * @param int $id
     * @param string $nickname
     * @param int $phone
     * @param int $status
     * @param int $lock
     * @return \think\response\Json
     */
    public function user_edit_update($id = 0, $nickname = '', $phone = 0, $status = 0, $lock = 0)
    {
        $validate = Loader::validate('User');
        if (!$validate->scene('account_edit')->check(['nickname' => $nickname, 'phone' => $phone])) {
            return json(['code' => 0, 'msg' => $validate->getError()]);
        }
        $data = ['nickname' => $nickname, 'phone' => $phone, 'status' => $status, 'lock' => $lock];
        $Agent = new AgentModel();
        $result = $Agent->save($data, ['id' => $id]);
        if ($result) {
            return json(['code' => 1, 'msg' => '编辑成功']);
        } else {
            return json(['code' => 0, 'msg' => '编辑失败']);
        }
    }

    /**
     *  用户取款
     * @param int $id
     * @return mixed
     */
    public function user_qukuan($id = 0)
    {
        $Bank = new \app\dest\model\Bank();
        $bank_coin = $Bank->where('user_id',$id)->find();
        if($bank_coin === null){
            $Bank->insert(['user_id'=>$id,'coin'=>0]);
        }
        $this->assign('bank_coin',$bank_coin->coin);
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     *  取款数据处理
     * @param $id
     * @param $coin
     * @return \think\response\Json
     */
    public function user_qukuan_save($id,$coin)
    {
        if(!$id){
            return json(['code'=>0,'msg'=>'数据有误']);
        }
        $Bank = new \app\dest\model\Bank();
        if(!$bank = $Bank->where('user_id',$id)->find()){
            $Bank->insert(['user_id'=>$id,'coin'=>0]);
            return json(['code'=>0,'银行号余额不足无法取款']);
        }
        if($coin > $bank->coin){
            return json(['code'=>0,'msg'=>'银行余额不足无法充值']);
        }
        $User = new User();
        $User->where('id',$id)->setInc('coin',$coin);
        $Bank->where('user_id',$id)->setDec('coin',$coin);
        return json(['code'=>1,'msg'=>'取款成功']);

    }

    /**
     *  用户密码修改
     * @param int $id
     * @return mixed
     */
    public function user_mima($id = 0)
    {
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     *  用户密码编辑保存
     * @param int $id
     * @param string $password
     * @return \think\response\Json
     */
    public function user_mima_save($id = 0, $password = '')
    {
        $User = new User();
        if (mb_strlen($password) < 6 || mb_strlen($password) > 12) {
            return json(['code' => 0, 'msg' => '密码长度必须大于6位数小于12位']);
        }
        $passwords = md5($password);
        $password_com = $User->where('id', $id)->field('password')->find();
        if ($passwords === $password_com->password) {
            return json(['code' => 0, 'msg' => '新密码不能和旧密码相同']);
        }
        $result = $User->user_changeAdminPassword($id, $passwords);
        if ($result) {
            return json(['code' => 1, 'msg' => '密码更新成功']);
        } else {
            return json(['code' => 0, 'msg' => '密码更新失败']);
        }
    }

    /**
     *  用户充值
     * @param int $id
     * @return mixed
     */
    public function user_charge($id = 0)
    {
        $this->assign('id', $id);
        return $this->fetch();
    }

    /**
     *  用户充值保存
     * @param int $id
     * @param int $coin
     * @return \think\response\Json
     */
    public function user_charge_save($id = 0, $coin = 0)
    {
        if ($bank_coin = \app\dest\model\Bank::get(['user_id' => $id]) === null) {
            db('bank')->insert(['user_id' => $id, 'coin' => 0]);
        }

        if (!is_numeric($coin)) {
            return json(['code' => 0, 'msg' => '输入有误,请重新输入']);
        }
        if (!$User = User::get($id)) {
            return json(['code' => 0, 'msg' => '用户不存在,请刷新页面']);
        }
        $User->coin += $coin;
        $result = $User->save();

        if ($result) {
            return json(['code' => 1, 'msg' => '充值成功']);
        } else {
            return json(['code' => 0, 'msg' => '充值失败']);
        }
    }

}