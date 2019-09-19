<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-30
 * Time: 14:02
 */

namespace app\admin\model;


use think\Model;

class LotteryHigh extends Model
{
    public function getPrizeAttr($value)
    {
        $prize = [0 => '六等奖', 1 => '五等奖', 2 => '四等奖', 3 => '三等奖', 4 => '二等奖', 5 => '一等奖'];
        return $prize[$value];
    }
}