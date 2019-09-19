<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-11-7
 * Time: 9:22
 */

namespace app\admin\model;


use think\Model;

class TopInfo extends Model
{
    /**
     *  获取类型
     */
    public function getTypeAttr($value)
    {
        $type = [0=>'系统消息',1=>'动态推送'];
        return $type[$value];
    }

    /**
     *  获取类型信息到add页面
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getTypeInfo()
    {
        $data = $this->select();
        return $data;
    }

}