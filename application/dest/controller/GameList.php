<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-20
 * Time: 16:18
 */

namespace app\dest\controller;

use app\dest\model\GameList as GameModel;
use think\Config;

/**
 *  游戏管理
 * Class GameList
 * @package app\dest\controller
 */
class GameList extends Base
{
    public function index($status = '')
    {
        $GameLIst = new GameModel();
        if($status!==''){
            $GameLIst->where('status',$status);
        }

        $data = $GameLIst->paginate(Config::get('agent_page_num'),false,['query'=>$_GET]);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        return $this->fetch();
    }

    public function changeShow()
    {
        $GameList = new GameModel();
        if ($this->request->isPost()) {
            $data['id'] = $this->request->param('id');
            $data['status'] = $this->request->param('status');
            $data = $GameList->allowField(true)->update($data);
            if ($data['status'] == 1) {
                $msg = '游戏开启成功';
                return json(['code' => 1, 'msg' => $msg]);
            } elseif ($data['status'] == 0) {
                $msg = '游戏关闭成功';
                return json(['code' => 1, 'msg' => $msg]);
            }
        } else {
            $res['code'] = 0;
            $res['msg'] = '这是个意外！';
            return $res;
        }
    }

}