<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-11
 * Time: 16:36
 */

namespace app\admin\controller;

use think\Config;
use think\Db;
use think\Loader;
use app\admin\model\Messages as MMessages;

/**
 * Class Message
 * @package app\admin\controller
 * 消息处理类
 */
class Message extends Base
{

    /**
     * @return mixed
     * 消息列表首页
     */
    public function index()
    {
        $message = new MMessages();
        $result = Db::name($message->tableName)->where('sender','neq', '')->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $finalRes = $message->formatMessagesOutput($result);
        $page = $result->render();
        $this->assign('data', $finalRes);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     *  消息添加处理
     * @return mixed
     */
    public function add($content = '')
    {
        if (request()->isPost()) {
            if (!$content ) {
                $this->error('请输入内容');
            }
            $message = new MMessages();
            $result = $message->saveMessage($content);
            if ($result) {
                $this->success('消息添加成功');                
            } else {
                $this->error('消息添加失败!');
            }
        }
        return $this->fetch();
    }

    /**
     *  消息删除处理
     * @param $id
     * @return \think\response\Json
     */
    public function del($id)
    {
        $Message = new MMessages();
        $result = $Message->deleteMessage($id);
        if ($result === false) {
            return json(['code' => 0, 'msg' => '数据删除失败,请检查']);
        } else {
            return json(['code' => 1, 'msg' => '数据删除成功']);
        }

    }

}