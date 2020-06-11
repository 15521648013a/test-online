
    
<link rel="stylesheet" type="text/css" href="<?=STATIC_PATH ?>/static/css/style.css"  >
</head>
<body>
<div class="main">
    <form action=" " id="testForm" method="post" >
    <div class="main-wrap">	
         <!-- 展示问题 -->
         <div id="simple" class="anchor" ></div>
    </div>
    <div class="question-act">
			   <input type="submit" onclick="tijiao();return false;" value="提交" >
    </div>
    </form>
</div>	
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
<script>
  function tijiao(){
    //判断手工评分是否合理
    flag = 1;
    $(".teacherCorrect").each(function(){
     //客观题另处理
     sum=0;
     if($(this).hasClass("objective")){
        //分割，再一一判断，小题之和不大于该题总分值
       str=$(this).val().trim().split(/\s+/);//按空格分割，成为数组；
       for(var i=0;i<str.length;i++){
        if(isNaN(str[i]) ) {alert("不能为非数字。");$(this).focus();flag = 0;return false;}
        else if((str[i])<0 )  { alert("分数不能小于0");$(this).focus();flag = 0;return false;}
       sum+=Number(str[i]);
       }
       alert(sum);
       truescore = $(this).nextAll(".score").val();
       if(sum>truescore) {alert("小题分值之和不能大于题目总分值");$(this).focus();flag = 0;return false;}
     }else{
      _score = $(this).val();
      if(isNaN(_score) ) {alert("不能为非数字。");$(this).focus();flag = 0;return false;}
      if(!$.trim(_score)) {alert("请输入得分。");$(this).focus();flag = 0;return false;}
      truescore = $(this).nextAll(".score").val();
      if(_score<0) { alert("分数不能小于0");$(this).focus();flag = 0;return false;}
      if(_score>truescore) {alert("分数不能大于题目分值");$(this).focus();flag = 0;return false;}
      //获取题目分值
      //truescore = 
     }
    })
    if(!flag) return 0;
  $.ajax({
      url:'<?=url("Test/putTestByCorrect/$testrecordid")?>',
      data:$('#testForm').serialize(),
      dataType:'json',
      type:'post',
      success:function(data){
        var new1 = data.data;
        alert("提交成功");
       var index=$(window.parent.document).find("#min_title_list li.active").index();				
	      ($(window.parent.document).find("#min_title_list li").eq(index)).find('i').click();
      }
  })
}
    			
//给问题绑定点击事件
$(document).on("click",".question-each",function(){
  $(".question-each").not($(this)).css('background-color','');
  $(this).css('background-color','#e2e2e2');
})

$(document).on("click",".question-each .question-each ",function(event){
 event.stopPropagation();
  $(this).css('background-color','#e2e2e2');
})
    //题目细节
    var littleIndex=1; var bigIndex=1;
    //显示题目
function aa(style,elem,isReNew=false,location='',myanswer='',score,truesore=0){
  //遍历数组
  if(elem){
     if(isReNew) qIndex =littleIndex++;else qIndex= bigIndex++;
    if(style ==1){
    var str = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; ">'
                    + '<div class="question-name" style="display:inline-block;margin-top:3%"><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                    + '<div class="option " style="margin-top:2%;word-wrap:break-word; word-break:break-all;"> ';          
       //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
       if(elem['options']){
       var json = JSON.parse(elem['options']);   
       j=65;
        for(var k in json){
          if(j==65)
           str +='<div class="questdiv " style="vertical-align:top;width:25%;display: inline-block;word-wrap:break-word; word-break:break-all;"><p  style="vertical-align:top;dispaly:inline;width:10px"><input type="radio" style="" value="'+String.fromCharCode(j)+'" name="'+location+'" required>'+String.fromCharCode(j)+'.</p>'+json[k]+'</div>';
           else  str +='<div class="questdiv " style="vertical-align:top;width:25%;display: inline-block;word-wrap:break-word; word-break:break-all;"><p  style="vertical-align:top;dispaly:inline;width:10px"><input type="radio" style="" value="'+String.fromCharCode(j)+'" name="'+location+'" required>'+String.fromCharCode(j)+'.</p>'+json[k]+'</div>';
           j++;
        } 
      }
      str +=("<br/></div>正确答案："+elem['answer']+"<br/>考生的回答："+myanswer+"<br/>该题分值："+truesore+"<br/>得分： "+score);
        str+='<input type="hidden"  name='+location+' value='+score+'>';
         return str+"</div>";
      }else if(style ==2){
            var str = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:5%">'
                   + '<div class="question-name" style="display:inline-block;"><span class="index">'+qIndex+'</span>.'+elem['title']+'</div>'
                   + '<div class="option" style="margin-top:2%;word-wrap:break-word; word-break:break-all;">';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                    var json = JSON.parse(elem['options']);
                    j=65;
                    for(var k in json){         
                      str +='<label ><input type="checkbox" value="'+String.fromCharCode(j)+'" name="'+location+'[]" >'+String.fromCharCode(j)+"."+json[k]+'</label>';
                      j++;
                    } 
                    str +="</div>"+"正确答案："+elem['answer']+"<br/>考生的回答："+myanswer+"<br/>该题分值："+truesore+"<br/>得分： "+score;"</div> ";
                    str+='<input type="hidden"  name='+location+' value='+score+'>';                  
                    
                    return str;
        }else if(style ==3){
          var str = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:5%">'
                   + '<div class="question-name" style="display:inline-block;"><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                   
                    str +='<div class="questdiv" style="width:100px;display:inline"><input type="radio"  style="" value="对" name="'+location+'" required>对</div>';
                      str +='<div class="questdiv" style="width:100px;display:inline"><input type="radio"  style="margin-left:10%" value="错" name="'+location+'" required>错</div>';
              
                     str +="</div>"+"正确答案："+elem['answer']+"<br/>考生的回答："+myanswer+"<br/>该题分值："+truesore+"<br/>得分： "+score+"</div>";               
                     str+='<input type="hidden"  name='+location+' value='+score+'>';  
                     return str;
                    
        }else if(style ==4){
          var str = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; ">'
                   + '<div class="question-name" style="display:inline-block;"><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
     str +=" </div>";   
     str +="<div>"+"参考答案："+elem['answer']+"</div> <div> 考生的回答："+myanswer+'</br>该题分值：'+truesore+'<br/>老师评分：<input type="text" class="teacherCorrect" name="'+location+'">';          
     str+='<input type="hidden"  class="score" value='+truesore+'></div>  </div>';  
     return str;
      
        }else {
 //最后是简答题
 var str = '<div class="question-each" contenteditable="false" name="question-each" style="border-bottom: 1px solid #7FFFD4; ">'
                + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" >删除</a><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                + '<div class="option "> </div>';   

      str +='<div> 考生的回答：<textarea  style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; width:100%;    min-height: 200px;" >'+myanswer+'</textarea></br>该题分值：'+truesore+'<br/>老师评分：<input type="text" class="teacherCorrect objective" name="'+location+'">';          
     str +="<br>"+"参考答案："+elem['answer']+" ";
     str+='<input type="hidden"  class="score" value='+truesore+'></div>  </div>';  
     return str;                 
         
}       
  }else{
 alert("这不是试题");
  } 
}
    <?php if(json_encode($allQuestion)){ ?>
    var questions = <?=json_encode($allQuestion)?>;
    var answer = <?=($answer)?>;
    var scores = <?=($scores)?>;
    var truescores = <?=($truescores)?>;
    <?php }?>
    console.log(questions);
      bigIndex=1; var _score="";
    $.each(questions,function(index1,elem){  
             //elem 是一类题  ,先生成 题框question-type ,一个题群一个框
        // var fremkName= "fremk["+index+"]";   也要找到题目再test中的位置，进行编号
        var qTitle=(elem["title"]);
         //var fremk= ' <div class="question-type" ><span style="color:#333;vertical-align: top;display: inline-block;">'+qTitle+'</span></div>';
         var fremk ='<div class="question-wrap queue"  name="question-wrap" ><div class="question-type"><div class="Qinsert" style="margin-top:10px"><h class="questionStyle">'+
         '<span style="color:#333;vertical-align: top;display: inline-block;">'+  qTitle+ ' </span></h>' +      
                                    
									'</div></div></div>';
         $(".main-wrap").append(fremk);//生成一个框
        if(elem['questions'])
        $.each(elem['questions'],function(index2,k){
          if(!k["Qstyle"]){
            //没有小题了
            //myanswer =  typeof answer[index1]['questions']['2'] != "undefined" ?answer[index1]['"questions"']['2']:'';
            //console.log(answer[index1]["'questions'"][index2]);
            var strAnswer='';var score=0,truesore=0;
            if(questions.hasOwnProperty(index1)){
            if(questions[index1]['questions'].hasOwnProperty(index2)){
             // alert("存在");
              strAnswer = answer[index1]["'questions'"][index2];
              //console.log(scores[index1]['questions']);
              score = scores[index1]["'questions'"][index2];
              truesore = truescores[index1]["questions"][index2];
              if($.isArray(score)){
               for(var i=0;i<score.length;i++){
                  _score+="第"+(i+1)+"题得： "+ score[i]+'分; ';
                  //alert(score[i]);
               }
              score = _score;
              
              }
              if($.isArray(truesore)){
               for(var i=0;i<truesore.length;i++){
                  _tscore+="第"+(i+1)+"题得： "+ truesores[i]+'分; ';
                  //alert(score[i]);
               }
               truesore = _score;
              
              }
              
             if($.isArray(strAnswer)){//多选
               strAnswer ='';
               $.each(answer[index1]["'questions'"][index2],function(index,elem){
                strAnswer +=elem;
               });
             }
            }}
            var Location= "Test["+index1+"]['questions']["+index2+"]";  //Test[0]['questions'][0],题目编号
            $(".main-wrap").children(".question-wrap").last().children(".question-type").append(aa(k['style'],k,false,Location,strAnswer,score,truesore)); //最后即最新添加
          }
          else{
           
              var kQuesstion = k["question"]; var ktitle=k["title"];//大题题干  
              var _truesore= JSON.parse(truescores[index1]["questions"][index2]);
              if($.isArray(kQuesstion)){ 
                var str1 = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:3%"><input type="hidden" name="questionNo[1][]" value="rrr">'
                    + '<div class="question-name" style="display:inline-block;margin-top:3%"><span class="index">'+(bigIndex++)+'</span><div class="bigtitle">'+ktitle+'</div></div>'
                        +'<div class="question-type "style="color: black">'
                        ;
                        //小题1
                     $.each(kQuesstion,function(index3,elem1){

                      truesore = typeof(_truesore[index3])=="undefined"?0:_truesore[index3];
                      score = typeof(scores[index1]["'questions'"][index2]["'question'"][index3])=="undefined"?0:scores[index1]["'questions'"][index2]["'question'"][index3];
                      var strAnswer='';
                      if(answer.hasOwnProperty(index1)){
                       if(answer[index1]["'questions'"].hasOwnProperty(index2))
                        if(answer[index1]["'questions'"][index2]["'question'"].hasOwnProperty(index3)){
                       // alert("存在");
                        strAnswer = answer[index1]["'questions'"][index2]["'question'"][index3];
                        score = scores[index1]["'questions'"][index2]["'question'"][index3];
                        truesore = _truesore[index3];
                       if($.isArray(strAnswer)){
                         strAnswer ='';
                         $.each(answer[index1]["'questions'"][index2]["'question'"][index3],function(index,elem){
                          strAnswer +=elem;
                         });
                       }
                      }
                      }
                    var location= "Test["+index1+"]['questions']["+index2+"]['question']["+index3+"]";  //Test[0]['questions'][0],题目编号       
                     str1+=aa(elem1['style'],elem1,true,location,strAnswer,score,truesore);    //添加一道小题      
                })                         
                     //小题2
                str1+="</div></div>";    littleIndex=1;
                $(".main-wrap").children(".question-wrap").last().children(".question-type").append(str1);   
                }
       }});  
     bigIndex=1;//重新编号
    });
    
$("p").css("display","inline");
$(".question-type span").css("color","black").css("font-size","large");//设置题目颜色
$(".option p").css("display","inline").css("vertical-align","top");
$(".question-name p").css("display","inline").css("vertical-align","top");
$(".bigtitle").css("vertical-align","top").css("display","inline-block");
$(".index").css("color","black");
newIndexq();
$(document).on('click','.questdiv',function(){
  $(this).find("input").prop("checked","checked");
});

//转罗马数字
function convert(num) {
    var a=[["","I","II","III","IV","V","VI","VII","VIII","IX"],  ["","X","XX","XXX","XL","L","LX","LXX","LXXX","XC"],  
  ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM"],
   ["","M","MM","MMM"]];  
    var i=a[3][Math.floor(num/1000)];
    var j=a[2][Math.floor(num%1000/100)];
    var k=a[1][Math.floor(num%100/10)];
    var l=a[0][num%10];
    return  i+j+k+l;
     
  }
function newIndexq(){
   
   $(".question-wrap").each(function(num1){
    $(this).children(".question-type").each(function(){
         $(this).children(".question-each").each(function(num2){
           //还有小题吗
           if($(this).children(".question-type").length>0){ 
            $(this).children(".question-name").find("span.index").html((num2+1));
            //小题取消拖动
             $(this).children(".question-type").children(".question-each").each(function(index){
                
                            $(this).attr("draggable","false");
                            $(this).removeClass("dragger");
                            $(this).children(".question-name").find("span.index").html(convert(index+1));
                            
             })
  
           } else{
                 $(this).children(".question-name").find("span.index").html(num2+1);
           }
         }) 
        // alert(num1);
         //alert(JSON.stringify(No));
  }); });
  };
</script>

 <!-- 试卷尾 -->	
    
 </div>
</div>