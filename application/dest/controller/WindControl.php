<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-24
 * Time: 14:04
 */

namespace app\dest\controller;

use app\dest\model\DetailLog;
use \app\dest\model\GameList as GameModel;
use app\dest\model\User;
use think\Config;

class WindControl extends Base
{
    public function index($nickname = '', $game_name = '', $startTime = '', $endTime = '')
    {
//        if($nickname == ''){
//            $this->error('请输入昵称');
//        }
        $Detail_log = new DetailLog();
        $User = new User();

        if($nickname){
            if(!$data = $User->where('nickname',$nickname)->field('id')->find()){
                $this->error('用户不存在');
            }
            $Detail_log->where('user_id',$data->id);
        }
        if($game_name){
            $Detail_log->where('game_id',$game_name);
        }
        if($startTime){
            $Detail_log->whereTime('create_time','>',$startTime);
        }
        if($endTime){
            $Detail_log->whereTime('create_time','<',$endTime);
        }
        $data = $Detail_log->field('action_id,coin,id')->select()->toArray();
//        halt($data);
        //玩家账号
        $this->assign('nickname',$nickname);
        //一段时间有效投注
        $touzhu = [];
        $num_bot = [];
        $winOrlose = [];
        foreach ($data as $v){
            if($v['action_id'] ==2){
                $touzhu[] = $v['coin'];
            }elseif ($v['action_id'] == 3){
                $num_bot[] = $v['id'];
                $winOrlose[] = $v['coin'];
            }
        }
        $sum_bet = abs(array_sum($touzhu));
        $this->assign('sum_bet',$sum_bet);
        //一段时间局数
        $this->assign('num_bot',count($num_bot));
        //一段时间盈利
        $this->assign('winOrlose',abs(array_sum($winOrlose)));
        $game_list = GameModel::all();
        $this->assign('game_list', $game_list);
        return $this->fetch('index');
    }


    public function search($nickname = '', $game_name = '', $startTime = '', $endTime = '')
    {
        if($nickname == ''){
            $this->error('请输入昵称');
        }
        $Detail_log = new DetailLog();
        $User = new User();

        if($nickname){
            if(!$data = $User->where('nickname',$nickname)->field('id')->find()){
                $this->error('用户不存在');
            }
            $Detail_log->where('user_id',$data->id);
        }
        if($game_name){
            $Detail_log->where('game_id',$game_name);
        }
        if($startTime){
            $Detail_log->whereTime('create_time','>',$startTime);
        }
        if($endTime){
            $Detail_log->whereTime('create_time','<',$endTime);
        }
        $data = $Detail_log->select()->toArray();
        //玩家账号
        $this->assign('nickname',$nickname);
        //一段时间有效投注
        $touzhu = [];
        $num_bot = [];
        $winOrlose = [];
        foreach ($data as $v){
            if($v['action_id'] ==2){
                $touzhu[] = $v['coin'];
            }elseif ($v['action_id'] == 3){
                    $num_bot[] = $v['id'];
                  $winOrlose[] = $v['coin'];
            }
        }
        $sum_bet = abs(array_sum($touzhu));
        $this->assign('sum_bet',$sum_bet);
        //一段时间局数
        $this->assign('num_bot',count($num_bot));
        //一段时间盈利
        $this->assign('winOrlose',abs(array_sum($winOrlose)));

        $game_list = GameModel::all();
        $this->assign('game_list', $game_list);
        return $this->fetch('index');
    }


}