<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-10-9
 * Time: 10:51
 */

namespace app\admin\model;

use think\Model;
use \app\admin\model\Agentrels as MAgentrels;
use \app\admin\model\Users as MUsers;
class Agents extends Model
{
    public $tableName  ='agents';
    /**
     * 输出代理列表
     */
    public function formatAgentsOutput($result){
        $finalRes = array();
        $mUser = new MUsers();
        foreach($result as $key => $item){
            $data = array();
            if(!array_key_exists('uid', $item)){continue;}
            $data['uid'] = $item['uid'];
            $uData = $mUser->getById($item['uid']);
            //如果没找到用户,跳过
            if(!$uData){continue;}
            $data['name'] = (array_key_exists('name', $uData)) ? $uData['name']:'';
            $data['nickName'] = (array_key_exists('nickName', $uData)) ? $uData['nickName']:'';
            $data['pid'] = (array_key_exists('pid', $item)) ? $item['pid']:'';
            $data['level'] = (array_key_exists('level', $item)) ? $item['level']:'';
            $data['directSubAgents'] = $this->getSubAgentsNum($item['uid'],1);
            $data['allSubAgents'] = $this->getSubAgentsNum($item['uid'],0);
            $data['directMbrsNum'] = (array_key_exists('members', $item)) ? sizeof($item['members']) : 0;
            $data['allMbrsNum'] = $data['directMbrsNum'] + $this->getAllInDirectMbrsNum($item['uid']);
            $data['state'] = (array_key_exists('state', $item)) ? $item['state']:'';
            $data['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
            $data['disableTime'] = (array_key_exists('disableTime', $item)) ? date('Y-m-d H:i:s', $item['disableTime']/1000):'';
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }

    public function getRebateSum($agentData){
        $rebateSum = 0;
        $report = (array_key_exists('report', $agentData)) ? $agentData['report']:'';
        if($report != ''){
            $perfs = (array_key_exists('curtperfsPerf', $report)) ? $report['perfs']:array();
            foreach($perfs as $perf){
                $rebateSum += $perf['rebateSum'];
            }
        }
        return  $rebateSum;
    }

    public function getWithdrawSum($agentData){
        $withdrawSum = 0;
        $report = (array_key_exists('report', $agentData)) ? $agentData['report']:'';
        if($report != ''){
            $withdraws = (array_key_exists('withdraws', $report)) ? $report['withdraws']:array();
            foreach($withdraws as $withdraw){
                if($withdraw['state'] == 'PROCESSED'){
                    $withdrawSum += $withdraw['amount'];
                }
            }
        }
        return  $withdrawSum;
    }

    public function getBalance($agentData) {
		return $this->getRebateSum($agentData) - $this->getWithdrawSum($agentData);
	}

    public function formatAgentsReportOutput($result){
        $finalRes = array();
        $mUser = new MUsers();
        foreach($result as $key => $item){
            $data = array();
            $data['uid'] = $item['uid'];
            $uData = $mUser->getById($item['uid']);
            //如果没找到用户,跳过
            if(!$uData){continue;}
            $data['name'] = (array_key_exists('name', $uData)) ? $uData['name']:'';
            $report = (array_key_exists('report', $item)) ? $item['report']:'';
            if($report != ''){
                $data['availBalance']= $this->getBalance($item);
                $data['rebateSum']= $this->getRebateSum($item);
                $data['withdrawSum']= $this->getWithdrawSum($item);
                $curtPerf = (array_key_exists('curtPerf', $report)) ? $report['curtPerf']:array();
                if($curtPerf != ''){
                    $data['perfSum']= (array_key_exists('perfSum', $curtPerf)) ? $curtPerf['perfSum']:0;
                    $data['selfPerf']= (array_key_exists('selfPerf', $curtPerf)) ? $curtPerf['selfPerf']:0;
                    $data['teamMembersPerf']= (array_key_exists('teamMembersPerf', $curtPerf)) ? $curtPerf['teamMembersPerf']:0;
                    $data['rebateSum']= (array_key_exists('rebateSum', $curtPerf)) ? $curtPerf['rebateSum']:0;
                    $data['teamRebate']= (array_key_exists('teamRebate', $curtPerf)) ? $curtPerf['teamRebate']:0;
                }else{
                    $data['perfSum']= 0;
                    $data['selfPerf']= 0;
                    $data['teamMembersPerf']= 0;
                    $data['rebateSum']= 0;
                    $data['teamRebate']= 0;
                }
            }else{
                $data['availBalance']= 0;
                $data['rebateSum']= 0;
                $data['withdrawSum']= 0;
                $data['perfSum']= 0;
                $data['selfPerf']= 0;
                $data['teamMembersPerf']= 0;
                $data['rebateSum']= 0;
                $data['teamRebate']= 0;
            }
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }

    public function getReportRecords($userId){
        $filter = [ 'uid' => $userId ];
        $agent = $this->where($filter)->find()->toArray();
        $finalRes = array();
        $report = (array_key_exists('report', $agent)) ? $agent['report']:'';
        if($report != ''){
            $perfs = (array_key_exists('perfs', $report)) ? $report['perfs']: array();
            if(sizeof($perfs) != 0){
                foreach($perfs as $item){
                    $perf = array();
                    $perf['batchId'] = (array_key_exists('batchId', $item)) ? $item['batchId']:'';
                    $perf['perfSum'] = (array_key_exists('perfSum', $item)) ? $item['perfSum']:0;
                    $perf['selfPerf'] = (array_key_exists('selfPerf', $item)) ? $item['selfPerf']:0;
                    $perf['teamMembersPerf'] = (array_key_exists('teamMembersPerf', $item)) ? $item['teamMembersPerf']:0;
                    $perf['rebateSum'] = (array_key_exists('rebateSum', $item)) ? $item['rebateSum']:0;
                    $perf['teamRebate'] = (array_key_exists('teamRebate', $item)) ? $item['teamRebate']:0;
                    $perf['periodType'] = (array_key_exists('periodType', $item)) ? $item['baperiodTypechId']:'';
                    $perf['startTime'] = (array_key_exists('startTime', $item)) ? date('Y-m-d H:i:s', $item['startTime']/1000):'';
                    $perf['endTime'] = (array_key_exists('endTime', $item)) ? date('Y-m-d H:i:s', $item['endTime']/1000):'';
                    array_push($finalRes, $perf);
                }
            }
        }
        return $finalRes;
    }

    public function getWithdrawRecords($userId){
        $filter = [ 'uid' => $userId ];
        $agent = $this->where($filter)->find()->toArray();
        $finalRes = array();
        $report = (array_key_exists('report', $agent)) ? $agent['report']:'';
        if($report != ''){
            $withdraws = (array_key_exists('withdraws', $report)) ? $report['withdraws']: array();
            if(sizeof($withdraws) != 0){
                foreach($withdraws as $item){
                    if($item['state'] != 'PROCESSED' ){continue;}
                    $withdraw = array();
                    $withdraw['id'] = (array_key_exists('id', $item)) ? $item['id']:'';
                    $withdraw['amount'] = (array_key_exists('amount', $item)) ? $item['amount']:0;
                    $withdraw['state'] = (array_key_exists('state', $item)) ? $item['state']:'';
                    $withdraw['paymentType'] = (array_key_exists('paymentType', $item)) ? $item['paymentType']:'';
                    $withdraw['paymentId'] = (array_key_exists('paymentId', $item)) ? $item['paymentId']:'';
                    $withdraw['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
                    array_push($finalRes, $withdraw);
                }
            }
        }
        return $finalRes;
    }

     /**
     * 取得指定的记录
     */
    public function getWithdraw($uid, $oid){
        $filter = [ 'uid' => $uid ];
        $agent = $this->where($filter)->find()->toArray();
        $finalRes = array();
        $mUser = new MUsers();
        $uData = $mUser->getById($agent['uid']);
        //如果没找到用户,跳过
        if(!$uData){return $finalRes;}
        $data['name'] = (array_key_exists('name', $uData)) ? $uData['name']:'';
        $report = (array_key_exists('report', $agent)) ? $agent['report']:'';
        if($report != ''){
            $withdraws = (array_key_exists('withdraws', $report)) ? $report['withdraws']: array();
            if(sizeof($withdraws) != 0){
                foreach($withdraws as $item){
                    if($item['id'] == $oid){
                        $finalRes['oid'] = $item['id'];
                        $finalRes['uid'] = $agent['uid'];
                        $finalRes['amount'] = (array_key_exists('amount', $item)) ? $item['amount']:0;
                        $finalRes['state'] = (array_key_exists('state', $item)) ? $item['state']:'';
                        $finalRes['paymentType'] = (array_key_exists('paymentType', $item)) ? $item['paymentType']:'';
                        $finalRes['paymentId'] = (array_key_exists('paymentId', $item)) ? $item['paymentId']:'';
                        $finalRes['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
                        break;
                    }
                    
                }
            }
        }
        return $finalRes;
    }

    /**
     * 取得提佣的申请
     */
    public function getWithdrawRequest(){
        $agents = $this->where('state', 'eq', 'APPROVED')->select()->toArray();
        $finalRes = array();
        $mUser = new MUsers();
        foreach($agents as $agent){
            $uData = $mUser->getById($agent['uid']);
            //如果没找到用户,跳过
            if(!$uData){continue;}
            $data['name'] = (array_key_exists('name', $uData)) ? $uData['name']:'';
            $report = (array_key_exists('report', $agent)) ? $agent['report']:'';
            if($report != ''){
                $withdraws = (array_key_exists('withdraws', $report)) ? $report['withdraws']: array();
                if(sizeof($withdraws) != 0){
                    foreach($withdraws as $item){
                        if($item['state'] != 'PROCESSED'){
                            $withdraw = array();
                            $withdraw['id'] = (array_key_exists('id', $item)) ? $item['id']:'';
                            $withdraw['uid'] = $agent['uid'];
                            $withdraw['name'] = $data['name'];
                            $withdraw['availBalance']= (array_key_exists('availBalance', $report)) ? $report['availBalance']:0;
                            $withdraw['amount'] = (array_key_exists('amount', $item)) ? $item['amount']:0;
                            $withdraw['state'] = (array_key_exists('state', $item)) ? $item['state']:'';
                            $withdraw['paymentType'] = (array_key_exists('paymentType', $item)) ? $item['paymentType']:'';
                            $withdraw['paymentId'] = (array_key_exists('paymentId', $item)) ? $item['paymentId']:'';
                            $withdraw['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
                            array_push($finalRes, $withdraw);
                        }
                       
                    }
                }
            }
        }
        return $finalRes;
    }

    public function getAgentsPerfSum($datas){
        $perfSum = array();
        $perfSum['totalABSum'] = 0;
        $perfSum['totalRBSum'] = 0;
        $perfSum['totalWRSum'] = 0;
        foreach($datas as $item){
            $perfSum['totalABSum'] += $item['availBalance'];
            $perfSum['totalRBSum'] += $item['rebateSum'];
            $perfSum['totalWRSum'] += $item['withdrawSum'];
        }
        return $perfSum;
    }
    /**
     * 格式化会员的输出
     */
    public function formatMemberOutput($item){
        $data = array();
        $data['id'] = $item['_id'];
        $data['name'] = (array_key_exists('name', $item)) ? $item['name']:'';
        $data['nickName'] = (array_key_exists('nickName', $item)) ? $item['nickName']:'';
        $data['openId'] = (array_key_exists('openId', $item)) ? $item['openId']:'';
        //上级ID
        $data['pid'] = (array_key_exists('pid', $item)) ? $item['pid']:'';
        $data['level'] = (array_key_exists('level', $item)) ? $item['level']:'';
        $data['cardNum'] = (array_key_exists('cardNum', $item)) ? $item['cardNum']:0;
        $data['score'] = (array_key_exists('score', $item)) ? $item['score']:0;
        //变成该代理的会员的时间
        $data['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
        $data['locked'] = (array_key_exists('locked', $item)) ? $item['locked']:false;
        return $data;
    }
    /**
     * 取得直接的会员
     */
    public function getDirectMembers($uid){
        $filter = [ 'uid' => $uid ];
        $agent = $this->where($filter)->find()->toArray();
        $agentMembers = (array_key_exists('members', $agent)) ? $agent['members']: array();
        $members = array();
        $mUser = new MUsers();
        foreach($agentMembers as $item){
            $uData = $mUser->getById($item['uid']);
            //更新会员相关的属性
            $uData['pid'] = $uid;
            $uData['createTime'] = $item['createTime'];
            $finalData = $this->formatMemberOutput($uData);
            array_push($members, $finalData);
        }
        return $members;
    }

    /***
     * 取得下属会员
     */
    public function getAllMembers($uid){
        $agentRels = new MAgentrels();
        $subAgents = $this->getSubAgents($uid, 0);
        $directMembers = $this->getDirectMembers($uid);
        $members = $directMembers ?? array();
        $mUser = new MUsers();
        foreach($subAgents as $item){
            $agentMembers = (array_key_exists('members', $item)) ? $item['members']: array();
            foreach($agentMembers as $subItem){
                $uData = $mUser->getById($subItem['uid']);
                //更新会员相关的属性
                $uData['pid'] = $item['uid'];
                $uData['createTime'] = $subItem['createTime'];
                $finalData = $this->formatMemberOutput($uData);
                array_push($members, $finalData);
            }
        }
        return $members;
    }


    /***
     * 取得下属会员的数量
     */
    public function getAllInDirectMbrsNum($userId){
        $agentRels = new MAgentrels();
        $count = 0;
        $subAgents = $this->getSubAgents($userId, 0);
        foreach($subAgents as $item){
            $tempCount = (array_key_exists('members', $item)) ? sizeof($item['members']) : 0;
            $count += $tempCount;
        }
        return $count;
    }

    public function getAgents($data){
        $agents = array();
        foreach($data as $item){
            $tempAgent = $this->where('uid', $item['uid'])->find()->toArray();
            array_push($agents, $tempAgent);
        }
        return $agents;
    }
    /***
     * 取得下属代理的数量
     */
    public function getSubAgents($userId, $level){
        $agentRels = new MAgentrels();
        if(null == $level || $level== 0){
            $filter = [
                'pid' => $userId,
            ];
        }else{
            $filter = [
                'pid' => $userId,
                'agentLevel' => $level
            ];
        }
        $agentRelsData = $agentRels->where($filter)->select()->toArray();
        $agents = array();
        foreach($agentRelsData as $item){
            $tempAgent = $this->where('uid', $item['uid'])->find()->toArray();
            array_push($agents, $tempAgent);
        }
        return $agents;
    }

    /***
     * 取得下属代理的数量
     */
    public function getSubAgentsNum($userId, $level){
        $agentRels = new MAgentrels();
        if(null == $level || $level== 0){
            $filter = [
                'pid' => $userId,
            ];
        }else{
            $filter = [
                'pid' => $userId,
                'agentLevel' => $level
            ];
        }
        $count = $agentRels->where($filter)->count();
        return $count;
    }

    /**
     * 保存代理的层级关系
     */
    public function saveAgentRels($pid, $uid){
        $agentRels = new MAgentrels();
        //保存代理关系
        $arData = ['pid' => $pid, 'uid' => $uid, 'agentLevel' => 1];
        $agentRels->insert($arData);
        if($pid == 'SYSTEM_ROOT_UID'){ return;}
        //查找上级代理所有的父代理
        $parents = $agentRels->where('uid', $pid)->select()->toArray();
        foreach($parents as $item){
            if($item['pid'] == SYSTEM_ROOT_UID){ continue;}
            $tempArData = ['pid' => $item['pid'], 'uid' => $uid, 'agentLevel' => $item['agentLevel'] + 1];
            $agentRels->insert($tempArData);
        }
    }

    /**
     * 更新提佣记录
     */
    public function updateWithdraw($uid, $data){
        $filter = [ 'uid' => $uid ];
        $agent = $this->where($filter)->find()->toArray();
        $report = (array_key_exists('report', $agent)) ? $agent['report']:'';
        $withdraws = $report['withdraws'];
        foreach($withdraws as $key => $item){
            if($item['id'] == $data['id']){
                $data['createTime'] = $item['createTime'];
                $withdraws[$key] = $data;
                //扣减金额
                if($data['state'] == 'PROCESSED'){
                    $availBalance = (array_key_exists('availBalance', $agent['report'])) ? $agent['report']['availBalance']:0;
                    $agent['report']['availBalance'] = $availBalance - $data['amount'];
                }
                break;
            }
        }
        $agent['report']['withdraws'] = $withdraws;
        $result = $this->where($filter)->update($agent);
        return $result;
    }
    /**
     * 更新提佣记录
     */
    public function deleteWithdraw($uid, $oid){
        $filter = [ 'uid' => $uid ];
        $agent = $this->where($filter)->find()->toArray();
        $report = (array_key_exists('report', $agent)) ? $agent['report']:'';
        $withdraws = $report['withdraws'];
        foreach($withdraws as $key => $item){
            if($item['id'] == $oid && $item['state'] != 'PROCESSED'){
                unset($withdraws[$key]);
                break;
            }
        }
        $agent['report']['withdraws'] = $withdraws;
        $result = $this->where($filter)->update($agent);
        return $result;
    }
    /**
     * 加入提佣记录
     */
    public function saveWithdraw($uid, $data){
        $filter = [ 'uid' => $uid ];
        $agent = $this->where($filter)->find()->toArray();
        $report = (array_key_exists('report', $agent)) ? $agent['report']:'';
        if($report != ''){
            $withdraws = (array_key_exists('withdraws', $report)) ? $report['withdraws']: array();
            array_push($withdraws, $data);
        }else{
            $withdraws = array();
            array_push($withdraws, $data);
        }
        //扣减金额
        if($data['state'] == 'PROCESSED'){
            $agent['report']['availBalance'] -= $data['amount'];
        }
        $agent['report']['withdraws'] = $withdraws;
        $result = $this->where($filter)->update($agent);
        return $result;
    }

    /**
     * 加入会员
     */
    public function addMember($pid, $uid){
        $filter = [ 'uid' => $pid ];
        $agent = $this->where($filter)->find()->toArray();
        $agentMembers = (array_key_exists('members', $agent)) ? $agent['members']: array();
        $agentMember = ['uid' => $uid, 'createTime' => currentMillis()];
        array_push($agentMembers, $agentMember);
        $agent['members'] = $agentMembers;
        $result = $this->where($filter)->update($agent);
        //更新用户的代理标识
        $mUser = new MUsers();
        $result = $mUser->updateById(['id' => $uid, 'agentId' => $pid]);
        return $result;
    }
    /**
     *  统计用户下注总coin
     * @param $value
     * @param $data
     * @return float|int
     */
    public function getTongJiAttr($value, $data)
    {
        $Detail_log = new DetailLog();
        $result = $Detail_log->where('user_id',$data['id'])->where('action_id',2)->sum('coin');
        return $result;
    }

    /**
     *  获取上级代理
     * @param $value
     * @param $data
     * @return mixed|string
     */
    public function getParentAgentAttr($value, $data)
    {
        if ($data['parent_id'] == 0) {
            return '顶级';
        }
        $result = $this->where('parent_id', $data['id'])->find()['nickname'];
        if ($result == null) {
            return '无上级';
        } else {
            return $result;
        }

    }

    /**
     *  用户数量统计
     * @param $value
     * @param $data
     * @return int
     */
    public function getUserNumAttr($value, $data)
    {
        $User = new User();
        $result = $User->where('parent_id', $data['id'])->count();
        return $result;

    }

    /**
     *  下级代理num获取器统计
     * @param $value
     * @param $data
     * @return int
     */
    public function getDaiLiNumAttr($value, $data)
    {
        $result = $this->where('parent_id', $data['id'])->count();
        return $result;
    }

    public function getBanksAttr($value, $data)
    {
        $Bank = new Bank();
        $result = $Bank->field('coin')->where('user_id', $data['id'])->find()->toArray();
        return $result;

    }

    /**
     * @param $data
     * @return array|string
     * 代理信息保存
     */
    public function saveAgent($data)
    {
        $result = $this->insert($data);
        return $result;
    }

    /**
     * 根据id获取用户信息
     */
    public function getAgentInfoEdit($id)
    {
        $agent = $this->where('uid', strval($id))->find()->toArray();
        $agent['state'] = (array_key_exists('state', $agent)) ? $agent['state']:'PENDING';
        return $agent;
    }

    /**
     * @param $id
     * @return null|static
     * 通过ID获取代理密码信息
     */
    public function getAgentPasswordInfo($id)
    {
        $data = $this->get($id);
        return $data;
    }

    /**
     *  通过ID获取用户密码信息
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     */
    public function getUserPasswordInfo($id)
    {
        $User = new User();
        $result = $User->find($id);
        return $result;
    }

    /**
     * @param $password
     * @param $id
     * @param $aid
     * @return bool
     * 更改用户密码
     */
    public function changePasswordPross($password, $id, $aid)
    {
        if ($aid == 0) {
            $result = $this->update(['id' => $id, 'password' => $password]);
            $res = $this->res($result);
            return $res;
        }
        if ($aid == 1) {
            $User = new User();
            $result = $User->update(['id' => $id, 'password' => $password]);
            $res = $this->res($result);
            return $res;
        }
    }

    /**
     *  结果处理
     * @param $result
     * @return bool
     */
    public function res($result)
    {
        if ($result) {
            return true;
        } else {
            return false;
        }
    }


    /**
     *  用户编辑保存
     * @param int $lock
     * @return array|string
     */

    public function editSave($pid, $uid, $state)
    {
        $filter = [ 'pid' => $pid,  'uid' => $uid];
        $agent = $this->where($filter)->find()->toArray();
        $agent['state'] = $state;
        if($agent['state'] == 'DISABLED'){
            $agent['disableTime'] = currentMillis();
        }else{
            $agent['disableTime'] = null;
        }
        $result = $this->where($filter)->update($agent);
        return $result;
    }

    /** 删除代理更新左右值
     * @param $id
     * @return array|int
     */
    public function userLftRgtDelete($id)
    {
        $data = $this->where('id', $id)->field('lft,rgt')->find()->toArray();
        $middle = $data['rgt'] - $data['lft'] + 1;
        $this->where('lft', 'between', [$data['lft'], $data['rgt']])->delete();
        db('user')->where('lft', 'between', [$data['lft'], $data['rgt']])->delete();
        $this->execute("UPDATE `agent` SET rgt = rgt-{$middle} WHERE rgt > {$data['rgt']}");
        $this->execute("UPDATE `agent` SET lft = lft-{$middle} WHERE lft > {$data['rgt']}");
        $this->execute("UPDATE `user` SET rgt = rgt-{$middle} WHERE rgt > {$data['rgt']}");
        $this->execute("UPDATE `user` SET lft = lft-{$middle} WHERE lft > {$data['rgt']}");
        return $data;
    }

    /** 用户转移
     * @param $pid
     * @param $id
     * @return int
     */
    public function userMoveNode($pid, $id)
    {
        //软删除当前节点状态设置为1
        $User = new User();
        $data = $User->where('id', $id)->field('lft,rgt')->find()->toArray();
        //查询下级有没有
//        $xiaji = $User->where('lft', 'between', [$data['lft'], $data['rgt']])->select()->toArray();
//        $arr = [];
//        foreach ($xiaji as $x){
//            $arr[] = $x['id'];
//        }
        $middle = $data['rgt'] - $data['lft'] + 1;  //2
        $this->execute("update `user` set `node_status`=1,`lft`=0,`rgt`=0 WHERE id = {$id}");
//        $User->where('id','in',$arr)->update(['node_status'=>1,'lft'=>0,'rgt'=>0]);
        //更新所有节点

        $this->execute("UPDATE `user` SET rgt = rgt-{$middle} WHERE rgt > {$data['rgt']} AND `node_status`=0");
        $this->execute("UPDATE `user` SET lft = lft-{$middle} WHERE lft > {$data['rgt']} AND `node_status`=0");
        $this->execute("UPDATE `agent` SET rgt = rgt-{$middle} WHERE rgt > {$data['rgt']} AND `node_status`=0");
        $this->execute("UPDATE `agent` SET lft = lft-{$middle} WHERE lft > {$data['rgt']} AND `node_status`=0");
        //当前节点设置为0再把当前节点添加到父节点并更新左右值
        $node = $this->where('id', $pid)->field('lft,rgt')->find()->toArray();
        $lft = $node['rgt'];
        $rgt = $lft + 1;
        $this->execute("update `user` set rgt = rgt+2 where rgt >= {$node['rgt']} AND `node_status`=0");
        $this->execute("update `user` set lft = lft+2 where lft > {$node['rgt']} AND `node_status`=0");
        $this->execute("update `agent` set rgt = rgt+2 where rgt >= {$node['rgt']} AND `node_status`=0");
        $this->execute("update `agent` set lft = lft+2 where lft > {$node['rgt']} AND `node_status`=0");
        $User->execute("update `user` set lft={$lft},rgt={$rgt},`node_status`=0,`parent_id`={$pid} where id={$id}");
        return 1;
    }
}