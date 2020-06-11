


</head>
<body>


<div class="yourshow">
<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add">
		<!--提交id-->
		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>班级名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="adminName" name="name">
			</div>
		</div>
		
		
	
		<div class="row cl">
				<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>申请加入：</label>
				<div class="formControls col-xs-8 col-sm-9 skin-minimal">
					<div class="radio-box">
                	
						<input name="enterway" type="radio" id="sex-1" value="1" checked>
						<label for="sex-1">审批加入</label>
					</div>
					<div class="radio-box">
						<input type="radio" id="sex-2" value="0" name="enterway">
						<label for="sex-2">自由加入</label>
					</div>
				</div>
		</div>
     
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">简介：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea name="profile" cols="" rows="" class="textarea"  placeholder="说点什么...100个字符以内" dragonfly="true" onKeyUp="$.Huitextarealength(this,100)"></textarea>
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
			password:{
				required:true,
			},
			password2:{
				required:true,
				equalTo: "#password"
			}
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
	$("#form-admin-add").validate({
		rules:{
			name:{
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
				url: "<?=url('Class/add')?>" ,
				success: function(data){
					//alert("nj");
					layer.msg('添加成功!',{icon:1,time:1000},function(){
						parent.$(".reloadtable")[0].click();
					});
					

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
