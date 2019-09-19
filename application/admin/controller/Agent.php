<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-10-9
 * Time: 10:51
 */

namespace app\admin\controller;

use app\admin\model\DetailLog;
use mptta\MPTTA;
use app\admin\model\Agents as MAgents;
use app\admin\model\Users as MUsers;
use think\Config;
use think\helper\Time;
use think\Loader;
use think\Db;

class Agent extends Base
{
    public $coin;
    public $id;

    public function index()
    {
        $Agent = new MAgents();
        $propList = $Agent->where('parent_id', 0)
            ->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $page = $propList->render();
        $this->assign('data', $propList);
        $this->assign('page', $page);
        return $this->fetch();

    }

    /**
     * 修改银行卡信息
     * @param int $id
     * @param int $aid
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit_bank_card(int $id = 0, int $aid = 0)
    {
        if ($aid == 1) {
            $User = new MUsers();
            $result = $User->where('id', $id)->field('id,bank_card')->find()->toArray();
            $this->assign('data', $result);
            $this->assign('aid', $aid);
        } elseif ($aid == 0) {
            $Agent = new MAgents();
            $result = $Agent->where('id', $id)->field('id,bank_card')->find()->toArray();
            $this->assign('data', $result);
            $this->assign('aid', $aid);
        }
        return $this->fetch();
    }

    /**
     *  编辑银行卡并且保存
     * @param int $id
     * @param int $aid
     * @param string $bank_card
     */
    public function edit_bank_card_save($id = 0, $aid = 0, $bank_card = '')
    {
        if ($aid == 0) {
            $Agent = new MAgents();
            $result = $Agent->save(['bank_card' => $bank_card], ['id' => $id]);
            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }
        } elseif ($aid == 1) {
            $User = new MUsers();
            $result = $User->save(['bank_card' => $bank_card], ['id' => $id]);
            if ($result) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }
        }
    }


    /**
     * 代理添加
     * @param string $pid
     * @param string $uid
     * @param string $state
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add($pid = '', $uid = '', $state = "APPROVED")
    {
        if (request()->isAjax()) {
            $data = ['pid' => $pid, 'uid' => $uid, 'state' => $state, 'createTime' => currentMillis()];
            $validate = Loader::validate('Agent');
            if (!$validate->scene('add')->check($data)) {
                return ['code' => 0, 'msg' => $validate->getError()];
            }
            $Agent = new MAgents();
            //检查上级代理Id
            if($data['pid'] != SYSTEM_ROOT_UID){
                $pid = $Agent->where('uid',$data['pid'])->field('uid')->find();
                if (!$pid) {
                    return ['code' => 0, 'msg' =>'上级代理Id无效,请重新输入'];
                }
            }
            //检查用户Id的有效性
            $user = new MUsers();
            $userData = $user->getById($data['uid']);
            if(!$userData){
                return ['code' => 0, 'msg' =>'用户Id无效,请重新输入'];
            }
            //检查用户是否已经是代理
            $aid = $Agent->where('uid',$data['uid'])->field('uid')->find();
            if ($aid) {
                return ['code' => 0, 'msg' =>'用户已经是代理,无需加入'];
            }
            //保存代理数据
            $result = $Agent->saveAgent($data);
            //更新代理层级数据
            $Agent->saveAgentRels($data['pid'],$data['uid']);
            if ($result) {
                return ['code' => 1, 'msg' => '代理添加成功'];
            } else {
                return ['code' => 0, 'msg' => '代理添加失败'];
            }
        }
        return $this->fetch();
    }

    /**
     *  代理更新页面
     * @param int $id
     * @param int $aid
     * @return mixed
     */
    public function edit(int $id = 0)
    {
        $Agent = new MAgents();
        $result = $Agent->getAgentInfoEdit($id);
        $this->assign('data', $result);
        return $this->fetch();
    }

    /**
     *  用户代理信息保存
     * @param int $id
     * @param int $aid
     * @param string $nickname
     * @param string $username
     * @param string $phone
     * @param string $status
     * @param int $lock
     * @return array
     */
    public function editSaves($pid = '', $uid = '', $state = '')
    {

        if (request()->isAjax()) {
            $Agent = new MAgents();
            $result = $Agent->editSave($pid, $uid, $state);
            if (!$result) {
                return ['code' => 0, 'msg' => '用户修改失败'];
            } else {
                return ['code' => 1, 'msg' => '用户修改成功'];
            }
        }

    }

    /**
     *  显示下级代理
     * @return mixed
     */
    public function getSubAgents($id, int $level)
    {
        $Agent = new MAgents();
        $agentRels = Db::name('agentrels')->where('pid', $id)->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $result = $Agent->getAgents($agentRels);
        $finalRes = $Agent->formatAgentsOutput($result);
        $page = $agentRels->render();
        $this->assign('data', $finalRes);
        $this->assign('page', $page);
        return $this->fetch('agent/sub_agents');
    }

    public function getSubMembers($id, int $level)
    {
        $Agent = new MAgents();
        if($level == 1){
            $finalRes = $Agent->getDirectMembers($id);
        }else{
            $finalRes = $Agent->getAllMembers($id);
        }
        $this->assign('data', $finalRes);
        $this->assign('page', '');
        return $this->fetch('agent/sub_members');
    }

    /**
     *  充值页面渲染a
     * @param int $id
     * @param int $aid
     * @return mixed
     */
    public function recharge(int $id = 0, int $aid = 0)
    {
        $this->assign('id', $id);
        $this->assign('aid', $aid);
        return $this->fetch();
    }

    /**
     * 充值
     * @param int $id
     * @param int $coin
     * @param int $aid
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function rechargeSave(int $id = 0, int $coin = 0, int $aid = 0)
    {
        $User = new MUsers();
        $Agent = new MAgents();
        if ($aid == 1) {
            $oldCoin = $User->where('id', $id)->find()->toArray()['coin'];
            $res = $User->where('id', $id)->setInc('coin', $coin);
            if ($res) {
                $resu = $this->coinChange(['user_id' => $id, 'coin' => $coin, 'action_id' => 1, 'before_coin' => $oldCoin]);
                if ($resu <= 0) {
                    return ['code' => 0, '日志记录失败'];
                }
                return ['code' => 1, 'msg' => '充值成功'];
            } else {
                return ['code' => 0, 'msg' => '充值失败'];
            }
        }
        $agent = $Agent->where('id',$id)->find();
        $detail_data = [
            'user_id' => $id,
            'action_id' => 1,
            'coin' => $coin,
            'before_coin' => $agent->coin,
            'after_coin' => $agent->coin + $coin,
            'create_time' => date('Y-m-d H:i:s', time()),
            'info' => '充值',
            'create_ip' => request()->ip()
        ];
        db('detail_log')->insert($detail_data);
        $res = $Agent->where('id', $id)->setInc('coin', $coin);
        if ($res) {
            return ['code' => 1, 'msg' => '余额充值成功'];
        } else {
            return ['code' => 0, 'msg' => '余额充值失败'];
        }
    }

    /**
     * 修改密码
     * @param string $id
     * @param string $aid
     * @return mixed
     * @throws \think\Exception
     */
    public function changePassword($id = '', $aid = '')
    {
        $Agent = new MAgents();
        if ($aid == 0) {
            $result = $Agent->getAgentPasswordInfo($id)->toArray();
            $this->assign('data', $result);
            $this->assign('aid', $aid);
            return $this->fetch();
        }
        if ($aid == 1) {
            $result = $Agent->getUserPasswordInfo($id)->toArray();
            $this->assign('data', $result);
            $this->assign('aid', $aid);
            return $this->fetch();
        }
    }

    /**
     * 修改密码保存
     * @param string $id
     * @param string $aid
     */
    public function changePasswordSave($id = '', $aid = '')
    {
        $Agent = new MAgents();
        $newPassword = md5(input('post.newpassword'));
        $password = md5(input('post.password'));
        if ($newPassword !== $password) {
            $this->error('对不起两次密码不相等,请重新输入');
        } else {
            $result = $Agent->changePasswordPross($password, $id, $aid);
            if ($result) {
                $this->success('密码修改成功');
            } else {
                $this->error('密码修改失败');
            }
        }
    }

    /**
     * 逻辑删除
     * @param string $id
     * @param string $aid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function del($id = '', $aid = '')
    {
        $Agent = new MAgents();
        if ($aid == 0) {
            if ($id) {
                $result = $Agent->userLftRgtDelete($id);
                if (!$result) {
                    return ['code' => 0, 'msg' => '删除失败'];
                } else {
                    return ['code' => 1, 'msg' => '删除成功'];
                }
            } else {
                return ['code' => 0, 'msg' => '数据异常请检查'];
            }
        }
        if ($aid == 1) {
            $User = new MUsers();
            $data = $User->where('id', $id)->field('lft,rgt')->find();
            $row = $User->where('lft', 'between', [$data->lft, $data->rgt])->count();
            if ($row > 1) {
                return ['code' => 0, 'msg' => '存在下级用户,请先删除下级用户'];
            }
            $result = $User->userLftRgtDel($id);
            if ($result == 1) {
                return ['code' => 1, 'msg' => '删除成功'];
            } else {
                return ['code' => 0, 'msg' => '删除失败'];
            }
        }
    }

    /**
     *  转移页面
     * @param int $id
     * @param int $aid
     * @return mixed
     */
    public function transfer($id = 0, $aid = 0)
    {
        $this->assign('data', $id);
        $this->assign('aid', $aid);
        return $this->fetch();
    }

    /**
     * 代理转移
     * @param int $id
     * @param string $name
     * @param int $aid
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function moveNode(int $id = 0, $name = '', int $aid = 0)
    {
        $Agent = new MAgents();
        $mpTta = new MPTTA();
        $data = $Agent->where('id|nickname|username', 'like', $name)->field('id, lft, rgt')->find();
        if (!$data) {
            $User = new MUsers();
            $data = $User->where('id|nickname|username', 'like', $name)->field('id,lft,rgt')->find();
            if (!$data) {
                $this->error('用户或者代理不存在');
            }
        }
        $row = $data->toArray();
        $pid = $row['id'];
        if ($aid == 1) {
            $User = new MUsers();
            $pidUser = $User->where('id', $pid)->find();
            if ($pidUser && $aid == 1) {
                $this->error('上级不能是用户');
            }
            $result = $Agent->userMoveNode($pid, $id);
            if ($result == 1) {
                $this->success('转移成功');
            } else {
                $this->error('转移失败');
            }
        } else {
            $Agent->where('lft', 'between', [$row['lft'], $row['rgt']])->select()->toArray();
            $result = $mpTta->moveNode($pid, $id);
            if ($result == 1) {
                $this->success('转移成功');
            } else {
                $this->error('转移失败');
            }
        }
    }

    /**
     * 业绩记录
     * @param string $userId
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function report_records($userId = '')
    {
        $agent = new MAgents();
        $finalRes = $agent->getReportRecords($userId);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

    /**
     * 提现记录
     * @param string $userId
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function withdraw_records($userId = '')
    {
        $agent = new MAgents();
        $finalRes = $agent->getWithdrawRecords($userId);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

    public function validateSaveWithdraw($id = '', $amount = '', $paymentType = '', $paymentId = ''){
        if (!is_numeric($amount) || $amount <=0 ) {
            return ['code' => 0, 'msg' => '金额不能为负数,零'];
        }
        if (!trim($paymentType) || !trim($paymentId) ) {
            return ['code' => 0, 'msg' => '支付方式和支付账户ID不能为空'];
        }
        $agent = new MAgents();
        //检查用户ID和金额
        $existAgent = $agent->where('uid',$id)->find();
        if (!$existAgent) {
            return ['code' => 0, 'msg' =>'用户Id无效,请重新输入'];
        }
        //检查金额
        $existAgent = $existAgent->toArray();
        $report = (array_key_exists('report', $existAgent)) ? $existAgent['report']:'';
        if($report != ''){
            $availBalance = (array_key_exists('availBalance', $report)) ? $report['availBalance']:0;
            if($availBalance == 0){
                return ['code' => 0, 'msg' =>'该用户尚无可提余额'];
            }elseif($availBalance < $amount){
                return ['code' => 0, 'msg' =>'输入的金额大于可用余额'];
            }
        }else{
            return ['code' => 0, 'msg' =>'该用户尚无可提余额'];
        }
        return null;
    }

    /**
     * 编辑提佣记录
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function edit_withdraw($uid = '', $oid = '', $amount = '', $paymentType = '', $paymentId = '', $state = '')
    {
        if (request()->isAjax()) {
            $data = ['id' => $oid, 
                    'amount' => $amount, 
                    'paymentType' => $paymentType, 
                    'paymentId' => $paymentId, 
                    'state' => $state];
            $validateRes = $this->validateSaveWithdraw($uid, $amount, $paymentType, $paymentId);
            if($validateRes){return $validateRes;}
            $agent = new MAgents();
            //更新代理层级数据
            $result = $agent->updateWithdraw($uid,$data);
            if ($result) {
                return ['code' => 1, 'msg' => '提佣记录修改成功'];
            } else {
                return ['code' => 0, 'msg' => '提佣记录修改失败,请确保余额充足'];
            }
        }
        $agent = new MAgents();
        //更新代理层级数据
        $finalRes = $agent->getWithdraw($uid, $oid);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

    /**
     * 增加提佣记录
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function add_withdraw($uid = '', $amount = '', $paymentType = '', $paymentId = '', $state = '')
    {
        if (request()->isAjax()) {
            $data = ['id' => getUniqueOrderId(), 
                    'amount' => $amount, 
                    'paymentType' => $paymentType, 
                    'paymentId' => $paymentId, 
                    'state' => $state, 
                    'createTime' => time()];
            $validateRes = $this->validateSaveWithdraw($uid, $amount, $paymentType, $paymentId);
            if($validateRes){return $validateRes;}
            $agent = new MAgents();
            //更新代理层级数据
            $result = $agent->saveWithdraw($uid,$data);
            if ($result) {
                return ['code' => 1, 'msg' => '提佣记录添加成功'];
            } else {
                return ['code' => 0, 'msg' => '提佣记录添加失败'];
            }
        }
        return $this->fetch();
    }

    /**
     * 删除提佣记录
     */
    public function del_withdraw($uid = '', $oid = '')
    {
        $agent = new MAgents();
        $result = $agent ->deleteWithdraw($uid, $oid);
        if ($result == 1) {
            return ['code' => 1, 'msg' => '删除成功'];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }

    /**
     * 提佣管理
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function withdraw()
    {
        $agent = new MAgents();
        $finalRes = $agent->getWithdrawRequest();
        $this->assign('page', null);
        $this->assign('data', $finalRes);
        return $this->fetch();
    }

    /**
     * 代理统计列表
     * @param string $userId
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function reports($userId = '')
    {
        $agent = new MAgents();
        if ($userId) {
            $result = Db::name($agent->tableName)->where('uid','eq', $userId)->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        }else{
            $result = Db::name($agent->tableName)->where('uid','neq', '')->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        }
        if ($userId != '') {
            $this->assign('quick', '<input type="button" name="Submit" class="btn btn-default radius" onclick="javascript:history.back(-1);" value="返回">');
        }
        $finalRes = $agent->formatAgentsReportOutput($result);
        $perfSum = $agent->getAgentsPerfSum($finalRes);
        $page = $result->render();
        $this->assign('page', $page);
        $this->assign('data', $finalRes);
        $this->assign('perfSum', $perfSum);
        return $this->fetch();
    }


    /**
     * 用户统计列表
     * @param string $id
     * @param string $nickname 昵称
     * @param string $username 账号
     * @param string $start 开始时间
     * @param string $end 结束时间
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function user_statistics($id = '', $nickname = '', $username = '', $start = '', $end = '')
    {
        $User = new MUsers();
        if ($nickname) {
            $User->where('nickname', trim($nickname));
        }
        if ($username) {
            $User->where('username', trim($username));
        }
        if ($start && !$end) {
            $startTime = strtotime($start);
            $User->where('create_time', 'egt', $startTime);
        }
        if (!$start && $end) {
            $endTime = strtotime($end);
            $User->where('end', 'elt', $endTime);
        }
        if ($start && $end) {
            $startTime = strtotime($start);
            $endTime = strtotime($end);
            $User->where('create_time', 'between', [$startTime, $endTime]);
        }
        if ($nickname != '' || $username != '' || $start != '' || $end != '') {
            $this->assign('quick', '<input type="button" name="Submit" class="btn btn-default radius" onclick="javascript:history.back(-1);" value="返回">');
        }
        $sum_coin = $User->field('sum(coin) as sum_coin')->where('parent_id', $id)->select()->toArray();
        $result = $User->where('parent_id', $id)->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $page = $result->render();
        $this->assign('sum_coin', $sum_coin[0]['sum_coin']);
        $this->assign('page', $page);
        $this->assign('data', $result);
        return $this->fetch();
    }

    /**
     * 用户搜索
     * @throws \think\exception\DbException
     */
    public function search($userId){
        $agent = new MAgents();
        $result =  $agent->where('uid','like', $userId)->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $finalRes = $agent->formatAgentsOutput($result);
        if ($userId != '') {
            $this->assign('quick', '<input type="button" name="Submit" class="btn btn-default radius" onclick="javascript:history.back(-1);" value="返回">');
        }
        if (sizeof($finalRes) == 0) {
            $this->error('你搜索的数据不存在');
        }
        $page = $result->render();
        $this->assign('page', $page);
        $this->assign('data', $finalRes);
        return $this->fetch('user/index');
    }

    /**
     * 用户搜索
     * @param string $nickname
     * @param string $username
     * @param string $start
     * @param string $end
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function user_search($username = '', $start = '', $end = '')
    {
        $User = new MUsers();
        if ($username) {
            $User->where('username', trim($username));
        }
        if ($start && !$end) {
            $startTime = strtotime($start);
            $User->where('create_time', 'egt', $startTime);
        }
        if (!$start && $end) {
            $endTime = strtotime($end);
            $User->where('end', 'elt', $endTime);
        }
        if ($start && $end) {
            $startTime = strtotime($start);
            $endTime = strtotime($end);
            $User->where('create_time', 'between', [$startTime, $endTime]);
        }
        if ($nickname != '' || $username != '' || $start != '' || $end != '') {
            $this->assign('quick', '<input type="button" name="Submit" class="btn btn-default radius" onclick="javascript:history.back(-1);" value="返回">');
        }
        $result = $User->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        if (!$result->items()) {
            $this->error('你搜索的数据不存在哦。。');
        }
        $page = $result->render();
        $this->assign('page', $page);
        $this->assign('data', $result);
        return $this->fetch('user_statistics');
    }

    /**
     * 查看下一级
     * @param $id
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function look_lower($id)
    {
        $Agent = new MAgents();
        $res = $Agent->where('parent_id', $id)->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $page = $res->render();
        $this->assign('page', $page);
        $this->assign('quick', "<a class='btn btn-default radius' href='/admin.php/admin/agent/agentstatistics'>返回</a>");
        $this->assign('data', $res);
        return $this->fetch('agent_statistics');
    }

    /**
     *  金币充值添加日志记录
     * @param $data
     * @return int|string
     */
    public function coinChange($data)
    {
        $DetailLog = new DetailLog();
        $result = [
            'user_id' => $data['user_id'],
            'action_id' => 1,
            'coin' => $data['coin'],
            'before_coin' => $data['before_coin'],
            'after_coin' => $data['coin'] + $data['before_coin'],
            'create_time' => date('Y-m-d H:i:s', time()),
            'info' => '充值',
            'create_ip' => request()->ip()
        ];
        $res = $DetailLog->insert($result);
        return $res;
    }

    /**
     * 获取上周下注收益
     */
    public function getLastWeekCoin()
    {
        $log = new DetailLog();
        //上周
        $datas = $log->query("select b.user_id,sum(coin) coin from (select * from detail_log where action_id=2 and YEARWEEK(date_format(create_time,'%Y-%m-%d %H:%i:%s')) = YEARWEEK(now())-1) as b GROUP BY b.user_id
");
        //昨天
//        $datas = $log->query("select b.user_id,sum(coin) coin from (SELECT * FROM `detail_log` WHERE TO_DAYS( NOW( ) ) - TO_DAYS( create_time) <= 1) as b GROUP BY b.user_id
//");
        foreach ($datas as $data) {
            $this->getAgentIncome($data['user_id']);
        }
    }


    /**
     * 计算收益
     * @param string $data_id
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAgentIncome($data_id = '')
    {

        $User = new MUsers();
        $DetailLog = new DetailLog();
        list($start, $end) = Time::lastWeek();
        //先找出我的直推佣金
        $direct_commission = $DetailLog->where('create_time', 'between', [date('Y-m-d H:i:s', $start), date('Y-m-d H:i:s', $end)])->where('user_id', $data_id)->where('action_id', 2)->sum('coin');
        $Agent = new MAgents();
        if ($lr = $Agent->where('id', $data_id)->find()) {
            $result = $Agent->where('lft', 'between', [$lr->lft, $lr->rgt])->field('id')->select()->toArray();
            unset($result[0]);
            $array_coin = [];
            $array_coin2 = [];
            foreach ($result as $res) {
                $array_coin[] = $DetailLog->where('create_time', 'between', [date('Y-m-d H:i:s', $start), date('Y-m-d H:i:s', $end)])->where('user_id', $res['id'])->where('action_id', 2)->field('SUM(coin) as coin,user_id')->select()->toArray();
            }
            foreach ($array_coin as $a_coin) {
                if (is_array($a_coin)) {
                    foreach ($a_coin as $a) {
                        $array_coin2[] = $a['coin'];
                    }
                }
            }
            $agent_sum_coin = array_sum($array_coin2); //代理下级 上周下注总数

            $result_user = $User->where('lft', 'between', [$lr->lft, $lr->rgt])->field('id')->select()->toArray();
            $array_coin_user = [];
            $array_coin_user2 = [];
            foreach ($result_user as $res_user) {
                $array_coin_user[] = $DetailLog->where('create_time', 'between', [date('Y-m-d H:i:s', $start), date('Y-m-d H:i:s', $end)])->where('user_id', $res_user['id'])->where('action_id', 2)->field('SUM(coin) as coin,user_id')->select()->toArray();
            }
            foreach ($array_coin_user as $user_coin) {
                if (is_array($array_coin)) {
                    foreach ($user_coin as $user_coin_s) {
                        $array_coin_user2[] = $user_coin_s['coin'];
                    }
                }
            }
            $user_sum_coin = array_sum($array_coin_user2); //代理下级用户 上周下注总数
            $total_sum_coin = $agent_sum_coin + $user_sum_coin;  //自己团队总收入
            $level_total = self::zhitui($total_sum_coin);
            $team_user_coin = [];
            foreach ($result as $res_c) {
                $team_user_coin[] = $DetailLog->where('create_time', 'between', [date('Y-m-d H:i:s', $start), date('Y-m-d H:i:s', $end)])->where('user_id', $res_c['id'])->where('action_id', 2)->field('SUM(coin) as coin,user_id')->select()->toArray();
            }
            $agent_coin_all = [];
            foreach ($team_user_coin as $team_coin) {
                if (is_array($team_coin)) {
                    foreach ($team_coin as $team) {
                        $level = self::zhitui($team['coin']);
                        $a = ($level_total['level'] - $level['level']) * $level['wan'];
                        $agent_coin_all[] = $a;
                        $Agent->where('id', $team['user_id'])->setInc('coin', $a);//资金明细表操作
                    }
                }
            }

            $all_dai_all = array_sum($agent_coin_all); //代理表下级总收益 + 用户表下面的总收益
            $user_team_coin_s = [];
            foreach ($result_user as $user_team) {
                $user_team_coin_s[] = $DetailLog->where('create_time', 'between', [date('Y-m-d H:i:s', $start), date('Y-m-d H:i:s', $end)])->where('user_id', $user_team['id'])->where('action_id', 2)->field('SUM(coin) as coin,user_id')->select()->toArray();
            }
            $user_coin_all = [];
            foreach ($user_team_coin_s as $user_team_coin_v) {
                if (is_array($user_team_coin_v)) {
                    foreach ($user_team_coin_v as $team_s) {
                        $level = self::zhitui($team_s['coin']);
                        $b = ($level_total['level'] - $level['level']) * $level['wan'];
                        $user_coin_all[] = $b;
                        $User->where('id', $team_s['user_id'])->setInc('coin', $b); //资金明细写入资金明细表
                        //写入资金明细表操作
                    }
                }
            }
            $all_yong_all = array_sum($user_coin_all); //用户表下级总收益 + 代理表下面的总收益 + 自己
            $self_coin = self::zhitui($direct_commission);
            $self_zhitui = $level_total['level'] * $self_coin['wan'];  //自己的直推佣金
            $self_total_shouyi = $self_zhitui + $all_dai_all + $all_yong_all;
            $self_result = $Agent->where('id', $data_id)->setInc('coin', $self_total_shouyi);//写入明细表操作
            if ($self_result === 0) {
                $User->where('id', $data_id)->setInc('coin', $self_total_shouyi);//写入明细表操作
            }
        }


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
                if ($subordinate_sum < 10000) {
                    $level = [
                        'level' => 10,
                        'wan' => 1
                    ];
                } else {
                    $level = [
                        'level' => 50,
                        'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                    ];
                }
                break;
            case $subordinate_sum >= 100000 && $subordinate_sum < 300000;
                $level = [
                    'level' => 60,
                    'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 300000 && $subordinate_sum < 600000;
                $level = [
                    'level' => 70,
                    'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 600000 && $subordinate_sum < 1000000;
                $level = [
                    'level' => 80,
                    'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 1000000 && $subordinate_sum < 2000000;
                $level = [
                    'level' => 100,
                    'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 2000000 && $subordinate_sum < 4000000;
                $level = [
                    'level' => 120,
                    'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 4000000 && $subordinate_sum < 6000000;
                $level = [
                    'level' => 140,
                    'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 6000000 && $subordinate_sum < 8000000;
                $level = [
                    'level' => 160,
                    'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 8000000 && $subordinate_sum < 10000000;
                $level = [
                    'level' => 180,
                    'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                ];
                break;
            case $subordinate_sum >= 10000000;
                $level = [
                    'level' => 200,
                    'wan' => intval($subordinate_sum >= 10000 ? $subordinate_sum / 10000 : $subordinate_sum)
                ];
                break;
        }
        return $level;
    }

}

