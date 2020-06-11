<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\Message;
use app\models\User;
class MessageController extends Controller{
    //消息列
    function listReciver(){
        $this->render();

    }
    function listSender(){
        $this->render();

    }
    //待办事宜列
    function readyList(){
        $this->render();
    }
    //详情
    function detail($messageid){
        $message = new Message();
        $Messages = $message->where(["messageid = :messageid"],[":messageid"=>$messageid])->fetchAll();
       
        $user = new User();
            
            
            foreach($Messages as &$value){
                //根据id查询姓名
                $id= $value['sender'];
                $result = $user->where(["userid = ? "],[$id])->fetch();
                
                if($result){
                    $senderName = $result["name"];
                    $value["senderName"]=$senderName;
                }else{
                    $value["senderName"]='null';
                }
                $id=$value["receiver"];
                $result = $user->where(["userid = ? "],[$id])->fetch();
                if($result){
                   // $senderName = $result["name"];
                    $value["receiverName"]=$result["name"];
                }else{
                    $value["receiverName"]='null';
                }
            }
            //搜索类型：
            $style= $Messages[0]["style"];
            if($style ==1){
                //加入申请
                $detail="用户名为： ".$Messages[0]['senderName']." 申请加入班级编号为： ".$Messages[0]['remark']." 的班级。";
            }else if($style == 2){
                //考试通知
                $remark = json_decode($Messages[0]['remark']);
                //有无考试时间
                if(!$remark[2]) $remark[2]="无"; else $remark[2]=date("Y-m-d H:i:s", $remark[2]);
                if(!$remark[3]) $remark[3]='无'; else $remark[3]=date("Y-m-d H:i:s", $remark[3]);
                $detail="班级编为： ".$remark[1].",试卷编号编号为: ".$remark[0]." 的考试。<br/>考试时间为:{$remark[2]}~".$remark[3];   
            }else if($style == 3){
                //邀请加入
                $detail="用户名为:".$Messages[0]['senderName']." 邀请你加入班级编号为: ".$Messages[0]['remark']." 的班级。";
            }
            $this->assign("detail",$detail);
            $this->assign("message",$Messages[0]);
            
        $this->render();
    }
    //插入一条消息
    function push(){
        //收件人
        $receiver = $_POST['receiver'];
        $sender = $_POST['sender'];
        $messageType = $_POST['messageType'];
        $content = $_POST['content'];
        $remark = $_POST['classid'];
        $message = new Message();
        ///status = > 0 ;表示消息未读
        $result = $message->add(["receiver"=>$receiver,"time"=>strtotime(date("Y-m-d H:i:s")),"sender"=>$sender,"remark"=>$remark,"style"=>$messageType,"content"=>$content,"status"=>0]);
    }
    //接收箱
    function requestList(){
         //获取当前页
         $currentPage= $_POST['page'];
         //获取一页数据数
         $limit =$_POST['limit']; 
         $userid= $_SESSION["userid"];
         $start = ($currentPage-1)*$limit;//开始截取数组的位置
         $message = new Message();
         $data= isset($_POST['data'])?$_POST['data']:'';
         if(!$data){
            $Messages = $message ->where(["receiver=?","receiverdel=?"],[$userid,0])->fetchAll();
         }else{
            //条件
           parse_str($data,$myArray);
           $keyWord = $myArray['input'];
           $condition[]="content like ?"; $elem[]="%$keyWord%";
           $condition[]="receiver=?"; $elem[]=$userid;
           $condition[]="receiverdel=?"; $elem[]=0;
           $Messages = $message ->where($condition, $elem)->fetchAll();
        
        }
            if($Messages){
            $usercount=count($Messages);
            //添加发送人名和接收人
            $user = new User();
            foreach($Messages as &$value){
                //根据id查询姓名
                $id= $value['sender'];
                $result = $user->where(["userid = ? "],[$id])->fetch();              
                if($result){
                    $senderName = $result["name"];
                    $value["senderName"]=$senderName;
                }else{
                    $value["senderName"]='null';
                }
                $id=$value["receiver"];
                $result = $user->where(["userid = ? "],[$id])->fetch();
                if($result){
                   // $senderName = $result["name"];
                    $value["receiverName"]=$result["name"];
                }else{
                    $value["receiverName"]='null';
                }
                $value['time']=date("Y-m-d ",$value['time']);
            }
        }
      else $usercount=0;
    
        $Messages=array_slice($Messages,$start,$limit);//截取
        $data=array("code"=>'0',"msg"=>"","count"=>$usercount,"data"=>$Messages);
        echo json_encode($data);

    }
    //发件
    
    function requestListSender(){
        //获取当前页
        $currentPage= $_POST['page'];
        //获取一页数据数
        $limit =$_POST['limit'];
        $userid= $_SESSION["userid"];
        $start = ($currentPage-1)*$limit;//开始截取数组的位置
        $message = new Message();
        $data= isset($_POST['data'])?$_POST['data']:'';
        if(!$data){
            $Messages = $message ->where(["sender=?","senderdel = ?"],[$userid,0])->fetchAll();
         }else
           {
            parse_str($data,$myArray);
            $keyWord = $myArray['input'];
            $condition[]="content like ?"; $elem[]="%$keyWord%"; 
            $condition[]="sender=?"; $elem[]=$userid;
            $condition[]="senderdel=?"; $elem[]=0;
            $Messages = $message ->where($condition, $elem)->fetchAll();
        }

           if($Messages){
           $usercount=count($Messages);
           //添加发送人名和接收人
           $user = new User();
           
           
           foreach($Messages as &$value){
               //根据id查询姓名
               $id= $value['sender'];
               $result = $user->where(["userid = ? "],[$id])->fetch();
               
               if($result){
                   $senderName = $result["name"];
                   $value["senderName"]=$senderName;
               }else{
                   $value["senderName"]='null';
               }
               $id=$value["receiver"];
               $result = $user->where(["userid = ? "],[$id])->fetch();
               if($result){
                  // $senderName = $result["name"];
                   $value["receiverName"]=$result["name"];
               }else{
                   $value["receiverName"]='null';
               }
           }
       }
           else $usercount=0;
 
    
     $Messages=array_slice($Messages,$start,$limit);//截取
       //$this->assign("Users",$Users);
       //$this->render();
       $data=array("code"=>'0',"msg"=>"","count"=>$usercount,"data"=>$Messages);
       echo json_encode($data);

   }
    //未读数量
    function _readyList(){
        $userid= $_SESSION["userid"];
        $Messages = ( new Message()) ->where(["status = ?","receiver=?","receiverdel=?"],[0,$userid,0])->fetchAll();
        $data=array("count"=>count($Messages));
        echo json_encode($data);
    }
     //请求待办事宜
    function requestReadyList(){
        $status = $_POST['status'];
        //获取当前页
        $currentPage= $_POST['page'];
      
        $userid= $_SESSION["userid"];
        //获取一页数据数
        $limit =$_POST['limit'];
        $start = ($currentPage-1)*$limit;//开始截取数组的位置
        $message = new Message();
        $data= isset($_POST['data'])?$_POST['data']:'';
        if(!$data){
            $Messages = $message ->where(["status = ?","receiver=?","receiverdel=?"],[$status,  $userid,0])->fetchAll();
        }else{
            //条件
           parse_str($data,$myArray);
           $keyWord = $myArray['input'];
           $condition[]="content like ?"; $elem[]="%$keyWord%";
           $condition[]="status = ?"; $elem[]=$status;
           $condition[]="receiver=?"; $elem[]=$userid;
           $condition[]="receiverdel=?"; $elem[]=0;
            $Messages = $message ->where($condition, $elem)->fetchAll();
        }   
            if($Messages){
           $usercount=count($Messages);
           //添加发送人名和接收人
           $user = new User();
           foreach($Messages as &$value){
               //根据id查询姓名
               $id= $value['sender'];
               $result = $user->where(["userid = ? "],[$id])->fetch();
               
               if($result){
                   $senderName = $result["name"];
                   $value["senderName"]=$senderName;
               }else{
                   $value["senderName"]='null';
               }
               $id=$value["receiver"];
               $result = $user->where(["userid = ? "],[$id])->fetch();
               if($result){
                  // $senderName = $result["name"];
                   $value["receiverName"]=$result["name"];
               }else{
                   $value["receiverName"]='null';
               }
           }
       }
      
     $Messages=array_slice($Messages,$start,$limit);//截取
       $data=array("code"=>'0',"msg"=>"","count"=>count($Messages),"data"=>$Messages);
       echo json_encode($data);

    }
    function showdetail($messageid){
        $message = new Message();
        $Messages = $message ->where(["messageid = ? "],[$messageid])->fetch(); 
        //是否查询成功，失败返回 false,成功返回结果集
        if($Messages){
            $user = new User();
            
            
                         //根据id查询姓名
                $id= $Messages['sender'];
                $result = $user->where(["userid = ? "],[$id])->fetch();
                
                if($result){
                    $senderName = $result["name"];
                    $Messages["senderName"]=$senderName;
                }else{
                    $Messages["senderName"]='null';
                }
                $id=$Messages["receiver"];
                $result = $user->where(["userid = ? "],[$id])->fetch();
                if($result){
                   // $senderName = $result["name"];
                    $Messages["receiverName"]=$result["name"];
                }else{
                    $Messages["receiverName"]='null';
                }
            
             $this->assign("Messages",$Messages);
             $this->render();
        }
        
    }
    //删除
    public function del(){
        $messageid=  $_POST["id"];
        $message = new Message();  
        //避免冗余，真删除
        $Messages = $message ->where(["messageid = ? "],[$messageid])->fetch(); 
        if($Messages['senderdel']== 1){
            echo  $message->delete($messageid);
        }
        else echo $message->where(["messageid = :messageid"],["messageid"=>$messageid])->update(["receiverdel"=>1]);
     }
     //删除
    public function senderdel(){
        $messageid=  $_POST["id"];
        $message = new Message();  
        //避免冗余，真删除
        $Messagess = $message ->where(["messageid = ? "],[$messageid])->fetch(); 
        if($Messages['receiverdel']== 1){
            echo  $message->delete($messageid);
        }
        else echo $message->where(["messageid = :messageid"],["messageid"=>$messageid])->update(["senderdel"=>1]);
     }
    //接收者批量删除 
    public function receiverDels(){
        $ids=  $_POST["ids"];
        if(count($ids)){
            foreach($ids as $k=>&$value){
                $Messages = (new Message()) ->where(["messageid = ? "],[$value])->fetch(); 
                   if($Messages['senderdel']== 1){
                       echo (new Message())->delete($value);
                   }else
                echo (new Message())->where(["messageid = :messageid"],["messageid"=>$value])->update(["receiverdel"=>1]);
            }
        }
     }
     //发送者批量删除
     public function senderDels(){
        $ids=  $_POST["ids"];
        if(count($ids)){
            foreach($ids as $k=>&$value){
                $Messages = (new Message()) ->where(["messageid = ? "],[$value])->fetch(); 
                   if($Messages['receiverdel']== 1){
                       echo (new Message())->delete($value);
                   }else
                echo  (new Message())->where(["messageid = :messageid"],["messageid"=>$value])->update(["senderdel"=>1]);
             ///$result=$message->delete($value);
            }
        }
     }
     //将消息设置为已读
     public function dealed(){
         $messageid = $_POST['data']['messageid'];
         $result = (new Message)->where(["messageid = :messageid"],["messageid"=>$messageid])->update(["status"=>1,"deal"=>"已处理"]);
         echo json_encode(["result"=>$result]);
     }
     //撤销申请
     function cancel(){
         $id= $_POST['id'];
         //彻底删除
         $result = (new Message)->delete($id);
     }
    
}