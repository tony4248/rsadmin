<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:73:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\agent\edit.html";i:1558401273;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\footer.html";i:1552892653;}*/ ?>
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

<title>添加用户 - TXCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>

<body>
    <article class="cl pd-20">
        <form action="" method="post" class="form form-horizontal form-agent-edittt">
            <input type="hidden" name="pid" value="<?php echo $data['pid']; ?>">
            <input type="hidden" name="uid" value="<?php echo $data['uid']; ?>">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>上级代理Id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['pid']; ?>" placeholder="">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>用户Id：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['uid']; ?>" placeholder="">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>状态：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input name="state" value="PENDING" type="radio" id="sex-1" <?php if($data['state'] == 'PENDING'): ?>checked<?php endif; ?>>
                        <label for="sex-1">待审批</label>
                    </div>
                    <div class="radio-box">
                        <input name="state" value="APPROVED" type="radio" id="sex-2" <?php if($data['state'] == 'APPROVED'): ?>checked<?php endif; ?>>
                        <label for="sex-2">有效</label>
                    </div>
                    <div class="radio-box">
                        <input name="state" value="DISABLED" type="radio" id="sex-3" <?php if($data['state'] == 'DISABLED'): ?>checked<?php endif; ?>>
                        <label for="sex-3">无效</label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <button class="btn btn-primary radius" id="button" type="button" value="&nbsp;&nbsp;修改&nbsp;&nbsp;">修改</button>
                </div>
            </div>
        </form>
    </article>

    <!--<script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js"></script>-->
<script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/static/admin/static/h-ui.admin/js/H-ui.admin.page.js"></script>
<link rel="stylesheet" type="text/css" href="/static/admin/css/jquery-ui.css" />
<script type="text/javascript" src="/static/admin/static/laydate/laydate.js"></script>


    <script type="text/javascript">
        $('#button').click(function() {
            var formData = $('.form-agent-edittt').serialize();
            $.ajax({
                url: "<?php echo url('agent/editSaves'); ?>",
                type: 'post',
                dataType: 'json',
                data: formData,
                success: function(res) {
                    if (res.code === 0) {
                        //失败
                        layer.msg(res.msg, {
                            icon: 2
                        }, 300);
                        var index = parent.layer.getFrameIndex(window.name);
                        setTimeout(function() {
                            parent.location.reload(); //刷新父级页面
                            parent.layer.close(index); //关闭弹出层
                        }, 2000);
                    } else {
                        //成功
                        layer.msg(res.msg, {
                            icon: 1
                        }, 300);
                        var index1 = parent.layer.getFrameIndex(window.name);
                        setTimeout(function() {
                            parent.location.reload();
                            parent.layer.close(index1); //关闭弹出层
                        }, 2000);
                    }
                }
            })
        })
    </script>
    <!--/请在上方写此页面业务相关的脚本-->
</body>

</html>