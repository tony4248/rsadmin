<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-10-12
 * Time: 11:28
 */

namespace app\admin\model;


use think\Model;

class GameList extends Model
{
    /**
     *  配置更新
     * @param $id
     * @param $setting
     * @return $this
     */
    public function settingUpdate($id, $setting)
    {
        $result = $this->where('id', $id)->update(['setting' => $setting]);
        return $result;
    }
}