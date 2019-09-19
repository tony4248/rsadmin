<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-10
 * Time: 10:50
 */

namespace app\admin\model;

use think\Model;

/**
 * Class ArticleCate
 * @package app\admin\model
 * 文章分类模型类
 */
class ArticleCate extends Model
{

    /**
     * @return false|\PDOStatement|string|\think\Collection
     * 获取分类列表
     */
    public function getArticleCate()
    {
        $data = $this->select();
        return $data;
    }

    /**
     * @param $data
     * @return $this
     * 保存分类
     */
    public function saveCate($catename)
    {
        $result = $this->insert(['catename' => $catename]);
        return $result;
    }

    /**
     * @param $id
     * @return null|static
     * 根据id获取分类信息
     */
    public function getCateInfo(int $id)
    {
        $result = $this->get($id);
        return $result;
    }

    /**
     * @param $id
     * @param $catename
     * @return bool
     * 分类保存
     */
    public function ArticleCateSave(int $id, $catename)
    {
        if ($catename) {
            $result = $this->where('id', $id)->update(['id' => $id, 'catename' => $catename]);
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return int
     * 删除分类
     */
    public function CateDel(int $id)
    {
        $result = $this->destroy($id);
        return $result;
    }
}