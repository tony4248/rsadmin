<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\login\index.html";i:1560385646;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>RS后台登录</title>
    <meta name="author" content="DeathGhost" />
    <link rel="stylesheet" type="text/css" href="/static/admin/login_new/css/style.css" tppabs="/static/admin/login_new/css/style.css" />
    <style>
        body {
            height: 100%;
            background: #16a085;
            overflow: hidden;
        }
        
        canvas {
            z-index: -1;
            position: absolute;
        }
        
        #vcode {
            position: absolute;
            bottom: 1px;
            left: 150px;
        }
    </style>
    <script type="text/javascript" src="/static/admin/lib/jquery/1.9.1/jquery.min.js"></script> <script type="text/javascript" src="/static/admin/lib/layer/2.4/layer.js"></script>
    <script src="/static/admin/login_new/js/verificationnumbers.js" tppabs="/static/admin/login_new/js/verificationNumbers.js"></script>
    <script src="/static/admin/login_new/js/particleground.js" tppabs="/static/admin/login_new/js/Particleground.js"></script>
    <script>
        $(document).ready(function() {
            //粒子背景特效
            $('body').particleground({
                dotColor: '#5cbdaa',
                lineColor: '#5cbdaa'
            });
        });

        function button_btn() {
            var data = $('#datas').serialize();
            $.ajax({
                url: "<?php echo url('login/dologin'); ?>",
                type: 'post',
                data: data,
                dataType: 'json',
                success: function(res) {
                    if (res.code === 1) {
                        // 成功
                        layer.msg(res.msg, {
                            icon: 1
                        });
                        setTimeout(function() {
                            window.location.href = "<?php echo url('index/index'); ?>";
                        }, 2000);

                    } else {
                        // 失败
                        layer.msg(res.msg, {
                            icon: 2
                        })
                    }
                },
                error: function(res) {
                    layer.msg('数据有误');
                }
            });
        }
    </script>
</head>

<body>
    <dl class="admin_login">
        <dt>
  <strong>RS后台管理系统</strong>
  <em>Background Management System</em>
 </dt>
        <form id="datas" method="post" action="">
            <dd class="user_icon">
                <input type="text" placeholder="账号" class="login_txtbx" id="username" name="username" />
            </dd>
            <dd class="pwd_icon">
                <input type="password" placeholder="密码" class="login_txtbx" id="password" name="password" />
            </dd>
            <dd class="val_icon">
                <input type="text" id="J_codetext" placeholder="验证码" autocomplete="off" name="code" maxlength="4" class="login_txtbx">
                <img id="vcode" onclick="refresh();" src="<?php echo captcha_src(); ?>" alt="captcha">
            </dd>
            <dd>
                <input type="button" value="立即登陆" onclick="button_btn();" class="submit_btn" />
            </dd>
        </form>
        <dd>
            <p>© 2018 txzh 版权所有</p>
            <p></p>
        </dd>
    </dl>
    <script>
        function refresh() {
            var vcode = document.getElementById('vcode');
            vcode.src = '<?php echo captcha_src(); ?>?' + Math.random();
        }

        /*回车键抬起触发登录*/
        $(document).keyup(function(event) {
            if (event.keyCode === 13) {
                button_btn();
            }
        });
    </script>
</body>

</html>