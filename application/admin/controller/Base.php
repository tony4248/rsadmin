<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-9-8
 * Time: 10:53
 */

namespace app\admin\controller;

use think\Controller;
use think\Request;

/**
 * Class Base
 * @package app\admin\controller
 * 权限认证登录基础类
 */
class Base extends Controller
{
    public function _initialize()
    {
        //初始化判断用户是否已经登录
        if (!session('admin_id')) {
            $this->error('你还未登录请先登录', 'login/index');
        }
        //$menu=db('setting')->where('name','menu_tree')->find()['value'];
        //$menu = '[{"title":"权限配置","nav":[{"title":"用户组管理","url":"/admin.php/admin/group/index"},{"title":"规则管理","url":"/admin.php/admin/rule/index"}]},{"title":"管理员管理","nav":[{"title":"管理员列表","url":"/admin.php/admin/admin/index"},{"title":"管理员登录日志","url":"/admin.php/admin/admin/loginlog"}]},{"title":"用户管理","nav":[{"title":"代理列表","url":"/admin.php/admin/user/index"},{"title":"用户列表","url":"/admin.php/admin/user/user_list"},{"title":"登陆日志","url":"/admin.php/admin/login_log/index"},{"title":"业绩报表","url":"/admin.php/admin/agent/reports"},{"title":"提佣管理","url":"/admin.php/admin/agent/withdraw"},{"title":"商品管理","url":"/admin.php/admin/shop/index"},{"title":"订单管理","url":"/admin.php/admin/shop/orders"},{"title":"对局记录","url":"/admin.php/admin/user/gamersls"},{"title":"俱乐部列表","url":"/admin.php/admin/club/index"}]},{"title":"文章管理","nav":[{"title":"文章列表","url":"/admin.php/admin/article/index"},{"title":"分类列表","url":"/admin.php/admin/cate/index"}]},{"title":"客服系统","nav":[{"title":"反馈列表","url":"/admin.php/admin/feedback/index"},{"title":"邮件列表","url":"/admin.php/admin/email/index"}]},{"title":"资金明细","nav":[{"title":"资金明细","url":"/admin.php/admin/detail/index"}]},{"title":"游戏列表","nav":[{"title":"百人牛牛","url":"/admin.php/admin/game_room/one_hundred"},{"title":"抢庄牛牛","url":"/admin.php/admin/game_room/grab_the_cattle"},{"title":"炸金花","url":"/admin.php/admin/game_room/fried_golden_flower"},{"title":"德州扑克","url":"/admin.php/admin/game_room/texas_playing_card"}]},{"title":"机器人管理","nav":[{"title":"机器人列表","url":"/admin.php/admin/robot/index"},{"title":"输赢统计","url":"/admin.php/admin/user/robotWinOrLose"}]},{"title":"顶部消息","nav":[{"title":"公告列表","url":"/admin.php/admin/anncmnt/index"},{"title":"消息列表","url":"/admin.php/admin/message/index"}]}]';
        $menu = '[{"title":"管理员管理","nav":[
                            {"title":"管理员列表","url":"/admin.php/admin/admin/index"},
                            {"title":"登录日志","url":"/admin.php/admin/admin/loginlog"}]},
                    {"title":"用户管理","nav":[
                            {"title":"用户列表","url":"/admin.php/admin/user/user_list"},
                            {"title":"对局记录","url":"/admin.php/admin/user/gamersls"},
                            {"title":"登录日志","url":"/admin.php/admin/user/loginlogs"}]},
                    {"title":"代理管理","nav":[
                            {"title":"代理列表","url":"/admin.php/admin/user/index"},
                            {"title":"业绩报表","url":"/admin.php/admin/agent/reports"},
                            {"title":"提佣管理","url":"/admin.php/admin/agent/withdraw"}]},
                    {"title":"商城管理","nav":[
                            {"title":"商品管理","url":"/admin.php/admin/shop/index"},
                            {"title":"订单管理","url":"/admin.php/admin/shop/orders"}]},
                    {"title":"俱乐部管理","nav":[
                            {"title":"俱乐部列表","url":"/admin.php/admin/club/index"}]},
                    {"title":"邮件管理","nav":[
                            {"title":"邮件列表","url":"/admin.php/admin/email/index"}]},
                    {"title":"反馈管理","nav":[
                            {"title":"反馈列表","url":"/admin.php/admin/feedback/index"}]},
                    {"title":"机器人管理","nav":[
                            {"title":"机器人列表","url":"/admin.php/admin/robot/index"}]},
                    {"title":"公告管理","nav":[
                            {"title":"公告列表","url":"/admin.php/admin/anncmnt/index"}]},
                    {"title":"客服管理","nav":[
                            {"title":"客服列表","url":"/admin.php/admin/kefu/index"}]},
                    {"title":"消息管理","nav":[
                            {"title":"消息列表","url":"/admin.php/admin/message/index"}]}]';
        $menu_tree = json_decode($menu,true);
        $this->assign('menu_tree', $menu_tree);
    }

}