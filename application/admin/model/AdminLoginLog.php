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

class AdminLoginLog extends Model
{
    protected $autoWriteTimestamp = true;
    public $tableName  ='admin_login_log';

     /**
     * 输出登录日志列表
     */
    public function formatLoginLogsOutput($result){
        $finalRes = array();
        foreach($result as $key => $item){
            $data = array();
            $data['admin_id'] = $item['admin_id'];          
            $data['ip'] = (array_key_exists('ip', $item)) ? $item['ip']:'';
            $data['create_time'] = (array_key_exists('create_time', $item)) ? $item['create_time']:'';
            $finalRes[$key] = $data;
        };
        return $finalRes;
    }
}