<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-9-30
 * Time: 13:18
 */

namespace app\admin\model;


use think\Model;

class UserYh extends Model
{
    public function getStatusAttr($value)
    {
        $status = [0=>'游客',1=>'正式'];
        return $status[$value];
    }

    public function getLockAttr($value)
    {
        $status = [0=>'未锁定',1=>'锁定'];
        return $status[$value];
    }

    public function editSaveUserInfo($data)
    {
        $id = $data['id'];
        $result = $this->where('id',$id)->update($data);
        return $result;
    }

    public function getUserPassword($id)
    {
        $data = $this->get($id);
        return $data;
    }

    public function changePasswordPro($password, $id)
    {
        $result = $this->update(['id' => $id, 'password' => $password]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}