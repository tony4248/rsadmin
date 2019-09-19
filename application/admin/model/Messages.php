<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-11
 * Time: 18:04
 */

namespace app\admin\model;


use think\Config;
use think\Model;

class Messages extends Model
{
    public $tableName  ='messages';

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
     * 输出消息的列表
     */
    public function formatMessagesOutput($result){
        $finalRes = array();
        foreach($result as $key => $item){
            $data = array();
            $data['id'] = $item['_id'];
            $data['content'] = (array_key_exists('content', $item)) ? $item['content']:'';
            $data['persistent'] = (array_key_exists('persistent', $item)) ? $item['persistent']:false;
            $data['createdDate'] = (array_key_exists('createdDate', $item)) ? date('Y-m-d H:i:s', $item['createdDate']/1000):'';
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }
    /**
     * 保存消息
     */
    public function saveMessage($content){
        $data = [
            '_id' => uniqueTimestampNums(),
            'content' => trim($content),
            'persistent' => true,
            'createdDate' => currentMillis()];
        $result = $this->insertById($data);
        return $result;
    }

    /**
     * 根据Id取得消息
     */
    public function getMessage($id){
        $data = array();
        $item = $this->getById($id);
        if(!$item){return $finalRes;}
        $data['id'] = $item['_id'];
        $data['content'] = (array_key_exists('content', $item)) ? $item['content']:'';
        $data['persistent'] = (array_key_exists('persistent', $item)) ? $item['persistent']:false;
        $data['createdDate'] = (array_key_exists('createdDate', $item)) ? date('Y-m-d H:i:s', $item['createdDate']/1000):'';
        return $data;
    }

    /**
     * 删除消息
     */
    public function deleteMessage($id){
        $result = $this->deleteById($id);
        return $result;
    }

}