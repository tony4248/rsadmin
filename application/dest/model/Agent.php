<?php

namespace app\dest\model;

use mptta\MPTTA;
use think\Db;
use think\Model;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-15
 * Time: 11:13
 */
class Agent extends Model
{


    /**
     *  插入数据更新左右值
     * @param $id
     * @param $nickname
     * @param $username
     * @param $password
     * @param $phone
     * @return false|int
     */
    public function insertTree($id, $nickname, $username, $password, $phone)
    {
        //TODO:问题件
        $data = ['nickname' => $nickname, 'username' => $username, 'phone' => $phone, 'create_time' => time(), 'parent_id' => $id, 'password' => md5($password)];
        $result = $this->create($data);
        $mptta = new MPTTA();
        $mptta->installNode($result->id,$id);
    }

    /**
     *  插入数据更新左右值
     * @param $id
     * @param $nickname
     * @param $username
     * @param $password
     * @param $phone
     * @return $this
     */
    public function insertTres($id, $nickname, $username, $password, $phone,$st)
    {
        $node = $this->where('id',$id)->field('lft,rgt')->find()->toArray();
        $lft = $node['rgt'];
        $rgt = $lft+1;
        if(!$st){
            $data = ['nickname' => $nickname, 'username' => $username, 'phone' => $phone, 'create_time' => time(), 'parent_id' => $id, 'password' => md5($password),'lft'=>$lft,'rgt'=>$rgt];
//            $this->execute("update `user` set rgt = rgt+2 where rgt >= {$node['rgt']}");
            db('user')->where('rgt','>=',$node['rgt'])->setInc('rgt',2);
//            $this->execute("update `user` set lft = lft+2 where lft > {$node['rgt']}");
            db('user')->where('lft','>',$node['rgt'])->setInc('lft',2);
//            $this->execute("update `agent` set rgt = rgt+2 where rgt >= {$node['rgt']}");
            db('agent')->where('rgt','>=',$node['rgt'])->setInc('rgt',2);
//            $this->execute("update `agent` set lft = lft+2 where lft > {$node['rgt']}");
            db('agent')->where('lft','>',$node['rgt'])->setInc('lft',2);
            $result = $this->create($data);
            $id = $result->id;
            $this->execute("insert into bank(id,user_id,coin) VALUES(null,{$id},0) ");
            return $result;
        }else{
            $data = ['nickname' => $nickname, 'username' => $username, 'phone' => $phone, 'create_time' => time(), 'parent_id' => $id, 'password' => md5($password),'lft'=>$lft,'rgt'=>$rgt];
//            $this->execute("update `agent` set rgt = rgt+2 where rgt >= {$node['rgt']}");
            db('agent')->where('rgt','>=',$node['rgt'])->setInc('rgt',2);
//            $this->execute("update `agent` set lft = lft+2 where lft > {$node['rgt']}");
            db('agent')->where('lft','>',$node['rgt'])->setInc('lft',2);
//            $this->execute("update `user` set rgt = rgt+2 where rgt >= {$node['rgt']}");
            db('user')->where('rgt','>=',$node['rgt'])->setInc('rgt',2);
//            $this->execute("update `user` set lft = lft+2 where lft > {$node['rgt']}");
            db('user')->where('lft','>',$node['rgt'])->setInc('lft',2);
            $result = db('user')->insert($data);
            $id = db('user')->getLastInsID();
            $this->execute("insert into bank(id,user_id,coin) VALUES(null,{$id},0) ");
            return $result;
        }
    }

    /** 根据父id获取子孙
     * @param $pid
     * @return array
     */
    public function getTress($pid)
    {
        $data = $this->where(['parent_id' => $pid])->select()->toArray();
        $UserYh = new User();
        $data2 = $UserYh->where(['parent_id' => $pid])->select()->toArray();
        $arr=array_merge($data,$data2);
        return $arr;
    }

    /** 删除代理更新左右值
     * @param $id
     * @return array|int
     */
    public function userLftRgtDelete($id)
    {
        $data = $this->where('id', $id)->field('lft,rgt')->find()->toArray();
        $middle = $data['rgt'] - $data['lft'] + 1;
        $this->where('lft', 'between', [$data['lft'], $data['rgt']])->delete();
        db('user')->where('lft', 'between', [$data['lft'], $data['rgt']])->delete();
//        $this->execute("UPDATE `user` SET rgt = rgt-{$middle} WHERE rgt > {$data['rgt']}");
        db('user')->where('rgt','>',$data['rgt'])->setDec('rgt',$middle);
//        $this->execute("UPDATE `user` SET lft = lft-{$middle} WHERE lft > {$data['rgt']}");
        db('user')->where('lft','>',$data['rgt'])->setDec('lft',$middle);
//        $this->execute("UPDATE `agent` SET rgt = rgt-{$middle} WHERE rgt > {$data['rgt']}");
        db('agent')->where('rgt','>',$data['rgt'])->setDec('rgt',$middle);
//        $this->execute("UPDATE `agent` SET lft = lft-{$middle} WHERE lft > {$data['rgt']}");
        db('agent')->where('lft','>',$data['rgt'])->setDec('lft',$middle);
        return $data;
    }

    public function getSelectNode($id)
    {
        $data = $this->where('id',$id)->field('lft, rgt')->find()->toArray();
        $lft = $data['lft'];
        $rgt = $data['rgt'];
        $res = $this->where('lft','between',[$lft, $rgt])->select()->toArray();
        return $res;
    }

    /**
     *  保存代理用户信息
     * @param $data
     * @return $this|int
     */
    public function saveDailiUserInfo($data)
    {
        $username = $this->where('username',$data['username'])->find();
        if($username){
            return 1; //账号已存在
        }
        $node = $this->where('id',$data['parent_id'])->field('lft,rgt')->find()->toArray();
        $lft = $node['rgt'];
        $rgt = $lft+1;
        $data = ['nickname' => $data['nickname'], 'username' => $data['username'], 'phone' => $data['phone'], 'create_time' => time(), 'parent_id' => $data['parent_id'], 'password' => md5($data['password']),'lft'=>$lft,'rgt'=>$rgt];
//        $this->execute("update `agent` set rgt = rgt+2 where rgt >= {$node['rgt']}");
        db('agent')->where('rgt','>=',$node['rgt'])->setInc('rgt',2);
//        $this->execute("update `agent` set lft = lft+2 where lft > {$node['rgt']}");
        db('agent')->where('lft','>',$node['rgt'])->setInc('lft',2);
//        $this->execute("update `user` set rgt = rgt+2 where rgt >= {$node['rgt']}");
        db('user')->where('rgt','>=',$node['rgt'])->setInc('rgt',2);
//        $this->execute("update `user` set lft = lft+2 where lft > {$node['rgt']}");
        db('user')->where('lft','>',$node['rgt'])->setInc('lft',2);
        $result = $this->create($data);
        return $result;
    }

    public function changeAdminPassword($id, $passwords)
    {
        $result = $this->where('id',$id)->update(['password'=>$passwords]);
        return $result;
    }

    /**
     *  用户转移
     * @param $pid
     * @param $id
     * @return int
     */
    public function userMoveNode($pid,$id)
    {
        //软删除当前节点状态设置为1
        $User = new User();
        $data = $User->where('id', $id)->field('lft,rgt')->find()->toArray();
        $middle = $data['rgt'] - $data['lft'] + 1;
//        $this->execute("update `user` set `node_status`=1,`lft`=0,`rgt`=0 WHERE id={$id}");
        db('user')->where('id',$id)->update(['node_status'=>1,'lft'=>0,'rgt'=>0]);
        //更新所有节点
//        $this->execute("UPDATE `user` SET rgt = rgt-{$middle} WHERE rgt > {$data['rgt']} AND `node_status`=0");
        db('user')->where('rgt','>',$data['rgt'])->where('node_status',0)->setDec('rgt',$middle);
//        $this->execute("UPDATE `user` SET lft = lft-{$middle} WHERE lft > {$data['rgt']} AND `node_status`=0");
        db('user')->where('lft','>',$data['rgt'])->where('node_status',0)->setDec('lft',$middle);
//        $this->execute("UPDATE `agent` SET rgt = rgt-{$middle} WHERE rgt > {$data['rgt']} AND `node_status`=0");
        db('agent')->where('rgt','>',$data['rgt'])->where('node_status',0)->setDec('rgt',$middle);
//        $this->execute("UPDATE `agent` SET lft = lft-{$middle} WHERE lft > {$data['rgt']} AND `node_status`=0");
        db('agent')->where('lft','>',$data['rgt'])->where('node_status',0)->setDec('lft',$middle);
        //当前节点设置为0再把当前节点添加到父节点并更新左右值
        $node = $this->where('id',$pid)->field('lft,rgt')->find()->toArray();
        $lft = $node['rgt'];
        $rgt = $lft+1;
//        $this->execute("update `user` set rgt = rgt+2 where rgt >= {$node['rgt']} AND `node_status`=0");
        db('user')->where('rgt','>=',$node['rgt'])->where('node_status',0)->setInc('rgt',2);
//        $this->execute("update `user` set lft = lft+2 where lft > {$node['rgt']} AND `node_status`=0");
        db('user')->where('lft','>',$node['rgt'])->where('node_status',0)->setInc('lft',2);
//        $this->execute("update `agent` set rgt = rgt+2 where rgt >= {$node['rgt']} AND `node_status`=0");
        db('agent')->where('rgt','>=',$node['rgt'])->where('node_status',0)->setInc('rgt',2);
//        $this->execute("update `agent` set lft = lft+2 where lft > {$node['rgt']} AND `node_status`=0");
        db('agent')->where('lft','>',$node['rgt'])->where('node_status',0)->setInc('lft',2);
//        $User->execute("update `user` set lft={$lft},rgt={$rgt},`node_status`=0,`parent_id`={$pid} where id={$id}");
        db('user')->where('id',$id)->update(['lft'=>$lft,'rgt'=>$rgt,'node_status'=>0,'parent_id'=>$pid]);
        return 1;
    }


}