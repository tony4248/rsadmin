<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-12
 * Time: 15:10
 */

namespace app\admin\model;


use think\Model;

/**
 * Class DetailLog
 * @package app\admin\model
 * 资金明细
 */
class DetailLog extends Model
{
    public function getDetailLog()
    {
        $data = $this->alias('l')
            ->join('user u', 'l.user_id = u.id')
            ->join('detail_action a', 'l.action_id = a.id')
            ->join('game_list g', 'l.game_id = g.id')
            ->field(
                'l.id,
                l.coin as coin,
                l.before_coin as before_coin,
                l.after_coin as after_coin,
                l.info as info,
                l.create_time as create_time,
                l.create_ip as create_ip,
                u.username as username,
                a.name action,
                g.name as game'
            )
            ->order('create_time desc')
            ->paginate(10);
        return $data;
    }

    /**
     * 获取用户名
     * @param $value
     * @param $data
     * @return mixed|string
     */
    public function getUserNickNameAttr($value, $data)
    {
        $User = new User();
        $data = $this->where('id', $data['id'])->find()->toArray()['user_id'];
        $result = $User->where('id', $data)->find();
        return $result['nickname'] ? $result['nickname'] : '';
    }

    /**
     *  获取游戏名称
     * @param $value
     * @param $data
     * @return mixed|string
     */
    public function getGameNameAttr($value, $data)
    {
        $GameList = new GameList();
        $data = $this->where('id', $data['id'])->find()->toArray()['game_id'];
        $result = $GameList->where('id', $data)->find();
        return $result['name'] ? $result['name'] : '';
    }

    /**
     *  获取操作名称
     * @param $value
     * @param $data
     * @return mixed|string
     */
    public function getActionNameAttr($value, $data)
    {
        $DetailAction = new DetailAction();
        $data = $this->where('id', $data['id'])->find()->toArray()['action_id'];
        $result = $DetailAction->where('id', $data)->find();
        return $result['name'] ? $result['name'] : '';

    }
}