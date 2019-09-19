<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:77:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\feedback\reply.html";i:1558968345;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\footer.html";i:1552892653;}*/ ?>
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

<title>回复邮件 - 邮件管理 - TXCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>

<body>
    <article class="cl pd-20">
        <form action="<?php echo url('feedback/reply'); ?>" method="post" class="form form-horizontal form-email-add">
            <input type="hidden" name="receiver" value="<?php echo $data['sender']; ?>">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>提交人ID：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['sender']; ?>" placeholder="" id="sender" name="sender">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>标题：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['title']; ?>" placeholder="" id="title" name="title">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>内容：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea name="content" disabled="disabled" class="textarea radius"><?php echo $data['content']; ?></textarea>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>回复：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <textarea name="replyContent" class="textarea radius"><?php echo $data['replyContent']; ?></textarea>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <input class="btn btn-primary radius" type="submit" id="button" value="&nbsp;&nbsp;回复&nbsp;&nbsp;">
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
        $(function() {
            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });

            $("#form-admin-add").validate({
                rules: {
                    adminName: {
                        required: true,
                        minlength: 4,
                        maxlength: 16
                    },
                    password: {
                        required: true,
                    },
                    password2: {
                        required: true,
                        equalTo: "#password"
                    },
                    sex: {
                        required: true,
                    },
                    phone: {
                        required: true,
                        isPhone: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    adminRole: {
                        required: true,
                    },
                },
                onkeyup: false,
                focusCleanup: true,
                success: "valid",
                submitHandler: function(form) {
                    $(form).ajaxSubmit();
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.$('.btn-refresh').click();
                    parent.layer.close(index);
                }
            });
        });
        //    $('#button').click(function () {
        //        var formData  = $('.form-email-add').serialize();
        //        $.ajax({
        //            url:"<?php echo url('email/add'); ?>",
        //            type:'post',
        //            data:formData,
        //            success:function (res) {
        //                if(res.code === 0 ){
        //                    //失败
        //                    layer.msg(res.msg,{icon:2},300);
        //                    var index = parent.layer.getFrameIndex(window.name);
        //                    setTimeout(function(){
        //                        parent.location.reload(); //刷新父级页面
        //                        parent.layer.close(index);//关闭弹出层
        //                    }, 2000);
        //                }else{
        //                    //成功
        //                    layer.msg(res.msg,{icon:1},300);
        //                    var index1 = parent.layer.getFrameIndex(window.name);
        //                    setTimeout(function(){
        //                        parent.location.reload();
        //                        parent.layer.close(index1);//关闭弹出层
        //                    }, 2000);
        //                }
        //            }
        //        })
        //    })
    </script>
    <!--/请在上方写此页面业务相关的脚本-->
</body>

</html>