<link href="<?=STATIC_PATH ?>/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="<?=STATIC_PATH ?>/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<link href="<?=STATIC_PATH ?>/static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?=STATIC_PATH ?>/static/lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css" />

<script>
 
    $(function(){
        var show_num = [];
        draw(show_num);
 
        $("#canvas").on('click',function(){
            draw(show_num);
        })
        //登录
        $("#login").on('click',function(){
            var val = $(".input-val").val().toLowerCase();
            var num = show_num.join("");
            if(val==''){
                alert('请输入验证码！');
            }else if(val == num){
                //alert('提交成功！');
               // $(".input-val").val('');
                //draw(show_num);
                $.ajax({
                type:"POST",//提交方式
                url:$("form").attr("action"),//提交的地址
                data:$("form").serialize(),//序列化提交数据
                dataType:"json",//设置数据提交类型
                success:function(data){
                if(data.status==1){
                    alert("登录成功！");

                    window.location.href="<?=url("Item/index")?>";
                }else{
                    //status=0;
                  if(data.existName==1){
                      //用户名存在，但密码错了
                      alert("密码错误");
                      
                  }else{
                      alert("用户名不存在");
                    
                  }
                  $(".input-val").val('');
                draw(show_num);
              }
           
                                 }         
  })
 
            }else{
                alert('验证码错误！请重新输入！');
                $(".input-val").val('');
                draw(show_num);
            }
        });
        //注册
        $("#register").on('click',function(){
            // alert("注册");
           // $(".password2").show();
           //注册弹窗
           layer_show("注册界面",'<?=url('User/register')?>',500,400);
        });

    })
 
    function draw(show_num) {
        var canvas_width=$('#canvas').width();
        var canvas_height=$('#canvas').height();
        var canvas = document.getElementById("canvas");//获取到canvas的对象，演员
        var context = canvas.getContext("2d");//获取到canvas画图的环境，演员表演的舞台
        canvas.width = canvas_width;
        canvas.height = canvas_height;
        var sCode = "A,B,C,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,W,X,Y,Z,1,2,3,4,5,6,7,8,9,0";
        var aCode = sCode.split(",");
        var aLength = aCode.length;//获取到数组的长度
        
        for (var i = 0; i <= 3; i++) {
            var j = Math.floor(Math.random() * aLength);//获取到随机的索引值
            var deg = Math.random() * 30 * Math.PI / 180;//产生0~30之间的随机弧度
            var txt = aCode[j];//得到随机的一个内容
            show_num[i] = txt.toLowerCase();
            var x = 10 + i * 20;//文字在canvas上的x坐标
            var y = 20 + Math.random() * 8;//文字在canvas上的y坐标
            context.font = "bold 23px 微软雅黑";
 
            context.translate(x, y);
            context.rotate(deg);
 
            context.fillStyle = randomColor();
            context.fillText(txt, 0, 0);
 
            context.rotate(-deg);
            context.translate(-x, -y);
        }
        for (var i = 0; i <= 5; i++) { //验证码上显示线条
            context.strokeStyle = randomColor();
            context.beginPath();
            context.moveTo(Math.random() * canvas_width, Math.random() * canvas_height);
            context.lineTo(Math.random() * canvas_width, Math.random() * canvas_height);
            context.stroke();
        }
        for (var i = 0; i <= 30; i++) { //验证码上显示小点
            context.strokeStyle = randomColor();
            context.beginPath();
            var x = Math.random() * canvas_width;
            var y = Math.random() * canvas_height;
            context.moveTo(x, y);
            context.lineTo(x + 1, y + 1);
            context.stroke();
        }
    }
 
    function randomColor() {//得到随机的颜色值
        var r = 256;
        var g = 256;
        var b = 256;
        return "rgb(" + r + "," + g + "," + b + ")";
    }
</script>

</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox">
    <form class="form form-horizontal" action="<?=url("User/checkLogin")?>" method="post">
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
          <input id="user" name="name" type="text" placeholder="账户" class="input-text size-L">
        </div>
      </div>
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
          <input id="password" name="password" type="password" placeholder="密码" class="input-text size-L">
        </div>
      </div>
      <div class="row cl password2" style="display:none">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
          <input id="password" name="password2" type="password" placeholder="确认密码" class="input-text size-L">
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          
          <input name="verify" class="input-text size-L input-val" type="text" placeholder="验证码"  onclick="if(this.value=='验证码:'){this.value='';}" value="" style="width:150px;">
          <canvas id="canvas" width="100" height="43"></canvas>
      </div>
      </div>
     
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <input name="" type="button" id="login" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
          <input name="" type="button" id="register" class="btn btn-success radius size-L" value="&nbsp;注&nbsp;&nbsp;&nbsp;&nbsp;册&nbsp;">
          
        </div>
      </div>
    </form>
  </div>
</div>
<div class="footer"></div>

<!--ajax验证-->

<script>
  /*
  $(function(){
  $("#login").on('click',function(e){
  //alert($("form").attr("action"));
  $.ajax({
          type:"POST",//提交方式
          url:$("form").attr("action"),//提交的地址
          data:$("form").serialize(),//序列化提交数据
          dataType:"json",//设置数据提交类型
          success:function(data){
              if(data.status==1){
                  alert("登录成功！");
                  window.location.href="<?=url("Item/index")?>";
              }else{
                  //status=0;
                  if(data.existName==1){
                      //用户名存在，但密码错了
                      alert("密码错误");
                  }else{
                      alert("用户名不存在");
                  }
              }
           
                                 }         
  })
  })
  })
 */
  
</script>
</script>