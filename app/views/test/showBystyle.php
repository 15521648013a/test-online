<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
</head>
<body>

<div class="page-container">
<form id="searchForm" action='<?=url("Question/search")?>'  method="post">
    <div class="cl pd-5 bg-1 bk-gray">
    		<div style="text-align: center;font-size: large;margin-bottom: 20px">查询条件</div> 
    		
                <table  >
                   
					<tr   >
					   
                        <label >难度:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
                        <select class="select" name="questionLevel" size="1" >
                            <option value="">难度不限</option>
                            <option value="易">易</option>
                            <option value="中">中</option>
                            <option value="难">难</option>
                            
                        </select>
                        </span> 
						<!--,,-->
						<label style="margin-left:30px">知识点:</label>
                        <span class="select-box radius" style="width:100px;"  style="margin-left:10%" >
						<select class="select" name="questionSubject" size="1" >
						<option value="">不限</option>
                        <?php foreach($Subjects as $k=>$value) { ?>
					        <option value="<?=$value['kownledgepointid']?>"><?=$value['kownledgepointname']?></option>
					    <?php }?>
						</select>
                        </span>
                        <label style="margin-left:10%">关键字:</label><input type="te1t" class="input-text  radius"" style="width: 20%;margin: 0 2px;" value=''  id="singlenumber"" name="keyWord"> 
                   </tr>
                 </table>
               
    		<span class="r"> <a class="btn btn-primary radius" href="javascript:;" data-type="reload" ><i class="Hui-iconfont"></i> 检索</a> </span></span> 
	</div>
    </form>
		<table class="layui-hide" id="demo" lay-filter="test">
       
       
       
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
var selects = parent.selects;//parent.selects[parent.styleid];//初始化,这是父页面已经选的数据中的题号
var kidSelects = [];//记录子页面当前所选中的题号，用于翻页保存
var paginate=[];//用于保存当前页的数据 kidSelects =paginate[0]+paginate[1]+...

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
  var table = layui.table;var select = parent.$("select").val();
  table.render({
    elem: '#demo'
    ,height: 500
    ,url: '<?=url("Test/showQuestionList/")?>'+select //数据接口
    ,where:{id:parent.styleid,'datas':parent.selects,style:"manual",kidSelects:kidSelects} ///传参要显示的已选id
    ,limit:10//一页多少行
    ,method:'post'
    ,id:'allTable'
    ,page: true //开启分页W
    ,cols: [[ //表头
        {type: 'checkbox', fixed: 'left'},
       {field: 'title', title: '题目', width:400, sort: true, fixed: 'left'}
     
      ,{field: 'level',title: '等级', width:100}
      ,{field: 'belonger', title: '出题人', width:300,}
      ,{field: 'subjectname', title: '科目', width:80, }
      
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
     
    checkStatus = table.checkStatus('allTable')
      ,data = checkStatus.data;
      console.log(data);
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
      //alert(data.length);console.log(data);
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
                 //var demoReload = $('#inputUser');
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

</script>
<script type="text/javascript">
//父窗口的题型号是否为空
//console.log(parent.questionIds[parent.styleid]);
var hh=parent.questionIds[parent.styleid];

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
    var checkId=[];var sum=0;
		$("input[name='ids[]']:checked").each(function(i){
            if($(this).val()!=""){
			checkId[sum]=$(this).val();sum++;}
		});
        if( !(parent.$(".active").length>0)||parent.$(".active").attr('name')=="question-wrap"){
        alert("请选择正确的插入点，或者先添加新题框。");
      }else{
        //alert("请选择正确的插入点，或者先添加新题框。");
        parent.questionIds[parent.styleid]=datas;//向父页面传数据
        //alert("请选择正确的插入点，或者先添加新题框。");
        parent.selects=parent.selects.concat(kidSelects);//向父页面的数组添加新的数据
        parent.aa(parent.styleid);//将数组里的题目添加到页面中
      }
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        parent.layer.close(index); //再执行关闭子窗口   
}
</script>
