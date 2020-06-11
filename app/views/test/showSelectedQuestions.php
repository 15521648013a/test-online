
<link rel="stylesheet" type="text/css" href="<?=STATIC_PATH ?>/static/css/style.css"  >
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
    data:{'id':parentId,'datas':parent.selects[parent.styleid]},//序列化
    success:function(datas){
        questions=datas.data;//获取前台所选中的题号对应的试题      //
       // alert(questions[0]['username']);
    }
  })
</script>

<!--显示题目-->

<div class="main">
	<div class="main-wrap">		
			<!-- 单选题 -->
			<div id="simple" class="anchor" ></div>
			<div class="question-wrap" id="singleselect" > 
                <div class="question-type">单选题<span>  </span></div>
                <!-- 一道题 -->
				
	            <!-- 一道题 -->
			</div>			
	</div>
</div>
<!--显示题目-->
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
<script>
    var num=1;
     $.each(questions,function(index,elem){
         if($.isPlainObject(elem)){
             //后台传的是json对象
           /* $.each(elem,function(index,elem){
             })*/  
             
                var str = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:5%">'
                     + '<div class="question-name" style="display:inline-block;"><span>'+(num++)+'</span>.'+elem['title']+'</div>'
                     + '<div class="option ">';
                 
        //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
        var json = JSON.parse(elem['options']);
         for(var k in json){
           // alert(json[k]);
            str +='<label ><input type="radio" value="A" name=" " required>'+json[k]+'</label>';
         } 
         str +="</div> </div>";
         $("#singleselect").append(str);
         }
         else alert(elem);
        
     })
     $(".question-type").css("color","red");
     $(".option p").css("display","inline-block");
     $(".question-name p").css("display","inline-block");
var jh={};
jh["m"]="kl";
jh["n"]=$("#div").html();
console.log(JSON.stringify(jh));

</script>
<script>


</script>

