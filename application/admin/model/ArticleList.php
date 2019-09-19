<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-8
 * Time: 12:42
 */

namespace app\admin\model;


use think\Config;
use think\Db;
use think\Loader;
use think\Model;

/**
 * Class ArticleList
 * @package app\admin\model
 * 文章模型类
 */
class ArticleList extends Model
{

    /**
     *  获取分类信息
     * @param $value
     * @param $data
     * @return mixed|string
     */
    public function getCateNameAttr($value, $data)
    {
        $ArticleCate = new ArticleCate();
        $result = $ArticleCate->where('id', $data['cate_id'])->find();
        return $result->catename ?? '';

    }

    /**
     *  保存文章数据处理
     * @param int $uid
     * @param $title
     * @param $s_title
     * @param int $cate_id
     * @param $status
     * @param $filePa
     * @return $this
     */
    public function articleProcessing($uid, $title, $s_title, $cate_id, $status, $filePa)
    {

        //图片处理
        $_data = [
            'title' => $title,
            's_title' => $s_title,
            'uid' => (int)$uid,
            'create_time' => date('Y-m-d H:i:s',time()),
            'cate_id' => $cate_id,
            'img_url' => $filePa,
            'status' => $status,
            'author' => session('admin.username')
        ];
        $validate = Loader::validate('ArticleList');
        if (!$validate->check($_data)) {
            return $validate->getError();
        }
        $result = $this->create($_data);
        return $result;
    }

    /**
     * @param $id
     * @param $uid
     * @param $title
     * @param $s_title
     * @param $cate_id
     * @param $status
     * @param $filePa
     * @return $this
     */
    public function articleEditProcessing($id, $uid, $title, $s_title, $cate_id, $status, $filePa)
    {

        //图片处理
        $_data = [
            'title' => $title,
            's_title' => $s_title,
            'uid' => (int)$uid,
            'create_time' => date('Y-m-d H:i:s',time()),
            'cate_id' => $cate_id,
            'img_url' => $filePa,
            'status' => $status,
            'author' => session('user.username')
        ];
        $validate = Loader::validate('ArticleList');
        if (!$validate->check($_data)) {
            return $validate->getError();
        }

        $result = $this->where('id', $id)->update($_data);
        return $result;
    }

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     * 获取内容
     */
    public function getContent($id)
    {
        $result = $this->find($id);
        return $result;
    }


    /**
    /**
     * @param $id
     * @return int
     * 删除文章并且删除本地图片
     */
    public function deleteArticle($id)
    {
        $res = $this->where('id',$id)->field('img_url')->find();
        $domain = request()->domain();  //当前域名
        $mulu = $_SERVER["DOCUMENT_ROOT"];
        $_res = str_replace($domain,$mulu,$res->img_url);
        if($res){
            $result = $this->where('id',$id)->delete();
//            if($result){
//                if(file_exists($_res)){
//                    $reS = unlink($_res);
//                    if($reS === true){
//                        return true;
//                    }else{
//                        return false;
//                    }
//                }
//            }
            if($result){
                if(file_exists($_res)){
                    unlink($_res);
                }
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }




    }


}