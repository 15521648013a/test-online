<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\ClassMember;
use app\models\Message;
use app\models\_Class;
use app\models\ClassTest;
class ClassTestController extends Controller
{ 
//删除
public function del(){
    $classid= $_POST["classid"];
    $testid= $_POST['testid'];
    $classTest =new ClassTest();
    $result=$classTest->delete($testid,$classid);
}  
//批量删除 
public function dels(){
    $ids=  $_POST["ids"];
    $classid = $_POST['classid'];
    if(count($ids)){
        $classTest =new ClassTest();
        foreach($ids as $k=>&$value){ ///value 试卷id
         $result=$classTest->delete($value,$classid);
         echo $result;
        }

    }

 }
 //设置班级考试状态
 function setting($testid=[]){
   $this->assign("classid",$testid[0]);
   $this->assign("testid",$testid[1]);
   $classid = $testid[0];
   $testid = $testid[1];
   $classTest =new ClassTest();
   $result = $classTest ->where(["classid = :classid","testid= :testid"],["classid"=>$classid,"testid"=>$testid])->fetch();
    //var_dump($testid);
   $result['starttime']=  $result['starttime']?  date("Y-m-d H:i:s",$result['starttime']):'';
   $result['endtime']=  $result['endtime']?  date("Y-m-d H:i:s",$result['endtime']):'';
   $this->assign("testMessage",$result);
   $this->render();
 }
 function saveSetting(){
     $select = $_POST['select'];
     $scan = $_POST['scan'];
     $testid = $_POST['testid'];
     $classid = $_POST['classid'];

     $starttime = $_POST['starttime'];
     $endtime = $_POST['endtime'];
     if($starttime)
     $starttime = strtotime($starttime);
     
     else $starttime= NULL;

     if($endtime)
     $endtime = strtotime($endtime);
     else $endtime= NULL;
     $remark=[];
     $remark[]=$testid ;
     $remark[]=$classid ;
     $remark[]=$starttime;
     $remark[]=$endtime;
    // echo $time;
    
     $classTest =new ClassTest();
     $result = $classTest ->where(["classid = :classid","testid= :testid"],["classid"=>$classid,"testid"=>$testid])->update(["status"=>$select,"starttime"=>$starttime,"endtime"=>$endtime,"isscan"=>$scan]);
     //向班级所有学生发布考试通知
     if($select ==1){
     $classMember = new ClassMember();
     $ClassMembers =  $classMember->where(["classid = ?"],[$classid])->fetchAll();
     foreach($ClassMembers as $value){
         if($value["identification"]=="学生"){
         $userid = $value["userid"];
         $result = (new Message)->add(["sender"=>$_SESSION['userid'],"time"=>strtotime(date("Y-m-d H:i:s"))+8*60*60,"receiver"=>$userid,"remark"=>json_encode($remark),"style"=>2,"content"=>"考试通知","status"=>0]);
     }
    }
    }
 }
 //往班级添加试卷
 function adds($classid){
     $ids=isset($_POST["ids"])?$_POST["ids"]:'';
     if($ids){
        foreach($ids as $k=>&$value){
            //查询班级成员，进行删除
           $result= (new ClassTest)->add(["classid"=>$classid,"testid"=>$value]);
           if(!$result){
               echo json_encode(["msg"=>"编号为$value 的试卷已在列表中","status"=>0]);return 0;
           }
        }
        echo json_encode(["msg"=>"添加成功","status"=>1]);
     }
 }
}