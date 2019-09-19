<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-9-30
 * Time: 14:12
 */

namespace app\admin\controller;

use \app\admin\model\UserYh as UserYhModel;
use GatewayClient\Gateway;
use mptta\MPTTA;
use think\Loader;

class UserYh extends Base
{

    /**
     *  用户信息编辑页面
     * @param string $id
     * @return mixed
     */
    public function editUser($id = '')
    {
        $UserYh = new UserYhModel();
        $result = $UserYh->find($id)->toArray();
        $this->assign('data', $result);
        return $this->fetch();
    }

    /**
     *    编辑用户信息
     * @param int $id
     * @param string $nickname
     * @param string $phone
     * @param int $status
     * @param int $lock
     */
    public function editSave(int $id = 0,string $nickname = '', $phone = '',$status = 0,$lock = 0)
    {
        $data = ['id'=>$id,'nickname'=>$nickname,'phone'=>$phone,'status'=>$status,'lock'=>$lock];
        $validate = Loader::validate('UserYh');
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }
        $UserYh = new UserYhModel();
        $result = $UserYh->editSaveUserInfo($data);
        if($result){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }

    /**
     *  转移页面
     * @param string $id
     * @return mixed
     */
    public function transfer($id = '')
    {
        $this->assign('data', $id);
        return $this->fetch();
    }

    /**
     * 用户转移
     * @param string $id
     * @param string $name
     */
    public function moveNode($id = '',$name='')
    {
        $User = new UserYhModel();
        $mpTta = new MPTTA();
        $data = $User->where('id|nickname|username', 'like', $name)->field('id, lft, rgt')->find();
        if (!$data) {
            $this->error('此用户不存在');
        }
        $row = $data->toArray();
        $pid = $row['id'];
        $mpTta->moveNode($pid, $id);
    }

    /**
     *  用户充值
     * @param int $id
     * @return mixed
     */
    public function chongzhi(int $id = 0)
    {
        $this->assign('id',$id);
        return $this->fetch();
    }

    public function chongzhiSave()
    {
        $UserYh = new UserYhModel();
        $data = input('post.');
        $res = $UserYh->execute("update `user_yh` set `coin`=`coin`+{$data['coin']} WHERE `id`={$data['id']}");
        if($res){
            $this->success('余额充值成功');
        }else{
            $this->error('余额充值失败');
        }
    }

    /**
     *  用户密码修改
     * @return mixed
     */
    public function user_change_password()
    {
        $UserYh = new UserYhModel();
        if (request()->isPost()) {
            $id = input('id');
            $newPassword = md5(input('post.newpassword'));
            $password = md5(input('post.password'));
            if ($newPassword !== $password) {
                $this->error('对不起两次密码不相等,请重新输入');
            } else {
                $result = $UserYh->changePasswordPro($password, $id);
                if ($result) {
                    $this->success('密码修改成功');
                } else {
                    $this->error('密码修改失败');
                }
            }

        }
        $id = input('id');
        $result = $UserYh->getUserPassword($id)->toArray();
        $this->assign('data', $result);
        return $this->fetch();
    }

    public function displayMail(int $id = 0)
    {
        $this->assign('id',$id);
        return $this->fetch();
    }

    /**
     *  发送邮件
     * @param int $user_id
     * @param string $context
     * @param string $name
     */
    public function sendMail($user_id = 0,$context='',$name='')
    {
        $result = $this->validate(
            [ 'name'  => $name,'context' => $context,],
            ['name'  => 'require|max:15', 'context'   => 'require',]);
        if(true !== $result){
           $this->error($result);
        }
        $data = ['name'=>$name,'context'=>$context,'create_time'=>date('Y-m-d H:i:s', time()),'user_id'=>$user_id];
        $Email = new \app\admin\model\Email();
        $result = $Email->insert($data);
        if($result){
            Gateway::$registerAddress = '192.168.31.27:1238';
            $res = [
                'id'=>$user_id,
                'name'=>$name,
                'context'=>$context,
                'item' => []
            ];
            $data = parseSocketData('email_new', $res);
            Gateway::sendToUid($user_id, $data);
            $this->success('邮件添加成功');
        }else{
            $this->error('邮件添加失败');
        }
    }

}