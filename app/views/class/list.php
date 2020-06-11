<!--渲染学生列表-->
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
</head>
<body>

    <!--嵌套叶-->
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 班级管理 <span class="c-gray en">&gt;</span> 班级列表 <a class="btn btn-success radius r " id ="btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="page-container" >
	<form id="searchForm" >
		<div class="text-c"> <label >按:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
                        <select class="select" name="condition" size="1" >
                            <option value="ID">编号</option>
                            <option value="className">名称</option>   
                        </select>
                        </span> 
			<input type="text" class="input-text" style="width:250px" placeholder="输入名称或编号" id="inputUser" name="keyWord">
	</form ><a class="btn btn-primary radius" href="javascript:;" data-type="reload" ><i class="Hui-iconfont"></i> 检索</a>
		<div style="border:3px solid #f5fafe; margin-top:5%">
		<div class="cl pd-5 bg-1 bk-gray "> 
			<span class="l">
			<?php $role=$_SESSION["role"];
			 if($role!=3){
				 ?>
				<a href="javascript:;" onclick="admin_add('添加班级','','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">
					&#xe600;</i> 创建班级</a>
			 <?php }?>
				<a href="javascript:;";	id="gh" data-type="reloadtable" class="btn btn-danger radius">刷新</a>
				<a href="javascript:;";	style="display:none" data-type="reloadtable" class="btn reloadtable ">reload</a>
					</span> <span class="r">
					共有数据：<strong>3</strong> 条</span> 
				
					
				</div>
                
             

				<table class="table table-border table-bordered table-hover table-bg" id="usertable" lay-filter="test">
				<tr>
				<th scope="col" colspan="7"></th>
			</tr>
			
</table>
</div>
	</div>

	   <!--嵌套叶-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/html" id="barDemo">
{{# if(d.exit ){ }}
  <a class="layui-btn layui-btn-xs" lay-event="enter">进入班级</a>
  {{#}else if(d.enterway ==1) {}}
  <a class="layui-btn layui-btn-xs" lay-event="application">申请加入</a>
  {{# }else{ }}
	<a class="layui-btn layui-btn-xs" lay-event="withoutCondition">直接加入</a>
  {{# }}}
 

  {{# if(d.exit ){ }}
  {{# if(d.identification=='老师' ){ }}
  
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">解散班级</a>
  {{# }else{}}

  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="out">退出班级</a>
  {{# }}}
  {{# }}}
  
</script>
         
<script type="text/javascript">
function aa(){

//
}
    /*
    分页处理
    */
var pageSize = 5; //每页显示的数据条数
var currentPageNo = 1; 
var  ids=[];
//$('table').html('');//首次清空table
layui.use('table', function(){
  var curr1;
  var table = layui.table;
  var $ = layui.$;
  //渲染表格
  var ins=table.render({
    elem: "#usertable"
    ,height: 500
	,id:'allTable'
    ,url: '<?=url("Class/listshow")?>' //数据接口
         ///传参要显示的已选id
    ,limit:10//一页多少行
	,where:{
    	'ids':ids//ids为全局变量
		,'userid':<?=$userid?>,
					}
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {type: 'checkbox', fixed: 'left'},
      {field: 'classid', title: 'ID', width:80, sort: true, fixed: 'left'}
      ,{field: 'classname',title: '班级名', width:200}
      ,{field: 'name',title: '创建者', width:80}
      ,{field: 'createtime',title: '创建时间', width:180,sort: true,}
	  ,{field: 'd',title: '加入方式', width:100,templet: function(d){
		  if(d.enterway==1) return "申请加入";else  return"自由加入";
      }},
	  {field: 'identification',title: '我的班级身份', width:120,}
      ,{fixed: 'right', title:'操作', toolbar: '#barDemo', }
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
	///if(id) id='';
	//this.where={};
	//练习
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
      layer.confirm('真的解散该班级么', function(index){
		var $ = layui.$;
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("Class/del")?>",
		    dataType:"json",
		    data: {id:data.classid},
		    success:function(data){
			}
		  });
          obj.del();
        layer.close(index);
		  layer.msg("解散成功!", {time: 1000}, function () {});
		 
      });
    } else if(obj.event === 'edit'){
		//alert(data.userid);\
		var $ = layui.$;	
		layer_show("编辑",'<?=url('Class/edit/')?>'+data.classid,800,600);
    }else if(obj.event === 'enter'){
		
		Hui_admin_tab('<a data-href="'+'<?=url("Class/teachermanage/")?>'+data.classid+ '" data-title="班级" href="javascript:;">班级</a>');

      
    }else if(obj.event === 'application'){
		url='<?=url('Class/application/')?>'+data.classid;
		layer_show("申请加入班级",url,800,600);
	}else if(obj.event ==="withoutCondition"){
		//发送ajax 请求，加入班级
		layer.confirm('真的加入么', function(index){
		var $ = layui.$;
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("ClassMember/addByFree")?>",
		    dataType:"json",
		    data: {classid:data.classid},
		    success:function(data){
				if(data.result==1){
					layer.msg("加入成功!", {time: 1000}, function () {
						//重载表格
						table.reload('allTable',{});  
					});
				}
			}
		  });
        //  obj.del();
       
		 
		 
      });
	}else if(obj.event ==="out"){
		layer.confirm('真的推出该班级么', function(index){
		var $ = layui.$;
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("ClassMember/out")?>",
		    dataType:"json",
		    data: {classid:data.classid,userid:data.userid},
		    success:function(data){
			}
		  });
          obj.del();
        layer.close(index);
		  layer.msg("删除成功!", {time: 1000}, function () {});
		 
      });
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
          ids[index]=value.classid;

	  })
	  /*批量删除*/
	  $.ajax({
			type:"post",
		    url:"<?=url("Class/dels")?>",
		    dataType:"json",
		    data: {ids:ids},//id数组传给后台
		    success:function(data){
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
                
                  table.reload('allTable', {
                    page: {
                      curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                     
                        'data':$("#searchForm").serialize(),
                      
                    }
                  });
            },
	reloadtable: function(){
         //执行重载
		 table.reload('allTable', {});
        

    }

  };
  //调用点击事件
  $('.btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });

});

   

//alert($("input[name='user1']").val());



	/*
		参数解释：
		title	标题
		url		请求的url
		id		需要操作的数据id
		w		弹出层宽度（缺省调默认值）
		h		弹出层高度（缺省调默认值）
	*/
	/*管理员-增加*/
	function admin_add(title,url,w,h){
		url='<?=url('Class/showadd')?>';
		layer_show(title,url,w,h);

	}
	//批量删除
	function datadel(){
		//获取选中的数据
	
		var checkId=[];
		$("input[name='check']:checked").each(function(i){
			checkId[i]=$(this).val();
		});
	     //alert(checkId[1]);
		$.ajax({
			type:"post",
			url:"{:url('user/dataDel')}",
			data: {"checkId":JSON.stringify(checkId)},
            dataType:"json",
			traditional:true,
			success:function(data){
				alert(data.message);
			}
		}

		);}
	/*管理员-删除*/
	function admin_del(obj,id){
		
		layer.confirm('确认要删除吗？',function(index){
			



			//$.get("{:url('user/deleteUser')}",{id:id})
			
				    //alert(id);
				
			      $.get("{:url('user/userDelete')}",{id:id})
					$(obj).parents("tr").remove();
					alert(data.message);
					layer.msg('已删除!',{icon:1,time:1000});
				
				
			
		});
	}
	
	/*管理员-编辑*/
	function admin_edit(title,url,id,w,h){
		//$.get(url,{id:id});
		layer_show(title,url,w,h);
	}
	/*管理员-停用*/
	function admin_stop(obj,id){
		layer.confirm('确认要停用吗？',function(index){
			//此处请求后台程序，下方是成功后的前台处理……
			$.get("{:url('user/setStatus')}",{id:id});
			$(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_start(this,id)" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
			$(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
			$(obj).remove();
			layer.msg('已停用!',{icon: 5,time:1000});
		});
	}
	
	/*管理员-启用*/
	function admin_start(obj,id){
		layer.confirm('确认要启用吗？',function(index){
			//此处请求后台程序，下方是成功后的前台处理……
			
			$.get("{:url('user/setStatus')}",{id:id});
			$(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_stop(this,id)" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
			$(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
			$(obj).remove();
			layer.msg('已启用!', {icon: 6,time:1000});
		});
	}
	//查找用户
	function seacherUser(){
		//获取参数
		var id=$("#inputUser").val();
		//var id=$("input[name='inputUser']").val();
		//alert(id);
		//ajax请求
		
		$("#test").html("");
		$.ajax({
			type:"post",
			url: "<?=url('User/seacherUser')?>",
			data:$("#inputUser"),
			dataType:"json",
			success:function(data){
				
				//$.get("{:url('user/seacherUser1')}",{id:data.message});
				
				//ids.length=0;
				//ids=[];
				if(data.count)
				{   //var Users=[];
					var Users=data.data;
					//清空接收数组
					ids=[];
				   for(var i=0;i<data.count;i++){
					ids[i]=Users[i].userid;
					}
					$("#gh").click();
					//清除表格
					///alert(ids.length);
					//重载表格
					
					
				}
				//alert(data.count);
				
				else
				alert("没有有该用户");
				                
			}
		});
		
					
	}
	</script>
