<?php
/**
 * Created by PhpStorm.
 * User: RSCMS_V2
 * Date: 2018-9-28
 * Time: 14:15
 */

namespace app\dest\controller;

use PHPQRCode\QRcode;
use think\Controller;
use think\Db;
use think\Loader;
use app\dest\model\User as UserM;

class Promotion extends Base
{
    /**
     *  二维码推广
     * @return mixed
     */
    public function index()
    {
        $url = request()->domain() . '/index.php/dest/promotion/add?id=' . session('agent_id');
        $errorCorrectionLevel = 'M'; //容错级别
        $matrixPointSize = 5;
        $object = new QRcode();
        //打开缓冲区
        ob_start();
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 8);
        $imageString = base64_encode(ob_get_contents());
        //关闭缓冲区
        ob_end_clean();
        //把生成的base64字符串返回给前端
        $data = array(
            'code' => 1,
            'data' => $imageString
        );
        $this->assign('data', $data);
        return $this->fetch('index');
    }

    /**
     *  用户扫码进入页面
     * @param int $id
     * @return mixed
     */
    public function add(int $id = 0)
    {
        if (empty($id)) {
            $this->error('数据有误');
        }
        $data = Db::table('user')->find($id);
        $assign = [
            'id' => $id,
            'nickname' => $data['nickname']
        ];
        $this->assign('data', $assign);
        return $this->fetch();
    }

    /**
     *   保存推广二维码用户数据
     * @param int $pid
     * @param string $nickname
     * @param string $username
     * @param string $password
     * @param string $phone
     * @param int $sex
     */
    public function save(int $pid = 0,string $nickname = '',string $username = '',string $password = '', $phone = '',int $sex = 0)
    {
        $data = ['parent_id'=>$pid,'nickname'=>$nickname,'username'=>$username,'password'=>$password,'phone'=>$phone,'sex'=>$sex];
        $Agent = new \app\dest\model\Agent();
        $validate = Loader::validate('Promotion');
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }
        $result = $Agent->saveDailiUserInfo($data);
        if($result){
            $this->success('注册成功');
        }else{
            $this->error('注册失败');
        }
    }

    /**
     *  链接推广二维码
     * @return mixed
     */
    public function linkPromotion()
    {
        $url = request()->domain() . '/index.php/dest/promotion/add?id=' . session('user_id');
        $errorCorrectionLevel = 'M'; //容错级别
        $matrixPointSize = 4;
        $object = new QRcode();
        //打开缓冲区
        ob_start();
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 10);
        $imageString = base64_encode(ob_get_contents());
        //关闭缓冲区
        ob_end_clean();
        //把生成的base64字符串返回给前端
        $data = array(
            'code' => 1,
            'data' => $imageString
        );
        $this->assign('data', $data);
        return $this->fetch();
    }



}