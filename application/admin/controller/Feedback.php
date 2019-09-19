<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-11
 * Time: 16:36
 */

namespace app\admin\controller;

use app\admin\model\Feedbacks as MFeedback;
use app\admin\model\Users as MUsers;
use think\Config;
use think\Db;
use think\Loader;

/**
 * Class Feedback
 * @package app\admin\controller
 * 邮件处理类
 */
class Feedback extends Base
{

    /**
     * @return mixed
     * 邮件列表首页
     */
    public function index()
    {
        $feedback = new MFeedback();
        $result = Db::name($feedback->tableName)->where('sender','neq', '')->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $finalRes = $feedback->formatFeedbacksOutput($result);
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
        $this->redirect('feedback/index');
    }

    /**
     * @return mixed
     *反馈列表首页
     */
    public function search($sender = '')
    {
        $feedback = new MFeedback();
        $result = $feedback->getFeedbacks($sender);
        $finalRes = $feedback->formatFeedbacksOutput($result);
        if (trim($sender) != '') {
            $this->assign('quick', '<input type="button" name="Submit" class="btn btn-default radius" onclick="javascript:history.back(-1);" value="返回">');
        }
        $page = null;
        $this->assign('data', $finalRes);
        $this->assign('page', $page);
        return $this->fetch('feedback/index');
    }

    /**
     * 回复反馈
     */
    public function reply($id = '', $sender = '', $replyContent = '')
    {
        if (request()->isPost()) {
            if (!$replyContent) {
                $this->error('请输入内容');
            }
            $feedback = new MFeedback();
            $result = $feedback->replyFeedback($id, $replyContent);
            if ($result) {
                $this->success('反馈回复成功');                
            } else {
                $this->error('反馈回复失败,请输入正确信息!');
            }
        }
        $feedback = new MFeedback();
        $result = $feedback->getFeedback($id);
        if ($result === false) {
            $this->error('对不起,反馈不存在,请刷新页面');
        }
        $this->assign('data', $result);
        return $this->fetch();
    }

    /**
     *  反馈删除处理
     * @param $id
     * @return \think\response\Json
     */
    public function del($id)
    {
        $feedback = new MFeedback();
        $result = $feedback->deleteFeedback($id);
        if ($result === false) {
            return json(['code' => 0, 'msg' => '数据删除失败,请检查']);
        } else {
            return json(['code' => 1, 'msg' => '数据删除成功']);
        }

    }

}