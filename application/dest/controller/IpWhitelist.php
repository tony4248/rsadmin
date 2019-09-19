<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-12-19
 * Time: 17:55
 */

namespace app\dest\controller;

use app\dest\model\IpSegment;
use \app\dest\model\IpWhitelist as IpModel;
use app\dest\model\Agent as Magent;
use think\Config;
use think\Loader;
use think\Session;

/**
 *  IP白名单类
 * Class IpWhitelist
 * @package app\dest\controller
 */
class IpWhitelist extends Base
{
    public function index()
    {
        $IpWhitelist = new IpModel();
        $data = $IpWhitelist->paginate(Config::get('myConfig.page_num'), false, ['query' => $_GET]);
        $page = $data->render();
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     *  根据id删除id黑名单
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        if (!$Ip = IpModel::get($id)) {
            return json(['code' => 0, 'msg' => '数据不存在,请刷新页面']);
        }
        $result = IpModel::destroy($id);
        if ($result) {
            return json(['code' => 1, 'msg' => '删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '删除失败']);
        }

    }

    /**
     *  备份
     * @return \think\response\Json
     */
    public function check_box_del()
    {
        $checks = input('ids/a');
        $ids = implode(",", $checks);
        $IpModel = new IpModel();
        $result = $IpModel->checkDel($ids);
        if ($result) {
            return json(['code' => 1, 'msg' => '删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }

    /**
     *  ip黑名单添加页面
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     *  保存ip黑名单
     * @param string $ip
     * @param string $description
     * @return \think\response\Json
     */
    public function ipSave($ip = '', $description = '')
    {
        $data = ['ip' => $ip];
        $Validate = Loader::validate('IpWhitelist');
        if (!$Validate->check($data)) {
            return json(['code' => 0, 'msg' => $Validate->getError()]);
        }

        $Agent = new Magent();
        $res = $Agent->where('create_ip', $ip)->field('nickname')->select()->toArray();
        $user = [];
        foreach ($res as $v) {
            $user[] = $v['nickname'];
        }
        $user_s = implode(',', $user);
        $agent_id = Session::get('agent_id');
        $agentName = $Agent->where('id', $agent_id)->field('nickname')->find();
        if ($agentName) {
            $Agent_name = $agentName->nickname;
        } else {
            $Agent_name = '';
        }
        $data = [
            'ip' => $ip,
            'description' => $description,
            'own' => $user_s,
            'add_person' => $Agent_name,
            'create_time' => now()
        ];
        $result = IpModel::create($data);
        if ($result) {
            return json(['code' => 1, 'msg' => '添加成功']);
        } else {
            return json(['code' => 0, 'msg' => '添加失败']);
        }

    }

    /**
     *  IP段页面
     * @return mixed
     */
    public function segment()
    {
        $IpSegment = new IpSegment();
        $data = $IpSegment->paginate(Config::get('myConfig.agent_page_num'), false, ['query' => $_GET]);
        $page = $data->render();
        $this->assign('data', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }

    /**
     *  IP段根据id删除
     * @param $id
     * @return \think\response\Json
     */
    public function segment_delete($id)
    {
        if (!$Ip = IpSegment::get($id)) {
            return json(['code' => 0, 'msg' => '数据不存在,请刷新页面']);
        }
        $result = IpSegment::destroy($id);
        if ($result) {
            return json(['code' => 1, 'msg' => '删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '删除失败']);
        }

    }

    /**
     *  IP段批量删除
     * @return \think\response\Json
     */
    public function ipSegmentBulkDeletion()
    {
        $checks = input('ids/a');
        $ids = implode(",", $checks);
        $IpSegment = new IpSegment();
        $result = $IpSegment->segmentCheckDel($ids);
        if ($result) {
            return json(['code' => 1, 'msg' => '删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }

    /**
     * IP段页面渲染
     */
    public function segment_add()
    {
        return $this->fetch();
    }


    /**
     *  IP段黑名单保存
     * @param string $ip_start
     * @param string $ip_end
     * @return \think\response\Json
     */
    public function segment_save($ip_start = '', $ip_end = '')
    {

        $data = ['ip_start' => $ip_start, 'ip_end' => $ip_end,'create_time' => now()];
        $Validate = Loader::validate('IpSegment');
        if(!$Validate->check($data)){
            return json(['code'=>0,'msg'=>$Validate->getError()]);
        }
        $ip_s = implode(explode('.',$ip_start));
        $ip_e = implode(explode('.',$ip_end));
        if($ip_s >= $ip_e){
            return json(['code'=>0,'msg'=>'ip开始不能大于结束']);
        }
        $IpSegment = new IpSegment();
        $row = $IpSegment->field('ip_start,ip_end')->select()->toArray();
        $a = ipAuth($ip_start,$row);
        if($a === true){
            return json(['code'=>0,'msg'=>'IP段开始已存在']);
        }
        $b = ipAuth($ip_end,$row);
        if($b === true){
            return json(['code'=>0,'msg'=>'IP段结束已存在']);
        }
        $result = IpSegment::create($data);
        if($result){
            return json(['code'=>1,'msg'=>'添加成功']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败']);
        }


    }



}