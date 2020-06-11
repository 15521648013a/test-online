<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\Grade;
use app\models\ClassMember;
class GradeController extends Controller{
    function list($request){
    // 渲染成绩页面
    $this->assign("testid",$request[0]); 
    $this->assign("classid",$request[1]); 
   // echo $request[0];
    $userid = $_SESSION['userid'];
    $identify = (new ClassMember())->where(["userid = ?","classid = ?"],[$userid,$request[1]])->fetch()['identification'];
    $this->assign("identify",$identify);
    $this->render();
    }
    function requestLists($userid=''){
        $id = isset($_POST['id'])?$_POST['id']:'';
        if($id){
            $userid=$id; 
        }
        if($userid){
            $currentPage= $_POST['page'];//第几页
            $limit =$_POST['limit'];//获取一页数据数
          //  $testid= isset($_POST['testid'])?$_POST['testid']:'';
            $start = ($currentPage-1)*$limit;//开始截取数组的位置
            $grade = new Grade();
            $data= isset($_POST['data'])?$_POST['data']:'';
            if(!$data){
                //echo "dsf";
                $classid = isset($_POST['classid'])? $_POST['classid']:''; 
                if($classid ){

                }
                $testid = isset($_POST['testid'])? $_POST['testid']:''; 
                if($classid ){
                    $condition[]="testrecord.testid = ?";$elem[]=$testid;
                }
                $condition[]="testrecord.userid = ?";$elem[]=$userid;
               
                  $condition[]="isscan=?";$elem[]=1;
                  $Grades = $grade->join("inner",'',"user","userid","userid")
                  ->join("inner",'testrecord',"test_paper","testid","testid")
                  ->join("inner",'testrecord',"classtest","testid","testid")
                  ->where($condition,$elem)->fetchAll();//获取所有
                  //数据库中有两个时间字段式样的
                  foreach($Grades as &$_value){
                      $recordid = $_value['recordid'];
                      $time = (new Grade)->where(["recordid = ?"],[$recordid])->fetch()['starttime'];
                      $_value['starttime']=$time;
                  }
            }else{
                parse_str($data,$myArray);
                $keyWord = $myArray['keyWord']; 
                $sCondition = $myArray['condition'];
                //echo  $questionLevel;
                //$questionTypes = $myArray['questionTypes'];
               // $condition = [];$elem=[];
                if($sCondition == 'testName')
                {
                    $condition[]="testName like ?"; $elem[]="%$keyWord%";
                }else if($sCondition == 'ID'){
                   $condition[]="test_paper.testid = ?";$elem[]=$keyWord;
                }
                $condition[]="testrecord.userid = ?";$elem[]=$userid;
                $condition[]="isscan=?";$elem[]=1;
                $Grades = $grade->join("inner",'',"user","userid","userid")
                ->join("inner",'testrecord',"test_paper","testid","testid")
                ->join("inner",'test_paper',"classtest","testid","testid")
                ->where($condition,$elem)->fetchAll();//获取所有
            }
               
                $count = count($Grades);
                $Grades=array_slice($Grades,$start,$limit);//截取
                //转换时间
                foreach($Grades as &$value){
                    $value['starttime']=date("Y-m-d H:i",  $value['starttime']);
                }
                $data=array("code"=>'0',"msg"=>"","count"=>$count,"data"=>$Grades);
                echo json_encode($data);
        }else{
        $currentPage= $_POST['page'];//第几页
        $limit =$_POST['limit'];//获取一页数据数
        $testid= isset($_POST['testid'])?$_POST['testid']:'';
        $start = ($currentPage-1)*$limit;//开始截取数组的位置
        $grade = new Grade();
       
            $Grades = $grade->join("inner",'',"user","userid","userid")
            ->join("inner",'testrecord',"test_paper","testid","testid")
            ->where(["testrecord.testid = ?"],[$testid])->fetchAll();//获取所有
            $count = count($Grades);
            $Grades=array_slice($Grades,$start,$limit);//截取
            //$this->assign("Users",$Users);
            //$this->render();
            //转换时间
            foreach($Grades as &$value){
                $value['starttime']=date("Y-m-d H:i",  $value['starttime']);
            }
            $data=array("code"=>'0',"msg"=>"","count"=>$count,"data"=>$Grades);
            echo json_encode($data);
        }
    }
    //单个删除
    function del(){
        $id=  $_POST["id"];
        if($id){
            $grade = new Grade();     
             $grade->delete($id);
        } 
    }
   //批量删除
    function dels(){
        $ids=  $_POST["ids"];
        if(count($ids)){
            $grade = new Grade();
            foreach($ids as $k=>$value){
             $grade->delete($value);
            }
        }
    }
    //请求用户的所有考试记录
    function allList($userid){
    // 渲染成绩页面
    $this->assign("userid",$userid); 
    $this->render();
    }

}