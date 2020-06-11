<script>
$(function(){

//关闭页面前提示
$(window).bind("beforeunload", function () {
	return "您尚未交卷！此操作将导致您的回答丢失。";
});
setInterval(function(){ 
//alert("sdf");
	//每5分钟请求，有有无新消息
	$.ajax({
		dataType:'json',
		url:"<?=url("Message/_readyList")?>",
		data:'',
		success:function(data){
			if(data.count){
		  $(".badge-dot").html(data.count);
			}else  $(".badge-dot").html('');
		}

	})
 }, 1000*30);
/*$("#min_title_list li").contextMenu('Huiadminmenu', {
bindings: {
'closethis': function(t) {
console.log(t);
if(t.find("i")){
	t.find("i").trigger("click");
}		
},
'closeall': function(t) {
alert('Trigger was '+t.id+'\nAction was Email');
},
}
});*/
});
</script>
<style type="text/css">
.badge-dot{
	position: relative;
    display: inline-block;
    padding: 0 6px;
    font-size: 12px;
    text-align: center;
    background-color: #FF5722;
    color: #fff;
    border-radius: 10px;
    line-height: 16px;}
</style>
</head>
<body>
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" href="/aboutHui.shtml">在线考试系统</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="/aboutHui.shtml">H-ui</a> 
			<span class="logo navbar-slogan f-l mr-10 hidden-xs"></span> 
			<a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
			
		<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
			<ul class="cl">
				<li></li>
				<li class="dropDown dropDown_hover">
					<a href="#" class="dropDown_A"><?= $username ?> <i class="Hui-iconfont">&#xe6d5;</i></a>
					<ul class="dropDown-menu menu radius box-shadow">
						<li><a href="javascript:;" onClick="myselfinfo()">个人信息</a></li>
						<li><a href="<?=url("User/login")?>">退出</a></li>
				</ul>
			</li>
				<li id="Hui-msg"> <a href="#" title="消息"><span class="badge badge-danger"></span><i class="Hui-iconfont" style="font-size:18px"></i></a> </li>
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

<!-- #include file="view/public/head.html"-->

<aside class="Hui-aside">
	<div class="menu_dropdown bk_2">
		<dl id="menu-article">
			<dt><i class="Hui-iconfont">&#xe622;</i> 消息管理<span class="badge-dot"></span><i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
				    <li><a data-href="<?=url("Message/readyList")?>" data-title="消息管理" href="javascript:void(0)">待办事宜</a></li>
					<li><a data-href="<?=url("Message/listReciver")?>" data-title="消息管理" href="javascript:void(0)">收件箱</a></li>
					<li><a data-href="<?=url("Message/listSender")?>" data-title="消息管理" href="javascript:void(0)">我的申请</a></li>
			</ul>
		</dd>
	</dl><?php if($_SESSION["role"] != 3){ if($_SESSION["role"] == 1){ ?>
	<dl id="menu-article">
			<dt><i class="Hui-iconfont">&#xe616;</i> 科目管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
				    <li><a data-href="<?=url("Subject/list")?>" data-title="科目管理" href="javascript:void(0)">科目管理</a></li>
			</ul>
		</dd>
	</dl>
	<?php } ?>
		<dl id="menu-picture">
			<dt><i class="Hui-iconfont">&#xe616;</i> 试卷管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					
					<li><a data-href="<?=url("Test/list")?>" data-title="试卷列表" href="javascript:void(0)">试卷列表</a></li>
			</ul>
		</dd>
	</dl>
		<dl id="menu-product">
			<dt><i class="Hui-iconfont">&#xe616;</i> 试题管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
				
				<li><a data-href="<?=url("Question/list")?>" data-title="试题列表"" href="javascript:void(0)">试题列表</a></li>
			</ul>
		</dd>
	</dl> <?php } ?>
		<dl id="menu-comments">
			<dt><i class="Hui-iconfont">&#xe616;</i> 班级管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a data-href="<?=url("Class/list/{$_SESSION['userid']}")?>" data-title="班级列表" href="javascript:;">班级列表</a></li>
					
			</ul>
		</dd>
	</dl>
		<dl id="menu-member">
			<dt><i class="Hui-iconfont">&#xe616;</i> 考试记录<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a data-href="<?=url("Grade/allList/{$_SESSION['userid']}")?>" data-title="我的考试" href="javascript:;">我的考试</a></li>
					
			</ul>
		</dd>
	</dl>
	<?php if($_SESSION["role"] == 1){ ?>
		<dl id="menu-admin">
			<dt><i class="Hui-iconfont">&#xe60d;</i> 人员管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a data-href="<?=url("User/show")?>" data-title="人员列表" href="javascript:void(0)">人员列表</a></li>
					
			</ul>
		</dd>
	</dl>  
	<?php } ?>
		
</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
	<div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
		<div class="Hui-tabNav-wp">
			<ul id="min_title_list" class="acrossTab cl">
				<li class="active">
					<span title="我的桌面" data-href="welcome.html">我的桌面</span>
					<em></em></li>
		</ul>
	</div>
		<div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
</div>
	<div id="iframe_box" class="Hui-article">
		<div class="show_iframe">
			<div style="display:none" class="loading"></div>
			<iframe scrolling="yes" frameborder="0" src="<?=url("item/welcome")?>"></iframe>
			
	</div>
</div>
</section>

<div class="contextMenu" id="Huiadminmenu">
	<ul>
		<li id="closethis">关闭当前 </li>
		<li id="closeall">关闭全部 </li>
</ul>
</div>


<!--请在下方写此页面业务相关的脚本-->

<script type="text/javascript">

/*个人信息*/
function myselfinfo(){
	layer_show("编辑",'<?=url("User/edit/".$_SESSION["userid"])?>',800,600);
}

/*资讯-添加*/
function article_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*图片-添加*/
function picture_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*产品-添加*/
function product_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*用户-添加*/
function member_add(title,url,w,h){
	layer_show(title,url,w,h);
}


</script> 
