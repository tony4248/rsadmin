<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:83:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\agent\report_records.html";i:1559022995;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\footer.html";i:1552892653;}*/ ?>
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

<title>业绩记录 - RSCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>

<body>
    <div class="mt-20">
        <table style="white-space: nowrap;word-break:keep-all;" class="table table-border table-bordered table-hover table-bg table-sort" id="table">
            <tr class="text-c">
                <th width="120">批次号</th>
                <th>总业绩</th>
                <th>只身业绩</th>
                <th>团队业绩</th>
                <th>自身佣金</th>
                <th>团队佣金</th>
                <th>报告周期</th>
                <th>开始时间</th>
                <th>截止时间</th>
            </tr>
            <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr class="text-c">
                <td id="id1"><?php echo $vo['batchId']; ?></td>
                <td><?php echo $vo['perfSum']; ?></td>
                <td><?php echo $vo['selfPerf']; ?></td>
                <td><?php echo $vo['teamMembersPerf']; ?></td>
                <td><?php echo $vo['rebateSum']; ?></td>
                <td><?php echo $vo['teamRebate']; ?></td>
                <td><?php echo $vo['periodType']; ?></td>
                <td><?php echo $vo['startTime']; ?></td>
                <td><?php echo $vo['endTime']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
    </div>
    <!--<script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>-->
<script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui.admin/js/H-ui.admin.page.js"></script>
<link rel="stylesheet" type="text/css" href="/static/admin/css/jquery-ui.css" />
<script type="text/javascript" src="/static/admin/static/laydate/laydate.js"></script>


    <!--请在下方写此页面业务相关的脚本-->
    <script type="text/javascript" src="/static/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script> <script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script> <script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script> <script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script type="text/javascript">
    </script>
    <!--/请在上方写此页面业务相关的脚本-->
</body>

</html>