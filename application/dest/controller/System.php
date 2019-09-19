<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-21
 * Time: 16:32
 */

namespace app\dest\controller;



class System extends Base
{
    public function set($data)
    {
        $config = json_decode($data, true);
        AgentSetting::set($config);
        return ['err' => 0];
    }
}