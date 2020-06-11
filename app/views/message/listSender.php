<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
</head>
<body>
    <!--嵌套叶-->
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 消息管理 <span class="c-gray en">&gt;</span> 发送消息列表 
    <a class="btn btn-success radius r " id ="btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="page-container" >
	<form id="searchForm" >
		<div class="text-c"> 
			<input type="text" class="input-text" style="width:250px" placeholder="输入关键字"  name="input">
			<a class="btn btn-primary radius" href="javascript:;" data-type="reload" ><i class="Hui-iconfont"></i> 检索</a>
		</div>
		</form >
		<div style="border:3px solid #f5fafe; margin-top:5%">
		<div class="cl pd-5 bg-1 bk-gray "> 
			<span class="l">
				<a href="javascript:;" data-type="getCheckData" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> 
				<a href="javascript:;";	id="gh" data-type="reload" class="btn btn-primary radius">刷新</a>
					</span> <span class="r">
					共有数据：<strong>3</strong> 条</span> 					
				</div>
				<table class="table table-border table-bordered table-hover table-bg" id="usertable" lay-filter="test">
				<tr>
				<th scope="col" colspan="7">发送箱列表</th>
			</tr>
			
</table>
</div>
	</div>

	   <!--嵌套叶-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/html" id="barDemo">
   {{# if(d.status==1){}}
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  {{# } else { }}
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="cancel">撤销</a>
  {{# }  }}
  <a class="layui-btn  layui-btn-xs" lay-event="detail">详情</a>
</script>
         
<script type="text/javascript">
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
    ,url: '<?=url("Message/requestListSender")?>' //数据接口
         ///传参要显示的已选id
    ,limit:10//一页多少行
	,where:{
    	'ids':ids//ids为全局变量
        ,'status':0//状态为未读
					}
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {type: 'checkbox', fixed: 'left'}  
      ,{field: 'senderName', clsss:'hh',title: '发送人', width:100}
	  ,{field: 'receiverName', title: '接收人', width:120,  }
      ,{field: 'content', clsss:'hh',title: '内容', width:100}
      ,{field: 'status', clsss:'hh',title: '状态', width:100,templet: function(d){
	     if(d.status ==1 ) return "已处理";else return"待处理";
      }}
      ,{field: 'deal', clsss:'hh',title: '结果', width:100}
      ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:250}
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
    //console.log(obj)
    if(obj.event === 'del'){
		layer.confirm('真的删除么', function(index){
		var $ = layui.$;
		obj.del();
        layer.close(index);
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("Message/senderdel")?>",
		    dataType:"json",
		    data: {id:data.messageid},
		    success:function(data){
			}
		  });
		  layer.msg("撤销成功!", {time: 1000}, function () {});
		
      });  
     
    } else if(obj.event === 'edit'){
		//alert(data.userid);\
		var $ = layui.$;
		/*
	$("[data-field='role']").each(function(){
       if($(this).text()=='学生')
	   {//var tr = ($(this).parents('tr').find("td").eq(2).text());
		//alert(tr.find("data-typecheckbox").attr("checked","true"));
		var tr = ($(this).parents('tr').find("td").eq(0).prop("checked", "checked"));//获取checkbox
        //  $(tr).prop("checked",true);
        console.log(tr);
         //索引
	   //alert(tr);
	   
	   }
	})*/
	
		layer_show("编辑",'<?=url('User/edit/')?>'+data.userid,800,600);
    }else if(obj.event === 'detail'){
		
		layer_show("编辑",'<?=url('Message/showdetail/')?>'+data.messageid,800,600);
	}else if(obj.event === 'cancel'){
		layer.confirm('真的撤销申请么', function(index){
		var $ = layui.$;
		obj.del();
        layer.close(index);
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("Message/cancel")?>",
		    dataType:"json",
		    data: {id:data.messageid},
		    success:function(data){
			}
		  });
		  layer.msg("撤销成功!", {time: 1000}, function () {});
		
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
          ids[index]=value.messageid;

	  })
	  /*批量删除*/
	  $.ajax({
			type:"post",
		    url:"<?=url("Message/senderDels")?>",
		    dataType:"json",
		    data: {ids:ids},//id数组传给后台
		    success:function(data){
				table.reload('allTable',{});  
			}
		  });
		
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
                //var demoReload = $('#demoReload');
                //执行重载
				
                table.reload('allTable', {
					page:{
						curr:curr1,
					},where:{
						'data':$("#searchForm").serialize(),
					}
					
                   
                });
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
		url='<?=url('User/showadd')?>';
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
