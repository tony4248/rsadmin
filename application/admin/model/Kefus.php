<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-4
 * Time: 15:04
 */

namespace app\admin\model;


use think\Model;

class Kefus extends Model
{
    public $tableName  ='kefus';
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
     * 输出客服的列表
     */
    public function formatKefusOutput($result){
        $finalRes = array();
        foreach($result as $key => $item){
            $data = array();
            $data['id'] = $item['_id'];
            $data['content'] = (array_key_exists('content', $item)) ? $item['content']:'';
            $data['active'] = (array_key_exists('active', $item)) ? $item['active']:false;
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }
    /**
     * 保存客服
     */
    public function saveKefu($content,$active){
        $data = [
            '_id' => uniqueTimestampNums(),
            'content' => trim($content),
            'active' => $active];
        $result = $this->insertById($data);
        return $result;
    }

    /**
     * 更新客服
     */
    public function updateKefu($id, $content,$active){
        $data = [
            'id' => $id,
            'content' => trim($content),
            'active' => $active == 1? true : false];
        $result = $this->updateById($data);
        return $result;
    }

    /**
     * 根据Id取得客服
     */
    public function getKefu($id){
        $data = array();
        $item = $this->getById($id);
        if(!$item){return $finalRes;}
        $data['id'] = $item['_id'];
        $data['content'] = $item['content'];
        $data['active'] = $item['active'];
        return $data;
    }

    /**
     * 删除客服
     */
    public function deleteKefu($id){
        $result = $this->deleteById($id);
        return $result;
    }

    /**
     * 取得全部客服
     */
    public function getKefus(){
        $result = $this->select()->toArray();
        return $result;
    }

}