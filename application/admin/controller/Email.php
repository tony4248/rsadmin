<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-11
 * Time: 16:36
 */

namespace app\admin\controller;

use app\admin\model\Emails as MEmails;
use app\admin\model\Users as MUsers;
use think\Config;
use think\Db;
use think\Loader;

/**
 * Class Email
 * @package app\admin\controller
 * 邮件处理类
 */
class Email extends Base
{

    /**
     * @return mixed
     * 邮件列表首页
     */
    public function index()
    {
        $email = new MEmails();
        $result = Db::name($email->tableName)->where('sender','neq', '')->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $finalRes = $email->formatEmailsOutput($result);
        $page = $result->render();
        $this->assign('data', $finalRes);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     * 空操作
     */
    public function _empty()
    {
        $this->redirect('email/index');
    }

    /**
     * @return mixed
     * 邮件列表首页
     */
    public function search($receiver ='', $sender = '')
    {
        $email = new MEmails();
        $result = $email->getEmails($sender, $receiver);
        $finalRes = $email->formatEmailsOutput($result);
        if (trim($receiver) != '' || trim($sender) != '') {
            $this->assign('quick', '<input type="button" name="Submit" class="btn btn-default radius" onclick="javascript:history.back(-1);" value="返回">');
        }
        $page = null;
        $this->assign('data', $finalRes);
        $this->assign('page', $page);
        return $this->fetch('email/index');
    }

    /**
     *  邮件添加处理
     * @param string $user_id
     * @param string $context
     * @param string $name
     * @param int $status
     * @return mixed
     */
    public function add($receiver = '',$sender = '',  $title = '', $content = '')
    {
        if (request()->isPost()) {
            if (!is_numeric($receiver)) {
                $this->error('请输入正确的收件人ID');
            }
            //检查用户Id的有效性
            if($receiver != '999999'){
                $user = new MUsers();
                $receiverData = $user->getById($receiver);
                if(!$receiverData){
                    $this->error('请输入正确的收件人ID');
                }
            }
            if (!$title ||!$content ) {
                $this->error('请输入标题和内容');
            }
            $email = new MEmails();
            $result = $email->saveEmail($receiver,$sender, $title, $content);
            if ($result) {
                $this->success('邮件添加成功');                
            } else {
                $this->error('邮件添加失败,请输入正确信息!');
            }
        }
        $this->assign('receiver', $receiver);
        return $this->fetch();
    }

    /**
     * 回复邮件
     */
    public function reply($id = '', $receiver = '',$sender = '',  $title = '', $content = '')
    {
        if (request()->isPost()) {
            if (!$title ||!$content ) {
                $this->error('请输入标题和内容');
            }
            $email = new MEmails();
            $result = $email->saveEmail($receiver,$sender, $title, $content);
            if ($result) {
                $this->success('邮件添加成功');                
            } else {
                $this->error('邮件添加失败,请输入正确信息!');
            }
        }
        $email = new MEmails();
        $result = $email->getEmail($id);
        if ($result === false) {
            $this->error('对不起,邮件不存在,请刷新页面');
        }
        $this->assign('data', $result);
        return $this->fetch();
    }

    /**
     *  邮件删除处理
     * @param $id
     * @return \think\response\Json
     */
    public function del($id)
    {
        $email = new MEmails();
        $result = $email->deleteEmail($id);
        if ($result === false) {
            return json(['code' => 0, 'msg' => '数据删除失败,请检查']);
        } else {
            return json(['code' => 1, 'msg' => '数据删除成功']);
        }

    }

}