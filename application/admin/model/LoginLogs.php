<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-11
 * Time: 14:59
 */

namespace app\admin\model;


use think\Config;
use think\Model;

class LoginLogs extends Model
{
    protected $autoWriteTimestamp = true;
    public $tableName  ='loginlogs';

     /**
     * 输出登录日志列表
     */
    public function formatLoginLogsOutput($result){
        $finalRes = array();
        foreach($result as $key => $item){
            $data = array();
            $data['uid'] = $item['uid'];          
            $data['ip'] = (array_key_exists('ip', $item)) ? $item['ip']:'';
            $data['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }
}