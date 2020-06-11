<?php
namespace app\controllers;
use fastphp\base\Controller;//基类控制器
use app\models\Question;
use app\models\QuestionType;
use app\models\Subject;
use app\models\TeacherSubject;
use app\models\Question_Kownledgepoint;
class QuestionController extends Controller
{
function list(){
    $question=new Question();
    $questionTypes = (new QuestionType())->fetchAll();
    $Questions=$question->join('inner','question','question_type','style','question_type_id')->where()->order()->fetchAll();
    $this->assign("Questsions",$Questions);
    $this->assign("questionsTypes",$questionTypes);
    $subject =new Subject();
    $Subjects= $subject->fetchAll();//获取所有科目，用于前台显示
    //默认知识点
    $this->assign('Subjects',$Subjects);
    $this->render('questionList');//渲染模板
}
function listshow(){

    $currentPage= $_POST['page'];//第几页
    $limit =$_POST['limit'];//获取一页数据数
    $start = ($currentPage-1)*$limit;//开始截取数组的位置
    //查询当前用户能管理的可目
    $userid = $_SESSION['userid'];
    $TeacherSubject=   (new TeacherSubject)->where(["teacherid = ?"],[$userid])->fetchAll();
    $data= isset($_POST['data'])?$_POST['data']:'';
    $question=new Question();
    $condition=[]; $elem=[];
    //提取科目编号
    //如果是管理员，则全部显示
 
    
   
    if($data){
        //解析序列化数据
        parse_str($data,$myArray);
         $keyWord = $myArray['keyWord']; 
         $questionLevel = $myArray['questionLevel'];
         //echo  $questionLevel;
         $questionTypes = $myArray['questionTypes'];
         $selectsubject = $myArray['selectsubject'];
         $selectpoint = $myArray['selectpoint'];
        // $condition = [];$elem=[];
       
        if($selectsubject)
         {
             $condition[]="subject = ?"; $elem[]=$selectsubject;
         }
         if($questionLevel)
         {
             $condition[]="level = ?"; $elem[]=$questionLevel;
         }
         if($questionTypes)
         {
             $condition[]="style = ?"; $elem[]=$questionTypes;
         }
         $condition[]= "belonger = ?";  $elem[]= $_SESSION['userid'];
         $condition[]= "title like ?";  $elem[]="%$keyWord%";
       
        // echo  $keyWord;
        $Questions=$question->join('inner','question','question_type','style','question_type_id')
        ->join('inner','question','subject','subject','subjectid')
        ->join('inner','question','user','belonger','userid')
        ->where($condition, $elem)->order(['questionNo ASC'])->fetchAll();
        //筛选知识点
        if($selectpoint)
        foreach($Questions as $sk=>$svalue){
            $points=(new Question_Kownledgepoint())->where(["questionid = ?"],[$svalue['questionNo']])->fetchAll();
            $_flag=0;
            foreach($points as $pvalue){
                $pointid =$pvalue['kownledgepointid'];
                //echo $pointid."df";
                if($pointid == $selectpoint)
                {
                    $_flag=1;break;
                }
            }
            if(!$_flag ){
               //删除
               unset($Questions[$sk]);
            }
            }
    }else{
        $condition[]= "belonger = ?";  $elem[]= $_SESSION['userid'];
        $Questions=$question->join('inner','question','question_type','style','question_type_id')
        ->join('inner','question','subject','subject','subjectid')
        ->join('inner','question','user','belonger','userid')
        ->where($condition, $elem)->order(['questionNo ASC'])->fetchAll();
    // echo  $subject;
     //var_dump($condition);
     //var_dump( $elem);
    }
    $count = count($Questions);
    $Questions=array_slice($Questions,$start,$limit);//截取
    //$this->assign("Users",$Users);
    //$this->render();
    $data=array("code"=>'0',"msg"=>"","count"=>$count,"data"=>$Questions);
    echo json_encode($data);
}
function edit($id=''){
    //根据id 进行查询
    $question=(new Question())->where(['questionNo = ?'],[$id])->fetch();
    $optionArray=[];
    //读取题型
    $questionTypes = (new QuestionType())->fetchAll();
    //当前是什么题型
    $style = $question["style"];

    $subject =new Subject();
    $Subjects= $subject->fetchAll();//获取所有科目，用于前台显示
    //获取知识点
    $point=(new Question_Kownledgepoint())->where(["questionid = ?"],[$id])->fetchAll();
    //只提取知识点，不要题号
    $keyPoint=[];
    foreach($point as $value){
        $keyPoint[]=$value['kownledgepointid'];
    }
    $this->assign('KeyPoint',$keyPoint);
    $this->assign('Subjects',$Subjects);
    $this->assign("questionsTypes",$questionTypes);
    $this->assign("Question",$question);
    
    //对选项进行特别处理。
    /*
    if($question['options']){
    $optionArray=(new Question())->_split($question['options']);//按拆分成数组
    }*/
    $this->assign('optionArray',$optionArray);
    $this->render();


}
function questionDetail($id=''){
    $question=(new Question())->where(['questionNo = ?'],[$id])->fetch();
    $this->assign("question",$question);
    $this->render();
}
function save(){
    //获取前台的数据
   
    $id=isset($_POST['id'])? $_POST['id']:'';//题号
    $options=isset($_POST['options'])? $_POST['options']:'';//选项数组
    $answer=isset($_POST['edit']["answer"])? $_POST['edit']["answer"]:'';//答案
    $level=isset($_POST["level"])? $_POST["level"]:'';//答案
    $analysis =isset($_POST['edit']["analysis"])? $_POST['edit']["analysis"]:'';//解析
    $subject=isset($_POST['subject'])?$_POST['subject']:'';
    if($answer){
        $answer=str_replace('<p>','',$answer);
        $answer=str_replace('</p>','',$answer);
    }
    $title=$_POST['edit']["title"];//题目
    $style= isset($_POST['questionType']) ? $_POST['questionType'] : '';//题型
    //更新
    $data = array( 'questionNo'=>$id,'subject'=>$subject,'title' => $title,'analysis'=>$analysis,'level'=>$level,'answer'=>$answer,'options'=>$options,'style'=>$style);
    $question =new Question();
    
    $result=$question->where(['questionNo = :questionNo'],[':questionNo' =>$id])->update($data);

    $subjectPoint = isset($_POST['subjectPoint'])?$_POST['subjectPoint']:[];
    //更新题目所属知识点，可能有多个
    //先删除再添加
    $point=(new Question_Kownledgepoint())->where(["questionid = ?"],[$id])->fetchAll();
    foreach($point as $value){
        ( new Question_Kownledgepoint())->delete($value['kownledgepointid'],$id);
    }
    foreach($subjectPoint as $value){
        //$value ---知识点kownledgepoint
       ( new Question_Kownledgepoint())->add(["questionid"=>$id,"kownledgepointid"=>$value]);
    }
    echo json_encode(array("status"=>$title)) ;
}
function delete(){
    $result=1;
    $status=1;
    
    $id=$_POST['id'];//获取id列
    //session_start();
    //$testid= $_SESSION['testid'];//获取试题号
    //删除q问题关联的知识点
    $questions=(new Question_Kownledgepoint())->where(["questionid = ?"],[$id])->fetchAll();
    foreach($questions as $value){
        ( new Question_Kownledgepoint())->delete($value['kownledgepointid'],$value["questionid"]);
    }    
        $count = (new Question())->delete($id);;//放回成功的行数
    echo  $count;
   
}
//批量删除 
public function dels(){
    $ids=  $_POST["ids"];
    if(count($ids)){
        $question = new Question();
        foreach($ids as $k=>&$value){
        $questions=(new Question_Kownledgepoint())->where(["questionid = ?"],[$value])->fetchAll();
        foreach($questions as $_value){
        ( new Question_Kownledgepoint())->delete($_value['kownledgepointid'],$_value["questionid"]);
    }   
         $result=$question->delete($value);
        // echo $result;
        }

    }
     echo json_encode(["data"=>"df"]);
 }
function showAdd(){
    $questionTypes = (new QuestionType())->fetchAll();
    $this->assign("questionsTypes",$questionTypes);
    $subject =new Subject();
    $Subjects= $subject->fetchAll();//获取所有科目，用于前台显示
    //默认知识点
    $this->assign('Subjects',$Subjects);
    $this->render("add");

}
function showAddByManual($id=''){
    //读取题型
    $questionTypes = (new QuestionType())->fetchAll();
    $this->assign("questionsTypes",$questionTypes);
    
    $this->assign("styleid",$id);
    $this->render("addByManual");
}
//插入数据
function add(){
    //获取数据
    $answer=isset($_POST['edit']["answer"])? $_POST['edit']["answer"]:'';//答案
    $style= isset($_POST['questionType']) ? $_POST['questionType'] : '';//题型
    $title=isset($_POST['edit']['title'])? $_POST['edit']['title']:'';//题目
    $options=isset($_POST['options'])? $_POST['options']:'';//选项数组
    $questionNo = isset($_POST['questionNo'])? $_POST['questionNo']:'';//选项数组
    $analysis =isset($_POST['edit']["analysis"])? $_POST['edit']["analysis"]:'';//解析
    $subject=isset($_POST['subject'])?$_POST['subject']:'';
    $point=isset($_POST['point'])?$_POST['point']:'';
    $level=isset($_POST['level'])?$_POST['level']:'';
    //将选项拼接成json字符串 {"A":"123","B":"3434",...}
    $userid = $_SESSION["userid"];  
    $question = new Question();
  
    $data=['answer'=>$answer,'style'=>$style,"belonger"=>$userid,'analysis'=>$analysis,'title'=>$title,"level"=>$level,'options'=>$options,'subject'=>$subject];
    //$data=['style'=>$style,'title'=>$title,'option'=>$options];
    $count=$question->add($data);
    $questionid= $question->newId();
    $subjectPoint = isset($_POST['subjectPoint'])?$_POST['subjectPoint']:[];
    //设置题目所属知识点，可能有多个
    new Question_Kownledgepoint();
    foreach($subjectPoint as $value){
        //$value ---知识点kownledgepoint
       ( new Question_Kownledgepoint())->add(["questionid"=>$questionid,"kownledgepointid"=>$value]);
    }
   
    echo json_encode(array("status"=>$count));
}
function search(){
    //获取查询条件
    $param=$_POST['keyWord'];
    $level=$_POST['questionLevel'];
    $questions='';
    $questions=(new Question())->join('inner','question','question_type','style','question_type_id')->search1(['title like ? ',' level like ? '],[$param,$level]);
    
    //$this->assign("Questsions",$questions);
    //$this->render('questionList');//渲染模板
    echo json_encode(array("status"=>count($questions),'data'=>$questions));
    //echo json_encode(array("status"=>1,'data'=>$param));

}
function showQuestions(){
    $question=new Question();
    $Questions=$question->join('inner','question','question_type','style','question_type_id')->where()->order()->fetchAll();
    $this->assign("Questsions",$Questions);
    $this->render('questionList');//渲染模板

}


}