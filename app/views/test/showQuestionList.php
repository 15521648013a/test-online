
</head>
<body>
<nav class="breadcrumb"> <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	
	<div class="cl pd-5 bg-1 bk-gray">
		<div style="text-align: center;">请选择科目</div> 
		<span class="l">
            <table  border=" 1px solid #ccc">
                <tr border=" 1px solid #ccc">
                    <label>录入时间:</label>
                    <input type="text" class="input-text  radius"" style="width: 20%;margin: 0 2px;" value=""  id="singlenumber"" name="_pass"> <label>-</label>
                    <input type="text" class="input-text  radius"" style="width: 20%;margin: 0 2px;" value=""  id="singlenumber"" name="_pass">
                    <label style="margin-left:10%">关键字:</label><input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value=""  id="singlenumber"" name="_pass"><br/>
                </tr>
                <tr  class="text-c">
                    <label >难度:</label>
                    <span class="select-box radius" style="width:100px;">
                    <select class="select" name="questionType" size="1">
                        <option value="1">难度不限</option>
                        <option value="2">易</option>
                        <option value="3">中</option>s
                        <option value="4">难</option>
                        
                    </select>
                    </span> 
               </tr>
             </table>
        </span>
	    <span class="r"> <a class="btn btn-primary radius" href="javascript:;" onclick="question_add('添加角色','./view/problem-add.html','800')"><i class="Hui-iconfont"></i> 检索</a> </span></span> </div>
	<table class="table table-border table-bordered table-hover table-bg" id="table"> 
		<thead>
			<tr>
				<th scope="col" colspan="6">试卷列表</th>
			</tr>
			<tr class="text-c">
				<th width="25"><input type="checkbox" value="" name="ids[]"></th>
				<th width="40">ID</th>
				<th width="200">题目</th>
				<th>分类</th>
				<th width="300">录入时间</th>
				<th width="70">操作</th>
			</tr>
        </thead>
        
		<form class="form form-horizontal" id="form-admin-add"  action="./loginController.php?action=del_question" method="post" >
		<tbody id="tbody">
		<?php foreach($Questions as $k=>$question): ?>
			<tr class="text-c">
			
				<td><input type="checkbox" value=<?=$question['questionNo']?> name="ids[]" ></td>
				<td><?=$question['questionNo']?></td>
				<td><?=$question['title']?></td>
				<td><?=$question['style']?></td>
				<td>202022</td>
                <td class="f-14">
                    <a title="编辑"  href='./view/problem-edit.php?id=<?=$question['questionNo']?>&style=<?=$question['style']?>'  style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> 
                    <a title="删除" href="javascript:;" onclick="admin_role_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
			    
				</tr>
		
                <?php endforeach ?>
               
        </tbody>
        
        </form>
       
    </table>
    <div align="center" style="margin-top:10px">
    <label>已选<lable id='lable' style="margin-right:10px;margin-left:10px">0</lable>条</lable>
    <a class="btn btn-primary radius" href="javascript:;" onclick="getdata()"><i class="Hui-iconfont"></i> 完成</a> </span>
        </div>
</div>

<script type="text/javascript">
//父窗口的题型号是否为空
console.log(parent.questionIds[parent.styleid]);
var hh=parent.questionIds[parent.styleid];
if(parent.questionIds[parent.styleid].length ) {
$('#lable').html(parent.questionIds[parent.styleid].length );
//alert(parent.questionIds[1][2]);
$("input[name='ids[]']").each(function(){
//处理复选框
//alert($(this).val());

if($.inArray($(this).val(),hh ) != -1)//是否在数组里
{$(this).attr("checked","checked");
}

});
}
//else alert("空");
/*显示试题*/
//监听同时改变父窗口数据
$(document).on('click',"[name='ids[]']",function(){
    var checkId=[];var sum=0;
//alert('ni');
$("input[name='ids[]']:checked").each(function(i){
			if($(this).val()!=""){
			checkId[sum]=$(this).val();sum++;}
		});
        $(window.parent.document).find("#lable<?=$id?>").html(sum);//父窗口中的相关lable的id
        $('#lable').html(sum);//子窗口
});


/*确定所选的题目*/
function getdata(){
    //alert($("form").serialize());
    var checkId=[];var sum=0;
		$("input[name='ids[]']:checked").each(function(i){
            if($(this).val()!=""){
			checkId[sum]=$(this).val();sum++;}
		});
       // $(window.parent.document).find("#lianxi").val(sum);
        parent.questionIds['<?=$id?>']=checkId;
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
              //layer.msg(index);
        parent.layer.close(index); //再执行关闭子窗口   
}


</script>
