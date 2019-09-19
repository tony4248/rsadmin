<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:75:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\detail\index.html";i:1552892653;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\header.html";i:1552892653;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\menu.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\footer.html";i:1552892653;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="Bookmark" href="favicon.ico" >
    <link rel="Shortcut Icon" href="favicon.ico" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/static/admin/lib/html5.js"></script>
    <script type="text/javascript" src="/static/admin/lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/static/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" href="/static/admin/static/h-ui.admin/skin/default/skin.css" type="text/css" id="skin">
    <link rel="stylesheet" type="text/css" href="/static/admin/css/style.css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js"></script>
    <!--<script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>-->
    <script type="text/javascript" src="/static/menu/menu/jquery-3.2.1.min.js"></script>
    <![endif]-->
<title>资金明细 - 明细列表 - TXCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>
<body>
<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="<?php echo url('index/index'); ?>">TXCMS_后台管理系统</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="#l">TXCMS_后台管理系统</a>
            <span class="logo navbar-slogan f-l mr-10 hidden-xs">v2.0</span>
            <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
            <nav class="nav navbar-nav">
                <ul class="cl">
                    <li class="dropDown dropDown_hover"><a href="javascript:;" class="dropDown_A"><i class="Hui-iconfont">&#xe600;</i> 新增 <i class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="javascript:;" onclick="article_add('添加管理员','<?php echo url("admin/add"); ?>')"><i class="Hui-iconfont">&#xe616;</i> 新增管理员</a></li>
                            <li><a href="javascript:;" onclick="product_add('添加文章','<?php echo url('article/add'); ?>')"><i class="Hui-iconfont">&#xe620;</i> 新增文章</a></li>
                            <li><a href="javascript:;" onclick="member_add('添加分类','<?php echo url("cate/add"); ?>','','510')"><i class="Hui-iconfont">&#xe60d;</i> 新增分类</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    <li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"><?php echo \think\Request::instance()->session('admin.username'); ?> <i class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a onclick="logout();">退出</a></li>
                        </ul>
                    </li>

                    <li class="dropDown dropDown_hover"> <a href="#" onclick="cleanCache();" class="dropDown_A" title="清空缓存">清空缓存<i class="badge badge-danger"></i></a> </li>


                    <li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
                            <li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
                            <li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
                            <li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
                            <li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
                            <li><a href="javascript:;" data-val="orange" title="橙色">橙色</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<script>
    /*退出*/
    function logout()
    {
        $.ajax({
            url:"<?php echo url('login/logout'); ?>",
            success:function (res) {
                if(res.code === 1){
                    //退出成功
                    layer.msg(res.msg,{icon:1});
                    setTimeout(function () {
                        window.location.href="<?php echo url('login/index'); ?>"
                    },2000)
                }else{
                    //退出失败
                    layer.msg(res.msg,{icon:2})
                }

            }
        })
    }
    /*
    清空缓存
     */
    function cleanCache()
    {
        $.ajax({
            url:"<?php echo url('index/cleanCache'); ?>",
            type:'post',
            success:function (res) {
                if(res.code === 1){
                    layer.msg(res.msg);
                }else{
                    layer.msg(res.msg);
                }
            }
        })
    }

    function article_add(title,url,id,w,h){
        layer_show(title,url,w,h);
    }
    function product_add(title,url,id,w,h){
        layer_show(title,url,w,h);
    }
    function member_add(title,url,id,w,h){
        layer_show(title,url,w,h);
    }
</script>
<?php
    $title = '';
    foreach ($menu_tree as $item) {
        foreach($item['nav'] as $val) {
            if ($val['url'] == '/admin.php/' . request()->pathinfo()) {
                $title = $item['title'];
            }
        }
    }
?>
<aside class="Hui-aside">
    <div class="menu_dropdown bk_2">
        <?php if(is_array($menu_tree) || $menu_tree instanceof \think\Collection || $menu_tree instanceof \think\Paginator): $i = 0; $__LIST__ = $menu_tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <dl>
            <dt class="<?php if($vo['title']==$title): ?>selected<?php endif; ?>"><i class="Hui-iconfont">&#xe63c;</i> <?php echo $vo['title']; ?><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd style="<?php if($vo['title']==$title): ?>display: block<?php endif; ?>">
                <ul>
                    <?php if(is_array($vo['nav']) || $vo['nav'] instanceof \think\Collection || $vo['nav'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['nav'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?>
                    <li><a href="<?php echo $nav['url']; ?>" title="<?php echo $nav['title']; ?>"><?php echo $nav['title']; ?></a></li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </dd>
        </dl>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        <dl id="menu-admin">
            <dt class="selected"><i class="Hui-iconfont">&#xe63c;</i> 栏目管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul>
                    <li><a href="<?php echo url('menu/index'); ?>" title="栏目管理">栏目列表</a></li>
                </ul>
            </dd>
        </dl>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<script>
</script>

<section class="Hui-article-box">
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> <a href="<?php echo url('index/index'); ?>">首页</a> <span class="c-gray en">&gt;</span> 资金 <span class="c-gray en">&gt;</span> 资金明细<a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="Hui-article">
        <article class="cl pd-20">
            <div class="mt-20">
                <form class="form-inline" role="form" method="get" action="<?php echo url('detail/index'); ?>">
                    <div class="form-group">
                        <span style="color: red;">明细查询</span> 用户:<input style="width: 120px;" type="text" class="input-text" name="nickname" placeholder="请输入账号" value="<?php echo !empty($nickname)?$nickname : '';; ?>">
                        游戏:<input style="width: 120px;" type="text" class="input-text" name="game" placeholder="请输入游戏名" value="<?php echo !empty($game)?$game : '';; ?>">
                        操作方式:<input style="width: 120px;" type="text" class="input-text" name="action" placeholder="请输入操作方式" value="<?php echo !empty($action)?$action : '';; ?>">
                        开始时间：<input type="text" class="input-text radius size-S" style="width: 100px" autocomplete="off" name="start" value="<?php echo !empty($start)?$start : '';; ?>" id="test1">-<input type="text" class="input-text radius size-S" style="width: 100px" autocomplete="off" name="end" value="<?php echo !empty($end)?$end : '';; ?>" id="test2">
                    </div>
                    <button type="submit" class="btn btn-default">明细查询</button>
                </form>
                <div class="table-responsive">
                <table class="table table-border table-bordered table-hover table-bg table-sort" id="mytable">
                    <thead>
                    <tr class="text-c">
                        <th width="50">ID</th>
                        <th width="60">用户</th>
                        <th width="60">游戏</th>
                        <th width="90">操作方式</th>
                        <th width="50">变动金额</th>
                        <th width="50">操作前余额</th>
                        <th width="50">操作后余额</th>
                        <th width="130">备注说明</th>
                        <th width="130">创建时间</th>
                        <th width="130">创建IP</th>
                        <th width="130">对局详情</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <tr class="text-c">
                        <td><?php echo $vo['id']; ?></td>
                        <td><?php echo $vo['user_nick_name']; ?></td>
                        <td><?php echo $vo['game_name']; ?></td>
                        <td><?php echo $vo['action_name']; ?></td>
                        <td><?php echo $vo['coin']; ?></td>
                        <td><?php echo $vo['before_coin']; ?></td>
                        <td><?php echo $vo['after_coin']; ?></td>
                        <td><?php echo $vo['info']; ?></td>
                        <td><?php echo $vo['create_time']; ?></td>
                        <td><?php echo $vo['create_ip']; ?></td>
                        <!--<td><a title="查看" href="javascript:;" onclick="detail_xq('对局详情','/admin.php/admin/detail/runningWater?id=<?php echo $vo['room_id']; ?>')" class="ml-5 btn btn-success-outline radius size-MINI" style="text-decoration:none">对局详情</a></td>-->
                        <td><a title="查看" href="javascript:;" onclick="detail_xq('对局详情','/admin.php/admin/detail/getGameDetails')" class="ml-5 btn btn-success-outline radius size-MINI" style="text-decoration:none">对局详情</a>
                        </td>
                    </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr class="text-c" id="totalRow">
                    </tr>
                    </tbody>
                </table>
                </div>
                <?php echo isset($quick)?$quick: ''; ?>
            </div>
        </article>
        <div style="float: right;"><?php echo $page; ?></div>
    </div>
</section>

<!--<script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>-->
<script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui.admin/js/H-ui.admin.page.js"></script>
<link rel="stylesheet" type="text/css" href="/static/admin/css/jquery-ui.css" />
<script type="text/javascript" src="/static/admin/static/laydate/laydate.js"></script>


<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/static/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/static/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/static/admin/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript">
    function detail_xq(title,url,id,w,h){
        layer_show(title,url,id,w,h)
    }
    laydate.render({
        elem: '#test1' //指定元素
        ,calendar: true
        ,theme: '#333'
        ,showBottom: true
    });
    laydate.render({
        elem: '#test2' //指定元素
        ,calendar: true
        ,theme: '#333'
        ,showBottom: true
    });

    $(document).ready(function() {

        var totalRow = 0;
        $('#mytable tr').each(function() {
            $(this).find('td:eq(4)').each(function(){
                totalRow += parseFloat($(this).text());
            });
        });

        $('#totalRow').append('<td colspan="3">当前页合计</td><td colspan="1" style="text-align: center"><span style="color: #761c19">当前页总变动金额：</span></td><td>'+totalRow+'</td><td></td><td></td><td></td><td></td>');
    });


</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>