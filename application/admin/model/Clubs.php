<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-21
 * Time: 11:15
 */

namespace app\admin\model;


use think\Model;
use \app\admin\model\Users as MUsers;
use \app\admin\model\Rooms as MRooms;
use \app\admin\model\ClubOrders as MClubOrders;

class Clubs extends Model
{
    public $tableName  ='clubs';

    public function getById($id){
        $result = getDataByObjId($this->tableName, $id);
        return $result;
    }

    public function updateById($data){
        $result = updateDataByObjId($this->tableName, $data);
        return $result;
    }

    public function deleteById($id){
        $result = deleteDataByObjId($this->tableName, $id);
        return $result;
    }

    public function insertById($data){
        $result = insertData($this->tableName, $data);
        return $result;
    }

    public function formatClubOutput($item){
        $data = array();
        $data['id'] = $item['_id'];
        $data['name'] = (array_key_exists('name', $item)) ? $item['name']:'';
        $data['description'] = (array_key_exists('description', $item)) ? $item['description']:'';
        $data['ownerId'] = (array_key_exists('ownerId', $item)) ? $item['ownerId']:'';
        $data['gameType'] = (array_key_exists('gameType', $item)) ? $item['gameType']:'';
        $data['feeRate'] = (array_key_exists('feeRate', $item)) ? $item['feeRate']:'';
        $data['level'] = (array_key_exists('level', $item)) ? $item['level']:'';
        $data['state'] = (array_key_exists('state', $item)) ? $item['state']:'';
        $data['members'] =  (array_key_exists('members', $item)) ? sizeof($item['members']):0;
        $data['gameConfs'] = (array_key_exists('gameConfs', $item)) ? sizeof($item['gameConfs']):0;
        $data['onlineRooms'] = (array_key_exists('onlineRooms', $item)) ? sizeof($item['onlineRooms']) : 0;
        $data['offlineRooms'] = (array_key_exists('offlineRooms', $item)) ? sizeof($item['offlineRooms']) : 0;
        $data['membersCapacity'] = (array_key_exists('membersCapacity', $item)) ? $item['membersCapacity']:'';
        $data['adminsCapacity'] = (array_key_exists('adminsCapacity', $item)) ? $item['adminsCapacity']:'';
        $data['autoApprove'] = (array_key_exists('autoApprove', $item)) ? $item['autoApprove']:'';
        $data['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
        return $data;
    }


    /**
     * 输出俱乐部列表
     */
    public function formatClubsOutput($result){
        $finalRes = array();
        if(!$result){return $finalRes;}
        //$result = $result->toArray();
        foreach($result as $key => $item){
            $data = $this->formatClubOutput($item);
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }

    /**
     * 取得会员
     */
    public function getClubMembers($clubId){
        $club = $this->getById($clubId);
        $membersData = (array_key_exists('members', $club)) ? $club['members']: array();
        $mUser = new MUsers();
        $members = array();
        $admins = array();
        $finalRes = array();
        foreach($membersData as $item){
            $data = array();
            $uData = $mUser->getById($item['uid']);
            if(!$uData){continue;}
            //更新会员相关的属性
            $data['id'] = $item['uid'];
            $data['name'] = (array_key_exists('name', $uData)) ? $uData['name']:'';
            $data['nickName'] = (array_key_exists('nickName', $uData)) ? $uData['nickName']:'';
            $data['level'] = (array_key_exists('level', $item)) ? $item['level']:'';
            $data['adminRole'] = (array_key_exists('adminRole', $item)) ? $item['adminRole']:'';
            $data['score'] = (array_key_exists('score', $item)) ? $item['score']:'';
            $data['state'] = (array_key_exists('state', $item)) ? $item['state']:'';
            $data['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
            if($data['adminRole'] == "NONE" || $data['adminRole'] == ""){
                array_push($members, $data);
            }else{
                array_push($admins, $data);
            }
        }
        $finalRes = array_merge($admins, $members);
        return $finalRes;
    }

    /**
     * 取得俱乐部玩法配置
     */
    public function getClubGameConfs($clubId){
        $club = $this->getById($clubId);
        $gameConfsData = (array_key_exists('gameConfs', $club)) ? $club['gameConfs']: array();
        $finalRes = array();
        foreach($gameConfsData as $item){
            $data = array();
            $data['gcId'] = $item['gcId'];   
            $data['rounds'] = (array_key_exists('rounds', $item)) ? $item['rounds']:0;
            $data['bottomScore'] = (array_key_exists('bottomScore', $item)) ? $item['bottomScore']:0;
            $data['chargingMode'] = (array_key_exists('chargingMode', $item)) ? $item['chargingMode']:'';
            $data['capacity'] = (array_key_exists('capacity', $item)) ? $item['capacity']:0;
            $data['minChips'] = (array_key_exists('minChips', $item)) ? $item['minChips']:0;
            $data['maxChips'] = (array_key_exists('maxChips', $item)) ? $item['maxChips']:0;
            $data['secret'] = (array_key_exists('secret', $item)) ? $item['secret']:0;
            array_push($finalRes, $data);
        }
        return $finalRes;
    }

    
    public function getClub($clubId){
        $club = $this->getById($clubId);
        $data = $this->formatClubOutput($club);
        return $data;
    }

    public function updateClub($data){
        $club = $this->getById($data['id']);
        $club['id'] = $data['id'];
        $club['name'] = $data['name'];
        $club['description'] = $data['description'];
        $club['feeRate'] = $data['feeRate'];
        $club['state'] = $data['state'];
        $result = $this->updateById($club);
        return $result;
    }

    public function transfClub($data){
        $club = $this->getById($data['id']);
        $club['id'] = $data['id'];
        $club['ownerId'] = $data['ownerId'];
        $result = $this->updateById($club);
        return $result;
    }

    public function deleteClub($id){
        //删除房间记录
        $rooms = new MRooms();
        $rooms->deleteClubOfflineRooms($id);
        //删除订单记录
        $clubOrders = new MClubOrders();
        $clubOrders->where('clubId',$id)->delete();
        //删除俱乐部
        $result = $this->deleteById($id);
        return $result;
    }
}