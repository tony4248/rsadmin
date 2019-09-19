<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\shop\edit_order.html";i:1559023004;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\footer.html";i:1552892653;}*/ ?>
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

<title>修改记录 - RSCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>

<body>
    <article class="cl pd-20">
        <form action="" method="post" class="form form-horizontal form-order-edit" id="form-order-edit">
            <input type="hidden" name="oid" value="<?php echo $data['oid']; ?>">
            <input type="hidden" name="uid" value="<?php echo $data['uid']; ?>">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>用户ID：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" disabled="disabled" value="<?php echo $data['uid']; ?>" placeholder="" id="uid" name="uid">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>订单类型：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box" style="width: 150px;">
                        <select class="select" size="1" id="type" name="type">
                            <option value="VIPRECHARGE" <?php if($data['type'] == 'VIPRECHARGE'): ?>selected="selected"<?php endif; ?>>VIP充值</option>
                            <option value="RECHARGE" <?php if($data['type'] == 'RECHARGE'): ?>selected="selected"<?php endif; ?>>充值</option>
                            <option value="WITHDRAW" <?php if($data['type'] == 'WITHDRAW'): ?>selected="selected"<?php endif; ?>>提现</option>
                        </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>产品类型：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box" style="width: 150px;">
                            <select class="select" size="1" id="prodType" name="prodType">
                                <option value="CARD" <?php if($data['prodType'] == 'CARD'): ?>selected="selected"<?php endif; ?>">钻石</option>
                                <option value="COIN" <?php if($data['prodType'] == 'COIN'): ?>selected="selected"<?php endif; ?>">金币</option>
                            </select>
                        </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>产品名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $data['prodName']; ?>" placeholder="" name="prodName" id="prodName">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>产品数量：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $data['qty']; ?>" placeholder="" name="qty" id="qty">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>金额：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $data['amount']; ?>" placeholder="" id="amount" name="amount">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">支付方式：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box" style="width: 150px;">
                    <select class="select" size="1" id="paymentType" name="paymentType">
                        <option value="BANKCARD" <?php if($data['paymentType'] == 'BANKCARD'): ?>selected="selected"<?php endif; ?>>银行卡</option>
                        <option value="ALIPAY" <?php if($data['paymentType'] == 'ALIPAY'): ?>selected="selected"<?php endif; ?>>支付宝</option>
                        <option value="WEIXINPAY" <?php if($data['paymentType'] == 'WEIXINPAY'): ?>selected="selected"<?php endif; ?>>微信支付</option>
                    </select>
                </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>支付账户ID：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $data['paymentId']; ?>" placeholder="输入支付账户ID" id="paymentId" name="paymentId">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>状态：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input name="state" value="PENDING" type="radio" id="sex-1" <?php if($data['state'] == 'PENDING'): ?> checked <?php endif; ?>>
                        <label for="sex-1">待审批</label>
                    </div>
                    <div class="radio-box">
                        <input name="state" value="APPROVED" type="radio" id="sex-2" <?php if($data['state'] == 'APPROVED'): ?> checked <?php endif; ?>>
                        <label for="sex-2">已批准</label>
                    </div>
                    <div class="radio-box">
                        <input name="state" value="PROCESSED" type="radio" id="sex-3" <?php if($data['state'] == 'PROCESSED'): ?> checked <?php endif; ?>>
                        <label for="sex-2">已处理</label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <input class="btn btn-primary radius" type="button" id="button" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
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
    <script type="text/javascript" src="/static/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script> <script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script> <script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script> <script type="text/javascript" src="/static/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script type="text/javascript">
        $('#button').click(function() {
            var formData = $('.form-order-edit').serialize();
            $.ajax({
                url: "<?php echo url('shop/edit_order'); ?>",
                type: 'post',
                dataType: 'json',
                data: formData,
                success: function(res) {
                    if (res.code === 0) {
                        //失败
                        layer.msg(res.msg, {
                            icon: 2
                        }, 300);
                    } else {
                        //成功
                        layer.msg(res.msg, {
                            icon: 1
                        }, 300);
                        var index1 = parent.layer.getFrameIndex(window.name);
                        setTimeout(function() {
                            parent.location.reload();
                            parent.layer.close(index1); //关闭弹出层
                        }, 1000);
                    }
                }
            })
        })
    </script>
    <!--/请在上方写此页面业务相关的脚本-->
</body>

</html>