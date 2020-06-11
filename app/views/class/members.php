<style type="text/css"> 
div.menu ul
{
    list-style:none; /* 去掉ul前面的符号 */
    margin: 0px; /* 与外界元素的距离为0 */
    padding: 0px; /* 与内部元素的距离为0 */
    width: auto; /* 宽度根据元素内容调整 */
}
div.menu ul li
{
    float:left; /* 向左漂移，将竖排变为横排 */
	width: auto;
}
div.menu ul li a, div.menu ul li a:visited
{
  
    border: 1px #4e667d solid; /* 边框 */
    color: black; /* 文字颜色 */
    display: block; /* 此元素将显示为块级元素，此元素前后会带有换行符 */
    line-height: 2em; /* 行高 */
    padding: 4px 10px; /* 内部填充的距离 */
    text-decoration: none; /* 不显示超链接下划线 */
    white-space: nowrap; /* 对于文本内的空白处，不会换行，文本会在在同一行上继续，直到遇到 <br> 标签为止。 */
}
/* 所有class为menu的div中的ul中的a样式(鼠标移动到元素中的样式) */
div.menu ul li a:hover
{
    background-color: #bfcbd6; /* 背景色 */
    color: #465c71; /* 文字颜色 */
    text-decoration: none; /* 不显示超链接下划线 */
}
/* 所有class为menu的div中的ul中的a样式(鼠标点击元素时的样式) */
div.menu ul li a:active
{
    background-color: #465c71; /* 背景色 */
    color: #cfdbe6; /* 文字颜色 */
    text-decoration: none; /* 不显示超链接下划线 */
}


.layui-nav .layui-nav-item {
    position: relative;
    display: inline-block;
    *display: inline;
    *zoom: 1;
    vertical-align: middle;
    line-height: 40px;
}
</style>

</head>
<body>
<ul class="layui-nav">
  <li class="layui-nav-item "><a href="<?=url("Class/teachermanage/$classid")?>">班级信息</a></li>
  <li class="layui-nav-item layui-this ">
    <a href="<?=url("Class/members/$classid")?>">成员列表</a>
  </li>

  <li class="layui-nav-item">
    <a href="<?=url("Class/showTest/$classid")?>">班级考试</a>
  </li>
 
</ul>


<!--body -->
<!--成员表-->
<!--渲染学生列表-->
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>


    <!--嵌套叶-->
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 班级 <span class="c-gray en">&gt;</span> 成员列表 <a class="btn btn-success radius r " id ="btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="page-container" >
	<form id="searchForm" >
		<div class="text-c"> <label >按:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
                        <select class="select" name="condition" size="1" >
                            <option value="ID">编号</option>
                            <option value="userName">用户名</option>   
                        </select>
                        </span> 
			<input type="text" class="input-text" style="width:250px" placeholder="输入用户名或编号"  name="input">
			
			<a class="btn btn-primary radius" href="javascript:;" data-type="reload" ><i class="Hui-iconfont"></i> 检索</a>
		</div>
		</form >
		<div style="border:3px solid #f5fafe; ">
		
		<div class="cl pd-5 bg-1 bk-gray "> 
			<span class="l">
			<?php if($identify == '老师'){ ?>
				<a href="javascript:;" data-type="getCheckData" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> 
				<a href="javascript:;" onclick="admin_add('邀请学生','','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">
					&#xe600;</i> 邀请</a>
					<?php }?>
					</span> <span class="r">
					共有数据：<strong>3</strong> 条</span> 
				
					
				</div>
                
	

				<table class="table table-border table-bordered table-hover table-bg" id="usertable" lay-filter="test">
				
			
</table>
</div>
	</div>

	   <!--嵌套叶-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/html" id="barDemo">
<?php if($identify == '老师'){ ?>
{{#  if(d.identification =="学生") { }}
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  {{# } }} 
<?php }?>
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
 
  //渲染表格
  var ins=table.render({
    elem: "#usertable"
    ,height: 500
	,id:'allTable'
    ,url: '<?=url("Class/requestMembers")?>' //数据接口
         ///传参要显示的已选id
    ,limit:10//一页多少行
	,where:{
    	'id':<?=$classid?>//ids为全局变量
					}
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {type: 'checkbox', fixed: 'left'},
	  {field: 'userid', title: '编号', width:80, sort: true, fixed: 'left'}
      ,{field: 'name',title: '姓名', width:100,}
      ,{field: 'sex',title: '性别', width:100}
      ,{field: 'identification',title: '身份', width:100}
      ,{field:'addtime',title: '加入时间',width:180}
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
	//this.where={};
	//练习
	
  }
  
  });
   //监听表格复选框选择
  table.on('checkbox(test)', function(obj){
    console.log(obj);
  });
  //监听行工具事件
  table.on('tool(test)', function(obj){
    var data = obj.data;//这一行的数据
    //console.log(obj)
    if(obj.event === 'del'){
		layer.confirm('真的删除该成员么？', function(index){
		var $ = layui.$;
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("ClassMember/del")?>",
		    dataType:"json",
		    data: {userid:data.userid,classid:data.classid},
		    success:function(data){
			}
		  });
          obj.del();
        layer.close(index);
		  layer.msg("删除成功!", {time: 1000}, function () {});
		 // $("#gh").click();
      });
    } else if(obj.event === 'edit'){
		//alert(data.userid);\
		var $ = layui.$;	
		layer_show("编辑",'<?=url('Class/edit/')?>'+data.classid,800,600);
    }else if(obj.event === 'enter'){
        window.location.href="<?=url("Class/teachermanage")?>";  //进入老师的班级
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
          ids[index]=value.userid;

	  })
	  /*批量删除*/
	  $.ajax({
			type:"post",
		    url:"<?=url("ClassMember/dels/$classid")?>",
		    dataType:"json",
		    data: {ids:ids},//id数组传给后台
		    success:function(data){
				table.reload('allTable',{}); 
			}
		  });
		  //dom删除，不刷新删除
	
		layer.msg("删除成功!", {time: 1000}, function () {
                               
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
                 var demoReload = $('#inputUser');
                  table.reload('allTable', {
                    page: {
                      curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                     
						'data':$("#searchForm").serialize(),
                      
                    }
                  });
            },
    enter: function(){
        alert("dsfdsf");
         //执行重载
        

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
		url='<?=url("Class/showaddmember/$classid")?>';
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
