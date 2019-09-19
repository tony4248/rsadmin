<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-21
 * Time: 11:15
 */

namespace app\admin\model;


use think\Model;

class SystemCache extends Model
{
    public $tableName  ='systemcaches';
    public $keyName = "SC01";

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

     /**
     * 取得配置
     */
    public function getSystemCache(){
        $systemCache = $this->getById($this->keyName);
        $onlineUsers = (array_key_exists('onlineUsers', $systemCache)) ? $systemCache['onlineUsers']: array();
        $onlineAis = (array_key_exists('onlineAis', $systemCache)) ? $systemCache['onlineAis']: array();
        $onlineRooms = (array_key_exists('onlineRooms', $systemCache)) ? $systemCache['onlineRooms']: array();
        $systemCache = ['onlineUsers' => $onlineUsers, 'onlineAis' => $onlineAis, 'onlineRooms' => $onlineRooms];
        return $systemCache;
    }


}