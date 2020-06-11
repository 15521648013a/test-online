<?php //当前的登录id
      
       // $_SESSION["userid"];
        ?>
<style type="text/css"> 

</style>

</head>
<body>

<div class="yourshow">
<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add">
		<!--提交id-->
		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>时间：</label>
			<div class="formControls col-xs-8 col-sm-9" style="display:inline">
                <input type="text" class="input-text" style="width:80%" value="" placeholder="" id="inputUser" name="inputUser">
                <input  id="search" class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;搜索&nbsp;&nbsp;">
			</div>
		</div>
        </form>

        <form class="form form-horizontal" id="form-add" >
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>发送方：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="namelab"> <?=$Messages['senderName'];?>  </label> 
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>接收方：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="countlab" >  <?=$Messages['receiverName'];?> </label> 
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
			<label class="form-label col-xs-4 col-sm-3">主题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<textarea name="content" cols="" rows="" class="textarea"  placeholder="说点什么...100个字符以内" dragonfly="true" onKeyUp="$.Huitextarealength(this,100)"><?=$Messages['content'];?>
                </textarea>
				<p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<input  id="submit" class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;关闭&nbsp;&nbsp;">
			</div>
        </div>
        
        <input type="hidden" name="receiver" id="receiver" value="">
        <input type="hidden" name="sender" id="sender" value="<?=$_SESSION["userid"]?>">
        <input type="hidden" name="messageType"  value="1">
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
    
        // $("#form-add").hide();
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
