<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
<script>
/*

	*/
    </script> 
</head>
<body>

    <!--嵌套叶-->
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 科目管理 <span class="c-gray en">&gt;</span>科目列表 <span class="c-gray en">&gt;</span> 知识点管理 <a class="btn btn-success radius r " id ="btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="page-container" >
	<form id="searchForm" >
		<div class="text-c">
			<input type="text" class="input-text" style="width:250px" placeholder="输入名称"  name="input">
			<a class="btn btn-primary radius" href="javascript:;" data-type="reload" ><i class="Hui-iconfont"></i> 检索</a>
			</div>	
			</form >
			
		</div>	

		<div style="border:3px solid #f5fafe; margin-top:5%">

		<div class="cl pd-5 bg-1 bk-gray "> 
			<span class="l">
				<a href="javascript:;" data-type="getCheckData" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> 
				<a href="javascript:;" onclick="admin_add('添加知识点','','400','200')" class="btn btn-primary radius"><i class="Hui-iconfont">
					&#xe600;</i> 添加知识点</a>
				<a href="javascript:;";	id="gh" data-type="reload" class="btn btn-danger radius">刷新</a>
					</span> <span class="r">
					共有数据：<strong>3</strong> 条</span> 	
				</div>
                
             
				<table class="table table-border table-bordered table-hover table-bg" id="usertable" lay-filter="test">
				<tr>
			</tr>
			
</table>
</div>
	</div>

	   <!--嵌套叶-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" onclick="aa();" lay-event="edit">编辑</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  
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
    ,url: '<?=url("Subject/setting/$subjectid")?>' //数据接口
         ///传参要显示的已选id
    ,limit:10//一页多少行
	,where:{
    	'ids':ids//ids为全局变量
					}
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {type: 'checkbox', fixed: 'left'}
     
      ,{field: 'kownledgepointname', clsss:'hh',title: '知识点', width:500}
      ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:300}
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
	this.where={};
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
    //console.log(obj)
    if(obj.event === 'del'){
      layer.confirm('真的删除行么', function(index){
		var $ = layui.$;
		obj.del();
        layer.close(index);
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("Subject/delKnowledgePoint")?>",
		    dataType:"json",
		    data: {kownledgepointid:data.kownledgepointid,subjectid:data.subjectid},
		    success:function(data){
			}
		  });
		  layer.msg("删除成功!", {time: 1000}, function () {});
		  $("#gh").click();
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
	
		layer_show("编辑s",'<?=url('Subject/showEditKnowledgePoint/')?>'+data.kownledgepointid,'400','200');
    }else if(obj.event === 'setting'){
		layer_show("编辑",'<?=url('Subject/showSetting/')?>'+data.subjectid,800,600);
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
	  console.log(checkStatus);
		  //dom删除，不刷新删除
		$("input[name='layTableCheckbox']:checked").each(function(){
			//$(this).parents("tr").hide();
			///$(this).parents("tr").remove();

		})
		layer.msg("删除成功!", {time: 1000}, function () {
                               
                            });
	  $.ajax({
			type:"post",
		    url:"<?=url("User/dels")?>",
		    dataType:"json",
		    data: {ids:ids},//id数组传给后台
		    success:function(data){
				table.reload('allTable',{});  
			}
		  });
		 // $("#gh").click();
	 
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
            }

  };
  //调用点击事件
  $('.btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });

});

   


	</script>
