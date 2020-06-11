
 <!-- 试卷尾 -->	
 <div class="question-act">
						<input type="submit" onclick="tijiao();return false;" value="交卷" >
                    </div>
</form>
 </div>
</div>
<script type="text/javascript">
     function tijiao(){


         alert("提交");
         $.ajax({
				type: "POST",
				url: "<?=url('Class/putTest')?>",
				data: $("#testForm").serialize(),
				dataType: "json",
				success: function(data){
					$(".score").html(data.data1+data.data2+data.data3);
					//关闭当前选项卡步骤，1，获取档期 li 的顺序 (class="active"),关闭当前选项卡，2，关闭 同一顺序的iframe，
					var index=$(window.parent.document).find("#min_title_list li.active").index();
					//alert(index);
					//关闭
					($(window.parent.document).find("#min_title_list li").eq(index)).find('i').click();
					//$(window.parent.document).find("#iframe_box .show_iframe").eq(index).css("display",'none');
					//$(window.parent.document).find("#min_title_list li").last()[0].dblclick();
					//alert($(window.parent.document).find("#min_title_list li").last().index());
					//$(window.parent.document).find("#min_title_list li").last().addClass('active');
					//$(window.parent.document).find("#iframe_box .show_iframe").last().css("display",'block');
					//$(window.parent.document).find("#iframe_box .show_iframe").eq(index).remove();

					//scrollToTab($(window.parent.document).find("#min_title_list li").last());
					
					
					//scrollToTab($(window.parent.document).find("#min_title_list li").last());//find("span")[0].click();
					//选中上一个index的li
                                   
				}
			});
     }

</script>