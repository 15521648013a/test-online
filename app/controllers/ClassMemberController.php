<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\ClassMember;
use app\models\Message;
use app\models\_Class;
class ClassMemberController extends Controller
{ 
   //add
   function add(){
       $sender = $_POST['data']['sender'];
       $receiver = $_POST['data']['receiver'];
       $classid = $_POST['data']["remark"];
       $messageid =$_POST['data']["messageid"];
       $style =$_POST['data']["style"];
       $time = strtotime(date('Y-m-d H:i:s'))+8*60*60;//获取当前时间戳，并转为int
       //被邀请
       if($style == 3) {
        $result = (new ClassMember) ->add(["classid"=>$classid,"userid"=>$receiver,"addtime"=>$time]);//失败返回
       }else{
           //申请
       $result = (new ClassMember) ->add(["classid"=>$classid,"userid"=>$sender,"addtime"=>$time]);//失败返回
       }
       if($result){
           //将消息状态改为已处理
           (new Message )->where(["messageid = :messageid"],["messageid"=>$messageid])->update(["status"=>1,"deal"=>"已同意"]);
           //班级人数加一
           $num = (new _Class)->where(["classid = :classid"],["classid"=>$classid])->fetch()["number"];//人数
           (new _Class)->where(["classid = :classid"],["classid"=>$classid])->update(["number"=> $num+1]);
       }
       echo json_encode(array("result"=>$result));

       

   } 
   //自由加入
   function  addByFree(){
       //班级编号
       $classid = $_POST['classid'];
       //用户账号
       $userid = $_SESSION['userid'];
       //当前日期
       $time = strtotime(date('Y-m-d H:i:s'))+8*60*60;//获取当前时间戳，并转为int
       $result = (new ClassMember) ->add(["classid"=>$classid,"userid"=>$userid,"addtime"=>$time]);//失败返回
       echo json_encode(array("result"=>$result));
   }
   //拒绝加入班级
   function refuse(){
    $sender = $_POST['data']['sender'];
       $receiver = $_POST['data']['receiver'];
       $classid = $_POST['data']["remark"];
       $messageid =$_POST['data']["messageid"];
       $style =$_POST['data']["style"];
       $time = strtotime(date('Y-m-d H:i:s'))+8*60*60;//获取当前时间戳，并转为int
    
           //将消息状态改为已处理
       $result=(new Message )->where(["messageid = :messageid"],["messageid"=>$messageid])->update(["status"=>1,"deal"=>"已拒绝"]);
          
       
       echo json_encode(array("result"=>$result));
  

   }
   //删除
   function del(){
    $classid= $_POST["classid"];
    $userid= $_POST['userid'];
    $result=(new ClassMember())->delete($userid,$classid);
    echo $result;
   }
  //批量删除 
  public function dels($classid){
    $ids=  $_POST["ids"];
    if(count($ids)){
        $classMember = new ClassMember();
        foreach($ids as $k=>&$value){
         $result=$classMember->delete($value,$classid);
         echo $result;
        }

    }
 }
 //退出班级
   function out(){
    $classid= $_POST["classid"];
    $userid= $_SESSION['userid'];
    $result=(new ClassMember())->delete($userid,$classid);
    echo $classid.$userid;
   }
}