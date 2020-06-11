<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\Test;
use app\models\Question;
use app\models\TestRecord;
use app\models\ClassTest;
//use app\models\PHPExcel_IOFactory;
use app\models\CurrentTest;
use app\models\Subject;
use app\models\QuestionType;
use PHPExcel\Classes\PHPExcel\PHPExcel_IOFactory;
use PHPExcel\Classes\PHPExcel;
use app\models\TeacherSubject;
use app\models\Subject_Kownledgepoint;
use app\models\Question_Kownledgepoint;
class TestController extends Controller
{
function  list(){
   
    $this->render();
 
}
function listShow(){
   //获取当前页
   $currentPage= $_POST['page'];
   //获取一页数据数
   $limit =$_POST['limit'];
   $start = ($currentPage-1)*$limit;//开始截取数组的位置
   $test = new Test();
   $serchids=isset($_POST['id'])?$_POST["id"]:'';
   $data= isset($_POST['data'])?$_POST['data']:'';
   $condition[]="designer = ?";$elem[]=$_SESSION['userid'];
   if($data){
    //解析序列化数据
    parse_str($data,$myArray);
   // echo $myArray['keyWord'];
   //带条件的查询
   //keyWord=&questionLevel=%E7%AE%80%E5%8D%95&questionTypes=unlimited
   //$param=$_POST['keyWord'];
   //$level=$_POST['questionLevel'];
     $keyWord = $myArray['keyWord']; 
     $sCondition = $myArray['condition'];
     //echo  $questionLevel;
     //$questionTypes = $myArray['questionTypes'];
     $condition = [];$elem=[];
     if($sCondition == 'testName')
     {
         $condition[]="testName like ?"; $elem[]="%$keyWord%";
     }else if($sCondition == 'ID'){
        $condition[]="testid = ?";$elem[]=$keyWord;
     }
     
      //var_dump($condition);var_dump($elem);
     $Tests = $test ->join("inner",'',"subject","subject","subjectid")
     ->join("inner",'',"user","designer","userid")
     ->where($condition,$elem)->fetchAll();   //in(要查找的id,[要查找的范围])
    // var_dump($Tests);
    }else{
    $Tests = $test ->join("inner",'',"subject","subject","subjectid")
    ->join("inner",'',"user","designer","userid")->where($condition,$elem)->fetchAll();
   }
      $testcount=count($Tests);
      $Tests=array_slice($Tests,$start,$limit);//截取
     //$this->assign("Users",$Users);
     //$this->render();
     foreach($Tests as &$value){
         $value['createtime']=date("Y-m-d H:i:s",$value['createtime']);
     }
     $data=array("code"=>'0',"msg"=>"","count"=>$testcount,"data"=>$Tests);
     echo json_encode($data);

}

//添加试卷
function add(){
    $subject =new Subject();
    $Subjects= $subject->fetchAll();//获取所有科目，用于前台显示
    $this->assign('Subjects',$Subjects);
    $questionType= new QuestionType();
    $QuestionTypes=$questionType->fetchAll();//获取所有题型
    $this->assign('QuestionTypes',$QuestionTypes);
     $this->render();

}
//编辑试卷
function edit1($id){
    $question = new Question();
    $test = new Test();
    $subject =new Subject();
    $questionType= new QuestionType();
    /*function join(连接类型，需要连接的表名，表1的字段，表2的字段) 如 inner join ... on 表1.表1的字段 - 表2.表2的字段
    return $this;      可以 多个 join()-> join()...
    */
    ///$Questions= $question->join('inner','','test_question','questionNo','QuestionNo')->where(['testid = ? '],[$id])->fetchAll();
    $Tests= $test->where(["testid = ?"],[$id])->fetch();//详情
    $Subjects= $subject->fetchAll();//获取所有科目，用于前台显示
    $QuestionTypes=$questionType->fetchAll();//获取所有题型
    $test_subject =($subject->fetch($Tests['subject']))['subjectname'];//获取科目名
    $this->assign('Subjects',$Subjects);
    $this->assign('QuestionTypes',$QuestionTypes);
    $this->assign('test_subject',$test_subject);
    $this->assign('Tests',$Tests);
   // $this->assign("Questions",$Questions);
    $this->render('edit');

}
   //批量删除 
public function dels(){
    $ids=  $_POST["ids"];
    if(count($ids)){
        $test =new Test();
        foreach($ids as $k=>&$value){
            //删除所有考试记录
            $currenttests = (new CurrentTest())->where(["testid = ?"],[$value])->fetchAll();
            foreach($currenttests as $_value){
                $result=  (new CurrentTest())->delete($_value['currentTestId']);
            }
         $result=$test->delete($value);
         echo $result;
        }

    }

 }
/*前端显示试卷*/
function show($id){
//按试卷id 查询试题，先查询包含哪些 题型 style
if(is_array($id)){
    //查询能否开始考试
    //查询是否考过了
    $userid = $_SESSION['userid'];
    $classid = $id[0];
    $testid= $id[1];
    $result = (new TestRecord)->where(["userid=?","testid =?"],[$userid,$testid])->fetch();
    if($result) {echo "已完成考试，不能再进行!";
        return 0;
    }
    $classTest =new ClassTest();
     $result = $classTest ->where(["classid = :classid","testid= :testid"],["classid"=>$classid,"testid"=>$testid])->fetch();
    if( $result['status'] !=1){
    echo "不能考试"; return 0;
    }else{
        //可以考试，时间是否到了
        $starttime = $result['starttime'];
        $endtime=$result['endtime']?$result['endtime']:0;
        if($starttime) {
            //当前时间
            $currenttime= strtotime(date("Y-m-d H:i:s"))+8*3600;
            if($starttime > $currenttime) {echo "时间未到！"; return 0;}
            else if($endtime == 0){
                //无截至
            }else if($endtime <  $currenttime){
                echo "已截至！"; return 0;
            }
        }
    }
   $id = $id[1];
}
$questionType= new QuestionType();
$test =new Test();
$question =new Question();
$Tests = $test -> where(['testid = ?'],[$id])->fetch();

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
    if($value){
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
                if(is_numeric($elem)&&is_numeric($j)){
                $Questions[$k]['questions'][$k1]['question'][]=$question->where(["questionNo = ?"],[$elem])->fetch();
                }else if($j=="Bigtitle")
                {$Questions[$k]['questions'][$k1]["title"]=$elem;}
                else{
                    $Questions[$k]['questions'][$k1]['question'][]=   json_decode($elem);
                }
            }
            //$Questions['"'.$k.'"'][]=json_decode($value);
        
        
        
        }else{
            $Questions[$k]['questions'][$k1]=json_decode($value);
        }
   
        
     }
    
}  }
$this->assign("allQuestion",json_encode($Questions));//转换成js可接收的类型  对象{....}
$this->assign("testid",$id);
//考试总时间
$time = $Tests['time'];
//查询有无进行过现在的考试
$currentTest = new CurrentTest();
$userid =  $_SESSION["userid"];
$result = $currentTest -> where(["userid = ?","testid= ? "],[$userid,$id])->fetch();
//获取当前时间
$current = strtotime(date("Y-m-d H:i:s"))+8*60*60;
if($result){
    //正在测试，返回剩余时间
    if(($current-$result['starttime'])>$time*60){
        //是过时的
$result = (new CurrentTest())->where(["currentTestId = :currentTestId"],["currentTestId"=>$result["currentTestId"]])->update(["starttime"=>$current]);
$this->assign("time",$time*60);//剩余时间   
}else{
    $remain=($current-$result['starttime'])<($time*60)?($time*60-($current-$result['starttime'])):0;
    $this->assign("time",$remain);//剩余时间
}
//是否有部分答案
$studnetAnswer = $result["saveTem"]?$result["saveTem"]:json_encode([]);


}else{
    //第一次测验，插入记录
    $result = (new CurrentTest())->add(["userid"=>$userid,"testid"=>$id,"starttime"=>$current]);

    $this->assign("time",$time*60);//剩余时间
    $studnetAnswer = json_encode([]);
}
//$this->assign("current",date("Y-m-d H:i:s",$current));
//$this->assign("other",$time*60-($current-$result['starttime']));

if($Tests){
   
  $this->assign("Tests",$testcontent);
  $this->assign("testName",$Tests['testName']);
  $this->assign("studnetAnswer",$studnetAnswer);
  $this->renderfile(["A"]);
}

}
//临时保存
function  temporary(){
    $testid = $_POST['testid'];
    $userid = $_SESSION["userid"];
    $Test = isset($_POST['Test'])?$_POST['Test']:'';
    (new CurrentTest())->where(["userid =:userid","testid = :testid"],["testid"=>$testid,"userid"=>$userid])->update(["saveTem"=>json_encode($Test)]);
}
//前端编辑页面
function edit($id){
    $test =new Test();
    $question =new Question(); $questionType= new QuestionType();
    $id = $id[1];$classid=$id[0];
    $Tests = $test -> where(['testid = ?'],[$id])->fetch();
    
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
        if($value){
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
                    if(is_numeric($elem)&&is_numeric($j)){
                    $Questions[$k]['questions'][$k1]['question'][]=$question->where(["questionNo = ?"],[$elem])->fetch();
                    }else if($j=="Bigtitle")
                    {$Questions[$k]['questions'][$k1]["title"]=$elem;}
                    else{
                        $Questions[$k]['questions'][$k1]['question'][]=   json_decode($elem);
                    }
                }
                //$Questions['"'.$k.'"'][]=json_decode($value);
            
            
            
            }else{
                $Questions[$k]['questions'][$k1]=json_decode($value);
            }
       
            
         }
        
    }  }
    $this->assign("allQuestion",json_encode($Questions));//转换成js可接收的类型  对象{....}
    $truescores= $Tests["scoreDetail"];
    $this->assign("truescores",$truescores);
    $this->assign("testid",$id);
    //考试总时间
    $time = $Tests['time'];
    //查询有无进行过现在的考试
    $currentTest = new CurrentTest();
    $userid =  $_SESSION["userid"];
    
    //获取当前时间
    $current = strtotime(date("Y-m-d H:i:s"))+8*60*60;
    $TeacherSubject=   (new TeacherSubject)->join('inner','','subject','subjectid','subjectid')
    ->where(["teacherid = ?"],[$userid])->fetchAll();//获取所有科目，用于前台显示
    $this->assign('Subjects',$TeacherSubject);
    //$this->assign("current",date("Y-m-d H:i:s",$current));
    $this->assign("other","dsf");
    if($Tests){
     // $this->assign("Tests",$testcontent);
      $this->assign("Tests",$Tests);
      $this->assign("classid",$classid);
      $this->assign("testid",$id);
      
      $QuestionTypes=$questionType->fetchAll();//获取所有题型
      $this->assign('QuestionTypes',$QuestionTypes);
      $this->renderfile(["editTest"]);
    }
}
/*
*
*对前端的试卷进行评分，并将考试结果插入到数据库中
*/
function putTest(){
    //获取提交的答卷
     $testid = $_POST['testid'];
     $Test = isset($_POST['Test'])?$_POST['Test']:'';// 如：Test[0]['questions'][0] 表示 试卷中的第0大题下的第0小题
                            // 如：Test[0]['questions'][0]['question'][1] 表示 试卷中的第0大题下的第0小题下的第1小题
    //测是获取 试卷中的第0大题下的第0小题的回答
    //$Answers=isset($_POST['Test'])?$_POST['Test']:[];//存放考生考试的答案
    $scoreDetail=[];//评分细节
    $test =new Test();
    $question =new Question();
   
    $Tests = $test -> where(['testid = ?'],[$testid])->fetch();
    //开始
    $testcontent = json_decode($Tests['testcontent'],true);//内容 ,数组
    $scoredetail=  json_decode($Tests['scoreDetail'],true);//每一道题的分数 ,数组
    //从数据库中，提取答案，和答卷一一对比，如答卷的某个答案为空，判定错误
  
    $test =new Test();
    $question =new Question();
   
    $Tests = $test -> where(['testid = ?'],[$testid])->fetch();
    //开始
    $testcontent = json_decode($Tests['testcontent'],true);//内容 ,数组
    $scoredetail=  json_decode($Tests['scoreDetail'],true);//每一道题的分数 ,数组
//$Tests = explode(',', $Tests['questionStyle']);
//先获取单选
$Questions=[];$score=0;   $flag11=0;/// $Tests['questionStyle']  = [][],二维数组  
foreach($testcontent as $kt=>$testCont)//$testCount={title,questions}
{
    //保存 题型说明
     $title = $testCont['titleType'];
     $Questions[$kt]['title']=$title;  
foreach($testCont['questions'] as $k1=>$value){  //$value = 1,2..// 试题号
    //echo $k."下的".$value;
    
    if(is_numeric($value)){       
        //是题号  Questions[1][] ,1 表示是题型
        //$Questions['"'.$k.'"'][]= $question->where(["questionNo = ?"],[$value])->fetch();
      //  echo $question->where(["questionNo = ?"],[$value])->fetch()["title"];
        $Questions[$kt]['questions'][$k1]=$question->where(["questionNo = ?"],[$value])->fetch();
        $answer= $Questions[$kt]['questions'][$k1]['answer']; //echo $answer;
         $oneScore= 0;//该题学生得分
         $trueScore= $scoredetail[$kt]['questions'][$k1];//回答正确得分
        //学生在该题的回答
        $studentAnswer = isset($Test[$kt]["'questions'"][$k1])?$Test[$kt]["'questions'"][$k1]:'';
        //var_dump($studentAnswer);
       /// echo $kt;echo '/'.$k1.'</br>';
        //回答为空吗
        if($studentAnswer){ 
            //回答正确吗
            //多选
            
            if(is_array($studentAnswer)){     $flag11++; 
               // echo $answer;
                //duo
               //比较与答案的长度
            $length = count($studentAnswer);
            ///echo $length;
            $answerlength= strlen($answer);
            if($length > $answerlength){
                //比答案长，错误
                $score+=0;$oneScore=0;
            }else if($length == $answerlength) {
                //需要完全匹配
                foreach($studentAnswer as $k=>$value1)
                {if(strstr($answer,$value1))//是否在答案中
                 {
                     $flag=1;continue;
                 }else{
                     $flag=0;break;
                 }
                }//foreach
                 if($flag){
                     //完全匹配
                     $score+=$trueScore;$oneScore=$trueScore;
                 }else{
                    $score+=0;$oneScore=0;
                 }
                
            }else{
                //最后是否式部分匹配，成功则有一半的分
                foreach($studentAnswer as $k=>$value1)
                {if(strstr($answer,$value1))//是否在答案中
                 {
                     $flag=1;continue;
                 }else{
                     $flag=0;break;
                 }
                }//foreach
                 if($flag){
                     //部分匹配
                     $score+=$trueScore/2;//半分
                     $oneScore=$trueScore/2;
                 }else{
                    $score+=0;$oneScore=0;
                 }

            }
            //多选
            }else{//一个答案
                if($studentAnswer == $answer){
                    //分数加一
                    $score+=$trueScore;$oneScore=$trueScore;
                }else{
                    $oneScore=0;
                }
            }
            $scoreDetail[$kt]["'questions'"][$k1]=$oneScore;
        }//else $score=2;
        else $scoreDetail[$kt]["'questions'"][$k1]=  0  ;
    }else  {// 有小题的问题
        $Questions[$kt]['questions'][$k1]["Qstyle"]="Hat";
        $hatscore = json_decode($scoredetail[$kt]['questions'][$k1],true);
        foreach(json_decode($value) as $j=>$elem){
            //echo "小题".$elem;
            if(is_numeric($elem)&&is_numeric($j)){
            $Questions[$kt]['questions'][$k1]['question'][]=$question->where(["questionNo = ?"],[$elem])->fetch();
            $answer= $Questions[$kt]['questions'][$k1]['question'][$j]['answer']; //echo $answer;
            $trueScore= $hatscore[$j];//回答正确得分
            //学生在该题的回答
            $studentAnswer = isset($Test[$kt]["'questions'"][$k1]["'question'"][$j])?$Test[$kt]["'questions'"][$k1]["'question'"][$j]:'';
            //echo $studentAnswer1;
           
            //验证答案
            if($studentAnswer){ 
                //回答正确吗
                //多选
                
                if(is_array($studentAnswer)){     $flag11++; 
                    //duo
                   //比较与答案的长度
                $length = count($studentAnswer);
                ///echo $length;
                $answerlength= strlen($answer);
                if($length > $answerlength){
                    //比答案长，错误
                    $score+=0;$oneScore=0;
                }else if($length == $answerlength) {
                    //需要完全匹配
                    foreach($studentAnswer as $k=>$value1)
                    {if(strstr($answer,$value1))//是否在答案中
                     {
                         $flag=1;continue;
                     }else{
                         $flag=0;break;
                     }
                    }//foreach
                     if($flag){
                         //完全匹配
                         $score+= $trueScore;$oneScore= $trueScore;
                     }else{
                        $score+=0;$oneScore=0;
                     }
                    
                }else{
                    //最后是否式部分匹配，成功则有一半的分
                    foreach($studentAnswer as $k=>$value1)
                    {if(strstr($answer,$value1))//是否在答案中
                     {
                         $flag=1;continue;
                     }else{
                         $flag=0;break;
                     }
                    }//foreach
                     if($flag){
                         //部分匹配
                         $score+= $trueScore/2;//半分
                         $oneScore=$trueScore/2;
                     }else{
                        $score+=0;
                        $oneScore=0;
                     }
    
                }
                //多选
                }else{//一个答案
                    if($studentAnswer == $answer){
                        //分数加一
                        $score+= $trueScore;$oneScore= $trueScore;
                    }else{
                        $score+=0;$oneScore=0;
                    }
                }
                $scoreDetail[$kt]["'questions'"][$k1]["'question'"][$j]=  $oneScore  ;
            }else{
                $scoreDetail[$kt]["'questions'"][$k1]["'question'"][$j]=  0  ;
            }
            //验证答案
            }
            else if($j=="title")
            {$Questions[$kt]['questions'][$k1]["title"]=$elem;

            }

           
        }
        //$Questions['"'.$k.'"'][]=json_decode($value);
    }
    
}  }
   
    //结束
    $testRecord = new TestRecord();
    (new CurrentTest())->delete($_SESSION["userid"],$testid); 
      if(isset($_SESSION["userid"])){
          //插入考试记录
          //当前时间

          $result=$testRecord->add(["testid"=>$testid,"userid"=>$_SESSION["userid"],"trueScore"=>$Tests['scoreDetail'],"score"=>$score,"student_answer"=>json_encode($Test),"score_detail"=>json_encode($scoreDetail),"starttime"=>strtotime(date('Y-m-d H:i',time()+8*60*60))]);
       // $result=$testRecord->where(["testid = :testid","userid = :userid"],[":testid"=>$testid,":userid"=> $_SESSION["userid"]])->update(["score"=>$total]);
       
      }
    echo json_encode(['data'=>$score]);
}
/*
/*
*老师对主观题进行评分
/*
*/
function putTestByCorrect($testrecordid){
    $Tests = isset($_POST['Test'])?$_POST['Test']:'';//前台已经评分的
    $allscore=0;//总分
    if($Tests){
        $score=0;
        foreach($Tests as &$value){
            $test = $value["'questions'"];//一类题型数组
            foreach($test as $k=>$v){
                //var_dump($v);
                if(isset($v["'question'"])){//大题下的小题
                   // echo "sd"
                foreach($v["'question'"] as $_k=>$_v)
                if(!is_array($_v)){
                if(preg_match("/\s/", $_v)){
                    $v= explode(" ",$_v);
                    $h=[];
                    foreach($v as $g ){//去除空格；
                    if($g!=' '){
                        $h[]=$g;
                    }
                    }
                    $value["'questions'"][$k]["'question'"][ $_k]=$h;
                    foreach($h as $value1){
                        $score+= $value1;
                        $allscore+=$value1;
                    }
                    //var_dump($v);
                }else{
                    
                    $allscore+=$_v;
                }
            
            }
            }else{
                //$v--答案，类型--字符串
                if(preg_match("/\s/", $v)){
                    $v= explode(" ",$v);
                    $h1=[];
                    foreach($v as $g ){//去除空格；
                    if($g!=' '){
                        $h1[]=$g;
                    }
                    }
                    $value["'questions'"][$k]=$h1;
                    foreach($h1 as $value1){
                        $score+= $value1;
                        $allscore+=$value1;
                    }
                    //var_dump($v);
                }else{
                    $allscore+=intval($v);
                }

            }
        }
        }
    }
//var_dump($Tests);
$testRecord = new TestRecord();
//$score += (new TestRecord )->where(["recordid= :testrecordid"],["testrecordid"=>$testrecordid])->fetch()['score'];
$result=$testRecord->where(["recordid= :testrecordid"],["testrecordid"=>$testrecordid])->update(["score_detail"=>json_encode($Tests),"score"=>$allscore,"iscorrect"=>1]);
echo json_encode(["data"=>"提交成功"]);
}
/*显示可组卷的试题
*
@param ：
*/
function showQuestionBystyle($style){

    //$sql="select * from question inner join question_type on style = question_type_id where style=".$style;
    $Questions=(new Question)->where(['style = ?'],[$style])->fetchAll();
    $this->assign("id",$style);//将题型id传给前台
    $this->assign("Questions",$Questions);
   // $this->render('showQuestionList');
   $this->render('eam');
   
}
function showQuestionList($subject=''){
    //获取当前页
    $currentPage= $_POST['page'];
    //获取要查询的题号组，用于checkbox的选中
    $datas = isset($_POST['datas'])?$_POST['datas']:[];//父页面已选的
    //获取一页数据数
    $limit =$_POST['limit'];
    //题型
    $id= isset($_POST['id'])?$_POST['id']:'';//题型id
    $style = isset($_POST['style'])?$_POST['style']:'';//是哪个模块在请求
    //$QuestionStyle = isset($_POST['QuestionStyle'])?$_POST['QuestionStyle']:'';//重载请求问题类型
    $kidSelects = isset($_POST['kidSelects'])?$_POST['kidSelects']:[];//记录子页面已选的
    $question = new Question();//实例化
    $num=0;
    $data= isset($_POST['data'])?$_POST['data']:'';//条件请求
    //$Questions= $question->in(['questionNo'],[$ids]);
    $condition[]= "subject = ?";  $elem[]=$subject;
    if($id){
        if($style == 'manual'){
            if($data){
                //解析序列化数据
                parse_str($data,$myArray);
               // echo $myArray['keyWord'];
               //带条件的查询
               //keyWord=&questionLevel=%E7%AE%80%E5%8D%95&questionTypes=unlimited
               //$param=$_POST['keyWord'];
               //$level=$_POST['questionLevel'];
                 $keyWord = $myArray['keyWord']; 
                 $questionLevel = $myArray['questionLevel'];
                 //echo  $questionLevel;
                 $questionSubject = $myArray['questionSubject'];
                 $condition = [];$elem=[];
                 /*
                 if($questionSubject)
                 {
                     $condition[]="kownledgepoint = ?"; $elem[]=$questionSubject;
                 }
                 */
                 if($questionLevel)
                 {
                     $condition[]="level = ?"; $elem[]=$questionLevel;
                 }
                 $condition[]= "title like ?";  $elem[]="%$keyWord%";
                 $condition[]= "style = ?";  $elem[]=$id;
                 $Questions=$question->join("inner","","subject","subject","subjectid")->where($condition, $elem)->order()->fetchAll(); 
                 //筛选知识点  
                 foreach($Questions as $sk=>$svalue){
                    $points=(new Question_Kownledgepoint())->where(["questionid = ?"],[$svalue['questionNo']])->fetchAll();
                    $_flag=0;
                    $str='';
                    foreach($points as $pvalue){
                        var_dump($points);
                        $pointid =$pvalue['kownledgepointid'];
                        $str.=$pointid.",";
                       // var_dump($pointid);
                        if($pointid == $questionSubject){
                            $_flag=1;
                        }
                    }
                    $Questions[$sk]["points"]=rtrim($str,',');
                    if(!$_flag && $questionSubject){
                       //删除
                       unset($Questions[$sk]);
                    }

                    }     
            
        }else{
                  //条件查询
                  $condition[]= "style = ?";  $elem[]=$id;
                  $Questions=(new Question)->join("inner","","subject","subject","subjectid")->where($condition, $elem)->fetchAll();
                   //筛选知识点  
                 foreach($Questions as $sk=>$svalue){
                    $points=(new Question_Kownledgepoint())->join("inner","","kownledgepoint","kownledgepointid","kownledgepointid")
                    ->where(["questionid = ?"],[$svalue['questionNo']])->fetchAll();
                    $str='';
                    foreach($points as $pvalue){         
                        $pointid =$pvalue['kownledgepointid'];
                        $str.=$pointid.",";
                    }
                    $Questions[$sk]["points"]=rtrim($str,',');

                    } 
        }


        //删除指定的数据
        foreach($Questions as $k=>$value){
            if(in_array($value['questionNo'], $datas))
                 //$num++;
                 unset($Questions[$k]);
            //array_splice($Questions,$k,1);
        }
        //设置选中
        foreach($Questions as &$value){
            if(in_array($value['questionNo'],$kidSelects))
            $value['LAY_CHECKED']=true;
            else $value['LAY_CHECKED']=false;
        }



        }else{ /*
           
    $Questions=(new Question)->where(['style = ?'],[$id])->fetchAll();
    foreach($Questions as &$value){
        if(in_array($value['questionNo'],$kidSelects))
        $value['LAY_CHECKED']=true;
        else $value['LAY_CHECKED']=false;
    }*/
}
   }else{
        $Questions=(new Question)->fetchAll();
    }
    $start = ($currentPage-1)*$limit;
//$ids= [["ID"=>$Questions[0]['answer'],"username"=>"1"],["ID"=>'李上进',"username"=>"2"],["ID"=>'李上进',"username"=>"3"],["ID"=>'李上进',"username"=>"4"],["ID"=>'李上进',"username"=>"5"],["ID"=>'李上进',"username"=>"6"],["ID"=>'李上进',"username"=>"7"],["ID"=>'李上进',"username"=>"8"],["ID"=>'李上进',"username"=>"9"],["ID"=>'李上进',"username"=>"10"],["ID"=>'李上进',"username"=>"11"],["ID"=>'李上进',"username"=>"12"],["ID"=>'李上进',"username"=>"13"],["ID"=>'李上进',"username"=>"14"],["ID"=>'李上进',"username"=>"15"],["ID"=>'李上进',"username"=>"16"]];
$ids=array_slice($Questions,$start,$limit);//截取5个
$data=array("code"=>'0',"msg"=>$num,"count"=>count($Questions),"data"=>$ids);
echo json_encode($data);
}
 function showQuestionsList(){
    //获取当前页
    $currentPage= $_POST['page'];
    //获取要查询的题号组，用于checkbox的选中
    $datas = isset($_POST['datas'])?$_POST['datas']:[];//父页面已选的
    //获取一页数据数
    $limit =$_POST['limit'];
    //题型
    $id= isset($_POST['id'])?$_POST['id']:'';//题型id
    $style = isset($_POST['style'])?$_POST['style']:'';//是哪个模块在请求
    //$QuestionStyle = isset($_POST['QuestionStyle'])?$_POST['QuestionStyle']:'';//重载请求问题类型
    $kidSelects = isset($_POST['kidSelects'])?$_POST['kidSelects']:[];//记录子页面已选的
    $question = new Question();//实例化
    $num=0;
    //$Questions= $question->in(['questionNo'],[$ids]);
    if($id){
        if($style == 'manual'){
            $Questions=(new Question)->where(['style = ?'],[$id])->fetchAll();
            //删除指定的数据
            /*
            foreach($Questions as $k=>$value){
                if(in_array($value['questionNo'], $datas))
                     //$num++;
                     unset($Questions[$k]);
                //array_splice($Questions,$k,1);
            }*/
            //设置选中
            foreach($Questions as &$value){
                if(in_array($value['questionNo'],$kidSelects)||in_array($value['questionNo'],$datas))
                $value['LAY_CHECKED']=true;
                else $value['LAY_CHECKED']=false;
            }
            
        }else{
    $Questions=(new Question)->where(['style = ?'],[$id])->fetchAll();
    foreach($Questions as &$value){
        if(in_array($value['questionNo'],$kidSelects)||in_array($value['questionNo'],$datas))
        $value['LAY_CHECKED']=true;
        else $value['LAY_CHECKED']=false;
    }}
   }else{
        $Questions=(new Question)->fetchAll();
    }
    $start = ($currentPage-1)*$limit;
//$ids= [["ID"=>$Questions[0]['answer'],"username"=>"1"],["ID"=>'李上进',"username"=>"2"],["ID"=>'李上进',"username"=>"3"],["ID"=>'李上进',"username"=>"4"],["ID"=>'李上进',"username"=>"5"],["ID"=>'李上进',"username"=>"6"],["ID"=>'李上进',"username"=>"7"],["ID"=>'李上进',"username"=>"8"],["ID"=>'李上进',"username"=>"9"],["ID"=>'李上进',"username"=>"10"],["ID"=>'李上进',"username"=>"11"],["ID"=>'李上进',"username"=>"12"],["ID"=>'李上进',"username"=>"13"],["ID"=>'李上进',"username"=>"14"],["ID"=>'李上进',"username"=>"15"],["ID"=>'李上进',"username"=>"16"]];
$ids=array_slice($Questions,$start,$limit);//截取5个
$data=array("code"=>'0',"msg"=>$num,"count"=>count($Questions),"data"=>$ids);
echo json_encode($data);
 }

function addQuestionBystyle($elem){
    $style=$elem[0];
    if( $style){
       
    $Questions=(new Question)->where(['style = ?'],[$style])->fetchAll();
    $this->assign("id",$style);//将题型id传给前台
    }else{
        $Questions=(new Question)->fetchAll();
        $this->assign("id",$style);//将题型id传给前台
    }
    //$Subjects = (new Subject())->fetchAll();
    //$this->assign("Subjects",$Subjects);
   // echo $elem[1];
    $userid = $_SESSION['userid'];
    $TeacherSubject=(new Subject_Kownledgepoint)->join('inner','','subject','subjectid','subjectid')->join('inner','','kownledgepoint','kownledgepointid','kownledgepointid')
    ->join('inner','subject','teachersubject','subjectid','subjectid')->where(["teacherid =?","subject_kownledgepoint.subjectid=?"],[$_SESSION['userid'],$elem[1]])->fetchAll();
    $this->assign("Subjects",$TeacherSubject);
   // var_dump($TeacherSubject);
    $this->assign("Questions",$TeacherSubject);
    
   // $this->render('showQuestionList');
   $this->render('showBystyle');

}

function showSelectedQuestions(){
   

    return $this->render();
}
//测试layer
function layer(){


    $this->render('layout');
}
function layerTable(){
   
    //获取当前页
    $currentPage= $_POST['page'];
    //获取要查询的题号组，
    $ids = $_POST['datas'];
    //获取一页数据数
    $limit =$_POST['limit'];

    $question = new Question();//实例化
    $Questions= $question->in(['questionNo'],[$ids]);
    //$this->assign("Tests",$Questions);
    $start = ($currentPage-1)*$limit;
$ids= [["ID"=>$Questions[0]['answer'],"username"=>"1"],["ID"=>'李上进',"username"=>"2"],["ID"=>'李上进',"username"=>"3"],["ID"=>'李上进',"username"=>"4"],["ID"=>'李上进',"username"=>"5"],["ID"=>'李上进',"username"=>"6"],["ID"=>'李上进',"username"=>"7"],["ID"=>'李上进',"username"=>"8"],["ID"=>'李上进',"username"=>"9"],["ID"=>'李上进',"username"=>"10"],["ID"=>'李上进',"username"=>"11"],["ID"=>'李上进',"username"=>"12"],["ID"=>'李上进',"username"=>"13"],["ID"=>'李上进',"username"=>"14"],["ID"=>'李上进',"username"=>"15"],["ID"=>'李上进',"username"=>"16"]];
$ids=array_slice($ids,$start,$limit);//截取5个
$data=array("code"=>'0',"msg"=>"","count"=>count($ids),"data"=>$ids);
echo json_encode($data);

}
function layerTable1(){
    //获取要查询的题号组，
    $ids = $_POST['datas'];
    $question = new Question();//实例化
    $Questions= $question->in(['questionNo'],[$ids]);
    //$this->assign("Tests",$Questions);
    ///$start = ($currentPage-1)*$limit;
//$ids= [["ID"=>$Questions[0]['answer'],"username"=>"1"],["ID"=>'李上进',"username"=>"2"],["ID"=>'李上进',"username"=>"3"],["ID"=>'李上进',"username"=>"4"],["ID"=>'李上进',"username"=>"5"],["ID"=>'李上进',"username"=>"6"],["ID"=>'李上进',"username"=>"7"],["ID"=>'李上进',"username"=>"8"],["ID"=>'李上进',"username"=>"9"],["ID"=>'李上进',"username"=>"10"],["ID"=>'李上进',"username"=>"11"],["ID"=>'李上进',"username"=>"12"],["ID"=>'李上进',"username"=>"13"],["ID"=>'李上进',"username"=>"14"],["ID"=>'李上进',"username"=>"15"],["ID"=>'李上进',"username"=>"16"]];
//$ids=array_slice($ids,$start,$limit);//截取5个
$data=array("code"=>'0',"msg"=>"","count"=>16,"data"=>$Questions);
echo json_encode($data);
   // echo json_encode(["count"=>3]);

}
/*保存试卷*/
function saveTest(){
    //获取题型数组{"1":[],"2":[],...}
    $ids =  isset($_POST['ids'])? $_POST['ids']:'';
    $subject=isset($_POST['subject'])?$_POST['subject']:'';
    $_time=isset($_POST['_time'])?$_POST['_time']:'120';
    $_mark=isset($_POST['_mark'])?$_POST['_mark']:'100';
    $_pass=isset($_POST['_pass'])?$_POST['_pass']:'60';
    $testid = isset($_POST['testid'])?$_POST['testid']:'';//为空则为添加操作，否则是更新
    $testName =$_POST['testName'];
   
    $userid =  $_SESSION["userid"];
    $test = new Test();
    $data = array('testName' => $testName,"createtime"=>strtotime(date("Y-m-d H:i:s"))+8*60*60,'testcontent'=>$ids,'time'=>$_time,'designer'=>$userid,'subject'=>$subject,'totalscore'=>$_mark,'passScore'=>$_pass);
    if($testid){
    $results =$test->where(['testid = :testid'],[':testid'=>$testid])->update($data);
    echo json_encode(array("data"=>'1'));
    //更新 
    }else{
        //$data = array('testName' => 'sd');
        $results =$test->add($data);//插入
        echo json_encode(array("data"=>'2'));
    }

}
//随机组卷
function addByRendom($classid = ''){
    $subject =new Subject();
    $userid=$_SESSION['userid'];
    $TeacherSubject=   (new TeacherSubject)->join('inner','','subject','subjectid','subjectid')->where(["teacherid = ?"],[$userid])->fetchAll();
    //$Subjects= $subject->fetchAll();//获取所有科目，用于前台显示
    $questionType= new QuestionType();
    $QuestionTypes=$questionType->fetchAll();//获取所有题型
    
    $this->assign('QuestionTypes',$QuestionTypes);
    $this->assign('Subjects',$TeacherSubject);
    $this->assign("classid",$classid);
    $this->render();
}
function saveTestByRandom1($classid = ''){
    $subject=isset($_POST['subject'])?$_POST['subject']:'';
    $_time=isset($_POST['_time'])?$_POST['_time']:'120';
    $_mark=isset($_POST['_mark'])?$_POST['_mark']:'100';
    $_pass=isset($_POST['_pass'])?$_POST['_pass']:'60';
    $testName =$_POST['testName'];
    $correct =$_POST['correct'];
    $dragger = $_POST["dragger"];//题型分布顺序
    $easy = $_POST["easy"];//题型分布顺序
    $middle = $_POST["middle"];//题型分布顺序
    $hard = $_POST["hard"];//题型分布顺序
    $detail = $_POST["detail"];//题型分布顺序
    $subjectPoint = isset($_POST['subjectPoint'])?$_POST['subjectPoint']:[];
    //各题型的分布及分值
    $sum= $_POST["sum"];
    $eachScore=$_POST["singleEachScore"];
    $questions=[]; $question = []; $allQuestions=[];$scoreDetail=[];$score=[];//分值详细分布
    foreach($dragger as $dragger_k => $dragger_vlaue){
        //$dragger_vlaue 为题型id
        $value = $sum["$dragger_vlaue"];//总题数
        //$k 为题型id 
        //[{"titleType":"hhh","questions":["78","79","140"]},
	    //{"titleType":"hhh","questions":["161","162","166","167"]}
        //]
       // echo $k;echo $value;
        
        if($value){
            for($i=0;$i<$value;$i++){
               $score[]= $eachScore["$dragger_vlaue"];
            }
            //题配比
            $i=0;// echo $value;
            if($easy[$dragger_vlaue]){   //容易题
                $question[$i] = (new  Question())->where(["style = ?","level= ?","subject = ?"],[$dragger_vlaue,'易',$subject])->fetchAll();
               
                // echo $easy[$dragger_vlaue];
                //剔除知识点不在范围内的
                //echo count($question[$i]);
                foreach($question[$i] as $sk=>$svalue){
                $points=(new Question_Kownledgepoint())->where(["questionid = ?"],[$svalue['questionNo']])->fetchAll();
                $_flag=0;
                foreach($points as $pvalue){
                    $pointid =$pvalue['kownledgepointid'];
                    $flag=in_array($pointid,$subjectPoint);//不在则返回-1；
                    //echo $flag.'//';
                    if($flag == 1){
                        $_flag=1;break;
                    }
                }//echo  "*".$_flag;
                if(!$_flag ){
                   //删除
                   //unset($question[$i][$sk]);
                }
                }
               /// var_dump($question[$i]);
                //是否有足够的题供选择
                if(count($question[$i])<$easy[$dragger_vlaue]){
                    //所选取的试题数比题库多
                   
                    echo json_encode(["msg"=>"所选取的容易试题数比题库多","keyWord"=>$dragger_vlaue,"status"=>0]);
                    return ;
                }else{
                    //随机选取$dragger_vlaue个
                    $questionIndex = array_rand($question[$i],$easy[$dragger_vlaue]);//键名
                    $hh=[];
                    if(is_array($questionIndex)){
                    foreach($questionIndex as $value){
                         $hh[]= $question[$i][$value]['questionNo'];
                    }}else $hh[]= $question[$i][$questionIndex]['questionNo'];
                    //将选择的试题加到数组中
                    $allQuestions=array_merge($allQuestions,$hh);
                }$i++; 
                //var_dump($allQuestions);
            }

            if($middle[$dragger_vlaue]){//中等题
                $question[$i] = (new  Question())->where(["style = ?","level= ?","subject = ?"],[$dragger_vlaue,'中',$subject])->fetchAll();
               
                //是否有足够的题供选择
                if(count($question[$i])<$middle[$dragger_vlaue]){
                   //所选取的试题数比题库多
                   echo json_encode(["msg"=>"所选取的中等试题数比题库多","keyWord"=>$dragger_vlaue,"status"=>0]);
                   return ;
                }else{
                    //随机选取$dragger_vlaue个
                    $questionIndex = array_rand($question[$i],$middle[$dragger_vlaue]);//键名
                    $hh=[];
                    if(is_array($questionIndex)){
                    foreach($questionIndex as $value){
                         $hh[]= $question[$i][$value]['questionNo'];
                    }}else $hh[]= $question[$i][$questionIndex]['questionNo'];
                    //将选择的试题加到数组中
                    $allQuestions=array_merge($allQuestions,$hh);
                } $i++;
            }
            if($hard[$dragger_vlaue]){//困难题
                $question[$i] = (new  Question())->where(["style = ?","level= ?","subject = ?"],[$dragger_vlaue,'难',$subject])->fetchAll();
                //是否有足够的题供选择
                if(count($question[$i])<$hard[$dragger_vlaue]){
                   //所选取的试题数比题库多
                   echo json_encode(["msg"=>"所选取的困难试题数比题库多","keyWord"=>$dragger_vlaue,"status"=>0]);
                   return ;
                }else{
                    //随机选取$dragger_vlaue个
                    $questionIndex = array_rand($question[$i],$hard[$dragger_vlaue]);//键名
                    $hh=[];
                    if(is_array($questionIndex)){
                    foreach($questionIndex as $value){
                         $hh[]= $question[$i][$value]['questionNo'];
                    }}else $hh[]= $question[$i][$questionIndex]['questionNo'];
                    //将选择的试题加到数组中
                    $allQuestions=array_merge($allQuestions,$hh);
                } $i++;
            }
            
      
      
       $questions[]=["titleType"=>(isset($detail["$dragger_vlaue"])?$detail["$dragger_vlaue"]:'空'),"questions"=>$allQuestions];
       $scoreDetail[]= ["titleType"=>(isset($detail["$dragger_vlaue"])?$detail["$dragger_vlaue"]:'空'),"questions"=>$score];
       $allQuestions=[];$score=[];
    }
    }
    //session_start();
    $userid = 5;
    $test = new Test();
    $data = array('testName' => $testName,"scoreDetail"=>json_encode($scoreDetail),"createtime"=>strtotime(date("Y-m-d H:i:s"))+8*60*60,'testcontent'=>json_encode($questions),'time'=>$_time,'designer'=>$userid,'subject'=>$subject,'totalscore'=>$_mark,'passScore'=>$_pass);
   
    $results =$test->add($data);//插入
    $testId =   $test->newId();
    $classTest = new ClassTest();
    $result = $classTest->add(["testid"=>$testId,"classid"=>$classid]);
    echo json_encode(["msg"=>"添加成功!","status"=>1]);
  
}
 function newList($classid=''){
     //手动编写新试卷
     $questionType= new QuestionType();
    $QuestionTypes=$questionType->fetchAll();//获取所有题型
    $userid=$_SESSION['userid'];
    $TeacherSubject=   (new TeacherSubject)->join('inner','','subject','subjectid','subjectid')->where(["teacherid = ?"],[$userid])->fetchAll();//获取所有科目，用于前台显示
    
    $this->assign('Subjects',$TeacherSubject);
    $this->assign('QuestionTypes',$QuestionTypes);
     $this->assign("classid",$classid);
     $this->renderfile(["testtool"]);
 }
 function showNewList(){
     
 }
 //保存新编的试题内容
 function addTest(){
     //获取试题
     $questions = $_POST['question'];
     $score = $_POST['Score'];
     $classid = isset($_POST['classid'])?$_POST['classid']:'';
     $testname = isset($_POST['testname'])?$_POST['testname']:'';
     $subject = isset($_POST['subject'])?$_POST['subject']:'';
     $allascore = isset($_POST['score'])?$_POST['score']:'';
     $time = isset($_POST['time'])?$_POST['time']:'';
      //$testcontent =[];
     //foreach($questions as $k=>$value){
          //if(is_numeric($value))
        //  {$testcontent[$k]=$value;}
          //else echo "zifu";
   //  }
  
   if(isset($_SESSION["userid"])){
     $data = array('testName' => $testname,"createtime"=>strtotime(date("Y-m-d H:i:s"))+8*3600,"scoreDetail"=>($score),'testcontent'=>($questions),'designer'=>$_SESSION["userid"],'time'=>$time,'subject'=> $subject,'totalscore'=> $allascore,'passScore'=>60);
     //echo json_encode($questions);
    // echo gettype(json_encode($testcontent));
    $test = new Test();
    $results =$test->add($data);//插入
    //往班级测试表插入最新数据；
    $testId = $test->newId();
    if($classid){
    $classTest = new ClassTest();
    $result = $classTest->add(["testid"=>$testId,"classid"=>$classid]);
    }
    echo json_encode(["data"=>$results ]);
   }
 }
 //保存编辑试卷
 function putTestByEdit(){
     //获取试题
     $questions = $_POST['question'];
     $scoreDetail = $_POST['Score'];
     $classid = isset($_POST['classid'])?$_POST['classid']:'';
     $testid = isset($_POST['testid'])?$_POST['testid']:'';
     $testname=$_POST['testname'];
     $score =$_POST['score'];
     $time= $_POST['time'];
      //$testcontent =[];
     //foreach($questions as $k=>$value){
          //if(is_numeric($value))
        //  {$testcontent[$k]=$value;}
          //else echo "zifu";
   //  }
  
   if(isset($_SESSION["userid"])){
     $data = array('testName' =>$testname,"createtime"=>strtotime(date("Y-m-d H:i:s"))+8*3600,"scoreDetail"=>$scoreDetail,'testcontent'=>$questions,'designer'=>$_SESSION["userid"],'time'=>$time,'totalscore'=>$score);
    // echo json_encode($testcontent);
    // echo gettype(json_encode($testcontent));
    $test = new Test();
    $results =$test->where(["testid = :testid"],["testid"=>$testid])->update($data);//插入
    //往班级测试表插入最新数据；
   // $testId = $test->newId();
    //$classTest = new ClassTest();
    //$result = $classTest->add(["testid"=>$testId,"classid"=>$classid]);
    echo json_encode(["data"=> $results ]);
   }
 }
 //文件上传
 function file(){
    $filesName = $_FILES['file']['name'];  //�ļ�������
	$filesTmpName = $_FILES['file']['tmp_name'];  //��ʱ�ļ�������
	$filePath = "./img/".date("Ymd",time()).rand(1,50).$filesName;
    while(file_exists($filePath))
	{$filePath = "./img/".date("Ymd",time()).rand(1,50).$filesName;}

    move_uploaded_file($filesTmpName, $filePath);
    $exfn = $this->_readExcel($filePath); // 读取内容，二维数组
    $option = [];
    $sel1=0;$sel2=0;
    foreach($exfn as $k=>$testcont)
    foreach($testcont as $k1=>$value){
        $option =[];$index= 0;//初始化
        if($value == '单选题'){
        $Questions['1'][$sel1]['title']=$testcont[$k1+1];
        $Questions['1'][$sel1]['style']=1;//单选
        //处理选项,先获取选项个数
        $num = intval($testcont[$k1+2]);
        $index = $k1+3;
        for($n=0;$n< $num;$n++){
            $option[]=$testcont[$index++];//将选项放入数组中
        }
        
        $Questions['1'][$sel1++]['options']=json_encode($option);//先转为json字符串，再赋值
       break; 
        }else if($value == '题型'){
        $Questions['2'][$sel2]['title']=$testcont[$k1+1];
        $Questions['2'][$sel2]['style']=2;//单选
        //处理选项,先获取选项个数
        $num = intval($testcont[$k1+2]);
        $index = $k1+3;
        for($n=0;$n< $num;$n++){
            $option[]=$testcont[$index++];//将选项放入数组中
        }
        $Questions['2'][$sel2++]['options']=json_encode($option);//先转为json字符串，再赋值
    break; 
        }
}     
   // $this->assign("allQuestion",json_encode($Questions));//转换成js可接收的类型  对象{....}
    //$this->assign("four",json_encode((object)$Questions4));//转换成js可接收的类型 对象{....}
    //$this->assign("three",json_encode((object)$Questions3));//转换成js可接收的类型 对象{....}
   
      //$this->render();
      //$this->renderfile(["A"]);
    
         echo json_encode((object) ["data"=>$Questions]);
         unlink($filePath); // 上传完文件之后删除文件，避免造成垃圾文件的堆积
 }
 public function _readExcel($path)
    {
        //引用PHPexcel 类
        //include_once('util/PHPExcel.php');
        //include_once('util/PHPExcel/IOFactory.php');//静态类
        $type = 'Excel2007';//设置为Excel5代表支持2003或以下版本，Excel2007代表2007版
        $xlsReader = PHPExcel_IOFactory::createReader($type);
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($path);
        //开始读取上传到服务器中的Excel文件，返回一个二维数组
        $dataArray = $Sheets->getSheet(0)->toArray();
        return $dataArray;
    }
    //预览
    function scan(){
        //$data = json_decode($data);
        ///$this->assign("allQuestion","'fsdfsfs'");//转换成js可接收的类型  对象{....}
       // echo $data;
     
        // $this->assign("Tests",$testcontent);
         //$this->render();
         $this->renderfile(["A"]);

    }
    //编辑文本工具页
    function showText(){
        $this->render();
    }

    function showTextByEdit($title=""){
        $this->assign("title",$title);
        $this->render();
       
    }
    //题目编辑器
    function showQuestionByEdit($id){
         //根据id 进行查询
    $question=(new Question())->where(['questionNo = ?'],[$id])->fetch();
    $optionArray=[];
    //读取题型
    $questionTypes = (new QuestionType())->fetchAll();
 
    
    $this->assign("questionsTypes",$questionTypes);
    $this->assign("Question",$question);
    //对选项进行特别处理。
    /*
    if($question['options']){
    $optionArray=(new Question())->_split($question['options']);//按拆分成数组
    }*/
    //$this->assign('optionArray',$optionArray);
    $this->render();
      
    }
    function showQuestionByEdit2($id){
        //根据id 进行查询

  // $question=$id;
   //var_dump(urldecode( urldecode ($id)));
  // $optionArray=[];
   //读取题型
   $question= json_decode( urldecode( urldecode ($id)),true);

   $questionTypes = (new QuestionType())->fetchAll();

   
   $this->assign("questionsTypes",$questionTypes);
   $this->assign("Question",$question);
   //对选项进行特别处理。
   /*
   if($question['options']){
   $optionArray=(new Question())->_split($question['options']);//按拆分成数组
   }*/
   //$this->assign('optionArray',$optionArray);
   $this->render("showQuestionByEdit");
     
   }

}