/* -----------txcms前端框架-------------
* H-ui.admin.page.js v3.1
* http://www.liaotaoo.cn/
* Created & Modified by liaoTao
* Date modified 2018.10.01
*/
/*左侧菜单响应式*/
function Huiasidedisplay(){
	if($(window).width()>=768){
		$(".Hui-aside").show()
	} 
}
/*获取皮肤cookie*/
function getskincookie(){
	var v = $.cookie("Huiskin");
	var hrefStr=$("#skin").attr("href");
	if(v==null||v==""){
		v="default";
	}
	if(hrefStr!=undefined){
		var hrefRes=hrefStr.substring(0,hrefStr.lastIndexOf('skin/'))+'skin/'+v+'/skin.css';
		$("#skin").attr("href",hrefRes);
	}
}
/*弹出层*/
/*
	参数解释：
	title	标题
	url		请求的url
	id		需要操作的数据id
	w		弹出层宽度（缺省调默认值）
	h		弹出层高度（缺省调默认值）
*/
function layer_show(title,url,w,h){
	if (title == null || title == '') {
		title=false;
	};
	if (url == null || url == '') {
		url="404.html";
	};
	if (w == null || w == '') {
		w=800;
	};
	if (h == null || h == '') {
		h=($(window).height() - 50);
	};
	var b = browserRedirect();
	if(b === 0){
        layer.open({
            type: 2,
            area: [w+'px', h +'px'],
            // area: ['100%', '100%'],
            fix: false, //不固定
            maxmin: true,
            shadeClose: true,
            shade:0.4,
            title: title,
            content: url
        });
	}else{
        layer.open({
            type: 2,
            area: ['100%', '100%'],
            // area: ['100%', '100%'],
            fix: false, //不固定
            maxmin: true,
            shadeClose: true,
            shade:0.4,
            title: title,
            content: url
        });
	}

}
/*关闭弹出框口*/
function layer_close(){
	var index = parent.layer.getFrameIndex(window.name);
	parent.layer.close(index);
}

/*判断是pc还是移动端*/
function browserRedirect() {
    var sUserAgent = navigator.userAgent.toLowerCase();
    var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
    var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
    var bIsMidp = sUserAgent.match(/midp/i) == "midp";
    var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
    var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
    var bIsAndroid = sUserAgent.match(/android/i) == "android";
    var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
    var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
    if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
    	//1 移动端
        return 1;
    } else {
    	//2 PC端
        return 0;
    }
}

/*时间*/
function getHTMLDate(obj) {
    var d = new Date();
    var weekday = new Array(7);
    var _mm = "";
    var _dd = "";
    var _ww = "";
    weekday[0] = "星期日";
    weekday[1] = "星期一";
    weekday[2] = "星期二";
    weekday[3] = "星期三";
    weekday[4] = "星期四";
    weekday[5] = "星期五";
    weekday[6] = "星期六";
    _yy = d.getFullYear();
    _mm = d.getMonth() + 1;
    _dd = d.getDate();
    _ww = weekday[d.getDay()];
    obj.html(_yy + "年" + _mm + "月" + _dd + "日 " + _ww);
};

/*个人信息*/
function myselfinfo(){
	layer.open({
		type: 1,
		area: ['300px','200px'],
		fix: false, //不固定
		maxmin: true,
		shade:0.4,
		title: '查看信息',
		content: '<div>管理员信息</div>'
	});
}
$(function(){
	getskincookie();
	Huiasidedisplay();
	var resizeID;
	$(window).resize(function(){
		clearTimeout(resizeID);
		resizeID = setTimeout(function(){
			Huiasidedisplay();
		},500);
	});
	$(".nav-toggle").click(function(){
		$(".Hui-aside").slideToggle();
	});
	$(".Hui-aside").on("click",".menu_dropdown dd li a",function(){
		if($(window).width()<768){
			$(".Hui-aside").slideToggle();
		}
	});
	
	/*左侧菜单*/
	$(".Hui-aside").Huifold({
		titCell:'.menu_dropdown dl dt',
		mainCell:'.menu_dropdown dl dd',
	});



	/*换肤*/
	$("#Hui-skin .dropDown-menu a").click(function(){
		var v = $(this).attr("data-val");
		$.cookie("Huiskin", v);
		var hrefStr=$("#skin").attr("href");
		var hrefRes=hrefStr.substring(0,hrefStr.lastIndexOf('skin/'))+'skin/'+v+'/skin.css';
		$(window.frames.document).contents().find("#skin").attr("href",hrefRes);
	});
}); 
