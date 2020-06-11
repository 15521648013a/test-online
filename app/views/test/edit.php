</head>
<body >
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 考试管理 <span class="c-gray en">&gt;</span> 试卷详情 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<article class="page-container">
	<form class="form form-horizontal" id="form-admin-add"  action="../loginController.php?action=save" method="post" >
		<input type="hidden" value='<?=$Tests['testid']?>' name="testid">
		
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>试题名称：</label>
		<div class="formControls col-xs-8 col-sm-9" id="title">
			<input type="text" class="input-text" value="<?=$Tests['testName']?>" placeholder="点击编辑"" id="title" name="testName">
			<div id="_content" style="display:block">
			</div>
		</div>
    </div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>评卷方式</label>
		<div class="formControls col-xs-8 col-sm-9 skin-minimal">
			<div class="radio-box">
				<input name="correct" type="radio" id="sex-1" value="auto" >
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
            <select class="select" name="subject" size="1">
                <?php foreach($Subjects as $k=>$value){ ?>
                <option value='<?=$value['subjectid']?>'><?=$value['subjectname']?></option>
                <?php } ?>
            </select>
            </span> </div>
    </div>
    <div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>考试时长：</label>
		<div class="formControls col-xs-8 col-sm-9" >
			<input type="text" class="input-text" style="width: 20%;" value="<?=$Tests['time']?>" placeholder=""" id="time" name="_time"><label>分钟</label>
			<div id="_answer" style="display:block">
			</div>
		</div>
		
    </div>
    <div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>试卷总分：</label>
		<div class="formControls col-xs-8 col-sm-9" id="answer">
			<input type="text" class="input-text" style="width: 20%;" value="<?=$Tests['totalscore']?>" placeholder=""" id="mark" name="_mark">
			<div id="_answer" style="display:block">
			</div>
		</div>
		
    </div>
    <div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>及格线：</label>
		<div class="formControls col-xs-8 col-sm-9" id="answer">
			<input type="text" class="input-text" style="width: 20%;" value="<?=$Tests['passScore']?>" placeholder=""" id="pass"" name="_pass">
			
		</div>
		
	</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>题目排序：</label>
		<div class="formControls col-xs-8 col-sm-9" id="answer">
			<span>
            <input type='text' style="margin:10px 2px;width:70px" class="btn btn-primary radius" name="style[]" value="单选题">
			<input type='text' style="margin:10px 2px;width:70px" class="btn btn-primary radius"  name="style[]" value="多选题">
			<input type='text' style="margin:10px 2px;width:70px" class="btn btn-primary radius" name="style[]" value="判断题">
			<input type='text' style="margin:10px 2px;width:70px" class="btn btn-primary radius"  name="style[]" value="填空题">
			<br/>
		 <label size="5">拖拽进行排序</label>
		</span>
		
		</div>
		
	</div>
	<div id="lx"></div>
	<script type="text/javascript">
    var gh=["1233","gh"];
	var styleid='';
	var questionIds =<?=$Tests['testcontent']?>;//试题内容,二维数组
    
    //定义接收从子窗口所选中的题目的数组。根据数据中的情况生成
	console.log(questionIds[1]);
	//alert(questionIds[1][2]);
	<?php
	//将试题内容转成 json 对象
	$testcontent = json_decode($Tests['testcontent'],true);
	$questioncount=[];//保存每种题型的题目总数
	foreach($testcontent as $k=>$value){ 
		if($value)
		$questioncount[$k]= count($value);//k表示 对应题型的id
		else $questioncount[$k]=0;
		//
		?>
   
		
	<?php 
	}
	?>
  </script>


	<?php foreach($QuestionTypes as $k=>$questionType) { ?>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span><?=$questionType['question_remark']?>:</label>
		<div class="formControls col-xs-8 col-sm-9" id="select">
			<label>共</label><input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value="" placeholder="" id="singlenumber"" name="sum['<?=$questionType['question_type_style']?>"><label>题,每题</label>
			<input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value=""  id="singlenumber"" name="singleEachScore['<?=$questionType['question_type_style']?>']"><label>分</label>
			<label>已选：</label><label id="lable<?=$questionType['question_type_id']?>"><?=$questioncount[$questionType['question_type_id']]?></label><label style="margin-left: 2px;">题</label><br/>
			<a href="javascript:;" onclick="showQuestion(<?=$questionType['question_type_id']?>)" class="btn btn-primary radius">看题</a> 
			<a class="btn btn-primary radius" href="javascript:;" onclick="selectQuestion('<?=$questionType['question_type_id']?>',<?=$questionType['question_type_id']?>)"> 选题</a> 		
		</div>		
	</div>	
	<?php } ?>
    <div class="row cl" >
		<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
			<input class="btn btn-primary radius"   onclick="save1();return false;" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
		</div>
	</div>
	</form>
	

</article>
<script type="text/javascript">

//var questionIds=[];
$("#content").html("");
var jj=1;//用于动态生成input的id,如 input1.input2
var click_input=null;//用于判断点击哪一个input
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
		function add(){
		$("#select").append('<input type="text" class="input-text"  value="" placeholder="option'+jj+'" name="input[]">'+
		'<div id="option'+jj+'"></div>');
		jj++;
		}

/*
根据问题类型选择
*/
function selectQuestion(questionType,id){
	styleid= id;
    url='<?=url("Test/showQuestionBystyle/")?>';
	layer_show("单选题",url+questionType,"600","580");//传参 questionType

}
//展示已选的题
function showQuestion(id){
	styleid= id;//给子窗口的
	url='<?=url("Test/showSelectedQuestions/")?>';
	layer_show("单选",url,"600","800");

}
//准备
function ready(){
    correct = '<?= $Tests['correctstyle']?>';
    if(correct == 'auto')
    {
    $("input#sex-1").prop("checked","true");
    }
    else $("input#sex-2").prop("checked","true");
    //选中科目
    subject='<?=$Tests['subject']?>';
    $(".select").val(subject);
}
ready();
//提交表单
function save1(){
  //window.location.href="../index.php";
  //alert(questionIds['singlenum']);
  var options = {
		url:'<?= url('Test/saveTest')?>',
		type:"post",
		dataType:"json",//设置数据提交类型
		data:{'ids':JSON.stringify(questionIds)},
		success:function(data){
			
            if(data.data==1)
			{//alert(data.data);
				parent.layer.msg("操作成功!", {time: 1000}, function () {
                            //重新加载父页面
                           //parent.location.reload();
                        });
                        return;

            }
          else{
              alert("没有数据");
               }
                                 } 
		}

$("form").ajaxSubmit(options);//额外添加数据
	//alert($("form").attr("action"));
}

</script> 
