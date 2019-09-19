<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-10-16
 * Time: 9:43
 */

namespace app\admin\model;


use think\Model;

class GamePlayLog extends Model
{
    /**
     *  获取游戏名称
     * @param $value
     * @param $data
     * @return string
     */
    public function getGameNameAttr($value, $data)
    {
        $GameList = new GameList();
        $data = $this->field('game_id')->where('id', $data['id'])->find()->toArray()['game_id'];
        $result = $GameList->where('id', $data)->field('name')->find()->toArray();
        return $result['name'] ? $result['name'] : '';

    }
}