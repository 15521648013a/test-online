<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
</head>
<body>
<nav class="breadcrumb"> <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	
	<div class="cl pd-5 bg-1 bk-gray">
		<div style="text-align: center;">请选择科目</div> 
		<span class="l">
            <table  border=" 1px solid #ccc">
                <tr border=" 1px solid #ccc">
                    <label>录入时间:</label>
                    <input type="text" class="input-text  radius"" style="width: 20%;margin: 0 2px;" value=""  id="singlenumber"" name="_pass"> <label>-</label>
                    <input type="text" class="input-text  radius"" style="width: 20%;margin: 0 2px;" value=""  id="singlenumber"" name="_pass">
                    <label style="margin-left:10%">关键字:</label><input type="text" class="input-text  radius"" style="width: 10%;margin: 0 2px;" value=""  id="singlenumber"" name="_pass"><br/>
                </tr>
                <tr  class="text-c">
                    <label >难度:</label>
                    <span class="select-box radius" style="width:100px;">
                    <select class="select" name="questionType" size="1">
                        <option value="1">难度不限</option>
                        <option value="2">易</option>
                        <option value="3">中</option>s
                        <option value="4">难</option>
                        
                    </select>
                    </span> 
                    
               </tr>
             </table>
        </span>
	    <span class="r"> <a class="btn btn-primary radius" href="javascript:;" data-type="reload"><i class="Hui-iconfont"></i> 检索</a> </span></span> </div>
	<table class="table table-border table-bordered table-hover table-bg" id="table"> 
		<thead>
			<tr>
				<th scope="col" colspan="6">试卷列表</th>
			</tr>
			<tr class="text-c">
				<th width="25"><input type="checkbox" value="" name="ids[]"></th>
				<th width="40">ID</th>
				<th width="200">题目</th>
				<th>分类</th>
				<th width="300">录入时间</th>
				<th width="70">操作</th>
			</tr>
        </thead>
        
		<form class="form form-horizontal" id="form-admin-add"  action="./loginController.php?action=del_question" method="post" >
		<table class="layui-hide" id="demo" lay-filter="test">
       
        </form>
       
    </table>
    <div align="center" style="margin-top:10px">
    <label>已选<lable id='lable' style="margin-right:10px;margin-left:10px">0</lable>条</lable>
    <a class="btn btn-primary radius" href="javascript:;" onclick="getdata()"><i class="Hui-iconfont"></i> 完成</a> </span>
        </div>
</div>
<script>
parentId= parent.styleid;//父页面的数据
var pageSize = 5; //每页显示的数据条数
var currentPageNo = 1; 
var arrayIndex = 1;//第几页
var datas= [];//初始化,这是给父页面选的新数据
var selects = parent.selects[parent.styleid];//初始化,这是父页面已经选的数据中的题号
var kidSelects = [];//记录子页面当前所选中的题号，用于翻页保存
var paginate=[];//用于保存当前页的数据 kidSelects =paginate[0]+paginate[1]+...
$('#lable').html(selects.length);
kidSelects=kidSelects.concat(selects);
//查找数组某个元素的位置,成功返回数组中的位置，失败则返回-1
function find(array,id){
    for(var i=0;i<array.length;i++){
        if($.isPlainObject(array[i])){//对象
        if(id == array[i].questionNo){
            return i;//下标
        }else{        
        }
    }else{
        if(id == array[i]){ //数组
            return i;//下标
        }else{
          
        }
    }
    }
    return -1;
}
//处理表格
layui.use('table', function(){
  var table = layui.table;
  table.render({
    elem: '#demo'
    ,height: 500
    ,url: '<?=url("Test/showQuestionsList")?>' //数据接口
    ,where:{id:parent.styleid,'datas':parent.selects[parent.styleid],style:"manual",kidSelects:kidSelects} ///传参要显示的已选id
    ,limit:10//一页多少行
    ,method:'post'
    
    ,page: true //开启分页W
    ,cols: [[ //表头
        {type: 'checkbox', fixed: 'left'},
      {field: 'title', title: '题目', width:300, sort: true, fixed: 'left'}
      ,{field: 'answer', title: '答案', width:80}
      
    ]],
    done: function(res, curr, count){
       paginate[curr]=new Array();//存放当前页面数据的数组
       arrayIndex=curr;//第几页 对应 第几号数组， paginate 为二维数组 ， 下标为页号,curr 返回当前页号     
    }
    
  });
  //监听表格复选框选择
  
   //其他页选了几个
  table.on('checkbox(test)', function(obj){
   //是全选框
   if(obj.type=="all"){
    checkStatus = table.checkStatus('demo')
      ,data = checkStatus.data;
    if(JSON.stringify(data)=='[]'){
      //当前是取消全选
      $.each(paginate[arrayIndex],function(index,elem){
        var num= find(kidSelects,elem);
        kidSelects.splice(num,1);
        var num1 = find(datas,elem);
        datas.splice(num1,1);
      })
      $('#lable').html(kidSelects.length);
      paginate[arrayIndex].length=0;//清空 
    }else{
      //选择全部数据
      paginate[arrayIndex].length=0;//先全部清空，再就数据全部插入
      $.each(data,function(index,elem){
      paginate[arrayIndex].push(elem.questionNo);
      if(find(kidSelects,elem.questionNo)==-1)//存在就不用插入了
      kidSelects.push(elem.questionNo);
      if(find(datas,elem.questionNo)==-1)//存在就不用插入了
      datas.push(elem);  
    })
   }
   }
   else{
    //这是点击单个选择框
   if(obj.checked){//添加
    datas.push(obj.data);
    kidSelects.push(obj.data.questionNo);
    paginate[arrayIndex].push(obj.data.questionNo);
   }else{//删除
    //查找位置
    var index1= find(datas,obj.data.questionNo);
    var index2= find(kidSelects,obj.data.questionNo);
    var index3= find(paginate[arrayIndex],obj.data.questionNo);
    //删除元素
    datas.splice(index1,1);
    kidSelects.splice(index2,1);
    paginate[arrayIndex].splice(index3,1);
      }
   }
    $('#lable').html(kidSelects.length);      ///标签显示选了几个
    layer.msg('选中了：'+ kidSelects.length + ' 个');//页面显示选了几个
  });
    
    //定义 点击事件
  var $ = layui.$, active = {
    
    reload: function () {
    //alert("df");                                  
                 var demoReload = $('.selectquestion option:selected') .val();
                 alert(demoReload);
                  table.reload('demo', {
                    page: {
                      curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                     
                        id: demoReload,
                      
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

</script>
<script type="text/javascript">
//父窗口的题型号是否为空
//console.log(parent.questionIds[parent.styleid]);
//var hh=parent.questionIds[parent.styleid];

//else alert("空");
/*显示试题*/
//监听同时改变父窗口数据
$(document).on('click',"[name='ids[]']",function(){
    var checkId=[];var sum=0;
//alert('ni');
$("input[name='ids[]']:checked").each(function(i){
			if($(this).val()!=""){
			checkId[sum]=$(this).val();sum++;}
		});
        $(window.parent.document).find("#lable<?=$id?>").html(sum);//父窗口中的相关lable的id
        $('#lable').html(sum);//子窗口
});


/*确定所选的题目*/
function getdata(){
    //alert($("form").serialize());
    var checkId=[];var sum=0;
		$("input[name='ids[]']:checked").each(function(i){
            if($(this).val()!=""){
			checkId[sum]=$(this).val();sum++;}
		});
       // $(window.parent.document).find("#lianxi").val(sum);
        //parent.questionIds['<?=$id?>']=checkId;
        //parent.questionIds[1]=datas;//向父页面传数据
        parent.selects[parent.styleid]=parent.selects[parent.styleid].concat(kidSelects);//向父页面添加新的数据
        
        //$(window.parent.document).find("#lable"+parentId).html(num);//修改父页面的label
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index); //再执行关闭子窗口   
}


</script>
