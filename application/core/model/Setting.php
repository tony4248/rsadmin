<?php
namespace app\core\model;

use think\Model;

class Setting extends Model
{
    
    private static $config = [];
    protected $updateTime = false;
    protected $createTime = false;
    
    /**
     * 设置全局配置参数 [可传入一个数组进行批量配置]
     * @param  array|string  $name [数组配置|配置名]
     * @param  string $value [配置值]
     * @param  string $title [显示标题]
     * @return set
     */
    public function setValue($name, $value, $title = '', $type = 0)
    {
        $data = [
            'name' => $name,
            'value' => $value
        ];

        if (!empty($title)) {
            $data['title'] = $title;
        }

        if (!empty($type)) {
            $data['type'] = $type;
        }

        if (!$this->hasValue($name)) {
            return $this->save($data);
        }

        self::$config[$name] = $value;        
        return $this->where(['name' => $name])->update($data);
    }

    /**
     * 获取配置
     * @param  string|array  $name [配置名]
     * @return getValue
     */
    public function getValue($name = '')
    {
        if (empty($name)) {
            $data = $this->column('value','name');
            return $data;
        }


        if (gettype($name) == 'array') {
            $data = $this->where(function($query) use($name) {
                foreach ($name as $item) {
                    $query->whereOr('name', $item);
                }
            })->column('value','name');
            return $data;
        }

        $this->setConfig($name);
        return self::$config[$name];
    }

    /**
     * 移除
     * @param  string  $name [配置名]
     * @return removeValue
     */
    public function removeValue($name)
    {
        $data = $this->destroy(['name' => $name]);
        return $data;
    }

    /**
     * 检查配置项是否存在
     * @param  string  $name [配置名]
     * @return hasValue
     */
    public function hasValue($name)
    {
        return $this->setConfig($name);
    }

    /**
     * 缓存配置信息
     * @param  string  $name [配置名]
     * @return setConfig
     */
    private function setConfig($name)
    {
        if (!isset(self::$config[$name])) {            
            $data = $this->get(['name' => $name]);
            if (!$data) {
                return;
            }
            self::$config[$name] = $data->value;
        }
        return true;
    }

    public function getByType($type = 0)
    {
        return $this->where(['type' => $type])->select();
    }
}
