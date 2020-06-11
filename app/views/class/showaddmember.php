<?php //当前的登录id
    
       // $_SESSION["userid"];
        ?>
<style type="text/css"> 
div.menu ul
{
    list-style:none; /* 去掉ul前面的符号 */
    margin: 0px; /* 与外界元素的距离为0 */
    padding: 0px; /* 与内部元素的距离为0 */
    width: auto; /* 宽度根据元素内容调整 */
}
div.menu ul li
{
    float:left; /* 向左漂移，将竖排变为横排 */
	width: 25%;
}
div.menu ul li a, div.menu ul li a:visited
{
  
    border: 1px #4e667d solid; /* 边框 */
    color: black; /* 文字颜色 */
    display: block; /* 此元素将显示为块级元素，此元素前后会带有换行符 */
    line-height: 2em; /* 行高 */
    padding: 4px 20px; /* 内部填充的距离 */
    text-decoration: none; /* 不显示超链接下划线 */
    white-space: nowrap; /* 对于文本内的空白处，不会换行，文本会在在同一行上继续，直到遇到 <br> 标签为止。 */
}
/* 所有class为menu的div中的ul中的a样式(鼠标移动到元素中的样式) */
div.menu ul li a:hover
{
    background-color: #bfcbd6; /* 背景色 */
    color: #465c71; /* 文字颜色 */
    text-decoration: none; /* 不显示超链接下划线 */
}
/* 所有class为menu的div中的ul中的a样式(鼠标点击元素时的样式) */
div.menu ul li a:active
{
    background-color: #465c71; /* 背景色 */
    color: #cfdbe6; /* 文字颜色 */
    text-decoration: none; /* 不显示超链接下划线 */
}
</style>

</head>
<body>

<div class="yourshow">
<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add">
		<!--提交id-->
		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red"></span></label>
			<div class="formControls col-xs-8 col-sm-9" style="display:inline">
                <input type="text" class="input-text" style="width:80%" value="" placeholder="输入用户名" id="inputUser" name="inputUser">
                <input  id="search" class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;搜索&nbsp;&nbsp;">
			</div>
		</div>
        </form>

        <form class="form form-horizontal" id="form-add" >
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>用户名：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="namelab">   </label> 
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>账号：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="countlab" >   </label> 
			</div>
		</div>
        
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>性别：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="sexlab">   </label> 
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">邀请理由：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea name="content" cols="" rows="" class="textarea"  placeholder="说点什么...100个字符以内" dragonfly="true" onKeyUp="$.Huitextarealength(this,100)"></textarea>
				<p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<input  id="submit" class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;邀请&nbsp;&nbsp;">
			</div>
        </div>
        <input type="hidden" name="classid" value="<?=$classid?>">
        <input type="hidden" name="receiver" id="receiver" value="">
        <input type="hidden" name="sender" id="sender" value="<?=$_SESSION["userid"]?>">
        <input type="hidden" name="messageType"  value="3">
		</form>
		</article>
</div>


	
	 

<script>
        $('.menu ul li').click(function(){
		
            var index = $(this).index(); //获取当前操作下标  ,0,1,2...
			//alert(index);
			$(".yourshow").hide(); //隐藏所有
            $('.yourshow').eq(index).show(); //显示当前
           // $('.yourshow').eq(index).siblings().hide(); //其他隐藏
        });

		
        $(".yourshow").hide(); //隐藏所有
        $('.yourshow:first').show(); //默认显示第一项
    
    $("#form-add").hide();
    </script>
 
 

		

	

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="<?=STATIC_PATH ?>/static/lib/jquery.validation/1.14.0/jquery.validate.js"></script> 
<script type="text/javascript" src="<?=STATIC_PATH ?>/static/lib/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="<?=STATIC_PATH ?>/static/lib/jquery.validation/1.14.0/messages_zh.js"></script> 
<script type="text/javascript">
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '10%'
	});
	$("#form-admin-add").validate({
		rules:{
			inputUser:{
				required:true,
			},
			
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			
			$(form).ajaxSubmit({
				type: 'post',
				url: "<?=url('User/seacherUserById')?>" ,//编辑密码
				success: function(data){
                    data=$.parseJSON( data);
					if(data.data!= false){
                        //alert(data.data['username']);
                       // console.log(data."data");
                      
                    var user = data.data;
                    //console.log($.parseJSON( data));
                        $("#namelab").html(user.name);
                        $("#countlab").html(user.userid);
                        $("#sexlab").html(user.sex);
                        $("#receiver").val(user.userid);
                        $("#form-add").show();
					
                    }else layer.msg('没有改账户!',{icon:1,time:1000});
				},
                error: function(XmlHttpRequest, textStatus, errorThrown){
					layer.msg('error!',{icon:1,time:1000});
				}
			});
			//var index = parent.layer.getFrameIndex(window.name);
			
			//parent.layer.close(index);
		}
	});
	$("#form-add").validate({
		rules:{
			
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			
			$(form).ajaxSubmit({
				type: 'post',
				url: "<?=url('Message/push')?>" ,
				success: function(data){
					//alert("nj");
					layer.msg('添加成功!',{icon:1,time:1000});
					parent.$('#gh').click();

				},
                error: function(XmlHttpRequest, textStatus, errorThrown){
					layer.msg('error!',{icon:1,time:1000});
				}
			});
			
		}
	});
});
</script> 
<!--/请在上方写此页面业务相关的脚本-->
<script>

	$(function(){

	    $("form").children().change(function(){
	        $("#submit").removeClass('disabled');
		});



     



	})
</script>
