<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
</head>
<body>
    <!--嵌套叶-->
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 消息管理 <span class="c-gray en">&gt;</span> 待办事宜
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
				<a href="javascript:;";	id="gh" data-type="reloadtable" class="btn btn-primary radius">刷新</a>
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
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  <a class="layui-btn  layui-btn-xs" lay-event="detail">详情</a>
  {{#  if(d.style== 1||d.style== 3){ }}
    <a class="layui-btn layui-btn-xs" lay-event="agree">同意</a>
    <a class="layui-btn layui-btn-xs" lay-event="refuse">拒绝</a>
  {{#  }else{ }}
  <a class="layui-btn  layui-btn-xs" lay-event="test">测试</a>
  {{# }}}
  <a class="layui-btn layui-btn-xs" lay-event="dealed">标记已读</a>
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
    ,url: '<?=url("Message/requestReadyList")?>' //数据接口
         ///传参要显示的已选id
    ,limit:10//一页多少行
	,where:{
    	'ids':ids//ids为全局变量
        ,'status':0//状态为未读
					}
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {type: 'checkbox', fixed: 'left'},
      {field: 'receiverName', title: '接收者', width:120, sort: true, fixed: 'left'}
      ,{field: 'senderName', clsss:'hh',title: '发送者', width:120}
	  ,{field: '', title: '类型', width:120,templet: function(d){
		  if(d.style==1)  return "申请加入班级";else if(d.style == 2) return "考试通知";else 
		  return "邀请加入班级";
      }}
      ,{field: 'content', clsss:'hh',title: '内容', width:350,}
      ,{fixed: 'right', title:'操作', toolbar: '#barDemo'}
    ]],
	done: function(res, curr, count){
    //如果是异步请求数据方式，res即为你接口返回的信息。
    //如果是直接赋值的方式，res即为：{data: [], count: 99} data为当前页数据、count为数据总长度
    console.log(res);
    $("strong").html(count);
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
	var $ = layui.$;
    //console.log(obj)
    if(obj.event === 'del'){
      layer.confirm('真的删除行么', function(index){
		var $ = layui.$;
		obj.del();
        layer.close(index);
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("User/del")?>",
		    dataType:"json",
		    data: {id:data.userid},
		    success:function(data){
			}
		  });
		  layer.msg("删除成功!", {time: 1000}, function () {});
		  $("#gh").click();
      });
    } else if(obj.event === 'detail'){
		//alert(data.userid);\
		var $ = layui.$;
		/*
	
	*/
	
		layer_show("详情",'<?=url("Message/detail/")?>'+data.messageid,800,600);
    }else if(obj.event === 'test'){
	
		testid=JSON.parse(data.remark)[0];classid=JSON.parse(data.remark)[1];
        layer.confirm('确定考试后，系统将开始倒计时，是否继续？', function(index){
        Hui_admin_tab('<a data-href='+'<?=url("Test/show/")?>'+classid+"/"+testid+ ' data-title="考试页面" href="javascript:;">考试页面</a>');
        layer.close(index);
      });
	}else if(obj.event === 'agree'){
		//同意加入班级
		var classid = data.remark;
		$.ajax({
			type:"post",
		    url:"<?=url("ClassMember/add")?>",
		    dataType:"json",
			data: {  data,
				   
			
			},
		    success:function(data){
				if(data.result){
				   alert("已同意");
				   obj.del();
                   layer.close(index);
				}else{
					alert("你已在班级中");
				}
			}
		  });

	}else if(obj.event ==='refuse'){
		//拒绝
		$.ajax({
			type:"post",
		    url:"<?=url("ClassMember/refuse")?>",
		    dataType:"json",
			data: {  data,
				   
			
			},
		    success:function(data){
				if(data.result){
				   alert("已拒绝");
				   obj.del();
                   layer.close(index);
				}else{
					alert("操作失败");
				}
			}
		  });
	}else{
		$.ajax({
			type:"post",
		    url:"<?=url("message/dealed")?>",
		    dataType:"json",
			data: {  data,
				   
			
			},
		    success:function(data){
				if(data.result){
				   
				   obj.del();
                   layer.close(index);
				}else{
				
				}
			}
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
		    url:"<?=url("Message/receiverDels")?>",
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
            },
	reloadtable : function(){
		table.reload('allTable');
	}

  };
  //调用点击事件
  $('.btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });

});


	</script>
