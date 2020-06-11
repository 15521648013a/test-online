<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\User;
use app\models\_Class;
use app\models\ClassMember;
use app\models\ClassTest;
use app\models\Question;
use app\models\Test;
use app\models\QuestionType;
use app\models\TestRecord;
use app\models\Grade;
use app\models\Message;
class ClassController extends Controller
{  
    function list($userid){
        $this->assign("userid",$userid);
        $this->render();
    } 
    function listshow(){
        $currentPage= $_POST['page'];//第几页
        $limit =$_POST['limit'];//获取一页数据数
        $id= isset($_POST['id'])?$_POST['id']:'';
        $userid = $_POST['userid'];
        $start = ($currentPage-1)*$limit;//开始截取数组的位置
        $class = new _Class();
        $ClassMember =  new ClassMember();
        $data= isset($_POST['data'])?$_POST['data']:'';
        //查询所属所有班级
        //$classes = $
        if(!$data){
            //全部
        $Classes = $ClassMember->join("inner","","class","classid","classid")->join("inner","class","user","creator","userid")->where(['classmember.userid = ?'],[$userid])->fetchAll();
          foreach($Classes as &$value){
              $value["exit"]=true;
          }  
        }else
        {  //条件
            parse_str($data,$myArray);
            $keyWord = $myArray['keyWord']; 
            $sCondition = $myArray['condition'];
            $condition = [];$elem=[];
            if($sCondition == 'className')
            {
                $condition[]="classname like ?"; $elem[]="%$keyWord%";
            }else if($sCondition == 'ID'){
               $condition[]="classid = ?";$elem[]=$keyWord;
            }
            $Classes = $class->join("inner","class","user","creator","userid")->where($condition,$elem)->fetchAll();
            //当前用户是否在班级中
            foreach($Classes as &$value){
                $flag = $ClassMember->where(["userid = ?","classid = ?"],[$userid,$value['classid']])->fetch();
                if($flag){
                    $value["exit"]=true;//将id插入结果集中
                    $value['identification']=$flag['identification'];
                }else  
                $value["exit"]=false;
                
            }
            
            //失败返回false;
           
            //$Classes = $ClassMember->join("inner","","class","classid","classid")->search1(["classname like ?"],[$id],["userid = ?"],[$userid]);
        }
        $count = count($Classes);
        $Classes=array_slice($Classes,$start,$limit);//截取
        foreach($Classes as &$value){
              $value["createtime"] = date("Y-m-d H:i:s", $value["createtime"]);
        }
        //$this->assign("Users",$Users);
        //$this->render();
        $data=array("code"=>'0',"msg"=>"","count"=>$count,"data"=>$Classes);
        echo json_encode($data);
        
    }
    public function edit($id){
        //根据i查找详细信息
        $class = new _Class();
        $Class = $class -> where(['classid = ?'],[$id])->fetch();//按班级id 查询
        //提取创建人id
        $creatorId = $Class['creator'];
        $user = new User();
        $creatorName = ($user->where(["userid = ?"],[$creatorId])->fetch())['name'];
        $this->assign("creatorName", $creatorName);
        $this->assign("Class",$Class);
        $this->render();

    }
			
    public function saveEdit(){
        $id=$_POST['id'];
        $name=$_POST['name'];
        $profile=$_POST['profile'];
        $enterway=$_POST['enterway'];
        $class = new _Class();
        $result=$class->where(["classid = :id"],[":id"=>$id])->update(["enterway"=>$enterway,"classid"=>$id,"classname"=>$name,"profile"=>$profile]);

    }
    public function del(){
        $id=$_POST['id'];
        $class = new _Class();
        $users = (new ClassMember)->where(["classid=?"],[$id])->fetchAll();
        foreach($users as $_v){
            $userid = $_v['userid'];
            (new ClassMember)->delete($userid,$id);
        }
        $class->delete($id);
    }
    //批量删除 
    public function dels(){
        $ids=  $_POST["ids"];
        if(count($ids)){
            $class = new _Class();
            foreach($ids as $k=>&$value){
                //查询班级成员，进行删除
                $users = (new ClassMember)->where(["classid=?"],[$value])->fetchAll();
                foreach($users as $_v){
                    $userid = $_v['userid'];
                    (new ClassMember)->delete($userid,$value);
                }
             $result=$class->delete($value);
             echo $result;
            }
 
        }
 
     }
    public function showadd(){
        $this->render();
    }
    public function add(){
        //获取提交信息
        $name= $_POST["name"];
       // $id =$_POST['id'];
         $userid=$_SESSION["userid"];
        $profile =$_POST['profile'];
        $enterway = $_POST['enterway'];
        $data = array("classname"=>$name,"profile"=>$profile,"creator"=>$userid,"createtime"=>strtotime(date('Y-m-d H:i'))+8*60*60,"enterway"=>$enterway);
        $class = new _Class();
        $result = $class->add($data);
        //获取刚创建的班级id
        $classid = $class ->newId();
        //往班级学生列表中插入一条记录
        $result=(new ClassMember)->add(["userid"=>$userid,"classid"=>$classid,"identification"=>"老师","addtime"=>strtotime(date('Y-m-d H:i'))+8*60*60]);
        echo json_encode(["result"=>date("Y-m-d H:i:s")]);

    }
    //邀请学生
    public function showaddmember($classid){
        $this->assign("classid",$classid);
        $this->render();
    }
    function teachermanage($id){
        $this->assign("classid",$id);
        //相关信息
        $classes = (new _Class())->where(["classid = ?"],[$id])->fetch();
        $classes['createtime']= date("Y-m-d H:i:s", $classes['createtime'])? date("Y-m-d H:i:s", $classes['createtime']):'无';
        $classes['number']= count((new ClassMember())->where(['classid = ?'],[$id])->fetchAll());
        $classes['creator']=(new User())->where(["userid=?"],[$classes['creator']])->fetch()['name'];
        $userid = $_SESSION['userid'];
        $identify = (new ClassMember())->where(["userid = ?","classid = ?"],[$userid,$id])->fetch()['identification'];
        $this->assign("identify",$identify);
        $this->assign("Classes",$classes);
        $this->render();
    }
    //班级成员列表
    function members($id){
        //按班级id 查找该班的人
        //$classnumbers = new ClassMember();
        //$ClassMember =  $classnumbers->where(["classid = ?"],[$id])->fetchAll();
        $this->assign("classid",$id);
        //当前登录用户在该班的身份
      
        $userid = $_SESSION['userid'];
        $identify = (new ClassMember())->where(["userid = ?","classid = ?"],[$userid,$id])->fetch()['identification'];
        $this->assign("identify",$identify);
        $this->render();
    }
    //请求
    function requestMembers(){
        //按班级id 查找该班的人
        $id= $_POST['id'];
        $currentPage= $_POST['page'];//第几页
        $limit =$_POST['limit'];//获取一页数据数
        $start = ($currentPage-1)*$limit;//开始截取数组的位置
        $classMember = new ClassMember();
        $data= isset($_POST['data'])?$_POST['data']:'';
        if(!$data){
            $ClassMembers =  $classMember->join("inner","","user","userid","userid")->where(["classid = ?"],[$id])->fetchAll(); 
        }else{
            //条件
            parse_str($data,$myArray);
            $keyWord = $myArray['input']; 
            $sCondition = $myArray['condition'];
            $condition = [];$elem=[];
            if($sCondition == 'userName')
            {
                $condition[]="name like ?"; $elem[]="%$keyWord%";
            }else if($sCondition == 'ID'){
               $condition[]="user.userid = ?";$elem[]=$keyWord;
            }
            $condition[]="classid = ?";$elem[]=$id;
            $ClassMembers =  $classMember->join("inner","","user","userid","userid")->where($condition,$elem)->fetchAll(); 
        
       
       
       }
        
       
        //获取学号；
        //$users= (new User())->fetchAll();
       

    
        $count = count($ClassMembers);
        $Classes=array_slice($ClassMembers,$start,$limit);//截取
        foreach($Classes as &$value){
            if($value['addtime'])
            $value['addtime'] = date("Y-m-d H:i:s",$value['addtime']);
            
        }
        //$this->assign("Users",$Users);
        //$this->render();
        $data=array("code"=>'0',"msg"=>"","count"=>$count,"data"=>$Classes);
        echo json_encode($data);
        //$this->render();
    }
    //考试表
    function showTest($classid){
        $this->assign("classid",$classid);
        //当前登录用户在该班的身份
        $userid = $_SESSION['userid'];
        $identify = (new ClassMember())->where(["userid = ?","classid = ?"],[$userid,$classid])->fetch()['identification'];
        $this->assign("identify",$identify);
        $this->render();
    }
    function requestTests(){
        $id= $_POST['id'];//班级id
        $currentPage= $_POST['page'];//第几页
        $limit =$_POST['limit'];//获取一页数据数
        $start = ($currentPage-1)*$limit;//开始截取数组的位置
        $data= isset($_POST['data'])?$_POST['data']:'';
        if($data){
            parse_str($data,$myArray);
            $keyWord = $myArray['keyWord']; 
            $sCondition = $myArray['condition'];
            //echo  $questionLevel;
            //$questionTypes = $myArray['questionTypes'];
           // $condition = [];$elem=[];
            if($sCondition == 'testName')
            {
                $condition="testName like ?"; $elem="%$keyWord%";
            }else if($sCondition == 'ID'){
               $condition="test_paper.testid = ?";$elem=$keyWord;
            }
            $ClassTests = (new ClassTest)->join("inner","","test_paper","testid","testid")
            ->join("inner","test_paper","subject","subject","subjectid")
            ->where(["classid = ?",$condition],[$id,$elem])->fetchAll();
        }else 
        $ClassTests = (new ClassTest)->join("inner","","test_paper","testid","testid")->join("inner","test_paper","subject","subject","subjectid")->where(["classid = ?"],[$id])->fetchAll();  
        $count = count($ClassTests);
        $ClassTests=array_slice($ClassTests,$start,$limit);//截取
        foreach($ClassTests as &$value){
            if($value['starttime'])
            $value['starttime']=date("Y-m-d H:i:s",$value['starttime']);
            if($value['endtime'])
            $value['endtime']=date("Y-m-d H:i:s",$value['endtime']);
        }
        //$this->assign("Users",$Users);
        //$this->render();
        $data=array("code"=>'0',"msg"=>"","count"=>$count,"data"=>$ClassTests);
        echo json_encode($data);
    }
    //考试界面
    function enterTest($id){
        //按试卷id 查询试题，先查询包含哪些 题型 style
$test =new Test();
$question =new Question();
$userid=$_SESSION["userid"];
//插入一条考试记录
$grade = new Grade();
//$flag = $grade ->where(["testid = :testid","userid = :userid"],[":testid"=>$id,":userid"=>$userid])->fetch();
//
   // $grade->add(["testid"=>$id,"userid"=>$userid]);

$Tests = $test -> where(['testid = ?'],[$id])->fetch();

$testcontent = json_decode($Tests['testcontent'],true);//内容 ,数组
//$Tests = explode(',', $Tests['questionStyle']);
//先获取单选
$Questions1= $question->in(['questionNo'],[$testcontent['1']]);//单选
$Questions2= (new Question) ->in(['questionNo'],[$testcontent['2']]);//多选
$Questions4= (new Question) ->in(['questionNo'],[$testcontent['4']]);//填空
$Questions3= (new Question) ->in(['questionNo'],[$testcontent['3']]);//判断
$this->assign("one",json_encode((object)$Questions1));//转换成js可接收的类型  对象{....}
$this->assign("two",json_encode((object)$Questions2));//转换成js可接收的类型  对象{....}
$this->assign("four",json_encode((object)$Questions4));//转换成js可接收的类型 对象{....}
$this->assign("three",json_encode((object)$Questions3));//转换成js可接收的类型 对象{....}
if($Tests){
  $this->assign("Tests",$testcontent);
  
  $this->assign("Testid",$id);

  //$this->render();
  $this->renderfile(["A","B","C","D"]);
}
    }
    //考试情况
    function putTest(){
        //查询考试包含哪些题型
       $testid= $_POST["testid"];//试卷号 
       $test =new Test();
       $question =new Question();
       $Tests = $test -> where(['testid = ?'],[$testid])->fetch();
       $testcontent = json_decode($Tests['testcontent'],true);//内容 ,数组
       $include=[];//题型数组
       foreach($testcontent as $k=>$value){
       $include[]=$k;}
       //获取对应的题型信息
       //$QuestionTypes = (new QuestionType())->fetch()
       //获取选择题
       $singleselet = isset($_POST['single'])?$_POST['single']:'';
       //多选
       $multipleselect = isset($_POST['multiple'])?$_POST['multiple']:'';
       //判断
       $yn = isset($_POST['yn'])?$_POST['yn']:'';
       //填空
       $fill = isset($_POST['fill'])?$_POST['fill']:'';
       $singlescore=0;//单选题得分
    if($singleselet){
      //有单选题，提取所有单选题的答案进行比较
      $Questions1= $question->in(['questionNo'],[$testcontent['1']]);//单选
      foreach($Questions1 as $k=>$value){
          if(isset($singleselet[$k])){
              //第$k 题是否为空
              if($singleselet[$k]==$value['answer']){
                  $singlescore+=2;
              } 
          }
          else {
             //错了
          }
      }

    }
    $multiplescore=0;
    if($multipleselect){
      //有多选题
      $Questions2= (new Question) ->in(['questionNo'],[$testcontent['2']]);//多选
      foreach($Questions2 as $k=>$value){
        if(isset($multipleselect[$k])){
            //比较与答案的长度
            $length = count($multipleselect[$k]);
            $answerlength= strlen($value['answer']);
            if($length > $answerlength){
                //比答案长，错误
                $multiplescore+=0;
            }
            else if($length == $answerlength) {
                //需要完全匹配
                foreach($multipleselect[$k] as $k=>$value1)
                {if(strstr($value['answer'],$value1))//是否在答案中
                 {
                     $flag=1;continue;
                 }else{
                     $flag=0;break;
                 }
                }//foreach
                 if($flag){
                     //完全匹配
                     $multiplescore+=10;
                 }else{
                    $multiplescore+=0;
                 }
                
            }else{
                //最后是否式部分匹配，成功则有一半的分
                foreach($multipleselect[$k] as $k=>$value1)
                {if(strstr($value['answer'],$value1))//是否在答案中
                 {
                     $flag=1;continue;
                 }else{
                     $flag=0;break;
                 }
                }//foreach
                 if($flag){
                     //部分匹配
                     $multiplescore+=5;//半分
                 }else{
                    $multiplescore+=0;
                 }

            }
        }
      }

    }
    $ynscore =0;
    if($yn){
        //有p判断题，提取所有单选题的答案进行比较
        $Questions3=(new Question)->in(['questionNo'],[$testcontent['3']]);//单选
        foreach($Questions3 as $k=>$value){
            if(isset($yn[$k])){
                //第$k 题是否为空
                if($yn[$k]==$value['answer']){
                    $ynscore+=2;
                } 
            }
            else {
               //错了
               $ynscore+=0;
            }
        }
  
      }
      //计算总分
      $total = $singlescore+ $ynscore+$ynscore;
      //将考试结果插入数据库中
      $testRecord = new TestRecord();
     
      if(isset($_SESSION["userid"])){
          //插入考试记录
          //当前时间

          $result=$testRecord->add(["testid"=>$testid,"userid"=>$_SESSION["userid"],"score"=>$total,"starttime"=>strtotime(date('Y-m-d H:i',time()+8*60*60))]);
       // $result=$testRecord->where(["testid = :testid","userid = :userid"],[":testid"=>$testid,":userid"=> $_SESSION["userid"]])->update(["score"=>$total]);
        echo json_encode(["data1"=>$singlescore,"data2"=>$ynscore,"data3"=>$ynscore]);
      }else{
          echo json_encode(["data"=>"没有登录"]);
      }
      
    }
    //申请加入班级界面
    function application($classid){
        $this->assign("classid",$classid);
        $class = new _Class();
        $Class = $class -> where(['classid = ?'],[$classid])->fetch();//按班级id 查询
        $creatorId = $Class["creator"];
        $creatorName = (new User)->where(["userid = ?"],[$creatorId])->fetch()["name"];
        $this->assign("creator",$creatorName);           
        $this->assign("Class",$Class);
        $this->render();

    }
    //发起申请
    function  appEnterClass(){
        $classid = $_POST["classid"];
        $sender = $_POST["sender"];//申请人，既发送信息者
        $receiver = $_POST["receiver"];//申请人，既发送信息者
        $messageType = $_POST['messageType'];//消息类型
        $content = $_POST['content'];//理由
        $data= ["remark"=>$classid,"sender"=>$sender,"time"=>strtotime(date("Y-m-d H:i:s"))+8*3600,"receiver"=>$receiver,"style"=>$messageType,"content"=>$content];
        $message = new Message();
        $result=$message ->add($data);
        echo json_encode(["data"=>$result]);
    }
    //从试卷库中选择试卷
    function selectTest($classid){
        $this->assign("classid",$classid);
        $this->render();
     
    }
    function selectTestShow(){
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
        $Tests = $test ->join("inner",'',"subject","subject","subjectid")->join("inner",'',"user","designer","userid")->where($condition,$elem)->fetchAll();
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
    

}