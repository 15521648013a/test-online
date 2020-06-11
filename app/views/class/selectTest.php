<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
</head>
<body>
    <!--嵌套叶-->
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 考试管理 <span class="c-gray en">&gt;</span> 考试列表 
    <a class="btn btn-success radius r " id ="btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="page-container" >
	<form id="searchForm" >
		<div class="text-c"> <label >按:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
                        <select class="select" name="condition" size="1" >
                            <option value="ID">编号</option>
                            <option value="testName">名称</option>   
                        </select>
                        </span> 
			<input type="text" class="input-text" style="width:250px" placeholder="输入关键字或id" id="inputUser" name="keyWord">
	</form >	
	<a class="btn btn-primary radius" href="javascript:;" data-type="reload" ><i class="Hui-iconfont"></i> 检索</a>
		</div>
		<div style="border:3px solid #f5fafe; margin-top:5%">
		<div class="cl pd-5 bg-1 bk-gray "> 
			<span class="l">
				<a href="javascript:;" data-type="getCheckData" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> 
				<a href="javascript:;" onclick="admin_add('添加管理员','','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">
					&#xe600;</i> 添加管理员</a>
				<a href="javascript:;";	id="gh" data-type="reload" class="btn btn-danger radius">刷新</a>
					</span> <span class="r">
					共有数据：<strong>3</strong> 条</span> 
				
					
				</div>
                
             

				<table class="table table-border table-bordered table-hover table-bg" id="usertable" lay-filter="test">
				<tr>
				<th scope="col" colspan="7">角色管理</th>
			</tr>
			
</table>
<div align="center" style="margin-top:10px">
  
    <a class="btn btn-primary radius" href="javascript:;" data-type="getCheckData"><i class="Hui-iconfont"></i> 完成</a> </span>
        </div>
</div>
	</div>

	   <!--嵌套叶-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="detail">详情</a>
</script>
         
<script type="text/javascript">
function mune(url){
	$(window.parent.document).find("#menu-comments ul").append('<li type="hidden"><a data-href='+url+' data-title="考试情况" href="javascript:;">考试情况</a></li>'); 
		 // alert($(window.parent.document).find("#menu-comments li").eq(0).find('a').attr('href'));
		  ($(window.parent.document).find("#menu-comments li").last().find('a'))[0].click();//选择最后一个
		  ($(window.parent.document).find("#menu-comments li").last()).remove();//删除

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
    ,url: '<?=url("class/selectTestShow")?>' //数据接口
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
      {field: 'testid', title: 'ID', width:120, sort: true, fixed: 'left'}
      ,{field: 'testName',title: '试卷名', width:100}
	  ,{field: 'subjectname', title: '科目', width:100}
	  ,{field: 'name',title: '出卷人', width:100}
	  ,{field: 'totalscore',title: '总分', width:100}
	  ,{field: 'createtime',title: '添加时间', width:200}
      
    ]],
	done: function(res, curr, count){
    //如果是异步请求数据方式，res即为你接口返回的信息。
    //如果是直接赋值的方式，res即为：{data: [], count: 99} data为当前页数据、count为数据总长度
    console.log(res);
    
    //得到当前页码
    console.log(curr); 
    curr1=curr;
	$("strong").html(count);
    //得到数据总量
    console.log(count);
	//this.where={};
	//练习
	
  }
  
  });
   //监听表格复选框选择
  table.on('checkbox(test)', function(obj){
	checkStatus = table.checkStatus('usertable')
      ,data = checkStatus.data;
    //console.log(data);
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
		    url:"<?=url("Test/del")?>",
		    dataType:"json",
		    data: {id:data.testid},
		    success:function(data){
			}
		  });
		  layer.msg("删除成功!", {time: 1000}, function () {});
		  
      });
    } else if(obj.event === 'edit'){
		layer_show("编辑",'<?=url('Test/edit/')?>'+"1"+"/"+data.testid,800,600);

    }else if(obj.event === 'detail'){
		

		mune('<?=url("Test/show/")?>'+data.testid);
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
          ids[index]=value.testid;

	  })
	  /*批量删除*/
	  $.ajax({
			type:"post",
		    url:"<?=url("ClassTest/adds/$classid")?>",
		    dataType:"json",
		    data: {ids:ids,},//id数组传给后台
		    success:function(data){
				if(data.status){
				layer.msg("添加成功!", {time: 1000}, function () {
					
                            });
				}else{
					layer.msg(data.msg, {time: 1000}, function () {
					
				});

				}
		              //$("#gh").click();
				
			}
		  });
		  //dom删除，不刷新删除
		
		
	 
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
               //  var demoReload = $('#inputUser');
                  table.reload('allTable', {
                    page: {
                      curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                     
                        'data':$("#searchForm").serialize(),
                      
                    }
                  });
            },

  };
  //调用点击事件
   //调用点击事件
   $('.btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });
});

   

	</script>
