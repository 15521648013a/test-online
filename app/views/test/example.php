
</head>
<body>

<script>
    //请求后台
    var parentId= parent.styleid;//父页面的数据
    var questions ;//用于保存从后台传来的数据;
 

    $.ajax({
    url:'<?=url('Test/layerTable1')?>',
    dataType:'json',
    async: false,//同步，
    type:'post',
    data:{'id':parentId,'datas':parent.questionIds[parentId]},//序列化
    success:function(datas){
        questions=datas.data;
        //
    }
    
  })




</script>
<table class="layui-hide" id="demo">
<div id="quest"></div>

<table>
<tbody>
    <tr><td>1</rd></tr>
    <tr><td>1</rd></tr>
    <tr><td>1</rd></tr>
    <tr><td>1</rd></tr>
    <tr><td>1</rd></tr>
    <tr><td>6</rd></tr>
    <tr><td>7</rd></tr>
    <tr><td>8</rd></tr>
    <tr><td>9</rd></tr>
    <tr><td>10</rd></tr>
    <tr><td>11</rd></tr>
    <tr><td>12</rd></tr>
    <tr><td>13</rd></tr>

</tbody>
</table>


<!--分页容器-->
<div id="test1"></div>
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
<script>
    //
     // alert(questions);
     $.each(questions,function(index,elem){
         if($.isPlainObject(elem)){
             //后台传的是json对象
            $.each(elem,function(index,elem){
               // alert(elem);
                 

             })
         }
         else alert(elem);
       
          

     })
$('#quest').html();



parentId= parent.styleid;//父页面的数据
var pageSize = 5; //每页显示的数据条数
var currentPageNo = 1; 
layui.use('table', function(){
  var table = layui.table;
  
  table.render({
    elem: '#demo'
    ,height: 500
    ,url: '<?=url("Test/layerTable")?>' //数据接口
    ,where:{'id':parentId,'datas':parent.questionIds[parentId]} ///传参要显示的已选id
    ,limit:6//一页多少行
    ,method:'post'
    //,page: true //开启分页
    ,cols: [[ //表头
      {field: 'ID', title: 'ID', width:80, sort: true, fixed: 'left'}
      ,{field: 'username', title: '用户名', width:80}
      
    ]]
  });
});

</script>
<script>
layui.use(['laypage','jquery'], function(){
 
  var laypage = layui.laypage,$ = layui.$;
  //执行一个laypage实例
  laypage.render({
    elem: 'test1' //注意，这里的 test1 是 ID，不用加 # 号
    ,count: 12 //数据总数，从服务端得到
    , limit: 10                      //每页显示条数
    , limits: [10, 20, 30]
    , curr: 1                        //起始页
    , groups: 5                      //连续页码个数
    , prev: '上一页'                 //上一页文本
    , netx: '下一页'                 //下一页文本
    , first: 1                      //首页文本
    , last: 100                     //尾页文本
    , layout: ['prev', 'page', 'next','limit','refresh','skip']
    //跳转页码时调用
    , jump: function (obj, first) { //obj为当前页的属性和方法，第一次加载first为true
        //非首次加载 do something
        console.log(obj.curr); //得到当前页，以便向服务端请求对应页的数据。
        console.log(obj.limit); //得到每页显示的条数
        if (!first) {
        	   //清空以前加载的数据
             $('tbody').html();
            //调用加载函数加载数据
            showRecord(obj.curr,obj.limit);
        }
    }
  });
});

function showRecord(curr,limit)
{
    if(curr === 2)
    $('tbody').html('<tr><td>2</td></tr>');
    else if(curr ==1){
        
        $('tbody').html('<tr><td>1</td></tr>');
    }}
</script>

