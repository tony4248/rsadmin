<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-11
 * Time: 15:26
 */

namespace app\admin\model;


use think\Config;
use think\Model;

/**
 * Class Suggest
 * @package app\admin\model
 * 反馈类
 */
class Suggest extends Model
{
    public function user()
    {
        return $this->hasOne('user', 'id', 'user_id');
    }


    /**
     * @return \think\Paginator
     * 获取反馈列表并分页
     */
    public function getSuggestInfo()
    {
        $result = $this->with('user')->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        return $result;
    }

    /**
     * @param $id
     * @return int
     * 根据id删除反馈列
     */
    public function suggestDel($id)
    {
        $result = $this->destroy(['id' => $id]);
        return $result;
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