<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:75:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\anncmnt\edit.html";i:1559022996;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\footer.html";i:1552892653;}*/ ?>
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

<title>公告管理 - 编辑公告 - RSCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>

<body>
    <article class="cl pd-20">
        <form action="<?php echo url('anncmnt/edit'); ?>" method="post" class="form form-horizontal form-email-add">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>内容：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea name="content" class="textarea radius"><?php echo $data['content']; ?></textarea>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">状态：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input name="active" type="radio" value=1 <?php if($data['active'] == true): ?> checked <?php endif; ?>>
                        <label for="sex-1">启用</label>
                    </div>
                    <div class="radio-box">
                        <input name="active" type="radio" value=0 <?php if($data['active'] == false): ?> checked <?php endif; ?>>
                        <label for="sex-2">禁用</label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <input class="btn btn-primary radius" type="submit" id="button" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
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


    <!--请在下方写此页面业务相关的脚本-->
    <script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script> <script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script> <script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script type="text/javascript">
    </script>
    <!--/请在上方写此页面业务相关的脚本-->
</body>

</html>