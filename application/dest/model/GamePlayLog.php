<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-22
 * Time: 16:30
 */

namespace app\dest\model;


use think\Model;

class GamePlayLog extends Model
{

    /**
     *  获取游戏名称
     * @param $val
     * @param $data
     * @return bool|string
     */
    public function getGameNameAttr($val,$data)
    {
        if(!$res = GameList::get($data['game_id'])){
            return '';
        }
        return $res['name'];
    }

}