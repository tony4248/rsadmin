<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:73:"C:\inetpub\wwwroot\qipai\public/../application/admin\view\menu\index.html";i:1559023002;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\meta.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\header.html";i:1559023139;s:64:"C:\inetpub\wwwroot\qipai\application\admin\view\common\menu.html";i:1552892653;s:66:"C:\inetpub\wwwroot\qipai\application\admin\view\common\footer.html";i:1552892653;}*/ ?>
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

<title>游戏列表 - 游戏列表 - RSCMS_V2</title>
<meta name="keywords" content="">
<meta name="description" content="">
<link rel="stylesheet" type="text/css" href="/static/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/static/menu/menu/base.css" />
<script type="text/javascript" src="/static/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="/static/menu/menu/bootstrap-alert.js"></script>
<script type="text/javascript" src="/static/admin/jquery-sortable.js"></script>
</head>
<body>
<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="<?php echo url('index/index'); ?>">RSCMS后台管理系统</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="#l">TXCMS_后台管理系统</a>
            <span class="logo navbar-slogan f-l mr-10 hidden-xs">v2.0</span>
            <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
            <nav class="nav navbar-nav">
                <ul class="cl">
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
    function logout() {
        $.ajax({
            url: "<?php echo url('login/logout'); ?>",
            success: function(res) {
                if (res.code === 1) {
                    //退出成功
                    layer.msg(res.msg, {
                        icon: 1
                    });
                    setTimeout(function() {
                        window.location.href = "<?php echo url('login/index'); ?>"
                    }, 2000)
                } else {
                    //退出失败
                    layer.msg(res.msg, {
                        icon: 2
                    })
                }

            }
        })
    }
    /*
    清空缓存
     */
    function cleanCache() {
        $.ajax({
            url: "<?php echo url('index/cleanCache'); ?>",
            type: 'post',
            success: function(res) {
                if (res.code === 1) {
                    layer.msg(res.msg);
                } else {
                    layer.msg(res.msg);
                }
            }
        })
    }

    function article_add(title, url, id, w, h) {
        layer_show(title, url, w, h);
    }

    function product_add(title, url, id, w, h) {
        layer_show(title, url, w, h);
    }

    function member_add(title, url, id, w, h) {
        layer_show(title, url, w, h);
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
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> <a href="<?php echo url('index/index'); ?>">首页</a>
        <span class="c-gray en">&gt;</span>
        游戏管理
        <span class="c-gray en">&gt;</span>
        游戏列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a> </nav>
    <div class="Hui-article">
        <article class="cl pd-20">
            <ol class="breadcrumb">
                <li class="active">
                    <i class="glyphicon glyphicon-link"></i> 后台栏目</li>
            </ol>
            <div class="box-padding">
                <div class="add-menu-group btn btn-primary">添加栏目</div>
                <ul class="menu-box">
                    <?php foreach($menu_tree as $item): ?>
                    <li class="menu-group" data-title="<?php echo $item['title']; ?>">
                        <div class="menu-1">
                            <div class="menu-toggle"><?php echo $item['title']; ?></div>
                            <div class="menu-action">
                                <div class="icon-svg editor editor1"></div>
                                <div class="icon-svg add add-menu-nav"></div>
                                <div class="icon-svg menu-delete-2 menu-dalete"></div>
                                <div class="icon-svg menu-move menu-move-1"></div>
                            </div>
                        </div>
                        <dl class="menu-2" style="display: none;">
                            <?php foreach($item['nav'] as $nav): ?>
                            <dd class="menu-nav" data-url="<?php echo $nav['url']; ?>" data-title="<?php echo $nav['title']; ?>">
                                <div class="icon-svg editor editor2"></div>
                                <div class="menu-title"><?php echo $nav['title']; ?></div>
                                <div class="menu-action">
                                    <div class="icon-svg menu-delete-2 menu-nav-dalete"></div>
                                    <div class="icon-svg menu-move menu-move-2"></div>
                                </div>
                            </dd>
                            <?php endforeach; ?>
                        </dl>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <button class="btn btn-primary btn-save">保存修改</button>

            </div>

            <template class="edit-title">
                <div class="form-horizontal">
                    <div class="form-group input-title">
                        <label for="inputEmail3" class="col-sm-2 control-label">标题</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail3" placeholder="标题">
                        </div>
                    </div>
                    <div class="form-group input-url">
                        <label for="inputEmail3" class="col-sm-2 control-label">链接</label>

                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail3" placeholder="链接">
                        </div>
                    </div>
                </div>
            </template>
        </article>
        <div style="float: right;"></div>
    </div>


</section>
<!--<script type="text/javascript" src="/static/admin/layui/dist/layui.all.js"></script>-->
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
    $(function () {
        var adjustment;
        var box = $(".menu-box").sortable({
            group: 'menu-box',
            handle: 'div.menu-move-1',
            onDragStart: function ($item, container, _super) {
                var offset = $item.offset(),
                    pointer = container.rootGroup.pointer;

                adjustment = {
                    left: pointer.left - offset.left,
                    top: pointer.top - offset.top
                };

                _super($item, container);
            },
            onDrag: function ($item, position) {

                $item.css({
                    left: position.left - adjustment.left,
                    top: position.top - adjustment.top
                });
            },
            onDrop: function ($item, container, _super, event) {
                // console.log(container.target[0]);
                // _super($item, container);
                $item.removeClass(container.group.options.draggedClass).removeAttr("style");
                $("body").removeClass(container.group.options.bodyClass)
            }
        });

        var menu = $('dl.menu-2').sortable({
            group: 'menu-2',
            handle: 'div.menu-move-2',
            containerSelector: 'dl',
            itemSelector: 'dd',
            placeholder: '<dd class="placeholder">',
            onDragStart: function ($item, container, _super) {
                var offset = $item.offset(),
                    pointer = container.rootGroup.pointer;

                adjustment = {
                    left: pointer.left - offset.left,
                    top: pointer.top - offset.top
                };

                _super($item, container);
            },
            onDrag: function ($item, position) {

                $item.css({
                    left: position.left - adjustment.left,
                    top: position.top - adjustment.top
                });
            }
        });
        $('.btn-save').click(function () {
            var menuGroupData = $(".menu-box").sortable("serialize").get(0);
            var menuNavData = $('.menu-2').sortable("serialize").get();

            var data = [];
            for (var p in menuGroupData) {
                var nav = menuNavData[p];
                var navArr = [];
                for (var _p in nav) {
                    navArr.push({
                        title: nav[_p].title,
                        url: nav[_p].url
                    });
                }
                data.push({
                    title: menuGroupData[p].title,
                    nav: navArr
                });
            }
            $.post("<?php echo url('System/set'); ?>", {data: JSON.stringify({'menu_tree': JSON.stringify(data)})}, function(data) {
                if (data.err) {
                    $.modal.alert(data.msg);
                } else {
                    $.modal.alert('保存成功');
                }
            });

        });
        $(document).on('click', '.menu-toggle', function () {
            $(this).parents('.menu-group').find('.menu-2').toggle(200);
        });

        $('.add-menu-group').click(function () {
            var menu_2 = $('<dl class="menu-2"></dl>');
            menu_2.sortable({ group: 'menu-2' });
            var menu_group = $(`<li class="menu-group" data-title="新建栏目">
        <div class="menu-1">
          <div class="menu-toggle">新建栏目</div>
          <div class="menu-action">
            <div class="icon-svg editor editor1"></div>
            <div class="icon-svg add add-menu-nav"></div>

            <div class="icon-svg menu-delete-2  menu-dalete"></div>
            <div class="icon-svg menu-move menu-move-1"></div>
          </div>
        </div>
      </li>`);
            menu_group.append(menu_2);
            $(".menu-box").append(menu_group);

        });
        $(document).on('click', '.add-menu-nav', function () {
            var $menu_2 = $(this).parents('.menu-group').find('.menu-2');
            $menu_2.is(":hidden") && $menu_2.show(200);
            $menu_2.append(`<dd class="menu-nav"  data-url="#" data-title="新建菜单">
          <div class="icon-svg editor editor2"></div>
          <div class="menu-title">新建菜单</div>
          <div class="menu-action">
            <div class="icon-svg menu-delete-2  menu-nav-dalete"></div>
            <div class="icon-svg menu-move menu-move-2"></div>
          </div>
        </dd>`);
        });
        $(document).on('click', '.menu-dalete', function () {
            $(this).parents('.menu-group').remove();
        });
        $(document).on('click', '.menu-nav-dalete', function () {
            $(this).parents('.menu-nav').remove();
        });

        $(document).on('click', '.editor2', function () {
            var $_this = $(this);
            var $parent = $_this.parents('.menu-nav');
            var $editForm = $($('.edit-title').html());

            var $title = $editForm.find('.input-title input');
            var $url = $editForm.find('.input-url input');
            $title.val($parent.data('title'));
            $url.val($parent.data('url'));

            var myModal = $.modal({
                title: '修改',
                html: $editForm,
                btn: [function ($btn) {
                    $btn.text('确认').click(function() {
                        $parent.data('title', $title.val());
                        $parent.data('url', $url.val());
                        $parent.find('.menu-title').text($title.val());
                        myModal.close();
                    });
                }]
            });
        });

        $(document).on('click', '.editor1', function () {
            var $_this = $(this);
            var $parent = $_this.parents('.menu-group');
            var $editForm = $($('.edit-title').html());

            var $title = $editForm.find('.input-title input');
            $editForm.find('.input-url').remove();
            $title.val($parent.data('title'));

            var myModal = $.modal({
                title: '修改',
                html: $editForm,
                btn: [function ($btn) {
                    $btn.text('确认').click(function() {
                        $parent.data('title', $title.val());
                        $parent.find('.menu-toggle').text($title.val());
                        myModal.close();
                    });
                }]
            });
        });
    })
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>