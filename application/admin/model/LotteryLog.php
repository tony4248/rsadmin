<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-28
 * Time: 14:57
 */

namespace app\admin\model;

use think\helper\Time;
use think\Model;

class LotteryLog extends Model
{
    /**
     *  获取用户名称
     * @param $val
     * @param $data
     * @return mixed
     */
    public function getUserNameAttr($val, $data)
    {
        if (is_null($data = User::get($data['user_id']))) {
            return '';
        }
        return $data->nickname;
    }

    /**
     *  获取转盘名称
     * @param $val
     * @param $data
     * @return mixed
     */
    public function getLotteryNameAttr($val, $data)
    {
        if (is_null($data = LotteryCate::get($data['type']))) {
            return '';
        }
        return $data->lottery_name;
    }

    /**
     *  批量删除
     * @param $ids
     * @return int
     */
    public static function checkDel($ids)
    {
        return self::destroy($ids);
    }

    /**
     *  抽奖记录
     *  获取用户详细信息
     */
    public static function get_user_sum($id)
    {
        list($start, $end) = Time::today();
        $user_exp = self::where('user_id', $id)->sum('exp');
        $today_user_exp = self::where('user_id', $id)->whereTime('create_time', 'between', [$start, $end])->sum('exp');
        $User = new User();
        $nickname = $User->where('id', $id)->value('nickname');
        $data = [
            'user_exp' => $user_exp,
            'today_user_exp' => $today_user_exp,
            'nickname' => $nickname
        ];
        return $data;
    }

}