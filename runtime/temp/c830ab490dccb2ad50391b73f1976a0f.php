<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:77:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\club\edit_club.html";i:1558872104;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\footer.html";i:1552892653;}*/ ?>
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

<title>编辑俱乐部 - TXCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>

<body>
    <article class="cl pd-20">
        <form action="<?php echo url('club/edit_club'); ?>" method="post" class="form form-horizontal">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $data['name']; ?>" autoComplete="off" placeholder="" id="name" name="name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">描述：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $data['description']; ?>" autoComplete="off" placeholder="" id="description" name="description">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">拥有者：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['ownerId']; ?>" placeholder="" id="ownerId" name="ownerId">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">玩法：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['gameType']; ?>" placeholder="" id="gameType" name="gameType">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">收费标准：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['feeRate']; ?>" placeholder="" id="feeRate" name="feeRate">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">级别：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['level']; ?>" placeholder="" id="level" name="level">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">会员容量：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['membersCapacity']; ?>" name="membersCapacity" id="membersCapacity">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">管理员容量：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['adminsCapacity']; ?>" name="adminsCapacity" id="adminsCapacity">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">状态：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input name="state" type="radio" value="ACTVIE" <?php if($data['state'] == 'ACTVIE'): ?> checked <?php endif; ?>>
                        <label for="sex-1">有效</label>
                    </div>
                    <div class="radio-box">
                        <input name="state" type="radio" value="DISABLED" <?php if($data['state'] == 'DISABLED'): ?> checked <?php endif; ?>>
                        <label for="sex-2">失效</label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <button class="btn btn-primary radius" value="&nbsp;&nbsp;修改&nbsp;&nbsp;">修改</button>
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


</body>

</html>