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
use app\admin\model\Users as MUsers;

class Feedbacks extends Model
{
    public $tableName  ='feedbacks';

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
     * 输出反馈的列表
     */
    public function formatFeedbacksOutput($result){
        $finalRes = array();
        $mUser = new MUsers();
        foreach($result as $key => $item){
            $data = array();
            $data['id'] = $item['_id'];
            $senderData = $mUser->getById($item['sender']);
            if($senderData){
                $sender = $senderData['name']."(".$item['sender'].")";
            }else{
                $sender = "未知用户(".$item['sender'].")";
            }
            $data['sender'] = $sender;
            $data['title'] = (array_key_exists('title', $item)) ? $item['title']:'';
            $data['content'] = (array_key_exists('content', $item)) ? $item['content']:'';
            $data['createdDate'] = (array_key_exists('createdDate', $item)) ? date('Y-m-d H:i:s', $item['createdDate']/1000):'';
            $data['replyContent'] = '';
            $data['replyDate'] = '';
            $response = (array_key_exists('response', $item)) ? $item['response']:'';
            if($response != ''){
                $data['replyContent'] = (array_key_exists('content', $response)) ? $response['content']:'';
                $data['replyDate'] = (array_key_exists('createdDate', $response)) ? date('Y-m-d H:i:s', $response['createdDate']/1000):'';
            }
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }

    public function replyFeedback($id, $content){
        $data = array();
        $item = $this->getById($id);
        $item['id'] = $id;
        $item['response']['content'] = trim($content);
        $item['response']['createdDate'] = currentMillis();
        $result = $this->updateById($item);
        return $result;
    }
   
    /**
     * 根据Id取得反馈
     */
    public function getFeedback($id){
        $data = array();
        $item = $this->getById($id);
        if(!$item){return $finalRes;}
        $data['id'] = $item['_id'];
        $data['sender'] = $item['sender'];
        $data['title'] = $item['title'];
        $data['content'] = $item['content'];
        $data['createdDate'] = (array_key_exists('createdDate', $item)) ? date('Y-m-d H:i:s', $item['createdDate']/1000):'';
        $response = (array_key_exists('response', $item)) ? $item['response']:'';
        if($response != ''){
            $data['replyContent'] = (array_key_exists('content', $response)) ? $response['content']:'';
            $data['replyDate'] = (array_key_exists('createdDate', $response)) ? date('Y-m-d H:i:s', $response['createdDate']/1000):'';
        }else{
            $data['replyContent'] = '';
            $data['replyDate'] = '';
        }
        return $data;
    }

    /**
     * 删除反馈
     */
    public function deleteFeedback($id){
        $result = $this->deleteById($id);
        return $result;
    }

    /**
     * 根据发送者取得反馈
     */
    public function getFeedbacks($sender=''){
        if(trim($sender) != ''){
            $filter = [
                'sender' => trim($sender),
            ];
        }
        $result = $this->where($filter)->select()->toArray();
        return $result;
    }

}