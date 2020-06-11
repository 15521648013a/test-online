<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\Test;
use app\models\Question;
use app\models\TestRecord;
class TestRecordController extends Controller{
   
    function showDetail($testrecordid =''){
       $testRecord = new TestRecord();
       $result = $testRecord->where(["recordid = ?"],[$testrecordid])->fetch();
       //考生答案
       $answer = $result['student_answer'];
       $testid = $result["testid"];
       $scores = $result["score_detail"];
       $truescores= $result["trueScore"];
       //
       //按试卷id 查询试题，先查询包含哪些 题型 style
$test =new Test();
$question =new Question();
$Tests = $test -> where(['testid = ?'],[$testid])->fetch();

$testcontent = json_decode($Tests['testcontent'],true);//内容 ,数组
//$Tests = explode(',', $Tests['questionStyle']);
//先获取单选
$Questions=[];  // $Tests['questionStyle']  = [][],二维数组
foreach($testcontent as $k=>$testCont)//$testCount={title,questions}
{
    //保存 题型说明
     $title = $testCont['titleType'];
     $Questions[$k]['title']=$title;
foreach($testCont['questions'] as $k1=>$value){  //$value = 1,2..// 试题号
    //echo $k."下的".$value;
    
    if(is_numeric($value)){
        //是题号  Questions[1][] ,1 表示是题型
        //$Questions['"'.$k.'"'][]= $question->where(["questionNo = ?"],[$value])->fetch();
      //  echo $question->where(["questionNo = ?"],[$value])->fetch()["title"];
        $Questions[$k]['questions'][$k1]=$question->where(["questionNo = ?"],[$value])->fetch();
    }else  if(array_key_exists("Bigtitle",json_decode($value))){
         // 有小题的问题
        $Questions[$k]['questions'][$k1]["Qstyle"]="Hat";
        foreach(json_decode($value) as $j=>$elem){
            //echo "小题".$elem;
            if(is_numeric($elem)&&is_numeric($j))
            $Questions[$k]['questions'][$k1]['question'][]=$question->where(["questionNo = ?"],[$elem])->fetch();
            else if($j=="Bigtitle")
            {$Questions[$k]['questions'][$k1]["title"]=$elem;}
        }
        //$Questions['"'.$k.'"'][]=json_decode($value);
    
    
    
    }else{
        $Questions[$k]['questions'][$k1]=json_decode($value);
    }
    
}  }
$this->assign("allQuestion",($Questions));//转换成js可接收的类型  对象{....}
$this->assign("answer",$answer);
$this->assign("scores",$scores);
$this->assign("truescores",$truescores);
  $this->render("A");
//var_dump($truescores);
    }
    //供老师阅卷
function correctDetail($testrecordid =''){

        $testRecord = new TestRecord();
        $result = $testRecord->where(["recordid = ?"],[$testrecordid])->fetch();
        //考生答案
        $answer = $result['student_answer'];
        $testid = $result["testid"];
        $scores = $result["score_detail"];
        $truescores= $result["trueScore"];
        //
        //按试卷id 查询试题，先查询包含哪些 题型 style
        $test =new Test();
        $question =new Question();
        $Tests = $test -> where(['testid = ?'],[$testid])->fetch();
        
        $testcontent = json_decode($Tests['testcontent'],true);//内容 ,数组
        //$Tests = explode(',', $Tests['questionStyle']);
        //先获取单选
        $Questions=[];  // $Tests['questionStyle']  = [][],二维数组
        foreach($testcontent as $k=>$testCont)//$testCount={title,questions}
        {
            //保存 题型说明
             $title = $testCont['titleType'];
             $Questions[$k]['title']=$title;
        foreach($testCont['questions'] as $k1=>$value){  //$value = 1,2..// 试题号
            //echo $k."下的".$value;
            
            if(is_numeric($value)){
                //是题号  Questions[1][] ,1 表示是题型
                //$Questions['"'.$k.'"'][]= $question->where(["questionNo = ?"],[$value])->fetch();
              //  echo $question->where(["questionNo = ?"],[$value])->fetch()["title"];
                $Questions[$k]['questions'][$k1]=$question->where(["questionNo = ?"],[$value])->fetch();
            }else  if(array_key_exists("Bigtitle",json_decode($value))){
                 // 有小题的问题
                $Questions[$k]['questions'][$k1]["Qstyle"]="Hat";
                foreach(json_decode($value) as $j=>$elem){
                    //echo "小题".$elem;
                    if(is_numeric($elem)&&is_numeric($j))
                    $Questions[$k]['questions'][$k1]['question'][]=$question->where(["questionNo = ?"],[$elem])->fetch();
                    else if($j=="Bigtitle")
                    {$Questions[$k]['questions'][$k1]["title"]=$elem;}
                }
                //$Questions['"'.$k.'"'][]=json_decode($value);
            
            
            
            }else{
                $Questions[$k]['questions'][$k1]=json_decode($value);
            }
            
        }  }
        $this->assign("allQuestion",$Questions);//转换成js可接收的类型  对象{....}
        $this->assign("answer",$answer);
        $this->assign("scores",$scores);
        $this->assign("testrecordid",$testrecordid);
        $this->assign("truescores",$truescores);
          $this->render();
        
        
        
}
    
function hj($testid){
    //按照试卷id 全部搜索
    $test =new Test();
    $Tests = $test -> where(['testid = ?'],[$testid])->fetch();
    $totalscore = $Tests['totalscore'];
    $testRecord = (new TestRecord)->where(["testid = ?"],[$testid])->order(["score ASC"])->fetchAll();
    //提取分数
    //不及格0-59
    $notPass = $totalscore*0.6;$notPassNum=0;
    //合格 60-74
    $pass = $totalscore*0.75;$passNum=0;
    //良好 75-84
    $good = $totalscore*0.85;$goodNum=0;
    //优秀 85-100
    $well = $totalscore*1;$wellNum=0;
    //用试卷总分划分
    if($testRecord){
       
    $frist =  $testRecord[0]['score'];
    $last = end($testRecord)['score'];
    $sum=0;
    foreach($testRecord as $value){
        $score=  $value['score'];
        $sum+=$score;
        if(0<=$score&&$score<$notPass) $notPassNum++;
        else if($notPass<=$score&&$score<$pass)  $passNum++;
        else if($pass<=$score&&$score<$good)  $goodNum++;
        else   $wellNum++;
    }
    $avr=$sum/count($testRecord);
    //将统计的结果放在数组里
    $result=[$notPassNum,$passNum,$goodNum,$wellNum];
}else{
    $frist =  0;
    $last = 0;
    $sum=0;
    $avr=0;
    $result=[0,0,0,0];
}
    $this->assign("totalscore",$totalscore);
    $this->assign("frist",$frist);
    $this->assign("last",$last);
    $this->assign("avr",$avr);
    $this->assign("notPass",$notPass);//$notPass = $totalscore*0.6;$notPassNum=0;
    //合格 60-74
    $this->assign("pass",$pass);//$pass = $totalscore*0.75;$passNum=0;
    //良好 75-84
    $this->assign("good",$good);//$good = $totalscore*0.85;$goodNum=0;
    //优秀 85-100
    $this->assign("well",$well);//$well = $totalscore*1;$wellNum=0;
    $this->assign("result",json_encode($result));
    //var_dump($testRecord);
    $this->render();
}
}