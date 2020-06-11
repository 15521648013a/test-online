<style type="text/css"> 
.dragger{
user-select: none;
display: inline-block;
margin: 5px 10px 5px 0;
width: 100px;
background: #fff;
border: 1px solid #000;
border-radius: 40px;
text-align: center;
font-size: 20px;
cursor: move;
color: #fff;
background-color: #5a98de;
border-color: #5a98de;}
</style>

</head>
<body >
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 考试管理 <span class="c-gray en">&gt;</span> 试卷详情 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<form class="form form-horizontal" id="form-test-add">
	
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>试题名称：</label>
		<div class="formControls col-xs-8 col-sm-9" >
			<input type="text" class="input-text" value="" placeholder="点击编辑"" id="testNamew" name="testName">
			
			</div>
		</div>
    </div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>评卷方式</label>
		<div class="formControls col-xs-8 col-sm-9 skin-minimal">
			<div class="radio-box">
				<input name="correct" type="radio" id="sex-1" value="auto"   checked >
				<label for="sex-1">自动</label>
			</div>
			<div class="radio-box">
				<input type="radio" id="sex-2" value="manual" name="correct" >
				<label for="sex-2" >手动</label>
			</div>
		</div>
		<br/>
        <label> 主观题需要老师评卷后才能显示。</label>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-3">考试科目：</label>
        <div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
            <select class="select selectsubject" name="subject" size="1">
                <?php foreach($Subjects as $k=>$value){ ?>
                <option value='<?=$k+1?>'><?=$value['subjectname']?></option>
                <?php } ?>
            </select>
            </span> </div>
    </div>
	<div class="row cl">
        <label class="form-label col-xs-4 col-sm-3">选择考察知识点范围：</label>
        <div class="formControls col-xs-8 col-sm-9"> 
		<div class="selectpoint" style="width:auto;min-height: 30px;border-width: 1px;
    border-style: solid;
    border-color: #e6e6e6;">

		</div>  
            </div>
    </div>
    <div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>考试时长(分钟）：</label>
		<div class="formControls col-xs-8 col-sm-9" >
			<input type="text" class="input-text" value="" placeholder=""" id="time" name="_time">
			<div id="_answer" style="display:block">
			</div>
		</div>
		
    </div>
    <div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>试卷总分：</label>
		<div class="formControls col-xs-8 col-sm-9" id="answer">
			<input type="text" class="input-text" value="" placeholder=""" id="mark" name="_mark">
			<div id="_answer" style="display:block">
			</div>
		</div>
		
    </div>
    <div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>及格线：</label>
		<div class="formControls col-xs-8 col-sm-9" id="answer">
			<input type="text" class="input-text" style="width: 20%;" value="" placeholder=""" id="pass"" name="_pass">
			
		</div>
		
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>题目排序：</label>
		<div class="col-xs-8 col-sm-9" style="position:inherit    ">
		<div id="container" class="">
         <div class="queue" >
         <?php foreach($QuestionTypes as $k=>$questionType) { ?>
         <div class="dragger" value=<?=$questionType['question_type_id']?>> <?=$questionType['question_remark']?></div>
         <?php } ?>
         </div>	
         </div>	
		</div>	
	</div>
	
	<div id="lx"></div>
	
	<!--展示题型-->
	<?php foreach($QuestionTypes as $k=>$questionType) { ?>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span><?=$questionType['question_remark']?>:</label>
		<div class="formControls col-xs-8 col-sm-9" id="select">
			<label>共</label><input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value="" placeholder=""  name="sum[<?=$questionType['question_type_id']?>]"><label>题,每题</label>
			<input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value=""  name="singleEachScore[<?=$questionType['question_type_id']?>]"><label>分</label>
			<label>描述：</label><input type="text" class="input-text  radius"" style="width: 20%;margin: 0 2px;" value=""  name="detail[<?=$questionType['question_type_id']?>]">
		    <label>易</label>
            <input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value=""  name="easy[<?=$questionType['question_type_id']?>]">
            <label>中</label>
            <input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value=""  name="middle[<?=$questionType['question_type_id']?>]">
            <label>难</label>
			<input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value=""  name="hard[<?=$questionType['question_type_id']?>]">
		</div>		
	</div>	
	<?php } ?>	
    <div class="row cl" style="margin-top:10px" >
		<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
			<input class="btn btn-primary radius"  type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
		</div>
	</div>
	
	
		 
	</form>
</article>
<script type="text/javascript">
//初始化知识点选择框
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
				  
				 for(var i=0;i<(data.data).length;i++){
				  str += '<lable><input type="checkbox" value="'+data.data[i]['kownledgepointid']+'" name="subjectPoint[]"  >'+data.data[i]['kownledgepointname']+'</lable>';
				  //str += '<option value="'+data.data[i]['kownledgepointid']+'">'+data.data[i]['kownledgepointname']+'</option>';
				 }
				 $(".selectpoint").html(str);
			  }
		})
		//监听所选科目selectsubject
	$(".selectsubject").change(function(){
		var select=$(this).val();
		$.ajax({
			  type:"POST",
              url:'<?=url('Subject/selectPoint')?>',
              data:{select:select},
              dataType:"json",
              success:function(data){
				  var str = '';
				 for(var i=0;i<(data.data).length;i++){
					str += '<lable><input type="checkbox" value="'+data.data[i]['kownledgepointid']+'" name="subjectPoint[]"  >'+data.data[i]['kownledgepointname']+'</lable>';
				 }
				 $(".selectpoint").html(str);
			  }
		})
		
	});
var styleid='';
//var questionId=new Array('singlenum','multiplenum');//接受子窗口选择的试题号数组。
//定义接收从子窗口所选中的题目的数组。根据数据中的情况生成
var selects = [];var num=[],questionIds=[]; var QuestionTypes =[]; QuestionTypes=<?= json_encode($QuestionTypes)?>;
<?php foreach($QuestionTypes as $k=>$questionType) { ?>
  questionIds['<?=$questionType['question_type_id']?>']=[];  //接受选中的数据
  selects['<?=$questionType['question_type_id']?>']=[];
  num['<?=$questionType['question_type_id']?>']=1;//题号
  <?php } ?>

$(document).ready(function(){
   
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});      
});
		
//准备
$("#form-test-add").validate({
		rules:{
			testName:{
				required:true,
			},
			_time:{
				required:true,
				min:0
			},
			_mark:{
				required:true,
				min:0
			},
			_pass:{
				required:true,
				min:0
			},
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			var dragger=[];
            $(".queue").children(".dragger").each(function(_index,elem){
               dragger[_index]=$(this).attr("value");
            });
			var flag=1;var allscore=0;
			//判断体量是否合理
			<?php foreach($QuestionTypes as $k=>$questionType) { ?>
              sum= $("input[name='sum[<?=$questionType['question_type_id']?>]']").val();
			  easy= $("input[name='easy[<?=$questionType['question_type_id']?>]']").val();
			  middle= $("input[name='middle[<?=$questionType['question_type_id']?>]']").val();
			  hard= $("input[name='hard[<?=$questionType['question_type_id']?>]']").val();
			  if(isNaN($.trim(sum))){
				alert("包含非数字");
				$("input[name='sum[<?=$questionType['question_type_id']?>]']").focus();
			  }
			  if(isNaN($.trim(easy))){
				alert("包含非数字"+easy);
				$("input[name='easy[<?=$questionType['question_type_id']?>]']").focus();
			  }
			  if(isNaN($.trim(middle))){
				alert("包含非数字");
				$("input[name='middle[<?=$questionType['question_type_id']?>]']").focus();
			  }
			  if(isNaN($.trim(hard))){
				alert("包含非数字");
				$("input[name='hard[<?=$questionType['question_type_id']?>]']").focus();
			  }
			  
			  if(sum!=0)
			if(sum== (Number(easy)+Number(middle)+Number(hard)) )
			{  //有无分值
			if(!$("input[name='singleEachScore[<?=$questionType['question_type_id']?>]']").val())
             {  flag=0;alert("请填写分值"); $("input[name='singleEachScore[<?=$questionType['question_type_id']?>]']").focus();  return;}
			   if(isNaN($("input[name='singleEachScore[<?=$questionType['question_type_id']?>]']").val()))
              { flag=0;alert("请填写为数字");$("input[name='singleEachScore[<?=$questionType['question_type_id']?>]']").focus();return;}
			  allscore+=sum*Number($("input[name='singleEachScore[<?=$questionType['question_type_id']?>]']").val());
              
			}else{
				alert("题数不匹配。总=易+中+难");
				$("input[name='sum[<?=$questionType['question_type_id']?>]']").focus();flag=0;return;
			}
         
			<?php }?>
			  //最后判断总分是否正确
            if(allscore != Number($("#mark").val())){
				alert("总分与题型总分值不匹配");$("#mark").focus();flag=0;
			}
			if(flag)
			$(form).ajaxSubmit({
				url:'<?= url("Test/saveTestByRandom1/$classid")?>',
		        type:"post",
		        dataType:"json",//设置数据提交类型
		        data:{dragger:dragger},
		        success:function(data){
                if(data.status==0){
                    console.log(QuestionTypes);
                    for(var i=0;i<QuestionTypes.length;i++){
                        if(QuestionTypes[i]['question_type_id']==data.keyWord){
                            alert(QuestionTypes[i]['question_remark']+data.msg);
                            break;
                        }
                    }                  
                }else if(data.status==1){
			    	alert(data.msg);
                }
                          } 
			});
		}
	});
</script>
<script src="<?=STATIC_PATH ?>/static/jQuery多容器之间拖曳_files/drag.js"></script>
<script src="<?=STATIC_PATH ?>/static/jQuery多容器之间拖曳_files/main.js"></script>

