</head>
<body >
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 角色管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add"   method="post" >
		

	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>文本：</label>
		<div class="formControls col-xs-8 col-sm-9" id="title">
		<script type='text/plain' id='titleEditor' name='edit[title]' > </script>	
		</div>
		
	</div>
	
	
	
	<div class="row cl">
		<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
			<input class="btn btn-primary radius"  onclick="save1();return false;" id="sdf" type="submit" value="&nbsp;&nbsp;确认&nbsp;&nbsp;">
		</div>
	</div>
	</form>
	

</article>




<script type="text/javascript">
$("#content").html("");
$("#ynStyle").show();//默认隐藏
$("#HatQuestion").hide();
  //var num=1;//题号
$(document).ready(function(){
   
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	
});
    
$("[name = 'edit[title]']").val(parent.title);
	
    function save1(){
        var title= $("[name = 'edit[title]']").val();
            parent.titledom.html(title);
           parent.titledom.find("p").css("display","inline-block");
        
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index); //再执行关闭子窗口  
        }
        
</script> 
<script src="<?=STATIC_PATH ?>/static/ext/ueditor/ueditor.config.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=STATIC_PATH ?>/static/ext/ueditor/ueditor.all.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

function showEdit(id){
	UE.getEditor(id, {
		toolbars: [
			[
				'bold', //加粗
				'indent', //首行缩进
				'snapscreen', //截图
				'italic', //斜体
				'underline', //下划线
				'strikethrough', //删除线
				'subscript', //下标
				'selectall', //全选
				'horizontal', //分隔线
				'removeformat', //清除格式
				'unlink', //取消链接
				'fontfamily', //字体
				'fontsize', //字号
				'paragraph', //段落格式
				'simpleupload', //单图上传
				'edittable', //表格属性
				'link', //超链接
				'emotion', //表情
				'spechars', //特殊字符
				'searchreplace', //查询替换
				'map', //Baidu地图
				'justifyleft', //居左对齐
				'justifyright', //居右对齐
				'justifycenter', //居中对齐
				'justifyjustify', //两端对齐
				'forecolor', //字体颜色
				'backcolor', //背景色
				'template', //模板
			]
		],
		initialFrameHeight:250
	})}


    var str=['titleEditor'];
   for(var i=0;i< str.length;i++)
   showEdit(str[i]);//启动编辑器
    </script>