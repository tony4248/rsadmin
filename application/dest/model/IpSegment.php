<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-20
 * Time: 14:17
 */

namespace app\dest\model;


use think\Model;

class IpSegment extends Model
{
    /**
     * @param $ids
     * @return int
     * 根据id批量删除IP段
     */
    public function segmentCheckDel($ids)
    {
        $result = $this->destroy($ids);
        return $result;
    }
}