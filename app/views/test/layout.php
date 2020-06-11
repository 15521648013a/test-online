</head>
<body>
           
<table class="layui-hide" id="demo">

dfsfs
</table>
              
          
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
 
<script>
    var pageSize = 5; //每页显示的数据条数
var currentPageNo = 1; 
layui.use('table', function(){
  var table = layui.table;
  
  table.render({
    elem: '#demo'
    ,height: 500
    ,url: '<?=url("Test/layerTable")?>' //数据接口
    ,where:{id:123,kl:parent.gh}
    ,limit:6//一页多少行
    ,method:'post'
    ,page: true //开启分页
    ,cols: [[ //表头
      {field: 'ID', title: 'ID', width:80, sort: true, fixed: 'left'}
      ,{field: 'username', title: '用户名', width:80}
      
    ]]
  });
});

</script>

