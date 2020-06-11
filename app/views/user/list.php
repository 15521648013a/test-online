

<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
<script>
/*

	*/

    </script> 
</head>
<body>


    <!--嵌套叶-->
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 人员列表 <a class="btn btn-success radius r " id ="btn-refresh" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
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
		<div style="border:3px solid #f5fafe; margin-top:5%">
		<div class="cl pd-5 bg-1 bk-gray "> 
			<span class="l">
				<a href="javascript:;" data-type="getCheckData" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> 
				<a href="javascript:;" onclick="admin_add('添加人员','','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">
					&#xe600;</i> 添加人员</a>
					<a href="javascript:;" onclick="admin_adds()" class="btn btn-primary radius"><i class="Hui-iconfont">
					&#xe600;</i> 批量导入</a>
				<a href="javascript:;";	id="gh" data-type="reload" class="btn btn-primary radius">刷新</a>
					</span> <span class="r">
					共有数据：<strong>3</strong> 条</span> 
				
				<input type="file" id="file"  style="display:none">
				</div>
                
             

				<table class="table table-border table-bordered table-hover table-bg" id="usertable" lay-filter="test">
				<tr>
				<th scope="col" colspan="7">人员管理</th>
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
    ,url: '<?=url("User/list")?>' //数据接口
         ///传参要显示的已选id
    ,limit:10//一页多少行
	,where:{
    	'ids':ids//ids为全局变量
					}
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {type: 'checkbox', fixed: 'left'},
      {field: 'userid', title: 'ID', width:80, sort: true, fixed: 'left'}
      ,{field: 'name', clsss:'hh',title: '用户名', width:100}
	  ,{field: 'sex', title: '性别', width:100}
      ,{field: 'email', title: '邮箱', width:200}
      ,{field: 'role', title: '分组', width:100}
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
		    url:"<?=url("User/del")?>",
		    dataType:"json",
		    data: {id:data.userid},
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
	
		layer_show("编辑",'<?=url('User/edit/')?>'+data.userid,800,600);
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
	//批量导入
	function admin_adds(){
		$("#file").click();
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
	$("#file").change(function(){
  //alert(document.getElementById('file').files[0].tmp_name);
  //发发送ajax请求
  var file1 = document.getElementById('file').files[0];
  var formData = new FormData();
  formData.append('file',file1);
  $.ajax({
             type: "POST",
             url: "<?=url("User/file")?>",  //同目录下的php文件
           
            data:formData,
            dataType:"json", //声明成功使用json数据类型回调
 
            //如果传递的是FormData数据类型，那么下来的三个参数是必须的，否则会报错
             cache:false,  //默认是true，但是一般不做缓存
             processData:false, //用于对data参数进行序列化处理，这里必须false；如果是true，就会将FormData转换为String类型
             contentType:false,  //一些文件上传http协议的关系，自行百度，如果上传的有文件，那么只能设置为false
             
             success: function(data){  //请求成功后的回调函数
              //alert(msg);
             // $("#result").append("<div> 你的名字是"+msg.file+"，性别："+msg.sex+"，年龄："+msg.age+"</div>");
               //$('#result').append("<img src = "+msg.file+">");
              //$("#pei").value("dsf");
              //document.getElementById("pei").value = msg.file;
              // $('#result').append("<img src = "+msg.file+">");
              //alert(data.data);
              //console.log(data.data);
              //保存当前的试题
               alert(data.data);
            }
        });
			//var filename = $(this).val();
		//	$("#originalTargetFileName").val(filename);
    //alert("点击");
    //$("#file").replaceWith('<input type="file" id="file" name="file" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="opacity:1"/>');
		});
	</script>
