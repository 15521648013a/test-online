</head>
<body >

<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add"   method="post" >
		

	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>文本：</label>
		<div class="formControls col-xs-8 col-sm-9" id="title">
		<script type='text/plain' id='titleEditor' name='edit[title]' > </script>	
		</div>
		
	</div>
	
	
	<!--单，多选择题的选项-->
	<!--判断题处理-->
	<div id="ynStyle">
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>设置</label>
		<div class="formControls col-xs-8 col-sm-9 skin-minimal">
			<div class="radio-box">
				<input name="sex1" type="radio" id="sex-1" value="continue" checked>
				<label for="sex-1">新题框</label>
			</div>
		
            <div class="radio-box">
				<input type="radio" id="sex-3" name="sex1" value="xiao">
				<label for="sex-2">大题(题干)</label>
			</div>
		</div>
	</div>
    </div>
	<!--判断题处理-->

	
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
    

	
    function save1(){
		//给父页面添加上
        //alert($("[name = 'edit[title]']").val());
        //alert($("input[name='sex1']:checked").val());
        var value = $("input[name='sex1']:checked").val();var flag=0; var num1=1;
        if(value == "renew"){
		     
                  /*
            //获取父页面的插入点
            if(parent.$(".insert").length > 0){
                parent.$(".insert").each(function(){
      
                 if($(this).val()=="插入点"){
                     $(this).parent().after('<div class="question-each"  name="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:3%"><div class="question-name"><span class="index">2</span>'+$("[name = 'edit[title]']").val()+'</div><div class="question-type "style="color: black"><div><input type="text" placeholder="插入" class="insert" style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none;display: block "></div> </div></div>'); flag=1;
                     $(this).parent().parent(".question-type").children(".question-each").each(function(elem){
                         //alert("dsf");
                      $(this).children(".question-name").find("span.index").html(num1++);
                      })
                 }
               })
               if(!flag) parent.$(".question-wrap").append('<div class="question-type "style="color: black"><h>'+$("[name = 'edit[title]']").val()+'</h><input type="text" placeholder="插入" class="insert" style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; "> </div>');//不指定插入点，默认从最后插
               } else parent.$(".question-wrap").append('<div class="question-type" style="color: black">'+$("[name = 'edit[title]']").val()+'<input type="text" placeholder="插入" class="insert" style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; "> </div>');//不指定插入点，默认从最后插
       //设置样式
              parent.$(".question-type p").css("display","inline-block");
              */
              //找到处于active的类作为插入点
              parent.$(".active").after($("[name = 'edit[title]']").val());
              //重新编号
              
        } else if(value == 'xiao') {
            parent.$(".active").after('<div class="question-each dragger"  name="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:3%"><div class="question-name"><a class="btn delbtn" name="新题" style="height:30px; margin-right:5px" >删除</a><span class="index">2</span><div class="bigtitle">'+$("[name = 'edit[title]']").val()+'</div></div><div class="question-type "style="color: black"><p>&nbsp;</p> </div></div>');
              //重新编号
              parent.$(".active").parent(".question-type").children(".question-each").each(function(elem){
                         //alert("dsf");
                      $(this).children(".question-name").find("span.index").html((num1++)+'.');
                      })
             

        }else{  
            if(parent.$(".main-wrap").find(".active").length>0){
			if(parent.$(".active").attr("name")=="question-wrap"){
				parent.$(".active").after('<div class="question-wrap queue"  name="question-wrap" ><div class="question-type"><div class="Qinsert" style="margin-bottom:20px;margin-top:10px"><a class="btn titlebtn" name="新题" style="height:30px; margin-right:5px" >删除</a><a class="btn editbtn" style="height:30px; margin-right:5px" name=""> 编辑</a><h class="questionStyle"><span style="color:#333;vertical-align: top;display: inline-block;">'+  $("[name = 'edit[title]']").val()+ ' </span></h>' +      
                                    
									'</div><p>&nbsp;</p></div></div>');
			}else
            parent.$(".active").parents(".question-wrap").after('<div class="question-wrap queue"  name="question-wrap" ><div class="question-type"><div class="Qinsert" style="margin-bottom:20px;margin-top:10px"><a class="btn titlebtn" name="新题" style="height:30px; margin-right:5px" >删除</a><a class="btn editbtn" style="height:30px; margin-right:5px" name=""> 编辑</a><h class="questionStyle" contenteditable="false"><span style="color:#333;vertical-align: top;display: inline-block;">'+  $("[name = 'edit[title]']").val()+ ' </span></h>' +      
                                    
                                     '</div><p>&nbsp;</p></div></div>');} 
                    else{alert("esf");
                        //新生成的
                        parent.$(".main-wrap").append('<div class="question-wrap queue""  name="question-wrap" ><div class="question-type"><div class="Qinsert" style="margin-bottom:20px;margin-top:10px"><a class="btn titlebtn" name="新题" style="height:30px; margin-right:5px" >删除</a><a class="btn editbtn" style="height:30px; margin-right:5px" name=""> 编辑</a><h class="questionStyle"><span style="color:#333;vertical-align: top;display: inline-block;">'+  $("[name = 'edit[title]']").val()+ ' </span></h>' 
                                    + '</div><p class="active">&nbsp;</p></div></div>');

                    }                
                                      }
            parent.$(".question-wrap").find(".question-name .bigtitle").css("display","inline-block").css("color","#333").css("vertical-align","top");
            //parent.$(".question-wrap").find(".questionStyle p").css("display","inline-block");
        
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