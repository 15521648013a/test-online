  
<style>
  .dragger{
  cursor: move;
  }
  ul {
    list-style: none;
    z-index:9999;
  }
  
  .model-select-box {
    width: 100px;
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

  //动态菜单条
  function mune(url,title){
   if(!title) title="新页面";
   $(window.parent.document).find("#menu-comments ul").append('<li type="hidden"><a data-href='+url+' data-title='+title+' href="javascript:;">'+title+'</a></li>'); 
       // alert($(window.parent.document).find("#menu-comments li").eq(0).find('a').attr('href'));
        ($(window.parent.document).find("#menu-comments li").last().find('a'))[0].click();//选择最后一个
        ($(window.parent.document).find("#menu-comments li").last()).remove();//删除
  
  }
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
        $option.find('li').click(function(event){
          event.stopPropagation();
          var flag = $(this).parent().siblings('div.model-select-text').val();
          var option = $(this).attr('data-option');
         // $(this).parent().siblings('div.model-select-text').text($(this).text());
          questionType =$(this).parent().parent().find(".model-select-text").attr("name");//获得当前题型
           var val = $(this).val();
           if($(this).parent().attr("name")=="content"){
            if(option ==1)
          { //var activename=document.activeElement.name.toLowerCase(); // 当前获取焦点的对象
                 // alert(activename);
            layer_show("文本行",'<?=url("Test/showText")?>',"800","500");//传参 questionType
          }else{
            //option=2
           alert("2");
             layer_show("单选题",url,"800","500");//传参 questionType
          }
           }else 
          if(option ==1)
          {  //判断是那个ul 下的 li         
              layer_show('添加','<?=url("Question/showAddByManual/")?>'+$(this).parent().attr("name"),800,600);
          }else{
            //option=2
            styleid = $(this).parent().attr("name");//题型id
            //alert($(this).parent().attr("name"));
             url='<?=url("Test/addQuestionBystyle/")?>'+styleid+'/'+$("select").val();
             layer_show("单选题",url,"800","500");//传参 questionType
          }
        })
  
      //点击文档，隐藏所有下拉
      $(document).click(function (e) {
        $option.slideUp(speed, function () {
        });
        //删除选中插入点
       
      });
      
    }
  
    selectModel();
  })
  $(document).on("click",".question-wrap",function(event){
    event.stopPropagation();
    $(this).css('background-color','#e2e2e2');
    //设置 active
    $(".active").not($(this)).css('background-color','');
    $(".active").removeClass('active');
    $(this).addClass("active");
    $(".insert").hide();
    $(this).children('.Qinsert').children('input').show();
  })
  //给问题绑定点击事件
  $(document).on("click",".question-each",function(event){
    event.stopPropagation();
    $(".question-each").not($(this)).css('background-color','');
    $(this).css('background-color','#e2e2e2');
    $(".active").not($(this)).css('background-color','');
    $(".active").not($(this)).removeClass('active');
    $(this).addClass("active");
    //显示删除按钮和 插入点delbtn
    $(".delbtn").hide(); 
    $(".insert").hide();
    
    $(this).find(".delbtn").show();
    $(this).find(".insert").show();
  })
  
  $(document).on("click",".question-each .question-each ",function(event){
   // $(".question-each .question-each").not($(this)).css('background-color','');
   event.stopPropagation();
   $(".active").css('background-color','');
   $(".active").removeClass('active');
    $(this).addClass("active");
   
    $(this).css('background-color','#e2e2e2');
  })
  
  $(document).on("click","p",function(event){
   // $(".question-each .question-each").not($(this)).css('background-color','');
   event.stopPropagation();
   $(".active").not($(this)).css('background-color','');
    $(".active").not($(this)).removeClass('active');
    $(this).addClass("active");
   
    $(this).css('background-color','#e2e2e2');
  })
  $(document).on("click",".upbtn",function(event){
      var find = $(".active");
      var brother=  find.prev(); //上一个节点
      if(find.attr("name")==brother.attr("name"))//同类型
      find.after(brother);
      newIndexq();
  })
  
  $(document).on("click",".downbtn",function(event){
    var find = $(".active");
      var brother=  find.next(); //下一个节点
      if(find.attr("name")==brother.attr("name"))//同类型
      brother.after(find);
      newIndexq();
  })
  var titledom;var title;//保存点击对象
  $(document).on("click",".editbtn",function(event){
    // alert($(this).next().find('p').html());
    titledom= $(this).next().find('span');
    title= $(this).next().find('span').html();
    // alert(title);
     layer_show("文本行",'<?=url("Test/showTextByEdit/")?>'+title,"800","500");//传参 questionType
  })
  $(document).on("click",".editQuestion",function(event){
    // alert($(this).next().find('p').html());
   
    titledom= $(this).parent().parent();//保存一道题question-each
    //区分是题库还是手写的
    if(parseInt(titledom.children("input[name='No']").val()) ){
        //将题号发个后端
        layer_show("文本行",'<?=url("Test/showQuestionByEdit/")?>'+titledom.children("input[name='No']").val(),"800","500");//传参 questionType
    }else{
    //alert("dsf");
    console.log(titledom.children("input[name='No']").val());
    //alert(encodeURI(titledom.children("input[name='No']").val()));
    var url="{dsfsf/p%20dfsdfsffsf%3C/p%3E}";
    var url1=encodeURIComponent(encodeURIComponent(titledom.children("input[name='No']").val()));
    alert(url+'******'+url1);
      //json类型
    layer_show("文本行",'<?=url("Test/showQuestionByEdit2/")?>'+url1,"800","500");//传参 questionType
  }
  title= $(this).next().find('span').html();
  // alert(title);

  })  
</script>
<!-- 功能菜单-->
<div style="position: fixed; background: #aeceef;">
  <div class="model-select-box">
              <div class="model-select-text " name="singleselect"> 文本行</div>
              <b class="bg1"></b>
              <ul class="model-select-option " name="content"> 
                <li data-option="1" >普通文字</li>
                <li data-option="2">题干文字</li>
              </ul>
  </div>
  <div class="model-select-box">
              <div class="model-select-text " name="singleselect"> 单选题</div>
              <b class="bg1"></b>
              <ul class="model-select-option " name="1"> 
                <li data-option="2" >题库导入</li>
                <li data-option="1">手动编辑</li>
              </ul>
  </div>
  <div class="model-select-box">
              <div class="model-select-text " name="mulityselect"> 多选题</div>
              <b class="bg1"></b>
              <ul class="model-select-option " name="2">
                <li data-option="2" >题库导入</li>
                <li data-option="1">手动编辑</li>
              </ul>
  </div>
  <div class="model-select-box">
              <div class="model-select-text " name="yn"> 判断题</div>
              <b class="bg1"></b>
              <ul class="model-select-option " name="3"> 
              <li data-option="2" >题库导入</li>
                <li data-option="1">手动编辑</li>
              </ul>
  </div>
  <div class="model-select-box">
              <div class="model-select-text " name="fill"> 填空题</div>
              <b class="bg1"></b>
              <ul class="model-select-option " name= "4">
              <li data-option="2" >题库导入</li>
                <li data-option="1">手动编辑</li>
              </ul>
  </div>
  <div class="model-select-box">
              <div class="model-select-text " name="fill"> 简答题</div>
              <b class="bg1"></b>
              <ul class="model-select-option " name= "6">
              <li data-option="2" >题库导入</li>
                <li data-option="1">手动编辑</li>
              </ul>
  </div>
  <a class="btn upbtn" style="height:30px; margin-right:5px" name="">上移 </a>
  <a class="btn downbtn" style="height:30px; margin-right:5px" name="">下移</a>
</div>
<div class="main">
    <?=$other?>
    <form action=" " id="container" method="post" >
    <input type="hidden" name="testid" value="<?=$testid?>">
    <div class="main-wrap">	
    <div style="    margin-top: 15px;margin-bottom: 10px;">
    试卷名：<input type="text" class="input-text" style="width: 20%;" value=<?=$Tests["testName"]?> placeholder=""" id="testname" name="testname">
    科目：<span class="select-box" style="width:150px;">
                <select class="select" name="subject" size="1">
                    <?php foreach($Subjects as $k=>$value){ ?>
                    <option value='<?=$k+1?>'><?=$value['subjectname']?></option>
                    <?php } ?>
                </select>
                </span> 
               
    总分：<input type="text" class="input-text" style="width: 20%;" value=<?=$Tests["totalscore"]?> placeholder=""" id="score" name="score">
    <a class="btn score" style="height:30px; margin-right:5px" name="">统计总分</a>
    考试时长：<input type="text" class="input-text" style="width: 10%;" value="<?=$Tests["time"]?>" placeholder=""" id="time" name="time">分钟
    </div>
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
  $(".score").click(function(){
    sum=0;
   $("input[name='Score']").each(function(index,elem){
     if(isNaN( elem.value)){
       alert("非数字！");$(this).focus();return 0;
     }else{
     sum+=parseFloat( $.trim(  elem.value  ) );
     }
   })
   $("#score").val(sum);
  })
  function tijiao(){
    var flag=1;
    $("input[name='Score']").each(function(){
      if(!$.trim($(this).val())){
        alert("请输入分值");
        $(this).focus();flag=0;
        return false;
      }else{
        if( isNaN( $(this).val() )  ){
          alert("非数字！");
          $(this).focus();flag=0;
         return false;
        }
      }
    })
    if(flag)
    {
      var No=[];
      var Score=[];//详细分值
      $(".question-wrap").each(function(num1){
      $(this).children(".question-type").each(function(){
              No[num1]={}; 
              No[num1]["titleType"]=$(this).find(".questionStyle span").html(); 
              No[num1]["questions"]=[];
              Score[num1]={}; 
              Score[num1]["titleType"]=$(this).find(".questionStyle span").html(); 
              Score[num1]["questions"]=[];
           $(this).children(".question-each").each(function(num2){
             //还有小题吗
             if($(this).children(".question-type").length>0){
               //再遍历、保存题干
               var xiao = {}; var score={}
              // var title = $(this).children(".question-name").find("p").text();//大题题干
               var title = $(this).children(".question-name").find(".bigtitle").html();//大题题干
               xiao["Bigtitle"]=title;  score["Bigtitle"]=title;
               $(this).children(".question-type").children(".question-each").each(function(index){
                 xiao[index]=$(this).children("input[name='No']").val();
                 score[index]=parseFloat($(this).children("input[name='Score']").val());
                //alert("大题下的小题："+$(this).children("input").val());
               })
               //alert(JSON.stringify(xiao));
               No[num1]["questions"][num2]=JSON.stringify(xiao);
               Score[num1]["questions"][num2]=JSON.stringify(score);
             } else{
            // alert("大题："+$(this).children("input").val());
           // alert(num2);
             No[num1]["questions"][num2]=$(this).children("input[name='No']").val();
             Score[num1]["questions"][num2]=parseFloat($.trim($(this).children("input[name='Score']").val()));
             }
           }) 
          // alert(num1);
           //alert(JSON.stringify(No));
      }); });
      $.ajax({
          url:'<?=url('Test/putTestByEdit')?>',
          data: {question:JSON.stringify(No),Score:JSON.stringify(Score),testid:<?=$testid?>,classid :<?=$classid?>,
            testname:$("#testname").val(),score:$("#score").val(),time:$("#time").val()
          },
          dataType:'json',
          type:'post',
          success:function(data){
            var new1 = data.data;
           alert("提交成功");
           parent.$("#gh")[0].click();
           var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                               parent.layer.close(index); //再执行关闭子窗口 
            // console.log(new1["'questions'"]);
          }
  
     })
    }
  }
  $(document).on("click",".question-each .question-each ",function(event){
   // $(".question-each .question-each").not($(this)).css('background-color','');
   event.stopPropagation();
    $(this).css('background-color','#e2e2e2');
  });
  var styleid =1;
  var parentStyle= 'testAdd';
    //var questionId=new Array('singlenum','multiplenum');//接受子窗口选择的试题号数组。
//定义接收从子窗口所选中的题目的数组。根据数据中的情况生成
var  questionIds= {};
var questionByManual=[];//保存当前手动输入的试题 二维数组 questionByManual[1][{elem}] 1表示题型 ，{elem}表示试题
//抽出所选的题号
var selects = [];var num=[];
<?php foreach($QuestionTypes as $k=>$questionType) { ?>
  questionIds['<?=$questionType['question_type_id']?>']=[];  //接受选中数据的同时
 // selects['<?=$questionType['question_type_id']?>']=[];
  num['<?=$questionType['question_type_id']?>']=1;//题号
  <?php } ?>
  $(".question-type").css("color","black");//设置题目颜色
  $(".option p").css("display","inline-block");
  $(".question-name p").css("display","inline-block");
  var countIndex=0;
  var questionType='';
    //题目细节
    var littleIndex=1; var bigIndex=1;
    //显示题目
    function aa1(style,elem,isReNew=false,location='',trueScore=0){
  //isExit(style);
  //遍历数组
  if(elem){
     // alert(elem['title']);
    //根据style ,将题目加到那种题型下
     //是不是小题重新排序
     selects.push(elem["questionNo"]);
     if(isReNew) qIndex =littleIndex++;else qIndex= bigIndex++;
    if(style ==1){
    var str = '<div class="question-each dragger" name="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:3%"><input type="hidden" name = "No" value='+elem["questionNo"]+'>'
                    + '<div class="question-name" style="display:inline-block;margin-top:3%"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" >删除</a><a class="btn editQuestion" style="height:30px; margin-right:5px" name=""> 编辑</a><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                    + '<div class="option " style="margin-top:2%;word-wrap:break-word; word-break:break-all;"> ';
                
       //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
       if(elem['options']){
       var json = JSON.parse(elem['options']);
      // console.log(json);
       
       j=65;
        for(var k in json){
          if(j==65)
           str +='<div class="questdiv " style="vertical-align:top;width:200px;display: inline-block;margin-right:2%;word-wrap:break-word; word-break:break-all;"><p  style="vertical-align:top;dispaly:inline;"><input type="radio" style="" value="'+String.fromCharCode(j)+'" name="'+location+'" required>'+String.fromCharCode(j)+'.</p>'+json[k]+'</div>';
           else  str +='<div class="questdiv " style="vertical-align:top;width:200px;display: inline-block;;margin-right:2%;word-wrap:break-word; word-break:break-all;"><p  style="vertical-align:top;dispaly:inline;"><input type="radio" style="" value="'+String.fromCharCode(j)+'" name="'+location+'" required>'+String.fromCharCode(j)+'.</p>'+json[k]+'</div>';
           j++;
        } 
      }
        str +='</div> 分值：<input type="text" name = "Score" required value='+trueScore+'></div> ';
        //$("#singleselect").append(str);
        //返回一道题
      
        //str={};
       // num[style]=++qIndex; 
         return str;
     
      }else if(style ==2){
          
            var str = '<div class="question-each dragger" name="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:5%"><input type="hidden" name = "No" value='+elem["questionNo"]+'>'
                   + '<div class="question-name" style="display:inline-block;"><span class="index">'+qIndex+'</span>.'+elem['title']+'</div>'
                   + '<div class="option" style="margin-top:2%;word-wrap:break-word; word-break:break-all;">';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                    var json = JSON.parse(elem['options']);
                    j=65;
                    for(var k in json){
                     // alert(json[k]);
                      str +='<label ><input type="checkbox" value="'+String.fromCharCode(j)+'" name="'+location+'[]" >'+String.fromCharCode(j)+'.'+json[k]+'</label>';
                      j++;
                    } 
                    str +='</div>分值：<input type="text" name = "Score" required value='+trueScore+'></div> ';
                     
                    // $("#singleselect").append(str);
                  
                    // str={};
                     num[style]++;
                    return str;
        //清空questionIds[1]，接收新的
        }else if(style ==3){
          var str = '<div class="question-each dragger" name="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:5%"><input type="hidden" name = "No" value='+elem["questionNo"]+'>'
                   + '<div class="question-name" style="display:inline-block;"><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                   
                      str +='<div class="questdiv" style="width:100px;display:inline"><input type="radio"  style="" value="对" name="'+location+'" required>'+"对"+"</div>";
                      str +='<div class="questdiv" style="width:100px;display:inline"><input type="radio"  style="margin-left:10%" value="错" name="yn['+num[style]+']" required>'+"错"+'</div>';
                      
                        str +='</div> 分值：<input type="text" name = "Score" required value='+trueScore+'></div> '; 
                     
                     //$("#yn").append(str);
                  
                    // str={};
                     num[style]++;   return str;
        }else if(style ==4){
          var str = '<div class="question-each dragger" name="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:5%"><input type="hidden" name = "No" value='+elem["questionNo"]+'>'
                   + '<div class="question-name" style="display:inline-block;"><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
     str +=" <p>请输入答案：<input type=\"text\" name="+location+" required></p> ";
     str +='</div> 分值：<input type="text" name = "Score" required value='+trueScore+'></div> ';       
     //  $("#fill").append(str);
     
       num[style]++; return str;

        }else {
 
 //最后是简答题
 //处理多选
 var str = '<div class="question-each dragger" name="question-each" contenteditable="false" name="question-each" style="border-bottom: 1px solid #7FFFD4; "><input type="hidden" name = "No" value='+elem["questionNo"]+'>'
                + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" >删除</a><a class="btn editQuestion" style="height:30px; margin-right:5px" name=""> 编辑</a><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                + '<div class="option "> ';        
  str +=' <p>请输入答案：<textarea contenteditable="true" style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; width:100%;    min-height: 200px;" name='+location+'> </textarea></p> </div> 分值：<input type="text" name = "Score" required value='+trueScore+'></div>';             
     
       num[style]++;  return str;
              

}
        
  }else{
    //从题号中读取
 
  
  }
    $(".question-type").css("color","black");//设置题目颜色
    $(".option p").css("display","inline");
    $(".question-name p").css("display","inline");
}

    //动态选择题目
    <?php $allQuestion=isset($allQuestion)?$allQuestion:'';?>
    
    <?php if($allQuestion){ ?>
    var questions = <?=$allQuestion?>;
    console.log(<?=$truescores?>);
    <?php }else{ ?>
    
    var questions = parent.newTest;
    <?php }?>
      bigIndex=1;
      var TrueScore = <?=$truescores?>;
      console.log(questions);
    $.each(questions,function(index1,elem){  
             //elem 是一类题  ,先生成 题框question-type ,一个题群一个框
        // var fremkName= "fremk["+index+"]";   也要找到题目再test中的位置，进行编号
        var qTitle=(elem["title"]);
         //var fremk= ' <div class="question-type" >'+qTitle+'<span> 编辑题目  </span></div>';
         var fremk ='<div class="question-wrap queue"  name="question-wrap" ><div class="question-type"><div class="Qinsert" style="margin-bottom:20px;margin-top:10px"><a class="btn titlebtn" name="新题" style="height:30px; margin-right:5px" >删除</a><a class="btn editbtn" style="height:30px; margin-right:5px" name=""> 编辑</a><h class="questionStyle">'+
         '<span style="color:#333;vertical-align: top;display: inline-block;">'+  qTitle+ ' </span></h>' +      
                                    
									'</div><p>&nbsp;</p></div></div>';
         $(".main-wrap").append(fremk);//生成一个框
        if(elem['questions']){
        $.each(elem['questions'],function(index2,k){
          if(!$.isPlainObject(k)){
           k=JSON.parse(k);
           // alert("sd");
          }else {
            //alert("sds");
          }
          if(!k["Qstyle"]){
            //没有小题了
            trueScore=TrueScore[index1]['questions'][index2]; 
            var Location= "Test["+index1+"]['questions']["+index2+"]";  //Test[0]['questions'][0],题目编号
            $(".main-wrap").children(".question-wrap").last().children(".question-type").append(aa1(k['style'],k,false,Location,trueScore)); //最后即最新添加
          }
          else{
              var kQuesstion = k["question"]; var ktitle=k["title"];//大题题干  
              
              if($.isArray(kQuesstion)){ 
                var str1 = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:3%"><input type="hidden" name="questionNo[1][]" value="rrr">'
                    + '<div class="question-name" style="display:inline-block;margin-top:3%"><span class="index">'+(bigIndex++)+'</span><div class="bigtitle" style="display:inline-block">'+ktitle+'</div></div>'
                        +'<div class="question-type "style="color: black">'
                        ;
                        //小题1
                        //解码
                      var xscore=  JSON.parse(TrueScore[index1]['questions'][index2]);
                     $.each(kQuesstion,function(index3,elem1){
                    var location= "Test["+index1+"]['questions']["+index2+"]['question']["+index3+"]";  //Test[0]['questions'][0],题目编号       
                    trueScore=xscore[index3];
                     str1+=aa1(elem1['style'],elem1,true,location,trueScore);    //添加一道小题      
                })                         
                     //小题2
                str1+="</div></div>";    littleIndex=1;
                $(".main-wrap").children(".question-wrap").last().children(".question-type").append(str1);   
                }
       }});  
     bigIndex=1;//重新编号
      }
    });
    

    $(".question-type").css("color","black");//设置题目颜色
    $(".option p").css("display","inline").css("vertical-align","top");
    $(".question-name p").css("display","inline");
    $(".index").css("color", "black");
    newIndexq();
//给选项设置监听事件
$(document).on('click','.questdiv',function(){
  $(this).find("input").prop("checked","checked");
 // alert("sd");
})
function newIndexq(){
   
   $(".question-wrap").each(function(num1){
    $(this).children(".question-type").each(function(){
         $(this).children(".question-each").each(function(num2){
           //还有小题吗
           if($(this).children(".question-type").length>0){ 
            $(this).children(".question-name").find("span.index").html(num2+1);
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
  }


  ///////////////////
  function aa(style,elem){
  //isExit(questionType);
  //遍历数组
  if(elem){
    selects.push(elem["questionNo"]);
    //alert('asd');
    //根据style ,将题目加到那种题型下
    if(style ==1){
      
      var str = '<div class="question-each dragger" name="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:3%"><input type="hidden" name="No" value=\''+ JSON.stringify(elem)+'\'>'
                    + '<div class="question-name" style="display:inline-block;margin-top:3%"><a class="btn editQuestion" style="height:30px; margin-right:5px" name=""> 编辑</a><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                    + '<div class="option " style="margin-top:2%;word-wrap:break-word; word-break:break-all;"> ';
                
       //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
       if(elem['options']){
       var json = JSON.parse(elem['options']);
      // console.log(json);
       
       j=65;
       for(var k in json){
          if(j==65)
           str +='<div class="questdiv " style="vertical-align:top;width:200px;display: inline-block;margin-right:2%;word-wrap:break-word; word-break:break-all;"><p  style="vertical-align:top;dispaly:inline;"><input type="radio" style="" value="'+String.fromCharCode(j)+'" name="" required>'+String.fromCharCode(j)+'.</p>'+json[k]+'</div>';
           else  str +='<div class="questdiv " style="vertical-align:top;width:200px;display: inline-block;;margin-right:2%;word-wrap:break-word; word-break:break-all;"><p  style="vertical-align:top;dispaly:inline;"><input type="radio" style="" value="'+String.fromCharCode(j)+'" name="" required>'+String.fromCharCode(j)+'.</p>'+json[k]+'</div>';
           j++;
        }  
      }
        str +='</div>分值：<input type="text" name = "Score" required ></div>  ';
        var flag=0; var num1=1;
        $(".active").after(str);
        $(".active").parent(".question-type").children(".question-each").each(function(elem){
                         //alert("dsf");
                      $(this).children(".question-name").find("span.index").html(num1++);
                      })
       // $("#singleselect").
       // str={};
        num[style]++;
        //重新排序
        

      }else if(style ==2){
            var str = '<div class="question-each" contenteditable="false" name="question-each" style="border-bottom: 1px solid #7FFFD4; "><input type="hidden" name="No" value=\''+ JSON.stringify(elem)+'\'>'
                   + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" name="'+style+'">删除</a><span class="index">'+(num[style])+'</span>.'+elem['title']+'</div>'
                   + '<div class="option ">';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                    var json = JSON.parse(elem['options']);
                    j=65;
                    for(var k in json){
                     // alert(json[k]);
                      str +='<label ><input type="checkbox" value="'+String.fromCharCode(j)+'" name="multiple['+num[style]+'][]" >'+json[k]+'</label>';
                      j++;
                    } 
                     str +='</div></div> <p><br></p>';
                      var flag=0;
                     if($(".insert").length > 0){
                         $(".insert").each(function(){
                
                           if($(this).val()=="插入点"){
                               $(this).parent().after(str); flag=1;
                               //排序
                               $(this).parents(".question-type").find(".question-each").each(function(elem){
                                    $(this).find("span.index").html(num1++);
                                    })
                           }
                         })
                         if(!flag)  $(".question-type").append(str);//不指定插入点，默认从最后插
                         }else{
                           $(".question-type").append(str);
                         }
                     str={};
                     num[style]++;
        //清空questionIds[1]，接收新的
        }else if(style ==3){
          var str = '<div class="question-each" contenteditable="false" name="question-each" style="border-bottom: 1px solid #7FFFD4;"><input type="hidden" name="No" value=\''+ JSON.stringify(elem)+'\'>'
                   + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" name="'+style+'">删除</a><span class="index">'+(num[style])+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                    var json = JSON.parse(elem['options']);
                    j=65;
                    for(var k in json){
                      if(j==65)
                      str +='<div class="questdiv" style="width:100px;display:inline"><input type="radio"  style="" value="'+json[k]+'" name="yn['+num[style]+']" required>'+json[k];
                      else str +='</div><div class="questdiv" style="width:100px;display:inline"><input type="radio"  style="margin-left:10%" value="'+json[k]+'" name="yn['+num[style]+']" required>'+json[k];
                      j++;
                        } 
                     str +="<div></div></div><p><br></p> ";
                     
                     var flag=0;
                     if($(".insert").length > 0){
                         $(".insert").each(function(){
                
                           if($(this).val()=="插入点"){
                               $(this).parent().after(str); flag=1;
                               //排序
                               $(this).parents(".question-type").find(".question-each").each(function(elem){
                                    $(this).find("span.index").html(num1++);
                                    })
                           }
                         })
                         if(!flag)  $(".question-type").append(str);//不指定插入点，默认从最后插
                         }else{
                           $(".question-type").append(str);
                         }
                     str={};
                     num[style]++;
        }else if(style ==4){
          var str = '<div class="question-each" contenteditable="false"  name="question-each"  style="border-bottom: 1px solid #7FFFD4; "><input type="hidden" name="No" value=\''+ JSON.stringify(elem)+'\'>'
                   + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn"  contenteditable="false" style="height:30px; margin-right:5px;display: none;display: none;" name="'+style+'">删除</a><span class="index">'+(num[style])+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
     str +=" <p>请输入答案：<input type=\"text\"  style=\"BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none;\" name=\"fill["+num[style]+"]\" required></p> </div> </div><p><br></p>";             
     var flag=0;
                     if($(".insert").length > 0){
                         $(".insert").each(function(){
                
                           if($(this).val()=="插入点"){
                               $(this).parent().after(str); flag=1;
                               //排序
                               $(this).parents(".question-type").find(".question-each").each(function(elem){
                                    $(this).find("span.index").html(num1++);
                                    })
                           }
                         })
                         if(!flag)  $(".question-type").append(str);//不指定插入点，默认从最后插
                         }else{
                           $(".question-type").append(str);
                         }
                     str={};
                     num[style]++;

        }else {
   // alert("jiand");
 //最后是简答题
 //处理多选

 var str = '<div class="question-each" contenteditable="false" name="question-each" style="border-bottom: 1px solid #7FFFD4; "><input type="hidden" name="No" value=\''+ JSON.stringify(elem)+'\'>'
                + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;" >删除</a><a class="btn editQuestion" style="height:30px; margin-right:5px" name=""> 编辑</a><span class="index">'+3+'</span>.'+elem['title']+'</div>'
                + '<div class="option "> ';        
  str +=' <p>请输入答案：<div style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none;height:100px"  ></div></p> </div>分值：<input type="text" name = "Score" required > </div><p><br></p>';             
                  var flag=0; var num1=1;
                   //判断焦点是哪个控件，题框择往最后插入，如果是小题，则往小题后面插
                   $(".active").after(str);//alert(str);
                  $(".active").parent(".question-type").children(".question-each").each(function(elem){
                         //alert("dsf");
                      $(this).children(".question-name").find("span.index").html(num1++);
                      })
                  //num[style]++;

 //清空questionIds[1]，接收新的
 
}
        
  }else{
    //从题号中读取
  console.log(questionIds[style]);
  //动态选中 题型位置进行添加，style = 1.单选，style= 2 ,多选
  if(style==1){

  $.each(questionIds[style],function(index,elem){ 
      var str = '<div class="question-each dragger"  contenteditable="false" name="question-each"  style="border-bottom: 1px solid #7FFFD4;"><input type="hidden" name = "No" value='+elem["questionNo"]+'>'
                    + '<div class="question-name" style="display:inline-block;margin-top:3%" contenteditable="true"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" name="'+style+'">删除</a><span class="index">'+(num[style])+'</span>.'+elem['title']+'</div>'
                    + '<div class="option " style="margin-top:2%"> ';         
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                    var json = JSON.parse(elem['options']);
                    j=65;
                     for(var k in json){
                       if(j==65)
                        str +='<div class="questdiv" style="width:200px;display:inline;"><input type="radio" style="" value="'+String.fromCharCode(j)+'" name="single['+num[style]+']" required>'+String.fromCharCode(j)+'.'+json[k]+'</div>';
                        else  str +='<div class="questdiv" style="width:200px;display:inline;margin-left:10%"><input type="radio" style="" value="'+String.fromCharCode(j)+'" name="single['+num[style]+']" required>'+String.fromCharCode(j)+'.'+json[k]+'</div>';
                        j++;
                     } 
                     str +='</div> 分值：<input type="text" name = "Score" required></div> ';
                     
                  
                      var flag=0; var num1=1;
                      //判断焦点是哪个控件，题框择往最后插入，如果是小题，则往小题后面插
                      var name = $(".active").attr("name");
                      
                        if(name == "question-wrap"){
                          $(".active").children(".question-type").append(str);
                          //$(".active").children(".question-type").children(".question-each").each(function(elem){
                          //$(this).children(".question-name").find("span.index").html(num1++);
                         // })
                        }
                        else{
                          $(".active").after(str);
                         // $(".active").parent(".question-type").children(".question-each").each(function(elem){
                          //$(this).children(".question-name").find("span.index").html(num1++);
                          //})


                        }
                      
                      
                     str={};
                     num[style]++;
  })
  //清空questionIds[1]，接收新的
  questionIds[style]=[];
  countIndex++;//题号加一
  }else if(style==2){
  //处理多选
  $.each(questionIds[style],function(index,elem){ 
    var str = '<div class="question-each" contenteditable="false" name="question-each" style="border-bottom: 1px solid #7FFFD4; "><input type="hidden" name="No" value='+elem["questionNo"]+'>'
                   + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" name="'+style+'">删除</a><span class="index">'+(num[style])+'</span>.'+elem['title']+'</div>'
                   + '<div class="option ">';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                    var json = JSON.parse(elem['options']);
                    j=65;
                    for(var k in json){
                     // alert(json[k]);
                      str +='<label ><input type="checkbox" value="'+String.fromCharCode(j)+'" name="multiple['+num[style]+'][]" >'+json[k]+'</label>';
                      j++;
                    } 
                     str +='</div></div><p><br></p> ';
                  
                    
                     var flag=0; var num1=1;
                      //判断焦点是哪个控件，题框择往最后插入，如果是小题，则往小题后面插
                      var name = $(".active").attr("name");
                    
                        if(name == "question-wrap"){
                          $(".active").children(".question-type").append(str);
                          $(".active").children(".question-type").children(".question-each").each(function(elem){
                          $(this).children(".question-name").find("span.index").html(num1++);
                          })
                        }else{
                          $(".active").after(str);
                          $(".active").parent(".question-type").children(".question-each").each(function(elem){
                          $(this).children(".question-name").find("span.index").html(num1++);
                          })


                        }
                     str={};
                     num[style]++;

  }) //清空questionIds[1]，接收新的
  questionIds[style]=[];
  countIndex++;//题号加一

  }else if(style==3){
  //处理多选
  $.each(questionIds[style],function(index,elem){ 
    var str = '<div class="question-each"   name="question-each" style="border-bottom: 1px solid #7FFFD4;"><input type="hidden" name="No" value='+elem["questionNo"]+'>'
                   + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" name="'+style+'">删除</a><span class="index">'+(num[style])+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                    var json = JSON.parse(elem['options']);
                    j=65;
                    for(var k in json){
                      if(j==65)
                      str +='<div class="questdiv" style="width:100px;display:inline"><input type="radio"  style="" value="'+json[k]+'" name="yn['+num[style]+']" required>'+json[k]+'</div>';
                      else str +='<div class="questdiv" style="width:100px;display:inline"><input type="radio"  style="margin-left:10%" value="'+json[k]+'" name="yn['+num[style]+']" required>'+json[k]+'</div>';
                      j++;
                        } 
                     str +='</div></div><p><br></p> ';
                     
                     var flag=0; var num1=1;
                      //判断焦点是哪个控件，题框择往最后插入，如果是小题，则往小题后面插
                      var name = $(".active").attr("name");
                    
                        if(name == "question-wrap"){
                          $(".active").children(".question-type").append(str);
                          $(".active").children(".question-type").children(".question-each").each(function(elem){
                          $(this).children(".question-name").find("span.index").html(num1++);
                          })
                        }else{
                          $(".active").after(str);
                          $(".active").parent(".question-type").children(".question-each").each(function(elem){
                          $(this).children(".question-name").find("span.index").html(num1++);
                          })


                        }
                     str={};
                     num[style]++;


  }) //清空questionIds[1]，接收新的
  questionIds[style]=[];
  countIndex++;//题号加一

  }else if(style==4){
 
    //最后是填空题
    //处理多选
  $.each(questionIds[style],function(index,elem){ 
    var str = '<div class="question-each" contenteditable="false" name="question-each" style="border-bottom: 1px solid #7FFFD4; "><input type="hidden" name="No" value='+elem["questionNo"]+'>'
                   + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" name="'+style+'">删除</a><span class="index">'+(num[style])+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
     str +=' <p>请输入答案：<input type=\"text\"  style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; name=fill['+num[style]+'"] required></p> </div> <input type="text" placeholder="插入" class="insert" style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; display: none;"></div><p><br></p>';             
                     var flag=0; var num1=1;
                      //判断焦点是哪个控件，题框择往最后插入，如果是小题，则往小题后面插
                      var name = $(".active").attr("name");
                    
                        if(name == "question-wrap"){
                          $(".active").children(".question-type").append(str);
                          $(".active").children(".question-type").children(".question-each").each(function(elem){
                          $(this).children(".question-name").find("span.index").html(num1++);
                          })
                        }else{
                          $(".active").after(str);
                          $(".active").parent(".question-type").children(".question-each").each(function(elem){
                          $(this).children(".question-name").find("span.index").html(num1++);
                          })


                        }
                     num[style]++;

    }) //清空questionIds[1]，接收新的
    questionIds[style]=[];
  }else {
 
 //最后是简答题
 //处理多选
$.each(questionIds[style],function(index,elem){ 
 var str = '<div class="question-each dragger" contenteditable="false" name="question-each" style="border-bottom: 1px solid #7FFFD4; "><input type="hidden" name="No" value='+elem["questionNo"]+'>'
                + '<div class="question-name" style="display:inline-block;"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" name="'+style+'">删除</a><span class="index">'+(num[style])+'</span>.'+elem['title']+'</div>'
                + '<div class="option "> ';        
  str +=' <p>请输入答案：<div style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none;height:100px" name=fill['+num[style]+'"] ></div></p> </div> </div><p><br></p>';             
                  var flag=0; var num1=1;
                   //判断焦点是哪个控件，题框择往最后插入，如果是小题，则往小题后面插
                   var name = $(".active").attr("name");
                 
                     if(name == "question-wrap"){
                       $(".active").children(".question-type").append(str);
                       $(".active").children(".question-type").children(".question-each").each(function(elem){
                       $(this).children(".question-name").find("span.index").html(num1++);
                       })
                     }else{
                       $(".active").after(str);
                       $(".active").parent(".question-type").children(".question-each").each(function(elem){
                       $(this).children(".question-name").find("span.index").html(num1++);
                       })


                     }
                  num[style]++;

 }) //清空questionIds[1]，接收新的
 questionIds[style]=[];
}
  }
  newIndexq();
     //小题另设样式
     if($(".active").parent().parent().prop("class")=="question-each"){
                        $(".active").parent().parent().find(".question-each").css("margin-left","20px");
                      }
    $(".question-type").css("color","black");//设置题目颜色
    $(".option p").css("display","inline-block");
    $(".question-name p").css("display","inline");
    registerDrag($('#container'));
   
}

//绑定事件
$(document).on('click',".delbtn",function(){
  alert("f");
   var num1=1;//题号顺序，从1开始
   var No= $(this).parents(".question-each").find('input[name="No"]').val();//保存当前删除题号
   var parents = $(this).parent().parent().parent();//这类题的父类 question-type
   $(this).parent().parent().remove();//删除一道题  
   //重新调整题号
   parents.children(".question-each").each(function(elem){  //question-each
   $(this).find("span.index:first").html(num1++);
        })
  //修改 selects，以便能重选删掉的题
  var style = $(this).attr("name");   
  var index = find(selects,No); 

  selects.splice(index,1);
  //修改当前题号
  num[style]=num1;
  $(".active").removeClass("active");
});
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
</script>
