<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-10-17
 * Time: 14:32
 */

namespace app\admin\model;


use think\Model;

class GameRoom extends Model
{
    public function update_room($id, $bot, $stint)
    {
        $result = self::where('id', $id)->update(['bot' => $bot, 'stint' => $stint]);
        return $result;
    }

    public function getGameNameAttr($value,$data)
    {
        $GameList = new GameList();
        $result = $GameList->where('id',$data['game_id'])->field('name')->find()->toArray();
        return $result['name'];
    }

    public function getGameIdAttr($value)
    {
        $data = [1=>'百人牛牛',2=>'抢庄牛牛',3=>'炸金花',4=>'德州扑克'];
        return $data[$value];
    }

}