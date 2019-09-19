<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-19
 * Time: 14:40
 */

namespace app\admin\controller;

use app\core\controller\Setting;

class System extends Base
{
    public function set($data)
    {
        $config = json_decode($data, true);
        Setting::set($config);
        return ['err' => 0];
    }
}