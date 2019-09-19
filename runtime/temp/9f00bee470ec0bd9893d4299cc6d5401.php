<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\club\club_members.html";i:1558859857;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;}*/ ?>
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

<title>俱乐部会员 - TXCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>

<body>
    <div class="mt-20">
        <div class="table-responsive">
            <table style="white-space: nowrap;word-break:keep-all;" class="table table-border table-bordered table-hover table-bg table-sort" id="table">
                <tr class="text-c">
                    <th width="50">ID</th>
                    <th>账号</th>
                    <th>昵称</th>
                    <th>等级</th>
                    <th>管理员角色</th>
                    <th>积分</th>
                    <th>状态</th>
                    <th width="">加入时间</th>
                </tr>
                <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr class="text-c">
                    <td id="id1"><?php echo $vo['id']; ?></td>
                    <td><?php echo $vo['name']; ?></td>
                    <td><?php echo $vo['nickName']; ?></td>
                    <td><?php echo $vo['level']; ?></td>
                    <td><?php if($vo['adminRole'] == 'NONE'): ?>无<?php elseif($vo['adminRole'] == 'ASSISTANT'): ?>助理管理员<?php elseif($vo['adminRole'] == 'ADMINISTRATOR'): ?>管理员<?php elseif($vo['adminRole'] == 'SUPERADMIN'): ?>超级管理员<?php else: ?>未知<?php endif; ?></td>
                    <td><?php echo $vo['score']; ?></td>
                    <td><?php if($vo['state'] == 'PENDING'): ?>待审批<?php elseif($vo['state'] == 'APPROVED'): ?>已批准<?php elseif($vo['state'] == 'DENIED'): ?>已拒绝<?php elseif($vo['state'] == 'DISABLED'): ?>已禁止<?php else: ?>未知<?php endif; ?></td>
                    <td><?php echo $vo['createTime']; ?></td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
        </div>
        <div style="float: right;"><?php echo $page; ?></div>
    </div>
    <!--/请在上方写此页面业务相关的脚本-->
</body>

</html>