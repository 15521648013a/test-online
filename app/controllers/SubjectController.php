<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\User;
use app\models\Subject;
use app\models\Subject_Kownledgepoint;
use app\models\KownledgePoint;
class SubjectController extends Controller
{   
    function list(){
        
      
        $this->render();
    } 
    function listShow(){
        //获取当前页
        $currentPage= $_POST['page'];
        //获取一页数据数
        $limit =$_POST['limit'];
        $start = ($currentPage-1)*$limit;//开始截取数组的位置
        $data= isset($_POST['data'])?$_POST['data']:'';
        if(!$data){
        $subjects = (new Subject)->fetchAll();
       }else
       {  //条件
           parse_str($data,$myArray);
           $keyWord = $myArray['input']; 
           $sCondition = $myArray['condition'];
           $condition = [];$elem=[];
           if($sCondition == 'subjectName')
           {
               $condition[]="subjectname like ?"; $elem[]="%$keyWord%";
           }else if($sCondition == 'ID'){
              $condition[]="subjectid = ?";$elem[]=$keyWord;
           }
           $subjects = (new Subject)->where($condition,$elem)->fetchAll();
       }

        $subjects=array_slice($subjects,$start,$limit);//截取
       $data=array("code"=>'0',"msg"=>"","count"=>count($subjects),"data"=>$subjects);
       echo json_encode($data);
    }
    function showadd(){
        $this->render();
    }
    //编辑窗
    function showEdit($subjectid){
        $subject = (new Subject)->where(["subjectid = ?"],[$subjectid])->fetch();
        $this->assign("subject",$subject);
        $this->render();
    }
    public function edit(){
        //根据i查找详细信息
        $subjectid= $_POST['id'];
        $subjectname=$_POST['subjectname'];
        echo  (new Subject)->where(["subjectid = :subjectid"],["subjectid"=>$subjectid])->update(["subjectname"=>$subjectname]);
    }
    //添加信息
    public function add(){
        //获取提交信息
        $name= $_POST["name"];
        $subject = new Subject();
        //判断科目名是否已存在
        $flag = $subject->where(["subjectname = ?"],[$name])->fetch();
        if($flag){
            echo json_encode(["status"=>0]);
            return 0;
        }
        $data = array("subjectname"=>$name);
        $result = (new Subject()) ->add($data);
        echo json_encode(["status"=>1]);//获取最新id
        //echo json_encode([])


    }
    
    public function del(){
        $id=$_POST['id'];
        $subject = new Subject();
        //先删除与之相关的知识点
        echo $subject->delete($id);
    }
    //批量删除 
    public function dels(){
       $ids=  $_POST["ids"];
       if(count($ids)){
           $user = new User();
           foreach($ids as $k=>&$value){
            $result=$user->delete($value);
            echo $result;
           }

       }

    }
    public function showSetting($subjectid){
        $this->assign("subjectid",$subjectid);
        $this->render();
    }
    public function setting($subjectid){
        //设置知识点
        //查询
        $currentPage= 1;
        //获取一页数据数
        $limit =10;
        $start = ($currentPage-1)*$limit;//开始截取数组的位置
        $data= isset($_POST['data'])?$_POST['data']:'';
        if(!$data){
      $subject_Kownledgepoint = (new Subject_Kownledgepoint)->join("inner","","kownledgepoint","kownledgepointid","kownledgepointid")->where(["subjectid=?"],[$subjectid])->fetchAll();
        }else
        {      
                parse_str($data,$myArray);
                $keyWord = $myArray['input']; 
                $condition[]="kownledgepointname like ?"; $elem[]="%$keyWord%";
                $condition[]="subjectid =?"; $elem[]=$subjectid;
                $subject_Kownledgepoint = (new Subject_Kownledgepoint)
                ->join("inner","","kownledgepoint","kownledgepointid","kownledgepointid")
                ->where($condition,$elem)->fetchAll();   
          
        }
      $subject_Kownledgepoints=array_slice($subject_Kownledgepoint,$start,$limit);//截取
      $data=array("code"=>'0',"msg"=>"","count"=>count($subject_Kownledgepoints),"data"=>$subject_Kownledgepoints);
        echo json_encode($data);
      //var_dump($subject_Kownledgepoint);
      //$this->render();

    }
    function showAddSubject_Kownledgepoint($subjectid){
        $this->assign("subjectid",$subjectid);
        $this->render();
    }
    function addSubject_Kownledgepoint($subjectid){
        $name= $_POST["name"];
      (new KownledgePoint)->add(["kownledgepointname"=>$name]);
      $id= (new KownledgePoint)->newId();//获取最新id
     (new Subject_Kownledgepoint)->add(["subjectid"=>$subjectid,"kownledgepointid"=>$id]);
    }
    //删除知识点
    function delKnowledgePoint(){
        //先删除关联表的，再删除知识点表
        $kownledgepointid = $_POST['kownledgepointid'];
        $subjectid = $_POST['subjectid'];
        (new Subject_Kownledgepoint)->delete($kownledgepointid, $subjectid);
        //彻底删除知识点
        (new KownledgePoint)->delete($kownledgepointid);
   
    }
    //编辑知识点
    function showEditKnowledgePoint($kownledgepointid=""){
        
      $this->assign("kownledgepoint",(new KownledgePoint)->where(["kownledgepointid=?"],[$kownledgepointid])->fetch());
      $this->assign("kownledgepointid",$kownledgepointid);
      $this->render();
    }
    function editKnowledgePoint($kownledgepointid){
        $name= $_POST["name"];
        $id= $_POST["id"];
        //更新
        (new KownledgePoint)->where(["kownledgepointid= :kownledgepointid"],["kownledgepointid"=>$id])->update(["kownledgepointname"=>$name]);
    }
    //知识点跟随科目选择
    function selectPoint(){
        $select = $_POST["select"];
        $data=(new Subject_Kownledgepoint)->join("inner","","kownledgepoint","kownledgepointid","kownledgepointid")->where(["subjectid = ?"],[$select])->fetchAll();
        echo json_encode(["data"=>$data]);
        //var_dump($data);
    }
    //tian



}