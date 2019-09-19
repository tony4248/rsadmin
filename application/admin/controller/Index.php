<?php

namespace app\admin\controller;

use think\Db;
use app\admin\model\Users as MUsers;
use app\admin\model\Agents as MAgents;
use app\admin\model\Clubs as MClubs;
use app\admin\model\Rooms as MRooms;
use app\admin\model\SystemCache as MSystemCache;


/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-5
 * Time: 16:25
 */
class Index extends Base
{
    /**
     * 项目首页
     */
    public function index()
    {   
        $config = $this->getConfig();
        $statistics =  $this->getStatistics();
        $this->assign('statistics', $statistics);
        $this->assign('config', $config);
        return $this->fetch();
    }
    public function getConfig(){
        $config = [
            'url' => $_SERVER['SERVER_ADDR'] ?? null,  //获取当前域名
            'document_root' => $_SERVER['DOCUMENT_ROOT'],  //网站目录
            'server_os' => PHP_OS  ?? null,                    //操作系统
            'server_port' => $_SERVER['SERVER_PORT']  ?? null,   //服务器端口
            'server_ip' => $_SERVER['SERVER_ADDR']  ?? null,      //服务器ip
            'server_soft' => $_SERVER['SERVER_SOFTWARE']  ?? null,  //web运行环境
            'php_version' => PHP_VERSION  ?? null,      //php版本
            'accept' => $_SERVER['HTTP_ACCEPT_LANGUAGE']  ?? null,   //服务器语言
            'user_agent' => $_SERVER['HTTP_USER_AGENT']  ?? null,   //当前浏览器user_agent
            'system_root' => $_SERVER['SystemRoot']  ?? null,   //服务器系统目录
            'zend_banben' => Zend_Version()  ?? null,    //Zend版本
            'apa_ngi' => PHP_SAPI  ?? null    //web服务器
        ];
        return $config;
    }
    public function getStatistics(){
        $user = new Musers();
        $userCount = $user ->count();
        $agent = new MAgents();
        $agenCount = $agent ->count();
        $club = new MClubs();
        $clubCount = $club ->count();
        $systemCache = new MSystemCache();
        $scData = $systemCache ->getSystemCache();
        $statistics = [
            'usesNum' => $userCount,
            'agentsNum' => $agenCount,
            'clubsNum' => $clubCount,
            'onlineUsersNum' => sizeof($scData['onlineUsers']),
            'onlineAisNum' => sizeof($scData['onlineAis']),
            'onlineRoomsNum' => sizeof($scData['onlineRooms'])
        ];
        return $statistics;
    }

    /**
     * 空操作
     */
    public function _empty()
    {
        $this->redirect('index/index');
    }

    public function cleanCache()
    {
        if (request()->isPost()){
            deldir(RUNTIME_PATH);
            return json(['code'=>1,'msg'=>'清除成功']);
        }else{
            deldir(RUNTIME_PATH);
            return json(['code'=>0,'msg'=>'清除失败']);
        }
    }

}