<style >
.hasBeenAnswer {
    background: #5d9cec;
    color: #fff;
}
.nr_right {
   width:25%;
    height: 100%;
    float: right;
}
.nr_rt_main {
    width: 100%;
    height: auto;}
    .rt_nr1 {
    width: 280px;
    position: fixed;
    top: 15px;
    z-index: 1000;
}
.rt_nr1 {
    width: 280px;
    position: fixed;
    top: 15px;
    z-index: 1000;
}
.rt_nr1_title {
  width: 280px;
    height: 45px;
    line-height: 45px;
    background: #f3f3f3;
}
.rt_nr1_title h2 {
    width: 130px;
    height: 45px;
    background: #389fc3;
    text-align: center;
   
    display: block;
    float: left;
    color: #fff;
}
.rt_nr1_title h2 i {
    padding: 0 5px;
    font-size: 14px;
    font-weight: normal;
}
.rt_content {
    width: 280px;
    height: 100%;
    border: 1px solid #e4e4e4;
    border-top: 0;
}    
.rt_content_tt {
    width: 95%;
    height: 40px;
    line-height: 40px;
    margin: 0 auto;
    border-bottom: 1px solid #e4e4e4;
}
.rt_content_tt h2 {
    width: 150px;
    font-size: 14px;
    display: inline-block;
}
.rt_content_tt p {
    
    display: inline-block;
}
.answerSheet ul {
    padding: 10px;
    text-align: left;
}
.answerSheet li {
    display: inline-block;
    margin-bottom: 5px;
    height: 30px;
    width: 30px;
    line-height: 30px;
    text-align: center;
    border: 1px solid #e4e4e4;
}
.answerSheet li a{
    display: block;
    
}
.main1 {
    min-height: 100%;
   
    background: rgb(255, 255, 255);
    margin: 0px auto;
}
.testName{
  
    height: 45px;
    background: #389fc3;
    text-align: center;
    display: block;
    color: #fff;
    font-size: x-large;
    margin: 0 40px 0px 40px;
}
.main-wrap{padding-top:0px;padding-bottom:32px;}
</style>

<div class="main1">
    

    
    <form action=" " id="testForm" method="post" >
    <input type="hidden" name="testid" value="<?=$testid?>">
    
     <div class="nr_right">
				<div class="nr_rt_main">
					<div class="rt_nr1">
						<div class="rt_nr1_title">
							<h2>
								<i class="icon iconfont">答题卡</i>
							</h2>
							<p class="test_time" id="timeout">
              
								<i class="icon iconfont"></i>剩余时间
                <span class="timeout"></span>
							   
              </p>
						</div>
						
						
						
						
						
					</div>

				</div>
			</div> 
    <div class="main-wrap"style="float:left;width:75%">	
    <div class="testName"> <?=$testName?>   </div>
    </div>
            <div class="question-act" style="clear:both">
						     <input type="submit" onclick="tijiao();return false;" value="交卷" >
            </div>
    </form>
</div>	
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
<script>
  function tijiao(){
  $.ajax({
      url:'<?=url('Test/putTest')?>',
      data:$('#testForm').serialize(),
      dataType:'json',
      type:'post',
      success:function(data){
        //var new1 = data.data;
       alert("提交成功。");
       var index=$(window.parent.document).find("#min_title_list li.active").index();				
	      ($(window.parent.document).find("#min_title_list li").eq(index)).find('i').click();
        // console.log(new1["'questions'"]);
      }

  })
}
        $(window).on("beforeunload", function () {
					return "您尚未交卷！此操作将导致您的回答丢失。";
				});
				timeOver = false; //保存当前是否已经达到交卷时间
				//倒计时功能
				$(".timeout").timeout({
					//考试时间（页面刷新时，时间会重置。）
					"maxTime": <?=$time?>,
					//到达时间自动交卷。（如果浏览器禁用JavaScript，此功能不会生效）
					"onTimeOver": function () {
						timeOver = true;
						alert("考试时间结束，系统自动交卷。");
            //$("#testForm").submit();//交卷
            tijiao();
					}
				});
				$("#testForm").submit(function (event) {
					$(window).off("beforeunload");      //解除绑定页面关闭事件
					timeOver || checkMultiple(event);	//检查多选题是否全部作答
				});
				//多选题至少选择一项
				function checkMultiple(event) {
					$(".jq-multiple .question-each").each(function () {
						if ($(this).find(".question-option input[type=checkbox]:checked").length < 1) {
							$(this).find(".question-option input[type=checkbox]:first").focus();
							event.preventDefault();  //阻止表单提交
							alert('您有多选题未作答。');
							return false;
						}
					});
				}
				;

    //给问题绑定点击事件
$(document).on("click",".question-each",function(){
  $(".question-each").not($(this)).css('background-color','');
  $(this).css('background-color','#e2e2e2');
  //显示删除按钮和 插入点delbtn
  $(".delbtn").hide(); 
  $(".insert").hide();
  $(this).find(".delbtn").show();
  $(this).find(".insert").show();
})

$(document).on("click",".question-each .question-each ",function(event){
 // $(".question-each .question-each").not($(this)).css('background-color','');
 event.stopPropagation();
  $(this).css('background-color','#e2e2e2');
})
    //题目细节
    var littleIndex=1; var bigIndex=1;
    //显示题目
    function aa(style,elem,isReNew=false,location=''){
  //isExit(style);
  //遍历数组
  if(elem){
     // alert(elem['title']);
    //根据style ,将题目加到那种题型下
     //是不是小题重新排序
     if(isReNew) qIndex =littleIndex++;else qIndex= bigIndex++;
    if(style ==1){
    var str = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; ">'
                    + '<div class="question-name" style="display:inline-block;margin-top:3%" id="'+location+'"><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                    + '<div class="option " style="margin-top:2%;word-wrap:break-word; word-break:break-all;"> ';
                
       //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
       if(elem['options']){
       var json = JSON.parse(elem['options']);
   
       
       j=65;
        for(var k in json){
          if(j==65)
           str +='<div class="questdiv save " style="vertical-align:top;width:25%;display: inline-block;word-wrap:break-word; word-break:break-all;"name="'+location+'"><p  style="vertical-align:top;dispaly:inline;width:10px"><input type="radio" style="" value="'+String.fromCharCode(j)+'" name="'+location+'" required>'+String.fromCharCode(j)+'.</p>'+json[k]+'</div>';
           else  str +='<div class="questdiv save" style="vertical-align:top;width:25%;display: inline-block;word-wrap:break-word; word-break:break-all;"name="'+location+'"><p  style="vertical-align:top;dispaly:inline;width:10px"><input type="radio" style=""  value="'+String.fromCharCode(j)+'" name="'+location+'" required>'+String.fromCharCode(j)+'.</p>'+json[k]+'</div>';
           j++;
        } 
      }
        str +="</div></div> ";
        //$("#singleselect").append(str);
        //返回一道题
      
        //str={};
       // num[style]=++qIndex; 
         return str;
     
      }else if(style ==2){
          
            var str = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:5%">'
                   + '<div class="question-name" style="display:inline-block;"id="'+location+'"><span class="index">'+qIndex+'</span>.'+elem['title']+'</div>'
                   + '<div class="option" style="margin-top:2%;word-wrap:break-word; word-break:break-all;">';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                    var json = JSON.parse(elem['options']);
                    j=65;
                    for(var k in json){
                     // alert(json[k]);
                     if(j==65)
                      str +='<label   ><input type="checkbox" class="save" value="'+String.fromCharCode(j)+'" name="'+location+'" >'+String.fromCharCode(j)+"."+json[k]+'</label>';
                      else  str +='<label   ><input type="checkbox" class="save" value="'+String.fromCharCode(j)+'" name="'+location+'" >'+String.fromCharCode(j)+"."+json[k]+'</label>';
                      j++;
                    } 
                     str +="</div></div> ";
                     
                    // $("#singleselect").append(str);
                  
                    // str={};
                     num[style]++;
                    return str;
        //清空questionIds[1]，接收新的
        }else if(style ==3){
          var str = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:5%">'
                   + '<div class="question-name" style="display:inline-block;"id="'+location+'"><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
                    //处理选项，根据数据库 {"A":"李白","B":"白居易"} json类型
                   
                      str +='<div class="questdiv" style="width:100px;display:inline"><input type="radio" class="save"  style="" value="对" name="'+location+'" required>'+"对"+"</div>";
                      str +='<div class="questdiv" style="width:100px;display:inline"><input type="radio" class="save"  style="margin-left:10%" value="错" name="'+location+'" required>'+"错"+'</div>';
                     
                     str +="</div></div> ";
                     
                     //$("#yn").append(str);
                  
                    // str={};
                     num[style]++;   return str;
        }else if(style ==4){
          var str = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:5%">'
                   + '<div class="question-name" style="display:inline-block;"id="'+location+'"><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                   + '<div class="option "> ';        
     str +=" <p>请输入答案：<input type=\"text\" class=\"save\" name="+location+" required></p> </div> </div>";             
     //  $("#fill").append(str);
     
       num[style]++; return str;

        }else {
 
 //最后是简答题
 //处理多选
 var str = '<div class="question-each" contenteditable="false" name="question-each" style="border-bottom: 1px solid #7FFFD4; ">'
                + '<div class="question-name" style="display:inline-block;"id="'+location+'"><a class="btn delbtn" contenteditable="false" style="height:30px; margin-right:5px;display: none;" >删除</a><span class="index">'+(qIndex)+'</span>.'+elem['title']+'</div>'
                + '<div class="option save"> ';        
  str +=' <p>请输入答案：<textarea contenteditable="true" type=\"text\"   class=\"save\" style="BORDER-TOP-STYLE: none; BORDER-RIGHT-STYLE: none; BORDER-LEFT-STYLE: none; width:100%;    min-height: 200px;" name='+location+'> </textarea></p> </div> </div><p><br></p>';             
     
       num[style]++;  return str;
              

}
        
  }else{
    //从题号中读取
 
  
  }
    $(".question-type").css("color","black");//设置题目颜色
    $(".option p").css("display","inline");
    $(".question-name p").css("display","inline-block");
}

    //动态选择题目
    <?php $allQuestion=isset($allQuestion)?$allQuestion:'';?>
    
    <?php if($allQuestion){ ?>
    var questions = <?=$allQuestion?>;
    console.log(questions);
    <?php }else{ ?>
   
    var questions = parent.newTest;
    <?php }?>
      bigIndex=1;

    $.each(questions,function(index1,elem){  
             //elem 是一类题  ,先生成 题框question-type ,一个题群一个框
        // var fremkName= "fremk["+index+"]";   也要找到题目再test中的位置，进行编号
        var qTitle=(elem["title"]);
        var fremk ='<div class="question-wrap queue"  name="question-wrap" ><div class="question-type"><div class="Qinsert" "><h class="questionStyle"><span style="font-size: large">'+  qTitle+ ' </span></h>' +      
                                    
                                    '</div></div></div>';
         $(".main-wrap").append(fremk);//生成一个框
         $(".rt_nr1").append('<div class="rt_content"> <div class="rt_content_tt">	<h2>第'+(index1+1)+'题</h2>		</div>'
         
         
								
									
						
         +'<div class="rt_content_nr answerSheet"><ul></ul></div></div>');
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
            if(k['style']==2){
               //多选
               var Location= "Test["+index1+"]['questions']["+index2+"][]";  //Test[0]['questions'][0],题目编号
            }else
            var Location= "Test["+index1+"]['questions']["+index2+"]";  //Test[0]['questions'][0],题目编号
            $(".main-wrap").children(".question-wrap").last().children(".question-type").append(aa(k['style'],k,false,Location)); //最后即最新添加
            //添加答题卡
            $("ul").last().append('<li><a href="#'+Location+'">'+(index2+1)+'</a></li>');
          }
          else{
              var kQuesstion = k["question"]; var ktitle=k["title"];//大题题干  
              
              if($.isArray(kQuesstion)){ 
                var str1 = '<div class="question-each" style="border-bottom: 1px solid #7FFFD4; margin-bottom:3%"><input type="hidden" name="questionNo[1][]" value="rrr">'
                    + '<div class="question-name" style="display:inline-block;margin-top:3%"><span class="index">'+convert(bigIndex++)+'</span><div class="bigtitle">.'+ktitle+'</div></div>'
                        +'<div class="question-type "style="color: black">'
                        ;
                        //小题1
                     $.each(kQuesstion,function(index3,elem1){
                      if(elem1['style']==2){
                   //多选
                 var location= "Test["+index1+"]['questions']["+index2+"]['question']["+index3+"][]";  //Test[0]['questions'][0],题目编号
                }else
                var location= "Test["+index1+"]['questions']["+index2+"]['question']["+index3+"]";  //Test[0]['questions'][0],题目编号


                  
                     str1+=aa(elem1['style'],elem1,true,location);    //添加一道小题   

                     $("ul").last().append('<li><a href="#'+location+'">'+(index2+1)+'.'+(index3+1)+'</a></li>');  
                })                         
                     //小题2
                str1+="</div></div>";    littleIndex=1;
                $(".main-wrap").children(".question-wrap").last().children(".question-type").append(str1);   
                
                }
       }});  
     bigIndex=1;//重新编号
      }
    });
    

    $(".question-type span").css("color","black");//设置题目颜色
    $(".option p").css("display","inline").css("vertical-align","top");
    $(".question-name p").css("display","inline").css("vertical-align","top");
    $(".bigtitle").css("vertical-align","top").css("display","inline-block");
    newIndexq();

//给选项设置监听事件
$(document).on('click','.questdiv',function(){
  $(this).find("input").prop("checked","checked");
 
  $.ajax({
      url:'<?=url('Test/temporary')?>',
      data:$('#testForm').serialize(),
      dataType:'json',
      type:'post',
      success:function(data){
        
        // console.log(new1["'questions'"]);
      }

  })
})



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
 $(".save").click(function(){

   //获取考试选择的答案，并异步保存到数据库中是
   $.ajax({
      url:'<?=url('Test/temporary')?>',
      data:$('#testForm').serialize(),
      dataType:'json',
      type:'post',
      success:function(data){
        
        // console.log(new1["'questions'"]);
      }

  })
  var examId = $(this).attr('name'); // 得到题目ID

				var cardLi = $('a[href="#' + examId + '"]'); // 根据题目ID找到对应答题卡
				// 设置已答题
				if(!cardLi.hasClass('hasBeenAnswer')){
					cardLi.addClass('hasBeenAnswer');
				}
        var flag=0;
        //多选题特殊处理
        if($('[name="'+examId+'"]').attr("type")=='checkbox'){
         
        $('[name="'+examId+'"]').each(function(){
          if($(this).is(':checked'))
           flag=1;return 0;
        })
        if(!flag){
         cardLi.removeClass('hasBeenAnswer');
        }
        }
        //填空题/简答题特殊处理
      
          $("[type='text']").each(function(){
            examId = $(this).attr('name');
            cardLi = $('a[href="#' + examId + '"]'); 
            if(!$.trim($(this).val() )){
           //为空
           cardLi.removeClass('hasBeenAnswer');
         }else{
          cardLi.addClass('hasBeenAnswer');
         }
          })
         
       
          
        
        
         
 })


 var studentAnswer=<?=($studnetAnswer)?>;
 console.log(studentAnswer);


 $.each(studentAnswer,function(index,elem){
   //elem = ["questions"][0]
   $.each(elem["'questions'"],function(_index,_elem){
   // alert(_elem);
   if(_elem["'question'"]){
    //题帽题
    $.each(_elem["'question'"],function(_index1,_elem1){
    //看是什么tixingn
    var style= questions[index]['questions'][_index]['question'][_index1]['style']; 
     if(style =='2'){//多选题
      
      var hh= "Test["+index+"]['questions']["+_index+"]['question']["+_index1+"][]";
      //_elem--[]数组
      $('[name="'+hh+'"]').each(function(){
         if(find(_elem1,$(this).val())!=-1){
         $(this).attr("checked",true);
         var cardLi = $('a[href="#' + hh + '"]'); // 根据题目ID找到对应答题卡
         if(!cardLi.hasClass('hasBeenAnswer')){
	    				cardLi.addClass('hasBeenAnswer');
	    			}
         }
        
       });
     }
    
     var hh= "Test["+index+"]['questions']["+_index+"]['question']["+_index1+"]";
        //遍历单选题
      $('[name="'+hh+'"]').each(function(){
        if($(this).val()==_elem1){
        $(this).attr("checked",true);
        }
        var cardLi = $('a[href="#' + hh + '"]'); // 根据题目ID找到对应答题卡
        if(!cardLi.hasClass('hasBeenAnswer')){
	    				cardLi.addClass('hasBeenAnswer');
	    			}
      });
       });
   }else{
     //看是什么tixingn
     var style= questions[index]['questions'][_index]['style']; //alert(style);
     if(style =='2'){//多选题
    
      var hh= "Test["+index+"]['questions']["+_index+"][]";
      //_elem--[]数组
      $('[name="'+hh+'"]').each(function(){
         if(find(_elem,$(this).val())!=-1){
         $(this).attr("checked",true);
         var cardLi = $('a[href="#' + hh + '"]'); // 根据题目ID找到对应答题卡
         if(!cardLi.hasClass('hasBeenAnswer')){
	    				cardLi.addClass('hasBeenAnswer');
	    			}
         }
        
       });
     }else if(style=='4'||style=='6'){//简答与填空
      var hh= "Test["+index+"]['questions']["+_index+"]";
      $('[name="'+hh+'"]').val(_elem);
      var cardLi = $('a[href="#' + hh + '"]'); // 根据题目ID找到对应答题卡
     if(!cardLi.hasClass('hasBeenAnswer')){
					cardLi.addClass('hasBeenAnswer');
				}
       }else{
   var hh= "Test["+index+"]['questions']["+_index+"]";
   //遍历单选题
   $('[name="'+hh+'"]').each(function(){
     if($(this).val()==_elem){
     $(this).attr("checked",true);
     }
     var cardLi = $('a[href="#' + hh + '"]'); // 根据题目ID找到对应答题卡
     if(!cardLi.hasClass('hasBeenAnswer')){
					cardLi.addClass('hasBeenAnswer');
				}
   });
   }
   }
 //  [{"'questions'":["C"]},{"'questions'":[{"'question'":["B"]}]}]
   

 })})
  //将临时答案填上；
  $(function() {
			$('li.option label').click(function() {
			//debugger;
				var examId = $(this).closest('.test_content_nr_main').closest('li').attr('id'); // 得到题目ID
				var cardLi = $('a[href=#' + examId + ']'); // 根据题目ID找到对应答题卡
				// 设置已答题
				if(!cardLi.hasClass('hasBeenAnswer')){
					cardLi.addClass('hasBeenAnswer');
				}
				
			});
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