<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:72:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\user\edit.html";i:1559292400;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\footer.html";i:1552892653;}*/ ?>
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

<title>编辑用户 - RSCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>

<body>
    <article class="cl pd-20">
        <form action="<?php echo url('user/edit'); ?>" method="post" class="form form-horizontal">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">账号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['name']; ?>" placeholder="" id="name" name="name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">昵称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $data['nickName']; ?>" autoComplete="off" placeholder="" id="nickName" name="nickName">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">OpenId：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['openId']; ?>" placeholder="" id="openId" name="openId">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">性别：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['sex']; ?>" placeholder="" id="sex" name="sex">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">头像：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['avatar']; ?>" placeholder="" id="avatar" name="avatar">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">级别：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box" style="width: 150px;">
                        <select class="select" size="1" id="level" name="level">
                            <option value="BASIC" <?php if($data['level'] == 'BASIC'): ?>selected="selected"<?php endif; ?>>BASIC</option>
                            <option value="STANDARD" <?php if($data['level'] == 'STANDARD'): ?> selected="selected" <?php endif; ?>>STANDARD</option>
                            <option value="VIP1" <?php if($data['level'] == 'VIP1'): ?> selected="selected" <?php endif; ?>>VIP1</option>
                            <option value="VIP2" <?php if($data['level'] == 'VIP2'): ?> selected="selected" <?php endif; ?>>VIP2</option>
                            <option value="VIP3" <?php if($data['level'] == 'VIP3'): ?> selected="selected" <?php endif; ?>>VIP3</option>
                            <option value="VIP4" <?php if($data['level'] == 'VIP4'): ?> selected="selected" <?php endif; ?>>VIP4</option>
                            <option value="VIP5" <?php if($data['level'] == 'VIP5'): ?> selected="selected" <?php endif; ?>>VIP5</option>
                        </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">手机：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['mobile']; ?>" name="mobile" id="mobile">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">钻石：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['cardNum']; ?>" name="cardNum" id="cardNum">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">积分：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['score']; ?>" name="score" id="score">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">实名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['realName']; ?>" name="realName" id="realName">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">身份证号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['idCardNo']; ?>" name="idCardNo" id="idCardNo">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">地址：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['address']; ?>" name="address" id="address">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">邮件地址：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['email']; ?>" name="email" id="email">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">开户行：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['bankName']; ?>" name="bankName" id="bankName">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">银行账户：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['bankAccount']; ?>" name="bankAccount" id="bankAccount">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">银行卡号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['bankCard']; ?>" name="bankCard" id="bankCard">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">支付宝账号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['alipayAccount']; ?>" name="alipayAccount" id="alipayAccount">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">支付宝ID：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['alipayId']; ?>" name="alipayId" id="alipayId">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">微信支付账号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['wxpayAccount']; ?>" name="wxpayAccount" id="wxpayAccount">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">微信支付ID：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" autoComplete="off" value="<?php echo $data['wxpayId']; ?>" name="wxpayId" id="wxpayId">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">注册时间：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['createTime']; ?>" name="create_time" id="createTime">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">是否锁定：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input name="locked" type="radio" value=0 <?php if($data['locked'] == false): ?> checked <?php endif; ?>>
                        <label for="sex-1">否</label>
                    </div>
                    <div class="radio-box">
                        <input name="locked" type="radio" value=1 <?php if($data['locked'] == true): ?> checked <?php endif; ?>>
                        <label for="sex-2">是</label>
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