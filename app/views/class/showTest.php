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
<style>


    ul {
      list-style: none;
	  z-index:9999;
    }

    .model-select-box {
      width: 120px;
      height: auto;
      line-height: 25px;
      border: 1px solid #000;
      float: left;
      margin-right: 20px;
      text-indent: 5px;
      position: relative;
      font-size: 18px;
      box-sizing: border-box
	  z-index:9999;
    }

    .model-select-text {
      height: 30px;
      padding-right: 27px;
      cursor: pointer;
      /* -moz-user-select: none;
      -webkit-user-select: none;
      user-select: none; */
    }

    .model-select-option {
      display: none;
      position: absolute;
      background: #fff;
      width: 100%;
      left: -1px;
      border: 1px solid #000;
	  z-index:9999;
    }

    .model-select-option li {
      height: 25px;
      line-height: 25px;
      color: #000;
      cursor: pointer;
	  z-index:9999;
    }

    .model-select-option li.selected {
      background: #06C;
      color: #fff;
    }

    /* 小三角 */
    .bg1{
    position: absolute;
    top:12px;
    right:5px;
    border-width: 6px;
    border-style: solid;
    border-color: #000 transparent transparent transparent;
}
</style>

<script>
$(function () {
      /*
       * 模拟网页中所有的下拉列表select
       */
      function selectModel() {
        var $box = $('div.model-select-box');
        var $option = $('ul.model-select-option', $box);
        var $txt = $('div.model-select-text', $box);
        var speed = 10;
        var $bg = $('b.bg1',$box)

        
        // 点击小三角
        $bg.click(function(){
          $option.not($(this).siblings('ul.model-select-option')).slideUp(speed, function () {
          });
          $(this).siblings('ul.model-select-option').slideToggle(speed, function () {
            // int($(this));
		  });
		 
          return false;
        })
        /*
         * 单击某个下拉列表时，显示当前下拉列表的下拉列表框
         * 并隐藏页面中其他下拉列表
         */
        $txt.click(function (e) {
			
          $option.not($(this).siblings('ul.model-select-option')).slideUp(speed, function () {
          });
          $(this).siblings('ul.model-select-option').slideToggle(speed, function () {
            // int($(this));
		  });
		  
          return false;
        });
        //点击选择，关闭其他下拉
        /*
         * 为每个下拉列表框中的选项设置默认选中标识 data-selected
         * 点击下拉列表框中的选项时，将选项的 data-option 属性的属性值赋给下拉列表的 data-value 属性，并改变默认选中标识 data-selected
         * 为选项添加 mouseover 事件
         */
          $option.find('li').each(function(index,element){
            // console.log($(this) + '1');
            if($(this).hasClass('selected')){
              $(this).parent('.model-select-option').siblings('.model-select-text').text($(this).text())
            }
            
            /*$(this).mousedown(function(){
              $(this).parent().siblings('div.model-select-text').text($(this).text())
              .attr('value', $(this).attr('data-option'));
                $option.slideUp(speed, function () {
                });
                $(this).addClass('selected').siblings('li').removeClass('selected');
                return false;
            })*/

            $(this).on('mouseover',function(){
              $(this).addClass('selected').siblings('li').removeClass('selected');
            })
		  })
		  $option.find('li').click(function(e){

			$(this).parent().siblings('div.model-select-text').text($(this).text())
              .attr('value', $(this).attr('data-option'));
      //进入试卷生成界面
           if($(this).attr("data-option")==5){
            layer_show('添加','<?=url("class/selectTest/$classid")?>',850,600);
           }else 
           if($(this).attr("data-option")==4){
            Hui_admin_tab('<a data-href="<?=url("Test/addByRendom/$classid")?>"+ data-title="随机组卷" href="javascript:;">随机组卷</a>');
           }else
           if($(this).attr("data-option")==3){
            Hui_admin_tab('<a data-href="<?=url("Test/add")?>" data-title="随机组卷" href="javascript:;">随机组卷</a>');
           }else
            if($(this).attr("data-option")==2){
              $("#file").click();
              //console.log(document.getElementById('file').files[0]);
            }else
            
              Hui_admin_tab('<a data-href="<?=url("Test/newList/$classid")?>" data-title="组卷" href="javascript:;">组卷</a>');
		  })

        //点击文档，隐藏所有下拉
        $(document).click(function (e) {
          $option.slideUp(speed, function () {
          });
        });
        
      }
    
      selectModel();
      
$("#file").change(function(){
  //alert(document.getElementById('file').files[0].tmp_name);
  //发发送ajax请求
  var file1 = document.getElementById('file').files[0];
  var formData = new FormData();
  formData.append('file',file1);
  $.ajax({
             type: "POST",
             url: "<?=url("Test/file")?>",  //同目录下的php文件
           
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
              newTest = data.data;//object
            }
        });
			//var filename = $(this).val();
		//	$("#originalTargetFileName").val(filename);
    //alert("点击");
    //$("#file").replaceWith('<input type="file" id="file" name="file" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="opacity:1"/>');
		});
    ///document.getElementById("file").change =function(){
     // alert(document.getElementById('file').files[0].name);
   // }
    })

var newTest = [];
$(document).on("click","#scanbtn",function(){
  alert(JSON.stringify(newTest));
  layer_show("预览试卷","<?=url("Test/scan/")?>",600,600);



})

</script>

</head>
<body>
<ul class="layui-nav">
  <li class="layui-nav-item "><a href="<?=url("Class/teachermanage/$classid")?>">班级信息</a></li>
  <li class="layui-nav-item ">
    <a href="<?=url("Class/members/$classid")?>">成员列表</a>
  </li>

  <li class="layui-nav-item layui-this">
    <a href="<?=url("Class/showTest/$classid")?>">班级考试</a>
  </li>
 
</ul>

<div style="display:none">
<input type="file" name="file" id="file" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="opacity:1">
<a class="btn " id="scanbtn" name="预览" style="height:30px; margin-right:5px" >预览</a>
</div>
<!--body -->
<!--成员表-->
<!--渲染学生列表-->
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>


    <!--嵌套叶-->

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
     
	</form >
  <a class="btn btn-primary radius" href="javascript:;" data-type="reload" ><i class="Hui-iconfont"></i> 检索</a>

  	</div>
		<div style="border:3px solid #f5fafe; ">
   
		<div class="cl pd-5 bg-1 bk-gray "> 
			<span class="l">
      <?php if($identify == '老师'){ ?>
				<a href="javascript:;" data-type="getCheckData" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> 
        <a href="javascript:;";	id="gh" data-type="reloadTable" class="btn btn-primary radius">刷新</a>
          
			
				<div class="model-select-box">
                <div class="model-select-text " value=""> 添加试卷</div>
                <b class="bg1"></b>
                <ul class="model-select-option ">
                  <li data-option="1" >手动编辑</li>
                  <!--
                  <li data-option="2">上传</li>
                  <li data-option="3">快速出卷</li>
                  -->
                  <li data-option="4">随机出卷</li>
                  <li data-option="5">导入</li>
                </ul>
                </div><?php }?>    
			  	</span> <span class="r">
					共有数据：<strong id="strong">3</strong> 条</span> 
				
					
				</div>
                
      	   

				<table class="table table-border table-bordered table-hover table-bg" id="usertable" lay-filter="test">
				
			
</table>
<script>
        
		
        //$(".yourshow").hide(); //隐藏所有
        //$('.yourshow:first').show(); //默认显示第一项
    

    </script>
</div>
	</div>

	   <!--嵌套叶-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/html" id="barDemo">
{{#  <?php if($identify == '老师'){ ?>  }}
  <a class="layui-btn layui-btn-xs" lay-event="setting">设置</a>
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
  <a class="layui-btn layui-btn-xs" lay-event="scan">批改</a>
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
  {{#  <?php }?>     }}
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="enter">开始考试</a>
  {{#  <?php if($identify == '老师'){ ?> }}
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="scan">成绩</a>
  {{# <?php }else{ ?> }}
  {{#  if(d.isscan ==1) { }}
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="scan">成绩</a>
  {{# } }}
  {{# <?php }?> }}
 
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
    ,url: '<?=url("Class/requestTests")?>' //数据接口
         ///传参要显示的已选id
    ,limit:10//一页多少行
	,where:{
    	'id':<?=$classid?>//ids为全局变量
					}
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {type: 'checkbox', fixed: 'left'},
      {field: 'testid', title: '编号', width:80, sort: true, fixed: 'left'}
      ,{field: 'testName',title: '试卷名', width:100}
      ,{field: 'subjectname',title: '科目名', width:80}
      ,{field: 'starttime',title: '考试时间', width:320,templet: function(d){
        if(!d.starttime) return '无';
         if(!d.endtime)  d.endtime= '';
         return d.starttime+'~'+d.endtime;   
      }}
      ,{field: 'time',title: '时长(分)', width:100}
      ,{field: 'status',title: '状态', width:100,templet: function (d) {
        if(d.status  == 1)
         return "允许考试";
         else return "不允许考试"}}
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
    $("#strong").html(count);
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
		    url:"<?=url("ClassTest/del")?>",
		    dataType:"json",
		    data: {testid:data.testid,classid:data.classid},
		    success:function(data){
			}
		  });
          obj.del();
        layer.close(index);
		  layer.msg("删除成功!", {time: 1000}, function () {});
		 
      });
    } else if(obj.event === 'edit'){
		//alert(data.userid);\
		var $ = layui.$;	
		layer_show("编辑",'<?=url('Test/edit/')?>'+data.classid+"/"+data.testid,800,600);
    }else if(obj.event === 'enter'){
        //alert("开始考试");
        //询问框
        layer.confirm('确定考试后，系统将开始倒计时，是否继续？', function(index){
        Hui_admin_tab('<a data-href='+'<?=url("Test/show/")?>'+data.classid+"/"+data.testid+ ' data-title="考试页面" href="javascript:;">考试页面</a>');
        layer.close(index);
      });
    }else if(obj.event ==="scan"){
     
      Hui_admin_tab('<a data-href="'+'<?=url("Grade/list/")?>'+data.testid+"/"+data.classid+ '" data-title="考试分析" href="javascript:;">考试分析</a>');
	}else if(obj.event==='setting'){
    layer_show("编辑",'<?=url('ClassTest/setting/')?>'+data.classid+"/"+data.testid,500,600);

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
        url:"<?=url("ClassTest/dels")?>",
		    dataType:"json",
		    data: {ids:ids,classid:<?=$classid?>},//id数组传给后台
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
  reloadTable:function(){
    table.reload('allTable', {
                   
                  });


   },
    enter: function(){
        alert("开始考试");
         //执行重载
        

    }

  };
  //调用点击事件
  $('.btn').on('click', function(){
    var type = $(this).data('type');
    active[type] ? active[type].call(this) : '';
  });

});

	</script>
