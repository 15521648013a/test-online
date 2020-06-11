var queueArr=[];var draggers=[];
var isDragging=false;var isMouseDown=false;
var dragger=null;var mouseX;var mouseY;var draggerLeft;var draggerTop;
var clone=null;var DRAG_THRESHOLD=5;var queueContainer;var queueActive={'border':' '};
var queueUnActive={'border':' '};

var registerDrag=function(container){
    queueContainer=container;
    $.each(container.find('.queue'),function(index,value){
        queueArr[index]=$(value);draggers[index]=[];
        elements=$(value).find('.dragger');
        $.each(elements,function(_index,_value){
            draggers[index][_index]=$(_value);});});

    for(var i=0;i<draggers.length;i++)
    for(var j=0;j<draggers[i].length;j++){
        draggers[i][j].on('mousedown',dragStart);}//绑定 当鼠标指针移动到元素上方，并按下鼠标左键时，会发生 mousedown 事件。
        $(document).on('mousemove',dragMove);
        $(document).on('mouseup',dragEnd);}

var dragStart=function(e){
    e.stopPropagation();
    isMouseDown=true;mouseX=e.clientX;mouseY=e.clientY;dragger=$(this);//dragger 表示当前的拖动的dragger
}

var dragMove=function(e){
    e.stopPropagation();
    if(!isMouseDown)return; 
    var dx=e.clientX-mouseX;var dy=e.clientY-mouseY;

    if(isDragging){
        clone.css({left:draggerLeft+dx,top:draggerTop+dy});arrangeDragger();}

        else if(Math.abs(dx)>DRAG_THRESHOLD||Math.abs(dy)>DRAG_THRESHOLD){
            clone=makeClone(dragger);
            draggerLeft=dragger.offset().left-parseInt(dragger.css('margin-left'))-parseInt(dragger.css('padding-left'));
            draggerTop=dragger.offset().top-parseInt(dragger.css('margin-top'))-parseInt(dragger.css('padding-top'));
            clone.css({left:draggerLeft,top:draggerTop});
            queueContainer.append(clone);
            dragger.css('visibility','hidden');
            isDragging=true;}
        }

var dragEnd=function(e){
    e.stopPropagation();if(isDragging){
        isDragging=false;clone.remove();
        dragger.css('visibility','visible');}
       for(var i=0;i<queueArr.length;i++)
       queueArr[i].css(queueUnActive);
       isMouseDown=false;
    ///alert("sdfsdf");
     //对试题进行编号
    newIndex();
    }

var makeClone=function(source){
    var res=source.clone();res.css({position:'absolute','z-index':100000});return res;}

var arrangeDragger=function(){
    for(var i=0;i<queueArr.length;i++)
queueArr[i].css(queueUnActive);var queueIn=findQueue();if(queueIn!=-1)
queueArr[queueIn].css(queueActive);
var hover=findHover(queueIn);
if(hover==null)
return;
var _hover=hover.hover;
var _insert=hover.insert;
var queueIdOriginal,drggerIdOriginal;
var queueIdHover,drggerIdHover;
for(var i=0;i<draggers.length;i++)
for(var j=0;j<draggers[i].length;j++){
    if(draggers[i][j][0]==dragger[0]){queueIdOriginal=i;drggerIdOriginal=j;}}

draggers[queueIdOriginal].splice(drggerIdOriginal,1);
if(_hover){
    for(var i=0;i<draggers.length;i++)
for(var j=0;j<draggers[i].length;j++){if(_hover&&draggers[i][j][0]==_hover[0]){queueIdHover=i;drggerIdHover=j;}}
if(_insert=='left'){
    _hover.before(dragger);
    draggers[queueIdHover].splice(drggerIdHover,0,dragger);}
else{_hover.after(dragger);draggers[queueIdHover].splice(drggerIdHover+1,0,dragger);}
}
else{
    draggers[queueIn].push(dragger);queueArr[queueIn].append(dragger);
}
console.log('*********f*********');
for(var i=0;i<draggers.length;i++)
for(var j=0;j<draggers[i].length;j++)
console.log(draggers[i][j][0]);console.log('*********fg*********');

}

var findQueue=function(){
    //查找要插在那个队列中
    var mx=-1,pos=-1;
    var cloneTop=clone.offset().top;
    var cloneHeight=clone.height();
    for(var i=0;i<queueArr.length;i++)
    {var queueTop=queueArr[i].offset().top;
        var queueHeight=queueArr[i].height();
        var val=Math.min(queueTop+queueHeight,cloneTop+cloneHeight)-Math.max(queueTop,cloneTop);
        if(val>mx){mx=val;pos=i;}}
     return pos;}

var findHover=function(queueIn){
    if(queueIn==-1)
return null;
var mx=-1,pos=null;
var cloneTop=clone.offset().top;var cloneHeight=clone.height();
var cloneLeft=clone.offset().left;var cloneWidth=clone.width();
var isOwn=false;
for(var i=0;i<draggers[queueIn].length;i++){
    var _draggerTop=draggers[queueIn][i].offset().top;
    var _draggerHeight=draggers[queueIn][i].height();
    var vertical=Math.min(_draggerTop+_draggerHeight,cloneTop+cloneHeight)-Math.max(_draggerTop,cloneTop);
    var _draggerLeft=draggers[queueIn][i].offset().left;
    var _draggerWidth=draggers[queueIn][i].width();
    var horizontal=Math.min(_draggerLeft+_draggerWidth,cloneLeft+cloneWidth)-Math.max(_draggerLeft,cloneLeft);
    if(vertical<=0||horizontal<=0) continue;
    var s=vertical*horizontal;//两个dragger重叠面积
    if(s<=cloneHeight*cloneWidth/3)
continue;
if(draggers[queueIn][i][0]==dragger[0]){//jq[0]表示获得dom对象
    isOwn=true;continue;}
if(s>mx){mx=s;pos=draggers[queueIn][i];}//找到要交换的dragger，重叠面积最那个
}

if(mx<0){
    if(isOwn)return null;
if(draggers[queueIn].length==0){
    return{'hover':null};
}else{
    var last,index=draggers[queueIn].length-1;
    while(index>=0&&draggers[queueIn][index][0]==dragger[0])
index--;
if(index>=0)
last=draggers[queueIn][index];
else
return{'hover':null};
if(cloneLeft>=last.offset().left+last.width())
return{'hover':last,'insert':'right'};
else
return null;}}
else{
    var posMid=(2*pos.offset().left+pos.width())/2;
    var cloneMid=(2*clone.offset().left+clone.width())/2;
    if(posMid>cloneMid)
return{'hover':pos,'insert':'left'};
else
return{'hover':pos,'insert':'right'};}
}
  //转罗马数字
  function convert1(num) {
    var a=[["","I","II","III","IV","V","VI","VII","VIII","IX"],  ["","X","XX","XXX","XL","L","LX","LXX","LXXX","XC"],  
  ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM"],
   ["","M","MM","MMM"]];  
    var i=a[3][Math.floor(num/1000)];
    var j=a[2][Math.floor(num%1000/100)];
    var k=a[1][Math.floor(num%100/10)];
    var l=a[0][num%10];
    return  i+j+k+l;
     
  }
////重新编号
function newIndex(){
    
    $(".question-wrap").each(function(num1){
    $(this).children(".question-type").each(function(){
         $(this).children(".question-each").each(function(num2){
           //还有小题吗
           if($(this).children(".question-type").length>0){ 
            $(this).children(".question-name").find("span.index").html(num2+1+'.');
            //小题取消拖动
             $(this).children(".question-type").children(".question-each").each(function(index){
                
                            //$(this).removeClass("dragger");
                            //$(this).attr("draggable","false");
                            $(this).children(".question-name").find("span.index").html(convert1(index+1)+'.');
                            
             })
  
           } else{
            
            $(this).children(".question-name").find("span.index").html(num2+1+'.');
           }
         }) 
        // alert(num1);
         //alert(JSON.stringify(No));
  }); });
  }

