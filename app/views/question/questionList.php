<!--渲染学生列表-->
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 试题管理 <span class="c-gray en">&gt;</span> 试题页面 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px"
 href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<form id="searchForm" action='<?=url("Question/search")?>'  method="post">
    <div class="cl pd-5 bg-1 bk-gray">
    		<div style="text-align: center;font-size: large;margin-bottom: 20px">查询条件</div> 
    		
                <table  >
                    <tr >
					<div style="height:10px"> </div>
                        <label >难度:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
                        <select class="select" name="questionLevel" size="1" >
                            <option value="">难度不限</option>
                            <option value="易">易</option>
			                <option value="中">中</option>
			                <option value="难">难</option>
                            
                        </select>
                        </span> 
						<!--,,-->
						<label style="margin-left:30px">题型:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
						<select class="select" name="questionTypes" size="1" >
						<option value="">不限</option>
                        <?php foreach($questionsTypes as $k=>$value) { ?>
					        <option value="<?=$value['question_type_id']?>"><?=$value['question_remark']?></option>
					    <?php }?>
						</select>
                        </span> 
					
                       
                    </tr>
					<tr   >
					    <div style="height:10px"> </div>
                        <label >科目:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
                        <select class="select selectsubject" name="selectsubject" size="1" >
						<option value="">不限</option>
						<?php foreach($Subjects as $k=>$value){ ?>
							
                            <option value='<?=$k+1?>'><?=$value['subjectname']?></option>
                            <?php } ?>
                           
                        </select>
                        </span> 
						<!--,,-->
						<label style="margin-left:30px">知识点:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
						<select class="select selectpoint" name="selectpoint" size="1" >
						<option value="">不限</option>
                        
						
						</select>
                        </span> 
						<label style="margin-left:10%">关键字:</label><input type="te1t" class="input-text  radius"" style="width: 30%;margin: 0 2px;" value=''  id="singlenumber"" name="keyWord">
						<a class="btn btn-primary radius" href="javascript:;" data-type="reload" ><i class="Hui-iconfont"></i> 检索</a> </span> 
                   </tr>
                 </table>
               
    		
	</div>
    </form>
	<div class="cl pd-5 bg-1 bk-gray" >
		<div style="text-align: center;" style="margin-top:3%"></div> 
		<span class="l">
		<a href="javascript:;" data-type="getCheckData" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> 
		<a href="javascript:;" onclick="showAdd()" class="btn btn-primary radius"><i class="Hui-iconfont"></i> 添加</a>
	    <a href="javascript:;";	data-type="reload1" class="btn btn-primary radius reload1">刷新</a> </span>
		<span class="r">共有数据：<strong>54</strong> 条</span> </div>
        <table class="table table-border table-bordered table-hover table-bg" id="usertable" lay-filter="test">
	
</div>


<!--请在下方写此页面业务相关的脚本-->


<!--请在下方写此页面业务相关的脚本-->
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" onclick="aa();" lay-event="edit">编辑</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="detail">详情</a>
</script>
<script type="text/javascript">

layui.use('table', function(){
  var curr1;
  var table = layui.table;
  var $ = layui.$;
  //渲染表格
  var ins=table.render({
    elem: "#usertable"
    ,height: 500
	,id:'allTable'
    ,url: '<?=url("Question/listshow")?>' //数据接口
         ///传参要显示的已选id
    ,limit:11//一页多少行
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {type: 'checkbox', fixed: 'left'},
      {field: 'questionNo', title: 'ID', width:80, sort: true, fixed: 'left'}
      ,{field: 'title',title: '题目', width:300}
      ,{field: 'level',title: '等级', width:100}
      ,{field: 'question_remark',title: '题型', width:100}
	  ,{field: 'subjectname',title: '科目', width:100}
	  ,{field: 'name',title: '出题人', width:100}
      ,{fixed: 'right', title:'操作', toolbar: '#barDemo',}
    ]],
	done: function(res, curr, count){
    //如果是异步请求数据方式，res即为你接口返回的信息。
    //如果是直接赋值的方式，res即为：{data: [], count: 99} data为当前页数据、count为数据总长度
    console.log(res);
    //得到当前页码
    console.log(curr); 
    curr1=curr;
    //得到数据总量
	console.log(count);
	$("strong").html(count);
  }
  
  });
   //监听表格复选框选择
  table.on('checkbox(test)', function(obj){
    console.log(obj);
  });
  //监听行工具事件
  table.on('tool(test)', function(obj){
	var data = obj.data;//这一行的数据
	var $ = layui.$;
    //console.log(obj)
    if(obj.event === 'del'){
      layer.confirm('真的删除问题么？', function(index){
		var $ = layui.$;
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("Question/delete")?>",
		    dataType:"json",
		    data: {id:data.questionNo},
		    success:function(data){
			}
		  });
          obj.del();
        layer.close(index);
		  layer.msg("删除成功!", {time: 1000}, function () {});
		  $("#gh").click();
      });
    } else if(obj.event === 'edit'){
		//alert(data.userid);\
		var $ = layui.$;	
		layer_show("编辑",'<?=url('Question/edit/')?>'+data.questionNo,800,600);
    }if(obj.event ==='detail'){
		layer_show("编辑",'<?=url('Question/questionDetail/')?>'+data.questionNo,800,600);
	}
  });

//定义 点击事件
  var $ = layui.$, active = {
    getCheckData: function(){ //获取选中数据
		var checkStatus = table.checkStatus('allTable')
      ,data = checkStatus.data;
      //layer.alert(data[1].name);
	  var ids=[];//存在id数组
	  $.each(data,function(index,value){
          ids[index]=value.questionNo;

	  })
	  /*批量删除*/
	  
		  //dom删除，不刷新删除
		
		layer.msg("删除成功!", {time: 1000}, function () {
                               
                            });
							//alert("dsf");
							//table.reload('allTable',{});  				
	  $.ajax({
			type:"post",
		    url:"<?=url("Question/dels")?>",
		    dataType:"json",
			async: false ,
		    data: {ids:ids},//id数组传给后台
		    success:function(data){
				//alert("dsf");
				table.reload('allTable',{});  
			}
		  });
	 
    }
    ,getCheckLength: function(){ //获取选中数目
      var checkStatus = table.checkStatus('allTable')
      ,data = checkStatus.data;
      layer.msg('选中了：'+ data.length + ' 个');
    }
    ,isAll: function(){ //验证是否全选
      var checkStatus = table.checkStatus('allTable');
      layer.msg(checkStatus.isAll ? '全选': '未全选')
    },
	reload: function () {
                 //var demoReload = $('#inputUser');
                  table.reload('allTable', {
                    page: {
                      curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                     
                         'data':$("#searchForm").serialize(),
                      
                    }
                  });
            },
	reload1: function(){
		table.reload('allTable', {});
         //执行重载
        

    }

  };
  //调用点击事件
  $('.btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });

});


/*显示试题*/

function datashow(obj){
	//alert(obj.innerHTML);//获取英语
	//根据英语和用户id,班级查询、
	$.ajax({
        type:"post",
		url:"loginController.php?action=shijuan",
		dataType:"json",
		data: {kemu:obj.innerHTML},
		success:function(data){
			if(data.status==1){
			$("#tbody").html("");

			for(var i=0;i<data.data.length;i++){
				var str ='<tr class="text-c">'+
				'<td><input type="checkbox" value="" name=""></td>'+
				'<td>'+data.data[i]["testid"]+'</td>'+
				'<td>'+data.data[i]["subject"]+'</td>'+
				'<td><a href="#">admin</a></td>'+
				'<td>拥有至高无上的权利</td>'+
				'<td class="f-14"><a title="编辑" href="javascript:;" onclick="enter_test('+data.data[i]["testid"]+')" style="text-decoration:none"><i class="btn btn-danger radius">开始考试</i></a> </td>'+
			'</tr>';
             $("#table").append(str);
			}
            //alert(data.data);

		}
		}

	}

	);
}
/* 搜索*/
function search1(){
	
	$.ajax({
			type:"post",
			url:'<?=url("Question/search")?>',//提交的地址 Question控制器下的delete方法
            data:$("#searchForm").serialize(),//序列化提交数据
            dataType:"json",
			
			success:function(data){
				//alert(data.data);
				//alert(data.data[0]['title']);
				if(data.status)
				parent.layer.msg("搜索成功!共"+data.status+'行', {time: 1000}, function () {				
							$("#tbody").html(""); var html='';
							for(var i=0;i<data.data.length;i++){
								html='<?=url("Question/edit/")?>'+data.data[i]['questionNo'];
			                 	var str ='<tr class="text-c">'+
			                 	'<td><input type="checkbox" value="'+data.data[i]['questionNo']+'" name="ids[]"></td>'+
				                '<td width="40">'+data.data[i]['questionNo']+'</td>'+
				                '<td width="200">'+data.data[i]['title']+'</td>'+
				                '<td>'+data.data[i]['question_remark']+'</td>'+
				                '<td>'+data.data[i]['level']+'</td>'+
				                '<td width="300">录入时间</td>'+          
				                '<td class="f-14"><a title="编辑"  href='+html+' style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,\'1\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>'
			                    +'</tr>';
                              $("#table").append(str);
			                }
                           //location.reload();//重新加载当前页面
						});
				else parent.layer.msg("没有结果！", {time: 1000}, function () {
                            //重新加载当前页面
                           //location.reload();
						});
                        //return;
			}
		})

}
/*进入考试页面*/
function enter_test(testid){
 //alert(id);
 //$.post("test.php",{data:id});
 window.location.href="test.php?data="+testid;

}
//试题删除
function datadel(){
		//var fdd=document.getElementById('testForm');
		//alert("dfdf");
		//var fd = new FormData(fdd);
		//fd.append("style",style);
		$.ajax({
			type:"post",
			url:'<?=url("Question/delete")?>',//提交的地址 Question控制器下的delete方法
            data:$("form").serialize(),//序列化提交数据
            dataType:"json",
			
			success:function(data){
				///alert(data.message);
				if(data.data)
				parent.layer.msg("操作成功!影响"+data.data+'行', {time: 1000}, function () {
                            //重新加载当前页面
                           location.reload();
						});
				else parent.layer.msg("操作失败，该题正被使用中！", {time: 1000}, function () {
                            //重新加载当前页面
                           location.reload();
						});
                        //return;
			}
		})
	}
/*管理员-角色-添加*/
function showAdd(){

	//layer_show('添加','<?=url("Question/showAdd")?>',800,600);
	Hui_admin_tab('<a data-href="<?=url("Question/showAdd")?>" data-title="编写题目" href="javascript:;">考试情况</a>');

}
/*管理员-角色-编辑*/
function admin_role_edit(title,url,id,w,h){
	//layer_show(title,url,w,h);
	window.location.href=url;
}
/*管理员-角色-删除*/
function question_del(obj,id){
	layer.confirm('试题删除须谨慎，确认要删除吗？',function(index){
		$.ajax({
			type: 'POST',
			url: '<?=url("Question/delete")?>',
			dataType: 'json',
			data:{"ids[]":id},
			success: function(data){
				$(obj).parents("tr").remove();
				layer.msg('已删除!',{icon:1,time:1000});
			},
			error:function(data) {
				console.log(data.msg);
			},
		});		
	});
};
//监听所选科目selectsubject
$(".selectsubject").change(function(){
		//alert($(this).val());
		var select=$(this).val();
		//异步请求改变知识点选择框
		$.ajax({
			  type:"POST",//提交方式
              url:'<?=url('Subject/selectPoint')?>',
              data:{select:select},
              dataType:"json",//设置数据提交类型
              success:function(data){
				  //selectpoint
				  var str = '<option value="">不限</option>';
				  //alert("sdf");
				  console.log(data.data);
				  
				 for(var i=0;i<(data.data).length;i++){
				  str += '<option value="'+data.data[i]['kownledgepointid']+'">'+data.data[i]['kownledgepointname']+'</option>';
				 }
				 $(".selectpoint").html(str);
			  }
		})
		
	});


</script>
