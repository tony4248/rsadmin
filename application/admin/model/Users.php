<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-11
 * Time: 9:39
 */

namespace app\admin\model;


use think\Db;
use think\Model;
use app\admin\model\Orders as MOrders;
use app\admin\model\SystemCache as MSystemCache;

/**
 * Class Users
 * @package app\admin\model
 * 用户管理类
 */
class Users extends Model
{
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    public $tableName  ='users';
    public function getStatusAttr($value)
    {
        $status = [0 => '游客', 1 => '正式', 2 => '机器人'];
        return $status[$value];
    }

    /**
     *  h获取状态
     * @param $value
     * @return mixed
     */
    public function getLockAttr($value)
    {
        $lock = [0 => '正常', 1 => '冻结'];
        return $lock[$value];
    }

    /**
     *  统计用户下注总金额
     * @param $value
     * @param $data
     * @return float|int
     */
    public function getUserBetCoinAttr($value,$data)
    {
        $Detail_log = new DetailLog();
        $result = $Detail_log->where('user_id',$data['id'])->where('action_id',2)->sum('coin');
        return $result;
    }


    /**
     *  所有用户统计金额
     * @return array
     */
    public function allUserBalances()
    {
        $data = $this->field('sum(coin) as sumcoin')->select();
        $ress = $this->select()->toArray();
        foreach ($ress as $a) {
            $ids[] = $a['id'];
        }
        foreach ($data as $da) {
            $arr = $da['sumcoin'];
        }
        $Bank = new Bank();
        $res = $Bank->field('sum(coin) as bank_coin')->where('user_id', 'in', $ids)->select();
        $array = [];
        foreach ($res as $da) {
            $array = $da['bank_coin'];
        }
        return $array + $arr;
    }

    public function daili_save($username,$nickname,$password,$pid,$agent,$phone)
    {
       $parent_user =  Agent::get($pid);
       $lft = $parent_user->rgt;
       $rgt = $lft+1;
       $data = ['nickname' => $nickname, 'username' => $username, 'phone' => $phone, 'create_time' => time(), 'parent_id' => $pid, 'password' => md5($password),'lft'=>$lft,'rgt'=>$rgt,'agent'=>$agent];
//        $this->execute("update `user` set rgt = rgt+2 where rgt >= {$parent_user->rgt}");
        db('user')->where('rgt','>=',$parent_user->rgt)->setInc('rgt',2);
//        $this->execute("update `user` set lft = lft+2 where lft > {$parent_user->rgt}");
        db('user')->where('lft','>',$parent_user->rgt)->setInc('lft',2);
//        $this->execute("update `agent` set rgt = rgt+2 where rgt >= {$parent_user->rgt}");
        db('agent')->where('rgt','>=',$parent_user->rgt)->setInc('rgt',2);
//        $this->execute("update `agent` set lft = lft+2 where lft > {$parent_user->rgt}");
        db('agent')->where('lft','>',$parent_user->rgt)->setInc('lft',2);
        $result = db('agent')->insert($data);
        return $result;
    }

   
    /**
     *  用户添加更新左右值
     * @param $id
     * @param $nickname
     * @param $username
     * @param $password
     * @param $phone
     * @return int|string
     */
    public function userSave(int $id, $nickname, $username, $password, $phone, $status, $lock)
    {
        $agent = new Agent();
        $node = $agent->where('id', $id)->field('lft,rgt')->find()->toArray();
        $lft = $node['rgt'];
        $rgt = $lft + 1;
        $data = ['nickname' => $nickname, 'username' => $username, 'phone' => $phone, 'create_time' => time(), 'parent_id' => $id, 'password' => md5($password), 'lft' => $lft, 'rgt' => $rgt, 'status' => $status, 'lock' => $lock];
        $this->execute("update `agent` set rgt = rgt+2 where rgt >= {$node['rgt']}");
        $this->execute("update `agent` set lft = lft+2 where lft > {$node['rgt']}");
        $this->execute("update `user` set rgt = rgt+2 where rgt >= {$node['rgt']}");
        $this->execute("update `user` set lft = lft+2 where lft > {$node['rgt']}");
        $result = db('user')->insert($data);
        $id = db('user')->getLastInsID();
        $this->execute("insert into bank(id,user_id,coin) VALUES(null,{$id},0) ");
        return $result;
    }

    /**
     *  删除用户更新左右值
     * @param $id
     * @return int $status
     */
    public function userLftRgtDel($id)
    {
        $res = $this->where('id', $id)->field('id,lft,rgt')->find()->toArray();
        $middle = $res['rgt'] - $res['lft'] + 1;
        $this->where('id', $id)->delete();
        $this->execute("UPDATE `user` SET rgt = rgt-{$middle} WHERE rgt > {$res['rgt']}");
        $this->execute("UPDATE `user` SET lft = lft-{$middle} WHERE lft > {$res['rgt']}");
        $this->execute("UPDATE `agent` SET rgt = rgt-{$middle} WHERE rgt > {$res['rgt']}");
        $this->execute("UPDATE `agent` SET lft = lft-{$middle} WHERE lft > {$res['rgt']}");
        return 1;
    }


    /**
     * @return \think\Paginator
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $result = $this->paginate(10);
        return $result;
    }

    public function getUserPassword($id)
    {
        $data = $this->get($id);
        return $data;
    }

    /**
     *  根据上级用户id 添加下级
     * @param $data
     * @return int|string
     */
    public function user_subordinate($data)
    {
        $row = self::where('id',$data['parent_id'])->field('lft,rgt')->find();
        $lft = $row->rgt;
        $rgt = $lft +1;
        $data['create_time'] = now();
        $data['password'] = md5($data['password']);
        $data['lft'] = $lft;
        $data['rgt'] = $rgt;
        self::execute("update `agent` set rgt = rgt+2 where rgt >= {$row->rgt}");
        self::execute("update `agent` set lft = lft+2 where lft > {$row->rgt}");
        self::execute("update `user` set rgt = rgt+2 where rgt >= {$row->rgt}");
        self::execute("update `user` set lft = lft+2 where lft > {$row->rgt}");
        $result = self::insert($data);
        return $result;
    }

    /**
     * 修改密码
     * @param $password
     * @param $id
     * @return bool
     */
    public function changePasswordPro($password, $id)
    {
        $result = $this->update(['id' => $id, 'password' => $password]);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function formatUsersOutput($result, $isHuman){
        $finalRes = array();
        $orders = new MOrders();
        //取得在线用户
        $systemCache = new MSystemCache();
        $scData = $systemCache ->getSystemCache();
        $onlineUsers = $scData['onlineUsers'];
        if(!$isHuman){
            $onlineUsers =$scData['onlineAis'];
        } 
        foreach($result as $key => $item){
            $item['id'] = $item['_id'];
            $item['name'] = (array_key_exists('name', $item)) ? $item['name']:'';
            $item['nickName'] = (array_key_exists('nickName', $item)) ? $item['nickName']:'';
            $item['openId'] = (array_key_exists('openId', $item)) ? $item['openId']:'';
            $item['level'] = (array_key_exists('level', $item)) ? $item['level']:'';
            $item['cardNum'] = (array_key_exists('cardNum', $item)) ? $item['cardNum']:0;
            $item['score'] = (array_key_exists('score', $item)) ? $item['score']:0;
            //取得该用户的充值提现订单
            $uOrders = $orders->getUserOrders($item['_id'], $isProcessed = true);
            $item['balance'] = (sizeof($uOrders) != 0) ? $orders->getBalance($uOrders) : 0;
            $item['rechargeSum'] = (sizeof($uOrders) != 0) ? $orders->getRechargeSum($uOrders): 0;
            $item['withdrawSum'] = (sizeof($uOrders) != 0) ? $orders->getWithdrawSum($uOrders): 0;
            $item['openId'] = (array_key_exists('openId', $item)) ? $item['openId']:'';
            $item['mobile'] = (array_key_exists('mobile', $item)) ? $item['mobile']:'';
            $item['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
            $item['isOnline'] =  in_array($item['id'], $onlineUsers) ? true:false;
            $item['locked'] = (array_key_exists('locked', $item)) ? $item['locked']:false;
            $finalRes[$key] = $item;
        };
        return $finalRes;
    }

    public function formatUserOutput($item){
        $item['id'] = $item['_id'];
        $item['name'] = (array_key_exists('name', $item)) ? $item['name']:'';
        $item['nickName'] = (array_key_exists('nickName', $item)) ? $item['nickName']:'';
        $item['openId'] = (array_key_exists('openId', $item)) ? $item['openId']:'';
        $item['sex'] = (array_key_exists('sex', $item)) ? $item['sex']:'';
        $item['avatar'] = (array_key_exists('avatar', $item)) ? $item['avatar']:'';
        $item['level'] = (array_key_exists('level', $item)) ? $item['level']:'';
        $item['mobile'] = (array_key_exists('mobile', $item)) ? $item['mobile']:'';
        $item['cardNum'] = (array_key_exists('cardNum', $item)) ? $item['cardNum']:0;
        $item['score'] = (array_key_exists('score', $item)) ? $item['score']:0;
        $item['realName'] = (array_key_exists('realName', $item)) ? $item['realName']:'';
        $item['idCardNo'] = (array_key_exists('idCardNo', $item)) ? $item['idCardNo']:'';
        $item['address'] = (array_key_exists('address', $item)) ? $item['address']:'';
        $item['email'] = (array_key_exists('email', $item)) ? $item['email']:'';
        if(array_key_exists('payment', $item) && sizeof($item['payment']) != 0){
            $payment = $item['payment'];
            $item['bankName'] = (array_key_exists('bankName', $payment)) ? $payment['bankName']:'';
            $item['bankAccount'] = (array_key_exists('bankAccount', $payment)) ? $payment['bankAccount']:'';
            $item['bankCard'] = (array_key_exists('bankCard', $payment)) ? $payment['bankCard']:'';
            $item['alipayAccount'] = (array_key_exists('alipayAccount', $payment)) ? $payment['alipayAccount']:'';
            $item['alipayId'] = (array_key_exists('alipayId', $payment)) ? $payment['alipayId']:'';
            $item['wxpayAccount'] = (array_key_exists('wxpayAccount', $payment)) ? $payment['wxpayAccount']:'';
            $item['wxpayId'] = (array_key_exists('wxpayId', $payment)) ? $payment['wxpayId']:'';
        }else{
            $item['bankName'] = '';
            $item['bankAccount'] = '';
            $item['bankCard'] = '';
            $item['alipayAccount'] = '';
            $item['alipayId'] = '';
            $item['wxpayAccount'] = '';
            $item['wxpayId'] = '';
        }
        $item['createTime'] = (array_key_exists('createTime', $item)) ? date('Y-m-d H:i:s', $item['createTime']/1000):'';
        $item['locked'] = (array_key_exists('locked', $item)) ? $item['locked']:false;
        return $item;
    }

    public function getById($id){
        $result = getDataByObjId($this->tableName, $id);
        return $result;
    }

    public function updateById($data){
        $result = updateDataByObjId($this->tableName, $data);
        return $result;
    }

    public function deleteById($id){
        $result = deleteDataByObjId($this->tableName, $id);
        return $result;
    }

    public function insertById($data){
        $result = insertData($this->tableName, $data);
        return $result;
    }
    /**
     * 根据id获取用户信息
     */
    public function getUserInfoEdit($id)
    {
        $result = getDataByObjId($this->tableName, $id);
        if(sizeof($result) != 0){
            $result = $this->formatUserOutput($result);
        }
        return $result;
    }

    public function formatUserInput($data){
        $item = array();
        $item['id'] = $data['id'];
        $item['nickName'] =  $data['nickName'] ?? '';
        $item['avatar'] = $data['avatar'] ?? '';
        $item['sex'] = $data['sex'] ?? 0;
        $item['level'] = $data['level'] ?? 'BASIC';
        $item['mobile'] = $data['mobile'] ?? '';
        $item['cardNum'] = $data['cardNum'] ?? 0;
        $item['score'] = $data['score'] ?? 0;
        $item['realName'] = $data['realName'] ?? '';
        $item['idCardNo'] = $data['idCardNo'] ?? '';
        $item['address'] = $data['address'] ?? '';
        $payment = array();
        $payment['bankName'] = $data['bankName'] ?? '';
        $payment['bankAccount'] = $data['bankAccount'] ?? '';
        $payment['bankCard'] = $data['bankCard'] ?? '';
        $payment['alipayAccount'] = $data['alipayAccount'] ?? '';
        $payment['alipayId'] = $data['alipayId'] ?? '';
        $payment['wxpayAccount'] = $data['wxpayAccount'] ?? '';
        $payment['wxpayId'] = $data['wxpayId'] ?? '';
        $item['payment'] = $payment;
        $item['locked'] = $data['locked'] == 1 ? true : false;
        return $item;
    }

    /**
     * @param $data
     * @return array|string
     * 编辑更新用户信息
     */
    public function editSave($data)
    {
        $User = new Users;
        if($User->validate(true)){
            $formatedInput = $this->formatUserInput($data);
            $result = updateDataByObjId($this->tableName, $formatedInput);
            return $result;
        }else{
            // 验证失败 输出错误信息
            return $User->getError();
        }
    }

    /**
     * @param $id
     * @return null|static
     * 通过ID获取用户密码
     */
    public function getUserPasswordInfo($id)
    {
        $data = $this->get($id);
        return $data;
    }

    /**
     * @param $password
     * @param $id
     * @return bool
     * 更改用户密码
     */
    public function changePasswordPross($password, $id)
    {
        $data = ['id' => $id, 'password' => $password];
        $result = updateDataByObjId($this->tableName, $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  机器人编辑保存
     * @param $data
     * @return $this
     */
    public function robotEditSave($data)
    {
        $result = self::where('id', $data['id'])->where('status', 2)->update($data);
        return $result;
    }

    /**
     *  获取用户在线状态
     * @param $value
     * @param $data
     * @return bool|int
     */
    public function getUserLoginStatusAttr($value, $data)
    {
        $ip = request()->ip();
        $User = new Users();
        $list = $User->where('id',$data['id'])->find();
        if (empty($list)) { // 不存在
            return true;
        } else { // 存在，检测IP是否一致，否则，检测是否超过5分钟
            if ($list['active_ip'] == $ip) {
                return '离线';
            } else {
                if ($list['active_time'] < time() - 10 * 60) {
                    return '不在线';
                } else {
                    return '在线';
                }
            }
        }
    }

    /**
     *  统计收益
     * @param $value
     * @param $data
     * @return float|int
     */
    public function getIncomAttr($value,$data)
    {
        $DetailLog = new DetailLog();
        //先找出我的直推佣金
        $data_s = $DetailLog->query("SELECT sum(coin) as coin FROM `detail_log` where DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(create_time) and user_id={$data['id']} and action_id=1");
        $da = [];
        foreach ($data_s as $dat) {
            $da['coin'] = $dat['coin'];
        }
        $self_yong_jin = self::zhitui($da['coin']);  //自己的直推佣金
        $dataS = $this->select()->toArray();
        $se = self::getTree($dataS, $data['id']);
        if(empty($se)){
            return  $self_yong_jin['wan'] * $self_yong_jin['level'];
        }
        $arr = [];
        foreach ($se as $s){
            if(is_array($s)){
                foreach ($s as $a){
                    $arr[] = $a['coin'];
                }
            }
        }
        $subordinate_sum = array_sum($arr) + intval($da['coin']);  //我的直推业务+下级总的金额就是我本月总的
        $total = $this->zhitui($subordinate_sum);   //我的分成
        $self_zhi_tui = $total['level'] * $self_yong_jin['wan'];   //我自己的直推佣金
        $arr2 = [];
        foreach ($se as $t){
            if(is_array($t)){
                foreach ($t as $c){
                    $row = self::zhitui($c['coin']);
                    $arr2[] = ($total['level'] - $row['level']) * $row['wan'];
                }
            }
        }
        $self_income = array_sum($arr2)+$self_zhi_tui;
        return $self_income;

    }


    /**
     *  获取子孙
     * @param $data
     * @param $id
     * @return array
     */
    static function getTree($data,$id)
    {
        $Detail_log = new DetailLog();
        $arr = [];
        foreach ($data as $key => $val) {
            if ($val['parent_id'] == $id) {
                $arr[]           = $Detail_log->query("SELECT sum(coin) coin FROM `detail_log` where DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(create_time) and user_id={$val['id']} and action_id=1");
                self::getTree($data, $val['id']);
            }
        }
        return $arr;
    }

    /**
     *  获取收益等级在哪个段位
     * @param $subordinate_sum
     * @return array
     */
    public function zhitui($subordinate_sum)
    {
        $level = [];
        switch (true) {
            case $subordinate_sum < 100000;
                if($subordinate_sum<10000){
                    $level = [
                        'level' => 0,
                        'wan' => 0
                    ];
                }else{
                    $level = [
                        'level' => 50,
                        'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                    ];
                }
                break;
            case $subordinate_sum >= 100000 && $subordinate_sum < 300000;
                $level = [
                    'level' => 60,
                    'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 300000 && $subordinate_sum < 600000;
                $level = [
                    'level' => 70,
                    'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 600000 && $subordinate_sum < 1000000;
                $level = [
                    'level' => 80,
                    'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 1000000 && $subordinate_sum < 2000000;
                $level = [
                    'level' => 100,
                    'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 2000000 && $subordinate_sum < 4000000;
                $level = [
                    'level' => 120,
                    'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 4000000 && $subordinate_sum < 6000000;
                $level = [
                    'level' => 140,
                    'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 6000000 && $subordinate_sum < 8000000;
                $level = [
                    'level' => 160,
                    'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 8000000 && $subordinate_sum < 10000000;
                $level = [
                    'level' => 180,
                    'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 10000000;
                $level = [
                    'level' => 200,
                    'wan' => intval($subordinate_sum>=10000 ? $subordinate_sum/10000 : $subordinate_sum)
                ];
                break;
        }
        return $level;
    }

}