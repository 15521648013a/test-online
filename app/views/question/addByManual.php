
</head>
<body >
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 角色管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add"   method="post" >
		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">题型：</label>
			<div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
				<select class="select selectquestion" name="questionType" size="1" >
				<?php foreach($questionsTypes as $k=>$value) { ?>
					<option value="<?=$value['question_type_id']?>"><?=$value['question_remark']?></option>
					<?php }?>>
				</select>
				</span> </div>
		</div>
		<script>
		
				</script>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>题目：</label>
		<div class="formControls col-xs-8 col-sm-9" id="title">
		<script type='text/plain' id='titleEditor' name='edit[title]' > </script>	
		</div>
		
	</div>
	
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3">等级：</label>
		<div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
			<select class="select" name="level" size="1">
				<option value="简单">简单</option>
				<option value="容易">容易</option>
				<option value="困难">困难</option>
			</select>
			</span> </div>
	</div>
	<!--单，多选择题的选项-->
	<div id="selectStyle">
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>选项个数</label>
		<div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
			<select class="select selectoption" name="adminRole" size="1">
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
			</span> <input type="button" class="btn " value="添加" onclick="add();"></div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>选项：</label>
		<div class="formControls col-xs-8 col-sm-9" id="select">
		</div>		
	</div>
    </div>
	<!--单，多选择题的选项-->
	<!--判断题处理-->
	<div id="ynStyle">
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>设置选项</label>
		<div class="formControls col-xs-8 col-sm-9 skin-minimal">
			<div class="radio-box">
				<input name="sex1" type="radio" id="sex-11" >
				<label ><input type="text" class="input-text" value="" placeholder="对" id="" name="name[]"></label>
			</div>
			<div class="radio-box">
				<input type="radio" id="sex-21" name="sex1" >
				<label ><input type="text" class="input-text" value="" placeholder="错" id="" name="name[]"></label>
			</div>
		</div>
	</div>
    </div>
	<!--判断题处理-->
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>答案：</label>
		<div class="formControls col-xs-8 col-sm-9" id="answer">
		<script type='text/plain'  id='answerEditor' name='edit[answer]' >  </script>
		</div>
		
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>解析：</label>
		<div class="formControls col-xs-8 col-sm-9">
		<script type='text/plain'  id='analysisEditor' name='edit[analysis]' >  </script>
		</div>
	</div>
	<div class="row cl">
		<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
			<input class="btn btn-primary radius"  onclick="save1();return false;" id="sdf" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
		</div>
	</div>
	</form>
	

</article>




<script type="text/javascript">
$("#content").html("");
$("#ynStyle").hide();//默认隐藏


$(document).ready(function(){
   
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	
	$("#form-admin-add").validate({
		rules:{
			adminName:{
				required:true,
				minlength:4,
				maxlength:16
			},
			titleEditor:{
                required:true,
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
				url: "xxxxxxx" ,
				success: function(data){
					layer.msg('添加成功!',{icon:1,time:1000});
				},
                error: function(XmlHttpRequest, textStatus, errorThrown){
					layer.msg('error!',{icon:1,time:1000});
				}
			});
			var index = parent.layer.getFrameIndex(window.name);
			parent.$('.btn-refresh').click();
			parent.layer.close(index);
		}
	});

     
	
});
    $(".selectoption").change(function(){
		    if($.trim($("#select").html())){//不空，已经有选项，要保留
			alert("不空");
              var number = parseInt( $(this).val());//需要生成number 个文本框
			  var inputnum=$("input[name='input[]']").length;//当前已有文本款的个数
			  
			  if(number<inputnum){
				  //需要截断
				  var count = inputnum - number;//删除的个数
				  var start = number-1;//下标从0开始
				   alert(count+"m"+start);
				  for(var i=0;i<count;i++,start++)
                   $("input[name='input[]']").eq(start).parent().html('');//清空
                   //alert("删除第"+start);
			  }
			  else{
				  //需要添加
				  var need = number - inputnum;
				  for(var i=0;i<need;i++){
					  //模拟点击事件
					  add();
				  }
			  }
			}
           else{
			   //alert($(this).val());
    		for(var i=0;i<$(this).val();i++){ 
    		$("#select").append('<div ><a class="btn delbtn"   style="height:30px; margin-right:5px">删除</a><input type="text" style="height:30px; position:absolute"; class="input-text"  value="" placeholder="点击编辑" name="input[]">'+
    		'<div  name="newdiv"></div><div style="height:20px"></div><div>');
			}
    		}
		 });

		$(document).on('click',".delbtn",function(){

        $(this).parent().html("");//this指按钮本身；
		var inputnum=$("input[name='input[]']").length;
		$(".selectoption").val(inputnum);
          //alert("lalaal");

		})
		 //监听所选的题型
    $(".selectquestion").change(function(){
		//alert($(this).val());
		if($(this).val()==3){
			//隐藏其他
			$("#selectStyle").hide();
            $("#ynStyle").show();
		}else{
			$("#selectStyle").show();
             $("#ynStyle").hide();
		}
    });
    var style = <?=$styleid?>;
		$(".selectquestion").val(style);
		$(".selectquestion").change();
	    ///$(".selectquestion").trigger("change");
		$(".selectquestion").attr("disabled",true);
		
    $(document).on('click', '.input-text', function() {
		if($(this).attr("name")=="input[]")
				 ///var jj=$(this).attr("placeholder");
				 //else var jj=$(this).attr("name");
				 var editorDiv = $(this).parent().find("div[name='newdiv']");
				 if(document.getElementById("editor")){
					UE.getEditor('editor').destroy();//删除文本器
					$("#editor").remove();
					$("._save").remove();
					$("._close").remove();
				 }
        		 if(!document.getElementById("editor")){
					// alert(jj.html());
					//alert(jj);//判断富文本是否存在，不存在才创建,存在则关闭其他的
        		 $(editorDiv).html("<script type='text/plain' name='newcontent' id='editor' name='content' >"+$(this).val()+" <\/script>"+
        		'<input type="submit"value="保存" class="_save btn" name="" onclick="return false;">'+
				'<input type="submit"value="关闭" class="_close btn" onclick="close1();return false;">');
				showEdit('editor');
				//click_input=jj;//保存当前的input的name
				//
				 }
		  });
		  
    $(document).on('click',"._save",function(){
	   //获取父类div的id;
	      //var id= $(this).parent().attr("id");
		  //var name=$(this).parent().attr("name"); 
			if($.trim($("[name='newcontent']").val()).length!=0)//不空才保存
			var parent =  $(this).parent().parent().find("input[name='input[]']");
		    $(parent).val($("[name='newcontent']").val());  	 
           
            close1();//关闭文本器
			$(this).parent().html('');
	});

	function add(){
		$("#select").append('<div ><a class="btn delbtn"   style="height:30px; margin-right:5px">删除</a><input type="text" style="height:30px; position:absolute"; class="input-text"  value="" placeholder="点击编辑" name="input[]">'+
    		'<div  name="newdiv"></div><div style="height:20px"></div><div>');
	}

    function close1(){
    	UE.getEditor('editor').destroy();//删除文本器
    	$("#editor").remove();
    	$("[name='newdiv']").html("");//关闭新生成的文本框
    }
    function save1(){
        //向父页面发送数据
        var title = $("[name='edit[title]']").val() ;
        //alert(title);
        var Options={};var newQuestion ={};
        newQuestion['title']=title;
        if($(".selectquestion").val()==3){
            $("input[name='name[]'").each(function(data){
			Options[data]=$(this).val();
			///alert($(this).val()+data);
		 })
		 }else
		 $("input[name='input[]'").each(function(data){
			Options[data]=$(this).val();
			///alert($(this).val()+data);
         })
         layer.msg('添加成功');alert('添加成功');
         //alert(JSON.stringify(Options));
         newQuestion['options']=JSON.stringify(Options);
		 newQuestion['style']=<?=$styleid?>;
         parent.aa(<?=$styleid?>,newQuestion);
         
         var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index); //再执行关闭子窗口   
        // parent.aa();
        /*
		//将选项拼接成json字符串
		var Options={};
		
		// Options[1]="sfdf";
		 //[2]="sfdf";
		 if($(".selectquestion").val()==3){
            $("input[name='name[]'").each(function(data){
			Options[data]=$(this).val();
			///alert($(this).val()+data);
		 })
		 }else
		 $("input[name='input[]'").each(function(data){
			Options[data]=$(this).val();
			///alert($(this).val()+data);
		 })
		
//console.log(JSON.stringify(Options));
        //if(!$.trim($("#titleEditor").val()))  alert("题目空");
      var oparate={
              type:"POST",//提交方式
              url:"<?=url('Question/add')?>",//提交的地址
              data:{'options':JSON.stringify(Options)},
              dataType:"json",//设置数据提交类型
              success:function(data){
    			//alert(data.status);
                if(data.status)
    			{//alert(data.data);
    				parent.layer.msg("更新成功!", {time: 1000}, function () {
                                //重新加载父页面
    						   parent.location.reload();
    						   //window.location.href = document.referrer;  
                            });
                            return;
                }
              else {  alert("没有数据");
                   }
                                     }         
	  }
	  $("form").ajaxSubmit(oparate);//额外添加数据
	  */
	  ///$(this).val()valert("dsfsdf");
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


   var str=['titleEditor','answerEditor','analysisEditor'];
   for(var i=0;i< str.length;i++)
   showEdit(str[i]);//启动编辑器

</script>		

<!--/请在上方写此页面业务相关的脚本-->
