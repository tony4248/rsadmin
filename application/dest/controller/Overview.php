<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-21
 * Time: 17:16
 */

namespace app\dest\controller;

use app\admin\model\Bank;
use app\admin\model\DetailLog;
use app\dest\model\Agent as AgentModel;
use app\dest\model\User;

/**
 *  数据总览
 * Class Overview
 * @package app\dest\controller
 */
class Overview extends Base
{
    public function index()
    {
        $self_id = session('agent_id');
        $AgenModel = new AgentModel();
        $DetailLog = new DetailLog();
        $Bank = new Bank();
        $User = new User();
        //直线会员
        $line_member = $User->where('parent_id',$self_id)->count();
        //直线代理
        $line_agent = $AgenModel->where('parent_id',$self_id)->count();
        //直线会员剩余总coin(bank+self)
        $line_member_all = $User->where('parent_id',$self_id)->field('id,coin')->select()->toArray();//直线会员
            //直线会员携带coin的和
        $array = [];
        foreach ($line_member_all as $k=>$v){
            $array[] = $v['coin'];
        }
            //直线会员bank coin的和
        $arr = [];
        foreach ($line_member_all as $k=>$v){
            if(!$coin = $Bank->where('user_id',$v['id'])->find()){
                $arr[] = 0;
            }else{
                $arr[] = $coin->coin;
            }
        }

        $linePersonalCoinAll = array_sum($array) + array_sum($arr);

        //直线会员输赢
        $line_array= [];
        foreach ($line_member_all as $k=>$v){
            $line_array[] = $DetailLog->where('user_id',$v['id'])->where('action_id',3)->sum('coin');
        }
        $lineMemberWinOrLose = array_sum($line_array);

        //直线会员总下注
        $total_bat = [];
        foreach ($line_member_all as $k=>$v){
            $total_bat[] = $DetailLog->where('user_id',$v['id'])->where('action_id',2)->sum('coin');
        }
        $line_member_bat = abs(array_sum($total_bat));
        $this->assign('line_member',$line_member); //直线会员数量
        $this->assign('line_agent',$line_agent);  //直线代理数量
        $this->assign('linePersonalCoinAll',$linePersonalCoinAll);  //直线会员自身携带金币+bank的和
        $this->assign('lineMemberWinOrLose',$lineMemberWinOrLose);  //直线会员输赢
        $this->assign('line_member_bat',$line_member_bat);  //直线会员总下注
        return $this->fetch();
    }


    /**
     *  获取下级
     * @param $data
     * @param $id
     * @return array
     */
    public function getTree($data,$id)
    {
        static $arr = [];
        foreach ($data as $key => $val) {
            if ($val['parent_id'] == $id) {
                $arr[]           = $val['id'];
                $this->getTree($data, $val['id']);
            }
        }
        return $arr;
    }

}