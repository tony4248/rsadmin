<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-21
 * Time: 11:15
 */

namespace app\admin\model;
use \app\admin\model\Clubs as MClubs;

use think\Model;

class Rooms extends Model
{
    public $tableName  ='rooms';

    public function getById($id){
        $result = getDataByObjId($this->tableName, $id);
        return $result;
    }

    public function getByIds($ids){
        $result = getDataByObjIds($this->tableName, $ids);
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
    public function deleteByIds($ids){
        $result = deleteDataByObjIds($this->tableName, $ids);
        return $result;
    }

    public function insertById($data){
        $result = insertData($this->tableName, $data);
        return $result;
    }

    /**
     * 输出房间的列表
     */
    public function formatRoomsOutput($result){
        $finalRes = array();
        if(!$result){return $finalRes;}
        foreach($result as $key => $item){
            $data = array();
            $data['id'] = $item['_id'];
            $data['price'] = (array_key_exists('price', $item)) ? $item['price']:0;
            $data['chargingMode'] = (array_key_exists('chargingMode', $item)) ? $item['chargingMode']:'';
            $data['bottomScore'] = (array_key_exists('bottomScore', $item)) ? $item['bottomScore']:0;
            $data['type'] = (array_key_exists('type', $item)) ? $item['type']:'';
            $data['owner'] = (array_key_exists('owner', $item)) ? $item['owner']:'';
            $data['category'] = (array_key_exists('category', $item)) ? $item['category']:'';
            $data['capacity'] = (array_key_exists('capacity', $item)) ? $item['capacity']:'';
            $data['rounds'] = (array_key_exists('rounds', $item)) ? $item['rounds']:'';
            $data['minChips'] = (array_key_exists('minChips', $item)) ? $item['minChips']:'';
            $data['maxChips'] = (array_key_exists('maxChips', $item)) ? $item['maxChips']:'';
            $data['users'] =  (array_key_exists('users', $item)) ? sizeof($item['users']):0;
            $data['createDate'] = (array_key_exists('createDate', $item)) ? date('Y-m-d H:i:s', $item['createDate']/1000):'';
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }
    /** 取得房间的对局总计 */
    public function getfinalRsls($roomId){
        $finalRes = array();
        $room = $this->getById($roomId);
        if(null || !(array_key_exists('finalRsls', $room))){return $finalRes;}
        $finalRsls = $room['finalRsls'];
        foreach($finalRsls as $key => $item){
            $data = array();
            $data['id'] = (array_key_exists('id', $item)) ? $item['id']:'';
            $data['name'] = (array_key_exists('name', $item)) ? $item['name']:'';
            $data['awarded'] = (array_key_exists('awarded', $item)) ? $item['awarded']:'';
            $finalRes[$key] = $data;
        }
        return $finalRes;
    }

    /** 取得每局的对局详情 */
    public function getGameRsl($roomId){
        $finalRes = array();
        $room = $this->getById($roomId);
        if(null || !(array_key_exists('gameRsls', $room))){return $finalRes;}
        $gameRsls = $room['gameRsls'];
        foreach($gameRsls as $key => $item){
            $data = array();
            $data['roundIdx'] = $item['roundIdx'];
            $userRsls = (array_key_exists('userRsls', $item)) ? $item['userRsls']:array();
            $uDatas = array();
            foreach($userRsls as $key => $uRsl){
                $uData = array();
                $uData['id'] = (array_key_exists('id', $uRsl)) ? $uRsl['id']:'';
                $uData['name'] = (array_key_exists('name', $uRsl)) ? $uRsl['name']:'';
                $uData['seatNo'] = (array_key_exists('seatNo', $uRsl)) ? $uRsl['seatNo']:'';
                $uData['dealer'] = (array_key_exists('dealer', $uRsl)) ? $uRsl['dealer']:'';
                $uData['win'] = (array_key_exists('win', $uRsl)) ? $uRsl['dealer']:'';
                $uData['score'] = (array_key_exists('score', $uRsl)) ? $uRsl['score']:0;
                $uData['awarded'] = (array_key_exists('awarded', $uRsl)) ? $uRsl['awarded']:0;
                array_push($uDatas,$uData);
            }
            $data['userRsls'] = $uDatas;
            array_push($finalRes, $data);
        }
        return $finalRes;
    }


    public function getClubOfflineRooms($clubId){
        $finalRes = array();
        $clubs = new MClubs();
        $club = $clubs->getById($clubId);
        if(!$club){return $finalRes;}
        $offlineRooms = (array_key_exists('offlineRooms', $club)) ? $club['offlineRooms']:array();
        $finalRes = $this->getByIds($offlineRooms);
        return $finalRes;
    }

    public function getClubOnlineRooms($clubId){
        $finalRes = array();
        $clubs = new MClubs();
        $club = $clubs->getById($clubId);
        if(!$club){return $finalRes;}
        $onlineRooms = (array_key_exists('onlineRooms', $club)) ? $club['onlineRooms']:array();
        $finalRes = $this->getByIds($onlineRooms);
        return $finalRes;
    }

    public function deleteClubOfflineRooms($clubId){
        $clubs = new MClubs();
        $club = $clubs->getById($clubId);
        if(!$club){return true;}
        $offlineRooms = (array_key_exists('offlineRooms', $club)) ? $club['offlineRooms']:array();
        $result = $this->deleteByIds($offlineRooms);
        return $result;
    }
}