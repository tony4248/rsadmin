<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-18
 * Time: 16:44
 */

namespace app\admin\controller;


use app\core\model\Setting;

class SystemSet extends Base
{
    public function index()
    {
        $setting = Setting::get(['game_name', 'coin_name', 'pagesize']);
        $this->assign('setting', $setting);
        return $this->fetch();
    }

    public function set($data)
    {
        $config = json_decode($data, true);
        $setting = Setting::set($config);
        return ['err' => 0];
    }
}