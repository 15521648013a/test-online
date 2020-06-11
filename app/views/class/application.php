<?php 


//echo  date('Y-m-d H:i:s');//当前时间

?>
</head>
<body>

<div class="yourshow">
<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add">
		<!--提交id-->
		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>班级编号：</label>
			<div class="formControls col-xs-8 col-sm-9" style="display:inline">
            <span class="c-red">*</span>
				<label style="margin-top:2cm;" id="namelab">  <?=$Class["classid"]?> </label> 
			</div>
		</div>
       
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>班级名称：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="namelab">   <?=$Class["classname"]?> </label> 
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>创建时间：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="countlab" >   <?= date("Y-m-d H:i",$Class["createtime"])?> </label> 
			</div>
		</div>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>创建者：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="countlab" > <?=$creator?>   </label> 
			</div>
		</div>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>接纳新成员方式：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="sexlab"> 审批加入   </label> 
			</div>
		</div>
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">申请理由：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea name="content" cols="" rows="" class="textarea"  placeholder="说点什么...100个字符以内" dragonfly="true" onKeyUp="$.Huitextarealength(this,100)"></textarea>
				<p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<input  id="submit" class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;申请&nbsp;&nbsp;">
			</div>
        </div>
        
        <input type="hidden" name="classid" id="" value="<?=$Class["classid"]?>">
        <input type="hidden" name="sender" id="" value="<?=$_SESSION["userid"]?>">
        <input type="hidden" name="receiver" id="" value="<?=$Class["creator"]?>">
        <input type="hidden" name="messageType"  value="1">
		</form>
		</article>
</div>


	
	
 

		

	

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
				url: "<?=url('Class/appEnterClass')?>" ,//编辑密码
				dataType:'json',
				success: function(data){
                    
					if(data.data){
						parent.layer.msg("已申请", {time: 1000}, function () {
                                 
							  
								});
					}
                   
				},
                error: function(XmlHttpRequest, textStatus, errorThrown){
					layer.msg('error!',{icon:1,time:1000});
				}
			});
			//var index = parent.layer.getFrameIndex(window.name);
			
			//parent.layer.close(index);
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
