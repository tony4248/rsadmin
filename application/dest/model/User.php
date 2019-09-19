<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-9-29
 * Time: 11:08
 */

namespace app\dest\model;


use think\Model;

class User extends Model
{

    public function getLockStatusAttr($val,$data)
    {
        if($data['lock'] == 0){
            return '正常';
        }else{
            return '禁用';
        }
    }
    /**
     *  删除用户更新左右值
     * @param $id
     * @return int
     */
    public function userLftRgtDel($id)
    {
        $res = $this->where('id',$id)->field('id,lft,rgt')->find()->toArray();
        $middle = $res['rgt'] - $res['lft'] + 1;
        $this->where('id',$id)->delete();
        $this->execute("UPDATE `user` SET rgt = rgt-{$middle} WHERE rgt > {$res['rgt']}");
        $this->execute("UPDATE `user` SET lft = lft-{$middle} WHERE lft > {$res['rgt']}");
        $this->execute("UPDATE `agent` SET rgt = rgt-{$middle} WHERE rgt > {$res['rgt']}");
        $this->execute("UPDATE `agent` SET lft = lft-{$middle} WHERE lft > {$res['rgt']}");
        return 1;
    }

    /**
     *  用户密码修改
     * @param $id
     * @param $passwords
     * @return $this
     */
    public function user_changeAdminPassword($id, $passwords)
    {
        $result = $this->where('id',$id)->update(['password'=>$passwords]);
        return $result;

    }
}