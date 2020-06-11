</head>
<body >
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 试题管理 <span class="c-gray en">&gt;</span> 编辑 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add"  action="../loginController.php?action=edit" method="post" >
		<input type="hidden" value="<?=$Question['questionNo']?>" name='id'>
		<!--数据库中读入-->
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">题型：</label>
			<div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;" >
				<select class="select selectquestion" name="questionType" size="1" disabled >
					<?php foreach($questionsTypes as $k=>$value) { ?>
					<option value="<?=$value['question_type_id']?>"><?=$value['question_remark']?></option>
					<?php }?>
				</select>
				</span> </div>
		</div>
		<div class="row cl">
        <label class="form-label col-xs-4 col-sm-3">科目：</label>
        <div class="formControls col-xs-8 col-sm-9">
		 <span class="select-box" style="width:150px;">
            <select class="select selectsubject" name="subject" size="1">
                <?php foreach($Subjects as $k=>$value){ ?>
                <option value='<?=$k+1?>'><?=$value['subjectname']?></option>
                <?php } ?>
            </select>
            </span> </div>
    </div>
    <script> 
	 $(".selectsubject").val(<?=$Question["subject"]?>);
	</script>
	<div class="row cl">
        <label class="form-label col-xs-4 col-sm-3">知识点：</label>
        <div class="formControls col-xs-8 col-sm-9"> 
		<div class="selectpoint" style="width:auto;min-height: 30px;border-width: 1px;
    border-style: solid;
    border-color: #e6e6e6;">

		</div>  
            </div>
    </div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>题目：</label>
		<div class="formControls col-xs-8 col-sm-9" id="title">
			<script type='text/plain' id='titleEditor' name='edit[title]' > <?=$Question['title']?> </script>
		</div>
		
	</div>
	
	<div id="lx"></div>
	
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3">等级：</label>
		<div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
			<select class="select" name="level" size="1">
				<option value="易">易</option>
				<option value="中">中</option>
				<option value="难">难</option>
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
			</span> <input type="button" class="btn " value="添加" onclick="add();">
		</div>
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>选项内容:</label>
		<div class="formControls col-xs-8 col-sm-9" id="select">
		       <?php if($Question['style']!=5&&$Question['style']!=6){
			     	foreach(json_decode($Question['options']) as $k=>$value){  ?>
				<div>
				<?=chr(65+$k)?>:	
				<a class="btn delbtn" style="height:30px; width:auto;margin-right:5px">删除</a>
			       <input type="text" style="height:30px; position:absolute"; class="input-text"  value='<?=$value?>'  name="input[]">
    	           <div  name="newdiv"></div>
		           <div style="height:20px"></div>   
				</div>	
					 <?php }}?>	
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
				<label >对</label>
			</div>
			<div class="radio-box">
				<input type="radio" id="sex-21" name="sex1" >
				<label >错</label>
			</div>
			
      
	      	</div>
	      </div>
    </div>
	<!--判断题处理-->
	<!--大题处理-->


<!-- 客观题-->
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>答案：</label>
		<div class="formControls col-xs-8 col-sm-9" id="answer">
		<script type='text/plain'  id='answerEditor' name='edit[answer]' > <?=$Question['answer']?> </script>
		</div>
		
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3">解析：</label>
		<div class="formControls col-xs-8 col-sm-9">
		<script type='text/plain'  id='analysisEditor' name='edit[analysis]' > <?=$Question['analysis']?> </script>
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
//选中题型-->
		
style = <?= $Question['style']?>;
$(".selectquestion").val(style);

$("#content").html("");
if(style==3){
			//隐藏其他
			$("#selectStyle").hide();
            $("#ynStyle").show();
		}else{
			$("#selectStyle").show();
             $("#ynStyle").hide();
		}

$.ajax({
	  type:"POST",//提交方式
      url:'<?=url('Subject/selectPoint')?>',
      data:{select:$(".selectsubject").val()},
      dataType:"json",//设置数据提交类型
      success:function(data){
		  //selectpoint
		  var str = '';
		  //alert("sdf");
		  console.log(data.data);
		  var KeyPoint=[];
		  KeyPoint= <?=json_encode($KeyPoint)?>;
		 for(var i=0;i<(data.data).length;i++){
		
		  str += '<lable style="margin-right: 10px;"><input type="checkbox" value="'+data.data[i]['kownledgepointid']+'" name="subjectPoint[]"  >'+data.data[i]['kownledgepointname']+'</lable>';
		  //str += '<option value="'+data.data[i]['kownledgepointid']+'">'+data.data[i]['kownledgepointname']+'</option>';
		 }
		 $(".selectpoint").html(str);
		 //再默认勾选
		 $("[name='subjectPoint[]']").each(function(){
              num= $(this).val();//alert(num);
             if($.inArray(num,KeyPoint)!=-1){
				  //选择
				 $(this).attr("checked",true);
			  }
		 });
		
	  }
})
//默认勾选


$(document).ready(function(){
   
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	


     
	
});
    $(".selectoption").change(function(){
		    if($.trim($("#select").html())){//不空，已经有选项，要保留
			//alert("不空");
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
			   alert($(this).val());
    		for(var i=0;i<$(this).val();i++){ 
    		$("#select").append('<div ><a class="btn delbtn"   style="height:30px; margin-right:5px">删除</a><input type="text" style="height:30px; position:absolute"; class="input-text"  value="" placeholder="点击编辑" name="input[]">'+
    		'<div  name="newdiv"></div><div style="height:20px"></div></div>');
			}
    		}
		 });
	//监听所选科目selectsubject
	$(".selectsubject").change(function(){
		var select=$(this).val();
		$.ajax({
			  type:"POST",
              url:'<?=url('Subject/selectPoint')?>',
              data:{select:select},
              dataType:"json",
              success:function(data){
				  var str = ''; var KeyPoint=[];
		  KeyPoint= <?=json_encode($KeyPoint)?>;
				 for(var i=0;i<(data.data).length;i++){
					str += '<lable><input type="checkbox" value="'+data.data[i]['kownledgepointid']+'" name="subjectPoint[]"  >'+data.data[i]['kownledgepointname']+'</lable>';
				 }
				 $(".selectpoint").html(str);
				 //再默认勾选
		     $("[name='subjectPoint[]']").each(function(){
                  num= $(this).val();//alert(num);
                 if($.inArray(num,KeyPoint)!=-1){
		    		  //选择
		    		 $(this).attr("checked",true);
			  }
		 });
			  }
		})
		
	});
		$(document).on('click',".delbtn",function(){

        $(this).parent().html("");//this指按钮本身；
		var inputnum=$("input[name='input[]']").length;
		$(".selectoption").val(inputnum);
          //alert("lalaal");

		})
		 //监听所选的题型
    $(".selectquestion").change(function(){
		 alert($(this).val());
		if($(this).val()==3){
			//隐藏其他
			$("#selectStyle").hide();
            $("#ynStyle").show();
		}else{
			$("#selectStyle").show();
             $("#ynStyle").hide();
		}
    });
    
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
		


      var oparate={
              type:"POST",//提交方式
              url:"<?=url('Question/save')?>",//提交的地址
              data:{'options':JSON.stringify(Options),questionType:style},
              dataType:"json",//设置数据提交类型
              success:function(data){
    			//alert(data.status);
                if(data.status)
    			{//alert(data.data);
    				parent.layer.msg("更新成功!", {time: 1000}, function () {
                                //重新加载父页面
    						   parent.$(".reload1")[0].click();
							   //window.location.href = document.referrer; 
							   var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                               parent.layer.close(index); //再执行关闭子窗口   
                            });
                            return;
                }
              else {  alert("没有数据");
                   }
                                     }         
	  }
	  $("form").ajaxSubmit(oparate);//额外添加数据
	  
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
	})
   }
   //showEdit('titeEditor');
   function ready(){
   var str=['titleEditor','analysisEditor','answerEditor'];
   for(var i=0;i< str.length;i++)
   showEdit(str[i]);
   //alert(str[0]);
   }
   ready();



   

  
  //当前有没有这种题型
function isExit(name){

            //根据name 
            if($('#singleselect').length>0){

			}else{
                var str='<div class="main"> <div class="main-wrap">	<div class="question-wrap" id="singleselect" name="1" > '+    
                        ' <div class="question-type"><a class="btn titlebtn" name="单选题" style="height:30px; margin-right:5px" >删除</a>单选题<span> 编辑题目  </span></div>' +      
                        ' </div></div></div>';	
                $(".main-wrap").append(str);

            }
			$("#HatQuestion #select").append(str);
			//设置样式
			$(".question-wrap").css("background-color",'rgb(252, 252, 252)').
			css("border-width",'1px').
			css(" border-style",'solid').
			css(" border-color",'rgb(224, 224, 224)').
			css(" border-image",'initial').css("padding",'20px').css("transition",'all 0.25s ease-in 0s');
        
    
   
          
}
</script>		

<!--/请在上方写此页面业务相关的脚本-->
