<style type="text/css"> 
div.menu ul
{
    list-style:none; /* 去掉ul前面的符号 */
    margin: 0px; /* 与外界元素的距离为0 */
    padding: 0px; /* 与内部元素的距离为0 */
    width: auto; /* 宽度根据元素内容调整 */
}
div.menu ul li
{
    float:left; /* 向左漂移，将竖排变为横排 */
	width: auto;
}
div.menu ul li a, div.menu ul li a:visited
{
  
    border: 1px #4e667d solid; /* 边框 */
    color: black; /* 文字颜色 */
    display: block; /* 此元素将显示为块级元素，此元素前后会带有换行符 */
    line-height: 2em; /* 行高 */
    padding: 4px 10px; /* 内部填充的距离 */
    text-decoration: none; /* 不显示超链接下划线 */
    white-space: nowrap; /* 对于文本内的空白处，不会换行，文本会在在同一行上继续，直到遇到 <br> 标签为止。 */
}
/* 所有class为menu的div中的ul中的a样式(鼠标移动到元素中的样式) */
div.menu ul li a:hover
{
    background-color: #bfcbd6; /* 背景色 */
    color: #465c71; /* 文字颜色 */
    text-decoration: none; /* 不显示超链接下划线 */
}
/* 所有class为menu的div中的ul中的a样式(鼠标点击元素时的样式) */
div.menu ul li a:active
{
    background-color: #465c71; /* 背景色 */
    color: #cfdbe6; /* 文字颜色 */
   
    text-decoration: none; /* 不显示超链接下划线 */
}
.layui-nav .layui-nav-item {
    position: relative;
    display: inline-block;
    *display: inline;
    *zoom: 1;
    vertical-align: middle;
    line-height: 40px;
}
</style>

</head>
<body>
<ul class="layui-nav">
  <li class="layui-nav-item layui-this"><a href="">班级信息</a></li>
  <li class="layui-nav-item ">
    <a href="<?=url("Class/members/$classid")?>">成员列表</a>
  </li>
  
  <li class="layui-nav-item">
    <a href="<?=url("Class/showTest/$classid")?>">班级考试</a>
  </li>
 
</ul>



<!--body -->

<div style=" margin:10px 20px">
<?php if($identify == '老师'){ ?>
    <div style="font-weight: bold;">
    <label style="font-weight: bold;"> 基本信息<a class="btn editbtn" style="height:30px; margin:5px" name="">编辑 </a></label>
    </div>
<?php } ?>    
    <div style="margin-top:10px">
        <div style="display:inline">
        班级编号：
        </div>
        <div style="display:inline">
        <?=$Classes['classid']?>
        <div>
    </div>
    <div style="margin-top:10px">
        <div style="display:inline;">
        班级名称：
        </div>
        <div style="display:inline">
        <?=$Classes['classname']?>
        <div>
    </div>
    <div style="margin-top:10px"> 
        <div style="display:inline">
        创建时间：
        </div>
        <div style="display:inline">
        <?=$Classes['createtime']?>
        <div>
    </div>
    <div style="margin-top:10px">
        <div style="display:inline">
        成员数：
        </div>
        <div style="display:inline">
        <?=$Classes['number']?>
        <div>
    </div>
    <div style="margin-top:10px">
        <div style="display:inline" >
        创建者：
        </div>
        <div style="display:inline">
        <?=$Classes['creator']?>
        <div>
    </div>
    <div style="margin-top:10px">
        <div style="display:inline">
        简介：
        </div>
        <div style="display:inline">
        <?=$Classes['profile']?>
        <div>
    </div>
</div>
<style type="text/css">
.fontq{
    font-size: 1.6em;
    line-height: 1.5em;}
</style>
<script>
$(document).on('click',".editbtn",function(){
    layer_show("编辑",'<?=url('Class/edit/')?>'+ <?=$Classes['classid']?>,800,500);
});
</script>