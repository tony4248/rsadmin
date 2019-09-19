<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-21
 * Time: 16:34
 */

namespace app\dest\controller;


use think\Controller;

class AgentSetting extends Controller
{
    static public $db;

    /**
     * 获取setting对象
     * @return Setting
     */
    static public function db()
    {
        if (empty(static::$db)) static::$db = new \app\dest\model\AgentSetting();
        return static::$db;
    }

    /**
     * 设置全局配置参数 [可传入一个数组进行批量配置]
     * @param  array|string  $name [数组配置|配置名]
     * @param  string $value [配置值]
     * @param  string $title [标题]
     * @return set
     */
    static public function set($name, $value = '', $title = '')
    {
        if (!$name) return;
        $set = [];
        //批量配置
        if (gettype($name) == 'array') {
            $set = $name;
            $return = $name;
        }
        //单个配置 允许标题
        if (!empty($value)) {
            $set[$name] = $value;
            $return = $value;
        }
        foreach ($set as $k => $v) {
            static::db()->setValue($k, $v, $title);
        }
        return $return;
    }

    /**
     * 获取全局配置参数 [可不传参数直接获取所有配置项]
     * @param  string  $name [配置名]
     * @return get
     */
    static public function get($name = '')
    {
        return static::db()->getValue($name);
    }

    /**
     * 根据type获取全局配置参数
     * @param  int  $type
     * @return getByType
     */
    static public function getByType($type = 0)
    {

    }

    /**
     * 判断配置知否存在
     * @param  string  $name [配置名]
     * @return has
     */
    static public function has($name)
    {
        return static::db()->hasValue($name);
    }

    /**
     * 移除
     * @param  string  $name [配置名]
     * @return remove
     */
    static public function remove($name)
    {
        return static::db()->removeValue($name);
    }
}