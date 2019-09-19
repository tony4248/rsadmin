<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-19
 * Time: 18:08
 */

namespace app\dest\model;


use think\Model;

class IpWhitelist extends Model
{
    /**
     * @param $ids
     * @return int
     * 根据id批量删除
     */
    public function checkDel($ids)
    {
        $result = $this->destroy($ids);
        return $result;
    }
}