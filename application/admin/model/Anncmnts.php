<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-4
 * Time: 15:04
 */

namespace app\admin\model;


use think\Model;

class Anncmnts extends Model
{
    public $tableName  ='anncmnts';
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
     * 输出公告的列表
     */
    public function formatAnncmntsOutput($result){
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
     * 保存公告
     */
    public function saveAnncmnt($content,$active){
        $data = [
            '_id' => uniqueTimestampNums(),
            'content' => trim($content),
            'active' => $active];
        $result = $this->insertById($data);
        return $result;
    }

    /**
     * 更新公告
     */
    public function updateAnncmnt($id, $content,$active){
        $data = [
            'id' => $id,
            'content' => trim($content),
            'active' => $active == 1? true : false];
        $result = $this->updateById($data);
        return $result;
    }

    /**
     * 根据Id取得公告
     */
    public function getAnncmnt($id){
        $data = array();
        $item = $this->getById($id);
        if(!$item){return $finalRes;}
        $data['id'] = $item['_id'];
        $data['content'] = $item['content'];
        $data['active'] = $item['active'];
        return $data;
    }

    /**
     * 删除公告
     */
    public function deleteAnncmnt($id){
        $result = $this->deleteById($id);
        return $result;
    }

    /**
     * 取得全部公告
     */
    public function getAnncmnts(){
        $result = $this->select()->toArray();
        return $result;
    }

}