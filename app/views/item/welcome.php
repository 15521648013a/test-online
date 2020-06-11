﻿<script type="text/javascript">
    //判断时间是否为个位数，如果时间为个位数就在时间之前补上一个“0”
	function check(val) {
		if (val < 10) {
			return ("0" + val);
		} 
		else {
			return (val);
		}
	}
	function displayTime() {
		//获取div元素
		var timeDiv=document.getElementById("timeDiv");
		//获取系统当前的年、月、日、小时、分钟、毫秒
		var date = new Date();
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		var hour = date.getHours();
		var minutes = date.getMinutes();
		var second = date.getSeconds();
		var timestr = year + "年" + month + "月" + day + "日  " + check(hour)
				+ ":" + check(minutes) + ":" + check(second);
		//将系统时间设置到div元素中
		//timeDiv.innerHTML = timestr;
		$("#timeDiv").html(timestr);
	}
	//每隔1秒调用一次displayTime函数
	
        window.setInterval("displayTime()",1000)//单位是毫秒
</script>

</head>
<body οnlοad="start();">
<div class="page-container">
	<p class="f-20 text-success">欢迎你！ <span class="f-14"></span><?=$_SESSION['username']?></p>
	<p class="f-20 text-success">当前登录身份： <span class="f-14"></span><?=$_SESSION['rolename']?></p>
	<p class="f-20 text-success">当前时间： <span class="f-14"></span><label id="timeDiv" style="font-size: 20px;"></label></p>
</div>
 

