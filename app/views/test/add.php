<style type="text/css"> 
.dragger{

user-select: none;
display: inline-block;
margin: 5px 10px 5px 0;
width: 100px;
height: 30px;
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
	<form class="form form-horizontal" id="form-admin-add"   >
		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">题型：</label>
			<div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width:150px;">
				<select class="select" name="questionType" size="1">
					<option value="1">单选</option>
					<option value="2">多选</option>
					<option value="3">判断</option>
					<option value="4">填空</option>
				</select>
				</span> </div>
		</div>
	<div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>试题名称：</label>
		<div class="formControls col-xs-8 col-sm-9" id="title">
			<input type="text" class="input-text" value="" placeholder="点击编辑"" id="title" name="testName">
			<div id="_content" style="display:block">
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
            <select class="select" name="subject" size="1">
                <?php foreach($Subjects as $k=>$value){ ?>
                <option value='<?=$k+1?>'><?=$value['subjectname']?></option>
                <?php } ?>
            </select>
            </span> </div>
    </div>
    <div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>考试时长：</label>
		<div class="formControls col-xs-8 col-sm-9" >
			<input type="text" class="input-text" style="width: 20%;" value="" placeholder=""" id="time" name="_time"><label>分钟</label>
			<div id="_answer" style="display:block">
			</div>
		</div>
		
    </div>
    <div class="row cl">
		<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>试卷总分：</label>
		<div class="formControls col-xs-8 col-sm-9" id="answer">
			<input type="text" class="input-text" style="width: 20%;" value="" placeholder=""" id="mark" name="_mark">
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
         <div class="dragger">单选题</div>
         <div class="dragger">多选题</div>
         <div class="dragger" style="visibility: visible;">判断题</div>
         <div class="dragger" style="visibility: visible;">填空题</div>
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
			<label>共</label><input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value="" placeholder=""  name="sum['<?=$questionType['question_type_style']?>"><label>题,每题</label>
			<input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value=""  name="singleEachScore['<?=$questionType['question_type_style']?>']"><label>分</label>
			<label>已选：</label><label id="lable<?=$questionType['question_type_id']?>">0</label><label style="margin-left: 2px;">题</label><br/>
			<a href="javascript:;" onclick="showQuestion(<?=$questionType['question_type_id']?>)" class="btn btn-primary radius">看题</a> 
			<a class="btn btn-primary radius" href="javascript:;" onclick="selectQuestion('<?=$questionType['question_type_id']?>',<?=$questionType['question_type_id']?>)"> 选题</a> 		
		</div>		
	</div>	
	<?php } ?>
	</form>
    <div class="row cl" style="margin-top:10px" >
		<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
			<input class="btn btn-primary radius"   onclick="save1();return false;" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
		</div>
	</div>
	
	

</article>
<script type="text/javascript">
var gh=["1233","gh"];
var styleid='';
//var questionId=new Array('singlenum','multiplenum');//接受子窗口选择的试题号数组。
//定义接收从子窗口所选中的题目的数组。根据数据中的情况生成
var selects = [];var num=[],questionIds=[];
<?php foreach($QuestionTypes as $k=>$questionType) { ?>
  questionIds['<?=$questionType['question_type_id']?>']=[];  //接受选中的数据
  selects['<?=$questionType['question_type_id']?>']=[];
  num['<?=$questionType['question_type_id']?>']=1;//题号
  <?php } ?>
 // questionIds[1]=[]; 
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
	styleid= id;//给子窗口的
    url='<?=url("Test/showQuestionBystyle/")?>';
	layer_show("单选题",url+questionType,"600","600");//传参 questionType

}
//展示已选的题
function showQuestion(id){
	styleid= id;//给子窗口的
	url='<?=url("Test/showSelectedQuestions/")?>';
	layer_show("已选",url,"600","800");

}
//准备

//提交表单
function save1(){
	//[{"titleType":"hhh","questions":["78","79","140"]},
	//{"titleType":"hhh","questions":["161","162","166","167"]}
    //]
   //window.location.href="../index.php";
  //alert(questionIds['singlenum']);
  //将数组转成统一的对象
  var questions=[]; var question = [];
  for(var i=0;i<selects.length;i++){
	  questions[i]={};question = [];
	  if($.isArray(selects[i])){
	  for(var j=0;j<selects[i].length;j++){
             question[j]=selects[i][j];
	  }}
	  questions[i]={"titleType":"hhh","questions":question};
  }
 console.log(JSON.stringify(questions));
  var options = {
		url:'<?= url('Test/saveTest')?>',
		type:"post",
		dataType:"json",//设置数据提交类型
		data:{'ids':JSON.stringify(questions)},
		success:function(data){
			alert("dsf");
            if(data.status==1)
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

$("#form-admin-add").ajaxSubmit(options);//额外添加数据
	//alert($("form").attr("action"));
}
</script>

<script src="<?=STATIC_PATH ?>/static/jQuery多容器之间拖曳_files/drag.js"></script>
<script src="<?=STATIC_PATH ?>/static/jQuery多容器之间拖曳_files/main.js"></script>

