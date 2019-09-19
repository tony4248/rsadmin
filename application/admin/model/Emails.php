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

class Emails extends Model
{
    public $tableName  ='emails';

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
     * 输出邮件的列表
     */
    public function formatEmailsOutput($result){
        $finalRes = array();
        $mUser = new MUsers();
        foreach($result as $key => $item){
            $data = array();
            $data['id'] = $item['_id'];
            if($item['sender'] == '000001'){
                $sender = '系统(000001)';
            }else{
                $senderData = $mUser->getById($item['sender']);
                if($senderData){
                    $sender = $senderData['name']."(".$item['sender'].")";
                }else{
                    $sender = "未知用户(".$item['sender'].")";
                }
                
            }
            if($item['receiver'] == '999999'){
                $receiver = '全体用户(999999)';
            }else{
                $receiverData = $mUser->getById($item['receiver']);
                if($receiverData){
                    $receiver = $receiverData['name']."(".$item['receiver'].")";
                }else{
                    $receiver = "未知用户(".$item['receiver'].")";
                }
            }
            $data['sender'] = $sender;
            $data['receiver'] = $receiver;
            $data['title'] = (array_key_exists('title', $item)) ? $item['title']:'';
            $data['content'] = (array_key_exists('content', $item)) ? $item['content']:'';
            $data['read'] = (array_key_exists('read', $item)) ? $item['read']:false;
            $data['sentDate'] = (array_key_exists('sentDate', $item)) ? date('Y-m-d H:i:s', $item['sentDate']/1000):'';
            $data['receivedDate'] = (array_key_exists('receivedDate', $item)) ? date('Y-m-d H:i:s', $item['receivedDate']/1000):'';
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }
    /**
     * 保存邮件
     */
    public function saveEmail($receiver,$sender, $title, $content){
        $data = [
            '_id' => uniqueTimestampNums(),
            'receiver' => trim($receiver),
            'sender' => trim($sender) !='' ? trim($sender): '000001',
            'title' => trim($title),
            'content' => trim($content),
            'read' => false,
            'sentDate' => currentMillis(),
            'receivedDate' => currentMillis()];
        $result = $this->insertById($data);
        return $result;
    }

    /**
     * 根据Id取得邮件
     */
    public function getEmail($id){
        $data = array();
        $item = $this->getById($id);
        if(!$item){return $finalRes;}
        $data['id'] = $item['_id'];
        $data['sender'] = $item['sender'];
        $data['receiver'] = $item['receiver'];
        $data['title'] = "Re:".$item['title'];
        return $data;
    }

    /**
     * 删除邮件
     */
    public function deleteEmail($id){
        $result = $this->deleteById($id);
        return $result;
    }

    /**
     * 根据发送者和接受者取得邮件
     */
    public function getEmails($sender='', $receiver=''){
        if(trim($sender) != '' && trim($receiver) != ''){
            $filter = [
                'sender' => trim($sender),
                'receiver' => trim($receiver),
            ];
        }elseif(trim($sender) != ''){
            $filter = [
                'sender' => trim($sender),
            ];
        }elseif(trim($receiver) != ''){
            $filter = [
                'receiver' => trim($receiver),
            ];
        }
        $result = $this->where($filter)->select()->toArray();
        return $result;
    }

}