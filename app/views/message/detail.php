<style type="text/css">
.dlg-msgDetail {
    border: 1px #B5BCC7 solid;
    padding: 5px;
    min-height: 200px;
    *height: 150px;
    _height: 150px;
    min-width: 220px;
    *width: 480px;
    _width: 480px;
    overflow: auto;
}
</style>
<article class="page-container">
		<form class="form form-horizontal" id="form-admin-add">
		<!--提交id-->
		
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>时间：</label>
			<div class="formControls col-xs-8 col-sm-9" style="display:inline">
                <?=date("Y-m-d H:i:s",$message['time'])?>
			</div>
		</div>
        </form>

        <form class="form form-horizontal" id="form-add" >
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>来源：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="namelab"> <?=$message["senderName"]?>  </label> 
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>主题：</label>
			<div class="formControls col-xs-8 col-sm-9">
			<span class="c-red">*</span>
				<label style="margin-top:2cm;" id="countlab" >  <?=$message["content"]?> </label> 
			</div>
		</div>
        
        
        
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">内容：</label>
			<div class="formControls col-xs-8 col-sm-9 dlg-msgDetail">
				<?=$detail?>
			</div>
		</div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
           
			</div>
        </div>
		</form>
		</article>