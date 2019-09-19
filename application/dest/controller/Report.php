<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-22
 * Time: 13:31
 */

namespace app\dest\controller;

use app\dest\model\DetailAction;
use app\dest\model\DetailLog;
use app\dest\model\GamePlayLog;
use app\dest\model\User;
use app\dest\model\GameList;
use app\dest\model\Agent as AgentModel;
use think\Config;

class Report extends Base
{
    /**
     *  统计
     * @param string $nickname
     * @param string $game_name
     * @param string $action_name
     * @param string $startTime
     * @param string $endTime
     * @return mixed
     */
    public function total($nickname = '', $game_name = '', $action_name = '', $startTime = '', $endTime = '')
    {
        $DetailLog = new DetailLog();
        $GameLIst = new GameList();
        $DetailAction = new DetailAction();
        $User = new User();
        if (!empty($nickname)) {
            $obj = $User->field('id')->where('nickname', trim($nickname))->find();
            if ($obj) {
                $DetailLog->where('user_id', $obj->id);
            }
        }
        if (!empty($game_name)) {
            $obj = $GameLIst->field('id')->where('name', trim($game_name))->find();
            if ($obj) {
                $DetailLog->where('game_id', $obj->id);
            }
        }
        if (!empty($action_name)) {
            $obj = $DetailAction->field('id')->where('name', trim($action_name))->find();
            if ($obj) {
                $DetailLog->where('action_id', $obj->id);
            }
        }
        if (!empty($startTime)) {
            $DetailLog->whereTime('create_time', '>=', $startTime);
        }
        if (!empty($endTime)) {
            $DetailLog->whereTime('create_time', '<=', $endTime);
        }
        if (!empty($startTime) && !empty($endTime)) {
            $DetailLog->whereTime('create_time', 'between', [$startTime, $endTime]);
        }
        if (!empty($nickname) || !empty($game_name) || !empty($action_name) || !empty($startTime) || !empty($endTime)) {
            $this->assign('quick', '<input type="button" name="Submit" class="btn btn-default radius" onclick="javascript:history.back(-1);" value="返回">');
        }
        $agent_id = session('agent_id');
        $data = $User->select()->toArray();
        $res = self::getTree($data, $agent_id);
        $data = $DetailLog->where('user_id', 'in', $res)->paginate(Config::get('myConfig.agent_page_num'), false, ['query' => $_GET]);
        $page = $data->render();
        $sum_coin = $DetailLog->where('user_id', 'in', $res)->where('action_id', 2)->sum('coin');
        $winOrLose = $DetailLog->where('user_id', 'in', $res)->where('action_id', 3)->sum('coin');
        $this->assign('winOrLose', $winOrLose);
        $this->assign('sum_coin', abs($sum_coin));
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     *  对局记录
     * @return mixed
     */
    public function game_play_log()
    {
        $GamePlayLog = new GamePlayLog();
        $data = $GamePlayLog->paginate(Config::get('myConfig.agent_page_num'), false, ['query' => $_GET]);
        $page = $data->render();
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     *  下注记录
     * @param string $nickname
     * @param string $game_name
     * @param string $action_name
     * @param string $startTime
     * @param string $endTime
     * @return mixed
     */
    public function bet_record($nickname = '', $game_name = '', $action_name = '', $startTime = '', $endTime = '')
    {
        $DetailLog = new DetailLog();
        $GameLIst = new GameList();
        $DetailAction = new DetailAction();
        $Agent = new AgentModel();
        $User = new User();
        if (!empty($nickname)) {
            $obj = $User->field('id')->where('nickname', trim($nickname))->find();
            if ($obj) {
                $DetailLog->where('user_id', $obj->id);
            }
        }
        if (!empty($game_name)) {
            $obj = $GameLIst->field('id')->where('name', trim($game_name))->find();
            if ($obj) {
                $DetailLog->where('game_id', $obj->id);
            }
        }
        if (!empty($action_name)) {
            $obj = $DetailAction->field('id')->where('name', trim($action_name))->find();
            if ($obj) {
                $DetailLog->where('action_id', $obj->id);
            }
        }
        if (!empty($startTime)) {
            $DetailLog->whereTime('create_time', '>=', $startTime);
        }
        if (!empty($endTime)) {
            $DetailLog->whereTime('create_time', '<=', $endTime);
        }
        if (!empty($startTime) && !empty($endTime)) {
            $DetailLog->whereTime('create_time', 'between', [$startTime, $endTime]);
        }
        if (!empty($nickname) || !empty($game_name) || !empty($action_name) || !empty($startTime) || !empty($endTime)) {
            $this->assign('quick', '<a class="btn btn-default radius" href="/admin.php/dest/report/bet_record">返回</a>');
        }

        $agent_id = session('agent_id');
        $data = User::all();
        $res = self::getTree($data, $agent_id); //我的用户

        $agent_user = $Agent->where('parent_id', $agent_id)->field('id')->select()->toArray();
        $data = [];
        foreach ($agent_user as $v) {
            $data[] = $v['id'];
        }
        $Subordinate_user = array_merge($data, $res);
        $rows = $DetailLog->where('user_id', 'in', $Subordinate_user)->where('action_id', 2)->order('create_time desc')->paginate(Config::get('myConfig.agent_page_num'), false, ['query' => $_GET]);

        $page = $rows->render();
        $total_sub = $DetailLog->where('action_id', 2)->where('user_id', 'in', $Subordinate_user)->sum('coin');  //总下注
        $this->assign('data', $rows);
        $this->assign('page', $page);

        $this->assign('total_sub', abs($total_sub));
        return $this->fetch();
    }

    /**
     *  充值记录
     * @param string $nickname
     * @param string $startTime
     * @param string $endTime
     * @return mixed
     */
    public function recharge_sub($nickname = '', $startTime = '', $endTime = '')
    {
        $DetailLog = new DetailLog();
        $GameLIst = new GameList();
        $DetailAction = new DetailAction();
        $Agent = new AgentModel();
        $User = new User();
        if (!empty($nickname)) {
            $obj = $User->field('id')->where('nickname', trim($nickname))->find();
            if ($obj) {
                $DetailLog->where('user_id', $obj->id);
            }
        }
        if (!empty($startTime)) {
            $DetailLog->whereTime('create_time', '>=', $startTime);
        }
        if (!empty($endTime)) {
            $DetailLog->whereTime('create_time', '<=', $endTime);
        }
        if (!empty($startTime) && !empty($endTime)) {
            $DetailLog->whereTime('create_time', 'between', [$startTime, $endTime]);
        }
        if (!empty($nickname) || !empty($game_name) || !empty($action_name) || !empty($startTime) || !empty($endTime)) {
            $this->assign('quick', '<a class="btn btn-default radius" href="/admin.php/dest/report/recharge_sub">返回</a>');
        }
        $agent_id = session('agent_id');
        $data = User::all();
        $my_user = self::getTree($data,$agent_id);  //我的用户
        $agent_user = $Agent->where('parent_id', $agent_id)->field('id')->column('id');
        $Subordinate_user = array_merge($my_user,$agent_user);
        $result = $DetailLog->field('room_id,game_id',true)->where('user_id','in',$Subordinate_user)->where('action_id',1)->order('create_time desc')->paginate(Config::get('myConfig.agent_page_num'), false, ['query' => $_GET]);
        $page = $result->render();

        $total_sub = $DetailLog->where('action_id', 1)->where('user_id', 'in', $Subordinate_user)->sum('coin');  //总下注
        $this->assign('data',$result);
        $this->assign('total_sub',$total_sub);
        $this->assign('page',$page);

        return $this->fetch();
    }

    /**
     *  下注记录批量删除
     * @return \think\response\Json
     */
    public function bet_batch_del()
    {
        $checks = input('ids/a');
        $ids = implode(",", $checks);
        $DetailLog = new DetailLog();
        $result = $DetailLog->checkDel($ids);
        if ($result) {
            return json(['code' => 1, 'msg' => '删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }

    /**
     *  删除记录
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        $DetailLog = new DetailLog();
        $result = $DetailLog->where('id',$id)->delete();
        if($result){
            return json(['code'=>1,'msg'=>'已删除']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败,请检查']);
        }

    }

    public function detail($id = '')
    {
        $GamePlayLog = new GamePlayLog();
        $result = $GamePlayLog->where('id', $id)->find()->toArray();
        switch ($result) {
            case $result['game_id'] == 4:
                self::TexasHold($result);
                return $this->fetch();
                break;
            case $result['game_id'] == 3:
                break;
            case $result['game_id'] == 2:
                echo "Number 3";
                break;
            case $result['game_id'] == 1:
                echo "Number 3";
                break;
            default:
                echo "No number between 1 and 3";
        }
    }


    public function TexasHold($result)
    {

    }

    /**
     *  获取下级
     * @param $data
     * @param $id
     * @return array
     */
    public function getTree($data, $id)
    {
        static $arr = [];
        foreach ($data as $key => $val) {
            if ($val['parent_id'] == $id) {
                $arr[] = $val['id'];
                $this->getTree($data, $val['id']);
            }
        }
        return $arr;
    }

}