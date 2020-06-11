<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script src="<?=STATIC_PATH ?>/static/layui/layui.js" charset="utf-8"></script>
</head>
<body >
<tr>
<td>试卷总分：<?=$totalscore?></td>&nbsp;
<td>最低分：<?=$last?></td>&nbsp;
<td>最高分：<?=$frist?></td>&nbsp;
<td>平均分：<?=$avr?></td>
</tr>
<!--<div id="pie1" style="width: 600px;height:400px;float:left"></div>-->
<div id="pie2" style="width: 700px;height:400px;float:left"></div>

<script type="text/javascript">
console.log(<?=$result?>);
   // var myChart1 = echarts.init(document.getElementById('pie1'));
    var myChart2 = echarts.init(document.getElementById('pie2'));
    option1 = {
	    title:{
            text:'按类型统计',
            top:'bottom',
            left:'center',
            textStyle:{
                fontSize: 14,
                fontWeight: '',
                color: '#333'
            },
        },//标题
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b}: {c} ({d}%)",
            /*formatter:function(val){   //让series 中的文字进行换行
                 console.log(val);//查看val属性，可根据里边属性自定义内容
                 var content = var['name'];
                 return content;//返回可以含有html中标签
             },*/ //自定义鼠标悬浮交互信息提示，鼠标放在饼状图上时触发事件
        },//提示框，鼠标悬浮交互时的信息提示
        legend: {
            show: false,
            orient: 'vertical',
            x: 'left',
            data: ['50%-学生', '25%-老师', '25%-家长']
        },//图例属性，以饼状图为例，用来说明饼状图每个扇区，data与下边series中data相匹配
        graphic:{
            type:'text',
            left:'center',
            top:'center',
            style:{
                text:'用户统计\n'+'100', //使用“+”可以使每行文字居中
                textAlign:'center',
                font:'italic bolder 16px cursive',
                fill:'#000',
                width:30,
                height:30
            }
        },//此例饼状图为圆环中心文字显示属性，这是一个原生图形元素组件，功能很多
        series: [
            {
                name:'用户统计',//tooltip提示框中显示内容
                type: 'pie',//图形类型，如饼状图，柱状图等
                radius: ['35%', '65%'],//饼图的半径，数组的第一项是内半径，第二项是外半径。支持百分比，本例设置成环形图。具体可以看文档或改变其值试一试
                //roseType:'area',是否显示成南丁格尔图，默认false
                itemStyle: {
                    normal:{
                        label:{
                            show:true,
                            textStyle:{color:'#3c4858',fontSize:"18"},
                            formatter:function(val){   //让series 中的文字进行换行
                                return val.name.split("-").join("\n");}
                        },//饼图图形上的文本标签，可用于说明图形的一些数据信息，比如值，名称等。可以与itemStyle属性同级，具体看文档
                        labelLine:{
                            show:true,
                            lineStyle:{color:'#3c4858'}
                        }//线条颜色
                    },//基本样式
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)',//鼠标放在区域边框颜色
                        textColor:'#000'
                    }//鼠标放在各个区域的样式
                },
                data: [
                    {value: 50, name: '50%-学生'},
                    {value: 25, name: '25%-老师'},
                    {value: 25, name: '25%-家长'},
                ],//数据，数据中其他属性，查阅文档
                color: ['#51CEC6','#FFB703','#5FA0FA'],//各个区域颜色
            },//数组中一个{}元素，一个图，以此可以做出环形图
        ],//系列列表
    };
    //myChart1.setOption(option1);
    //折线图
    var option2 = {
  // ----  标题 -----
 
  // ---- legend ----
  legend: {
    type: 'plain',  // 图列类型，默认为 'plain'
    top: '1%',  // 图列相对容器的位置 top\bottom\left\right
    selected: {
      '人数': true  // 图列选择，图形加载出来会显示选择的图列，默认为true
    },
    textStyle: {  // 图列内容样式
      color: '#fff',  // 字体颜色
      backgroundColor: 'black'  // 字体背景色
    },
    tooltip: {  // 图列提示框，默认不显示
      show: true,
      color: 'red'  
    },
    data: [   // 图列内容
      {
        name: '人数',
        icon: 'circle',
        textStyle: {
          color: 'red',  // 单独设置某一个图列的颜色
          backgroundColor: '#fff' // 单独设置某一个图列的字体背景色
        }
      }
    ]
  },
  // ---  提示框 ----
  tooltip: {
    show: true,   // 是否显示提示框，默认为true
    trigger: 'item', // 数据项图形触发
    axisPointer: {   // 指示样式
      type: 'shadow',
      axis: 'auto'
    },
    padding: 5,
    textStyle: {   // 提示框内容的样式
      color: '#fff'  
    }
  },
  // ---- gird区域 ---
  gird: {
    show: false,    // 是否显示直角坐标系网格
    top: 80,  // 相对位置 top\bottom\left\right
    containLabel: false, // gird 区域是否包含坐标轴的刻度标签
    tooltip: {
      show: true,
      trigger: 'item',   // 触发类型
      textStyle: {
        color: '#666'
      }
    }
  },
  //  ------  X轴 ------
  xAxis: {
    show: true,  // 是否显示
    position: 'bottom',  // x轴的位置
    offset: 0, // x轴相对于默认位置的偏移
    type: 'category',   // 轴类型， 默认为 'category'
    name: '成绩',    // 轴名称
    nameLocation: 'end',  // 轴名称相对位置
    nameTextStyle: {   // 坐标轴名称样式
      color: 'red',
      padding: [5, 0, 0, -5]
    },
    nameGap: 15, // 坐标轴名称与轴线之间的距离
    nameRotate: 0,  // 坐标轴名字旋转
    axisLine: {       // 坐标轴 轴线
      show: true,  // 是否显示
      symbol: ['none', 'arrow'],  // 是否显示轴线箭头
      symbolSize: [8, 8], // 箭头大小
      symbolOffset: [0, 7],  // 箭头位置e
      // ------   线 ---------
      lineStyle: {
        color: 'blue',
        width: 1,
        type: 'solid'
      }
    },
    axisTick: {    // 坐标轴 刻度
      show: true,  // 是否显示
      inside: true,  // 是否朝内
      length: 3,     // 长度
      lineStyle: {   // 默认取轴线的样式
        color: 'red',
        width: 1,
        type: 'solid'
      }
    },
    axisLabel: {    // 坐标轴标签
      show: true,  // 是否显示
      inside: false, // 是否朝内
      rotate: 0, // 旋转角度
      margin: 5, // 刻度标签与轴线之间的距离
      color: 'red'  // 默认取轴线的颜色 
    },
    splitLine: {    // gird区域中的分割线
      show: false,  // 是否显示
      lineStyle: {
        // color: 'red',
        // width: 1,
        // type: 'solid'
      }
    },
    splitArea: {    // 网格区域
      show: false  // 是否显示，默认为false
    },
    data: ['不及格(0~<?=$notPass?>)', '合格(<?=$notPass?>~<?=$pass?>)', '良好(<?=$pass?>~<?=$good?>)', '优秀(<?=$good?>~<?=$well?>)']
  },
  //   ------   y轴  ----------
  yAxis: {
    show: true,  // 是否显示
    position: 'left', // y轴位置
    offset:10, // y轴相对于默认位置的偏移
    type: 'value',  // 轴类型，默认为 ‘category’
    name: '人数',   // 轴名称
    nameLocation: 'end', // 轴名称相对位置value
    
  },
  //  -------   内容数据 -------
  series: [
    {
      name: '人数',      // 序列名称
      type: 'bar',      // 类型
      legendHoverLink: true,  // 是否启用图列 hover 时的联动高亮
      label: {   // 图形上的文本标签
        show: false,
        position: 'insideTop', // 相对位置
        rotate: 0,  // 旋转角度
        color: '#eee'
      },
      itemStyle: {    // 图形的形状
        color: 'blue',
        barBorderRadius: [18, 18, 0 ,0]
      },
      barWidth: 30,  // 柱形的宽度
      barCategoryGap: '20%',  // 柱形的间距
      data:<?=$result?>    }
  ]
};
myChart2.setOption(option2);
    </script>



