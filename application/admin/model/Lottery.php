<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-30
 * Time: 13:23
 */

namespace app\admin\model;


use think\Model;

class Lottery extends Model
{
    protected $db = null;

    public function getPrizeAttr($value)
    {
        $prize = [0 => '六等奖', 1 => '五等奖', 2 => '四等奖', 3 => '三等奖', 4 => '二等奖', 5 => '一等奖'];
        return $prize[$value];
    }

    public function tableN($lev)
    {
        switch ($lev) {
            case 1:
                $this->db = new Lottery();
                break;
            case 2:
                $this->db = new LotteryMiddle();
                break;
            case 3:
                $this->db = new LotteryHigh();
                break;
        }
        return $this->db;
    }

}