
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add">
		<!--提交id-->
		<input type='hidden' value="<?=$testid?>" name="testid">
        <input type='hidden' value="<?=$classid?>" name="classid">
        <!--
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>管理员：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="" placeholder="" id="adminName" name="name">
			</div>
		</div>
		-->
	
        <div class="row cl">
       	<label class="form-label col-xs-4 col-sm-3" style="width:18%"><span class="c-red">*</span> 考试的开始日期：</label>
           <div class="formControls col-xs-8 col-sm-9">
              <input type="text" class="layui-input" style="width:160px;  " id="test3" name="starttime" value="<?= $testMessage["starttime"]?$testMessage["starttime"]:'';?>">
              
            </div>
        </div>
        <div class="row cl">
       	<label class="form-label col-xs-4 col-sm-3" style="width:18%"><span class="c-red">*</span> 考试的截至日期：</label>
           <div class="formControls col-xs-8 col-sm-9">
              
          
            <input type="text" class="layui-input" style="width:160px;" id="test4" name="endtime" value="<?= $testMessage["endtime"]?$testMessage["endtime"]:'';?>">
            </div>
        </div>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3" style="width:18%"><span class="c-red">*</span>设置是否允许考试：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<div class="questdiv " style="vertical-align:top;width:200px;margin-right:2%;word-wrap:break-word; word-break:break-all;">
                 <label   style="display: block;    margin-bottom: 5px;">
                 <input type="radio" style="" value="1" name="select" required="">允许考试
                 </label   >
                 <label  style="display: block;    margin-bottom: 5px;">
                 <input type="radio" style="" value="0" name="select" checked required="">不允许考试
                 </label >
                
                 </div>
			</div>
		</div>
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3" style="width:18%"><span class="c-red">*</span>设置是否允许查成绩：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<div class="questdiv " style="vertical-align:top;width:200px;margin-right:2%;word-wrap:break-word; word-break:break-all;">
                 <label   style="display: block;    margin-bottom: 5px;">
                 <input type="radio" style="" value="1" name="scan" required="">允许查看考试情况
                 </label   >
                 <label  style="display: block;    margin-bottom: 5px;">
                 <input type="radio" style="" value="0" name="scan" checked required="">不允许查看考试情况
                 </label >
               
                 </div>
			</div>
		</div>
        <div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<input  id="submit" style="margin-right:5px" onclick="yes();return false"; class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;确认&nbsp;&nbsp;">
                <input  id="_submit" class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;取消&nbsp;&nbsp;">
			</div>
		</div>
      
        </form>

</article>        

<script>
layui.use('laydate', function(){
  var laydate = layui.laydate;
  
  //执行一个laydate实例

  //某个时间在当前时间的多久前
  
  laydate.render({
    elem: '#test3'
    ,type: 'datetime'
   
  });

  laydate.render({
    elem: '#test4'
    ,type: 'datetime'
   
  });
  
});
</script>
 





<script>
$(function () {
$('label').click(function(){
$(this).find("input").prop("checked");
});
$("input:radio[name=select][value=<?=$testMessage['status']?>]").attr("checked",true);  

$("input:radio[name=scan][value=<?=$testMessage['isscan']?>]").attr("checked",true);  
})
function yes(){
    //判断输入是否合法
    flag = tab($("#test3").val(),$("#test4").val());
    if(flag){
    $.ajax({
        type: "POST",
        url: "<?=url("ClassTest/saveSetting")?>",
        data: $("#form-admin-add").serialize(),
        dataType: "json",
        success: function(data){ 

            
        }
    });
    parent.$("#gh")[0].click();
    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
           parent.layer.close(index); //再执行关闭子窗口  

 }
}

function tab(date1,date2){
    var oDate1 = new Date(date1);
    var oDate2 = new Date(date2);
    if(oDate1.getTime() > oDate2.getTime()){
        alert('开始时间比截至时间大');
        return 0;
    } else {
       return 1;
    }
}


</script>