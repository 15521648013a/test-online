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



</style>

</head>
<body>
<!--body -->
<!--成员表-->
<!--渲染学生列表-->
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>


    <!--嵌套叶-->
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 考试记录管理 <span class="c-gray en">&gt;</span> 考试记录列表 <a class="btn btn-success radius r " id ="btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="page-container" >
	<form id="searchForm"  style="margin-bottom:10px">
		<div class="text-c"> <label >按:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
                        <select class="select" name="condition" size="1" >
                            <option value="ID">编号</option>
                            <option value="testName">名称</option>   
                        </select>
                        </span> 
			<input type="text" class="input-text" style="width:250px" placeholder="输入关键字或id" id="inputUser" name="keyWord">
			<a class="btn btn-primary radius" href="javascript:;" data-type="reload" ><i class="Hui-iconfont"></i> 检索</a>
	</form >
		<div style="border:3px solid #f5fafe; ">
		<div class="cl pd-5 bg-1 bk-gray "> 
			<span class="l">
				
			
				<a href="javascript:;";	id="gh" data-type="reloadTable" class="btn btn-danger radius">刷新</a>
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
  <a class="layui-btn  layui-btn-xs" lay-event="detail">详情</a>
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
    ,url: '<?=url("Grade/requestLists/$userid")?>' //数据接口
         ///传参要显示的已选id
    ,limit:10//一页多少行
	,where:{
    
					}
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {type: 'checkbox', fixed: 'left'},
      {field: 'userid', title: 'ID', width:80, sort: true, fixed: 'left'}
	  ,{field: 'testid',title: '试卷编号', width:100}
      ,{field: 'name',title: '姓名', width:100}
      ,{field: 'testName',title: '试卷', width:100}
      ,{field: 'totalscore',title: '总分', width:100}
	  ,{field: 'score',title: '得分', width:100}
	  ,{field: 'starttime',title: '时间', 
	  }
	  ,{field: 'classid',title: '班级编号', 
	  }
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
      layer.confirm('真的删除行么', function(index){
		var $ = layui.$;
		  //发送请求删除
		  $.ajax({
			type:"post",
		    url:"<?=url("Class/del")?>",
		    dataType:"json",
		    data: {id:data.recordid},
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
		layer_show("编辑",'<?=url('Class/edit/')?>'+data.classid,800,600);
    }else if(obj.event === 'detail'){
		Hui_admin_tab('<a data-href='+'<?=url("TestRecord/showDetail/")?>'+data.recordid+ ' data-title="考试页面" href="javascript:;">考试页面</a>');
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
          ids[index]=value.recordid;

	  })
	  /*批量删除*/
	  $.ajax({
			type:"post",
		    url:"<?=url("Grade/dels")?>",
		    dataType:"json",
		    data: {ids:ids},//id数组传给后台
		    success:function(data){
				    layer.msg("更新成功!", {time: 1000}, function () {
                                //重新加载父页面
    						 
                            });
                            return;
			}
		  });
		  //dom删除，不刷新删除
		$("input:checkbox:checked").each(function(){
			$(this).parents("tr").remove();
		})
		layer.msg("删除成功!", {time: 1000}, function () {
                               
                            });
		  $("#gh").click();
	 
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
			reloadTable: function(){
      table.reload('allTable');

    },

  };
  //调用点击事件
  $('.btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });

});

   


	</script>
