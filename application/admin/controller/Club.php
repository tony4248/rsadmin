<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-10
 * Time: 17:30
 */

namespace app\admin\controller;

use app\admin\model\Users as MUsers;
use app\admin\model\Agents as MAgents;
use app\admin\model\Clubs as MClubs;
use think\Config;
use think\Db;
use think\Loader;

/**
 * Class Club
 * @package app\admin\controller
 * 用户处理类
 */
class Club extends Base
{

    /**
     * 俱乐部列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $clubs = new MClubs();
        $result = Db::name($clubs->tableName)->where('ownerId','neq', '')->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $finalRes = $clubs->formatClubsOutput($result);
        $page = $result->render();
        $this->assign('data', $finalRes);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     * 俱乐部搜索
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function search($clubId, $userId)
    {
        $clubs = new MClubs();
        if($userId){
            $result = Db::name($clubs->tableName)->where('ownerId','eq', $userId)->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
            if(!$result){
                $this->error('此用户没有俱乐部');
            }
        }elseif($clubId){
            $club = $clubs->getById($clubId);
            if(!$club){
                $this->error('此俱乐部不存在');
            }
            $result = array();
            array_push($result,$club);
        }else{
            $this->error('请输入查询条件');
        }
        $page = null;
        $finalRes = $clubs->formatClubsOutput($result);
        $this->assign('quick', '<input type="button" name="Submit" class="btn btn-default radius" onClick="javascript:window.history.back();return false;" value="返回">');
        $this->assign('data', $finalRes);
        $this->assign('page', $page);
        return $this->fetch('club/index');
    }

    /**
     *  显示俱乐部会员
     * @return mixed
     */
    public function getClubMembers($id)
    {
        $clubs = new MClubs();
        $result = $clubs->getClubMembers($id);
        $page = null;
        $this->assign('data', $result);
        $this->assign('page', $page);
        return $this->fetch('club/club_members');
    }
     /**
     *  显示俱乐部玩法
     * @return mixed
     */
    public function getClubGameConfs($id)
    {
        $clubs = new MClubs();
        $result = $clubs->getClubGameConfs($id);
        $page = null;
        $this->assign('data', $result);
        $this->assign('page', $page);
        return $this->fetch('club/game_confs');
    }

    public function validateSaveClub($id = '', $name = '', $description = '', $feeRate = ''){
        if (!is_numeric($feeRate) || $feeRate <0 ) {
            return ['code' => 0, 'msg' => '费率不能为负数'];
        }
        if (!trim($name) || !trim($name) ) {
            return ['code' => 0, 'msg' => '名称不能为空'];
        }
        return null;
    }
    /**
     * 编辑俱乐部
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function edit_club($id = '', $name = '', $description = '', $feeRate = 0, $state = '')
    {
        if (request()->isPost()) {
            $data = ['id' => $id, 
                    'name' => $name, 
                    'description' => $description, 
                    'feeRate' => $feeRate, 
                    'state' => $state];
            $validateRes = $this->validateSaveClub($id, $name, $description, $feeRate);
            if($validateRes){return $validateRes;}
            $cluds = new MClubs();
            $result = $cluds->updateClub($data);
            if ($result) {
                $this->success('俱乐部修改成功');
            } else {
                $this->error('俱乐部修改失败');
            }
        }
        $cluds = new MClubs();
        //更新代理层级数据
        $finalRes = $cluds->getClub($id);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

    public function validateTransferClub($id = '', $uid = ''){
        if (!is_numeric($uid) || strlen($uid) !=6 ) {
            return ['code' => 0, 'msg' => '代理ID错误'];
        }
        return null;
    }

     /**
     * 转移俱乐部
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function transfer_club($id = '', $uid = '')
    {
        if (request()->isPost()) {
            $data = ['id' => $id, 
                    'ownerId' => $uid];
            $validateRes = $this->validateTransferClub($id, $uid);
            if($validateRes){return $validateRes;}
            $agent = new MAgents();
            //代理的有效性
            $aid = $agent->where('uid',$uid)->field('uid')->find();
            if (!$aid) {
                $this->error('用户不是代理,无法接受俱乐部');
            }
            $cluds = new MClubs();
            $result = $cluds->transfClub($data);
            if ($result) {
                $this->success('俱乐部转移成功');
            } else {
                $this->error('俱乐部转移失败');
            }
        }
        $clubs = new MClubs();
        //更新代理层级数据
        $finalRes = $clubs->getClub($id);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

     /**
     * 删除俱乐部
     */
    public function del_club($id = '')
    {
        $clubs = new MClubs();
        $result = $clubs ->deleteClub($id);
        if ($result == 1) {
            return ['code' => 1, 'msg' => '删除成功'];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }
}