<link rel="stylesheet" type="text/css" href="<?=STATIC_PATH ?>/static/css/style.css"  >
<style type="text/css">
    .test{
        
        margin-left: 10px;
    margin-right: 10px;
    margin: 0 40px 20px;
    background-color: #dcf0e3;
    padding: 0 5px;
    border-left: 5px solid #9099AE;
    font-size: 12px;
    height:50px;
    }
 </style>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 角色管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="main">
	<div class="main-wrap">	
    <!-- 试卷头 -->	
    <form action=" " id="testForm" method="post" >
        <input type="hidden" name="testid" value="<?=$Testid?>">
        
        <div class="test" > 得分：<div class="score"></div></div>