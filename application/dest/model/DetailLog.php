<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-22
 * Time: 13:30
 */

namespace app\dest\model;


use think\Model;

class DetailLog extends Model
{
    /**
     *  用户昵称
     * @param $val
     * @param $data
     * @return mixed
     */
    public function getUserNameAttr($val,$data)
    {
       if(!$user_name = User::get($data['user_id'])){
           return '';
       }
        return $user_name->nickname;
    }

    /**
     *  获取操作名
     * @param $val
     * @param $data
     * @return string
     */
    public function getActionNameAttr($val,$data)
    {
       if($result = DetailAction::get($data['action_id'])){
           return $result['name'];
       }else{
           return '';
       }
    }

    /**
     *  获取游戏名称
     * @param $val
     * @param $data
     * @return string
     */
    public function getGameNameAttr($val,$data)
    {
        if(!$result = GameList::get($data['game_id'])){
            return '';
        }
        return $result['name'];
    }

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