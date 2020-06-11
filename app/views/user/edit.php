
<style type="text/css"> 
div.menu ul
{
    list-style:none; /* 去掉ul前面的符号 */
    margin: 10px; /* 与外界元素的距离为0 */
    padding: 0px; /* 与内部元素的距离为0 */
    width: auto; /* 宽度根据元素内容调整 */
}
div.menu ul li
{
    float:left; /* 向左漂移，将竖排变为横排 */
	width: auto;
}
div.menu ul li a, div.menu ul li a:visited
{
  
    border: 1px #4e667d solid; /* 边框 */
    color: black; /* 文字颜色 */
    display: block; /* 此元素将显示为块级元素，此元素前后会带有换行符 */
    line-height: 2em; /* 行高 */
    padding: 4px 10px; /* 内部填充的距离 */
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
<div class="menu">
<ul >
	<li>
		<a href="#" >用户资料</a>
	</li>

	<li>
		<a href="#" >修改密码</a>
	</li>

 	<li>
		<a href="#" >管理科目</a>
	</li>

</ul>
</div>

<div class="yourshow">
<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add">
		<!--提交id-->
		<input type='hidden' value="<?=$User['userid']?>" name="id">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>姓名：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<input type="text" class="input-text"  value="<?=$User["name"]?>" placeholder="" id="adminName" name="name">
			</div>
		</div>
		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>邮箱：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" placeholder="@" value='<?=$User["email"]?>' name="email" id="email">
			</div>
		</div>
		<!--性别-->
	
		<div class="row cl">
				<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>性别：</label>
				<div class="formControls col-xs-8 col-sm-9 skin-minimal">
					<div class="radio-box">
                	
						<input name="sex" type="radio" id="sex-1" value="男" checked>
						<label for="sex-1">男</label>
					</div>
					<div class="radio-box">
						<input type="radio" id="sex-2" value="女" name="sex">
						<label for="sex-2">女</label>
					</div>
				</div>
		</div>
        <!--显示性别-->
			<script>
                 sex = '<?= $User['sex']?>';
                 if(sex == '男')
                 {
                 $("input#sex-1").prop("checked","true");
                 }
                 else $("input#sex-2").prop("checked","true");
            </script>
		
			<!--根据session 判断是谁登录
			<div class="row cl">
				<label class="form-label col-xs-4 col-sm-3">启用状态：</label>
				<div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
					<select class="select" name="status" size="1" id="status">       
                        <option value="1"" >启用</option>
						<option value="0" >不启用</option> 
					</select>
					</span>
				</div>
            </div>
			-->
            <!--显示状态-->
			<script>
                 status = <?= $User['status']?>;
               //选中 角色
			   $(".select").val(status);
			  

            </script>
		<?php $sessionid=$_SESSION['role'];
		
		if($sessionid ==1)//管理员
		{
		?>
        <div class="row cl ">	
		<label class="form-label col-xs-4 col-sm-3">角色：</label>
		<div class="formControls col-xs-8 col-sm-9"> 
		<span class="select-box" style="width:150px;">
			<select class="select" name="role" size="1">
			<?php foreach($Role as $k=>$value){ ?>
				<option value="<?=$k?>"><?=$value?></option>
			
			<?php } ?>
			</select>
			</span> 
		</div>
	   </div>	
			<?php } ?>
	    <!--显示角色-->
			<script>
                 role = <?= $User['role']?>;
               //选中 角色
			   $(".select").val(role);
            </script>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">备注：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea name="" cols="" rows="" class="textarea"  placeholder="说点什么...100个字符以内" dragonfly="true" onKeyUp="$.Huitextarealength(this,100)"></textarea>
				<p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<input  id="submit" class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
			</div>
		</div>
		
		</form>
		</article>
</div>


	<div class="yourshow">

	<article class="page-container">
		<form class="form form-horizontal" id="passwordForm">
		<input type='hidden' value="<?=$User['userid']?>" name="id">
		<?php
		if($sessionid !=1)//管理员
		{
		?>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>旧密码：</label>
			<div class="formControls col-xs-8 col-sm-9">
			    <input type="password" class="input-text" autocomplete="off" value="<?=$User['password']?>"   id="old" hidden>
				<input type="password" class="input-text" autocomplete="off" value="" placeholder="密码"  name="oldPassword">
			</div>
		</div>
		<?php } ?>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>新密码：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="password" class="input-text" autocomplete="off" value="" placeholder="密码" id="password" name="password">
			</div>
		</div>

		<div class="row cl">
				<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>确认密码：</label>
				<div class="formControls col-xs-8 col-sm-9">
					<input type="password" class="input-text" autocomplete="off"  placeholder="确认新密码" id="password2" name="password2">
				</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<input  id="submit" class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
			</div>
		</div>
		</form>
		</article>
    </div>

<div class="yourshow">

<article class="page-container">
	<form class="form form-horizontal" id="subjectForm">
	<input type='hidden' value="<?=$User['userid']?>" name="id">
	<?php foreach($Subject as $k=>$value){ ?>
	<label ><input type="checkbox" value="<?=$value["subjectid"]?>" name="subject[]" ><?=$value["subjectname"]?></label>
	<?php } ?>
	<div class="row cl">
		<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
			<input  id="submit" class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
		</div>
	</div>
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
		$("li").last().hide();
		//老师角色，显示分配科目
		var role = 	<?=$User['role']?>;
		var Role = 	<?=$_SESSION['role']?>;
		if(Role==1){
       if(role != 3){
		   $("li").last().show();
	   }
	   console.log(<?=json_encode($TeacherSubject)?>);
	   var TeacherSubject=<?=json_encode($TeacherSubject)?>;
	   for(var i=0;i<TeacherSubject.length;i++){
		   var id= TeacherSubject[i]['subjectid'];
		   $("input[name='subject[]']").each(function(){
		//	alert(id);
			   if($(this).val()==id)
			   $(this).attr("checked",true);
			   //alert(id+$(this).val());
		   });
	   }
		}
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
		increaseArea: '20%'
	});
	$("#passwordForm").validate({
		rules:{
			oldPassword:{
				equalTo:"#old",
			
			},
			password:{
				required:true,
			},
			password2:{
				required:true,
				equalTo: "#password"
			}
		},
		messages:{
			oldPassword:{
          required: "请输入旧密码",
          minlength: "密码长度不能小于 6 个字母",
          equalTo: "旧密码错误"
            },
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			
			$(form).ajaxSubmit({
				type: 'post',
				url: "<?=url('User/editPassword')?>" ,//编辑密码
				success: function(data){
					
					layer.msg('修改成功!',{icon:1,time:1000});
					parent.$('#gh').click();
				},
                error: function(XmlHttpRequest, textStatus, errorThrown){
					layer.msg('error!',{icon:1,time:1000});
				}
			});
			//var index = parent.layer.getFrameIndex(window.name);
			
			//parent.layer.close(index);
		}
	});
	//科目
	$("#subjectForm").validate({
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			
			$(form).ajaxSubmit({
				type: 'post',
				url: "<?=url('User/editSubject')?>" ,//编辑密码
				dataType:'json',
				success: function(data){
					
					layer.msg('修改成功!',{icon:1,time:1000});
					parent.$('#gh').click();
				},
                error: function(XmlHttpRequest, textStatus, errorThrown){
					layer.msg('error!',{icon:1,time:1000});
				}
			});
			//var index = parent.layer.getFrameIndex(window.name);
			
			//parent.layer.close(index);
		}
	});
	//
	$("#form-admin-add").validate({
		rules:{
			adminName:{
				required:true,
				minlength:4,
				maxlength:16
			},
			password:{
				required:true,
			},
			password2:{
				required:true,
				equalTo: "#password"
			},
			sex:{
				required:true,
			},
			phone:{
				required:true,
				isPhone:true,
			},
			email:{
				required:true,
				email:true,
			},
			adminRole:{
				required:true,
			},
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			
			$(form).ajaxSubmit({
				type: 'post',
				url: "<?=url('User/saveEdit')?>" ,
				dataType:'json',
				success: function(data){
					layer.msg(data.msg,{icon:1,time:1000});
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



        $("#submit1").on("click", function(event){
            $.ajax({
				type: "POST",
				url: "<?=url('User/saveEdit')?>",
				data: $("#form-admin-add").serialize(),
				dataType: "json",
				success: function(data){
				    if (data.status == 1) {
						alert(data.message);

					} else {
                        alert(data.data);

					}
				}
			});

		})



	})
</script>
