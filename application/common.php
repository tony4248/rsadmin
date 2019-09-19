<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Db;

const SYSTEM_ROOT_UID = '000001';


function getAvatar($idx){
    $allAvatars = array('man_01_png','man_02_png','man_03_png','man_04_png','man_05_png','man_06_png',
    'man_07_png','man_08_png','man_09_png','man_10_png','man_10_png','man_12_png',
    'woman_01_png','woman_02_png','woman_03_png','woman_04_png','woman_05_png','woman_06_png',
    'woman_07_png','woman_08_png','woman_09_png','woman_10_png','woman_11_png','woman_12_png',);
    $idx = ($idx > 23) ? 23 : $idx;
    $idx = ($idx < 0) ? 0 : $idx;
    return $allAvatars[$idx];
}


// 应用公共文件

function parseSocketData($name, $data)
{
    return json_encode([$name, $data]);
}

/**
 *  json格式转换
 * @param $json
 * @return mixed
 */
function parse_json($json)
{
    return json_decode($json,true);
}

/**
 *  清空缓存
 * @param $dir
 * @param string $folder
 * @return bool
 */
function deldir($dir,$folder='y'){
    //删除当前文件夹下得文件（并不删除文件夹）：
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath,$folder);
            }
        }
    }
    closedir($dh);
    //删除当前文件夹
    if($folder=='y'){
        if(rmdir($dir)){
            return true;
        } else {
            return false;
        }
    }
}

/**
 *  当前时间
 * @return false|string
 */
function now()
{
    return date('Y-m-d H:i:s',time());
}

/**
 *  IP验证
 * @param $ip
 * @param $config
 * @return bool
 */
function IpAuth($ip, $config){
    $ipArr = explode(".", $ip);
    for ( $i=0; $i<count($config); $i++ ){
        $ips = explode(".", $config[$i]['ip_start']);
        $ipe = explode(".", $config[$i]['ip_end']);
        for( $j=0; $j<4; $j++ ){
            if( $ips[$j]==$ipArr[$j] || $ipArr[$j]==$ipe[$j] ){
                if($j == 3){
                    return true;
                }else{
                    continue;
                }
            }else if( $ips[$j]<$ipArr[$j] && $ipArr[$j]<$ipe[$j] ){
                return true;
            }else{
                continue 2;
            }
        }
    }
    return false;
}
/***
 * 产生unique id
 */
function generateRandomInts($length = 10) {
    $characters = '0123456789';
    $randomString = '';
    $startIdx = 0;
    for ($i = 0; $i < $length; $i++) {
        //首位不为零
        if($i==0){$startIdx = 1;}
        else {$startIdx = 0; }
        $randomString .= $characters[rand($startIdx, strlen($characters) - 1)];
    }
    return $randomString;
  }

/**
 * 根据objectId来查询对象
 */
function getDataByObjId($tableName, $id){
    $filter = [
        '_id' => $id
    ];
    $query = new MongoDB\Driver\Query($filter, null);
    $res = Db::query($tableName, $query);
    return $res[0] ?? [];
}
/**
 * 根据objectIds来查询对象
 */
function getDataByObjIds($tableName, $ids){
    $filter = [
        '_id' => ['$in' => $ids]
    ];
    $query = new MongoDB\Driver\Query($filter, null);
    $res = Db::query($tableName, $query);
    return $res;
}

/**
 * 加入新对象
 */
function insertData($tableName, $data){
    $bulk = new MongoDB\Driver\BulkWrite;
    try {
        $bulk->insert($data);
        $res = Db::execute($tableName, $bulk);
        return 1;
    } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
        return 0;
    }
    return $res;
}
/**
 * 根据objectId来更新对象
 * 1:成功,0:失败
 */
function updateDataByObjId($tableName, $data){
    $filter = [
        '_id' => $data['id'],
    ];
    unset($data['id']);
    $bulk = new MongoDB\Driver\BulkWrite;
    try {
        $bulk->update($filter,['$set' => $data]);
        $res = Db::execute($tableName, $bulk);
        return 1;
    } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
        return 0;
    }
   
    
}
/**
 * 根据objectId来删除对象
 */
function deleteDataByObjId($tableName, $id){
    $filter = [
        '_id' => $id,
    ];
    $bulk = new MongoDB\Driver\BulkWrite;
    try {
        $bulk->delete($filter);
        $res = Db::execute($tableName, $bulk);
        return 1;
    } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
        return 0;
    }
}

/**
 * 根据objectIds来删除对象
 */
function deleteDataByObjIds($tableName, $ids){
    $filter = [
        '_id' => ['$in' => $ids]
    ];
    $bulk = new MongoDB\Driver\BulkWrite;
    try {
        $bulk->delete($filter);
        $res = Db::execute($tableName, $bulk);
        return 1;
    } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
        return 0;
    }
}

function getUniqueOrderId(){
    $date = new DateTime(); //this returns the current date time
    $prefix = $date->format('YmdHis');
    $suffix = rand(10, 99);
    return "I".strval($prefix).strval($suffix);
}

function uniqueTimestampNums(){
    $date = new DateTime(); //this returns the current date time
    $prefix = $date->format('YmdHis');
    $suffix = rand(10, 99);
    return strval($prefix).strval($suffix);
}

function currentMillis() {
    list($usec, $sec) = explode(" ", microtime());
    return round(((float)$usec + (float)$sec) * 1000);
}
