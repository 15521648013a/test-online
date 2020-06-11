<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\User;
use app\models\Role;
use app\models\Subject;
use app\models\TeacherSubject;
use PHPExcel\Classes\PHPExcel\PHPExcel_IOFactory;
use PHPExcel\Classes\PHPExcel;
class UserController extends Controller
{   
    function login(){
        
        
        $this->render();
    } 
    //判断是否被下线
    function isOutLogin($username){
        return $results=(new User())->where(['name = :name'],["name"=>$username])->fetch()['login_time'];//成功返回数据，失败返回false
    }
    function checkLogin(){
        $name= $_POST['name'];
        $password = $_POST['password'];
        $user = new User();
        //先查询 名字，再匹配密码
        $results=$user->where(['name = ?'],[$name])->fetch();//成功返回数据，失败返回false
        //var_dump($results)
        if($results){
        //匹配密码
        $existName=1;//用户账号存在
        if($results['password']==md5($password)){
            $status= 1;//成功
           
            $_SESSION["username"]= $results['name'];//用户民
            $_SESSION["role"]=$results['role'];
            $_SESSION["userid"]=$results['userid']; //echo $_SESSION["userid"];
            $_SESSION["login"]=(date("Ymd",time()).rand(1,50));
            $_SESSION["rolename"]=(new Role)->where(["roleid=?"],[$results['role']])->fetch()['rolename'];
            $results=(new User())->where(['name = :name'],["name"=>$name])->update(['login_time'=>$_SESSION["login"]]);//成功返回数据，失败返回false
            //echo $_SESSION["userid"];
        }else{
         $status=0;//密码错误
        }
        }else{
            $status=0;$existName=0;
        }
        echo json_encode(["status"=>$status,"existName"=>$existName]);
    }
    function show(){
         $this->render('list');
     }
     //注册
     function register(){

       $this->render();
     }
     function addRegister(){
        $name      =$_POST["name"];
        $password =$_POST["password"];
        $email=$_POST["email"];
        $sex=$_POST["sex"];
        //查询用户名和邮箱是否已被使用
        $user =new User();
        $username = $user ->where(["name = ?"],[$name])->fetch();//成功返回1，失败返回 false
        if($username){
            echo json_encode(["msg"=>"用户民已被使用"]);return;
        }
        $_email = $user ->where(["email = ?"],[$email])->fetch();//成功返回1，失败返回 false
        if($_email){
            echo json_encode(["msg"=>"邮箱已被使用"]);return;
        }
        $data = array("name"=>$name,"email"=>$email,"sex"=>$sex,"role"=>3,"password"=>$password);
        $result = (new User)->add($data);
        if($result){
        echo json_encode(["msg"=>"注册成功!"]);return;
        }
        else{
            echo json_encode(["msg"=>"注册失败!"]);return;
        }
     }
    function list(){
        //获取当前页
        $currentPage= isset($_POST['page'])?$_POST['page']:'';
        //if( !$currentPage) {echo "未登录"."<script>window.location.href='/test-online/index.php/User/login'</script>";return ;}
        //获取一页数据数
        $limit =$_POST['limit'];
        $start = ($currentPage-1)*$limit;//开始截取数组的位置
        $user =new User();
        $role = new Role();
        $data= isset($_POST['data'])?$_POST['data']:'';
        if(!$data){
        $Users = $user -> fetchAll();   
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
            $condition[]="userid = ?";$elem[]=$keyWord;
         }

        $Users = $user ->where($condition,$elem)->fetchAll();
    
    
    }
        $Role = $role->fetchAll();
         //处理分组名 ， 比如 1->管理员 ，2->教师
         $roles =array();
        foreach($Role as $k=>$value)
        {
            $roles[$value['roleid']]=$value['rolename'];
        }
        $n='';$rolename='';
        foreach($Users as &$value){
         //获取role
         $n= $value['role'];//比如 取得对应分组的id
         if($n)
         $rolename=$roles[$n];// 更改为 分组名
         else $rolename="学生";//默认学生
         $value['role']=$rolename;
        }
        $usercount=count($Users);
       
         $Users=array_slice($Users,$start,$limit);//截取
        //$this->assign("Users",$Users);
        //$this->render();
        $data=array("code"=>'0',"msg"=>"","count"=>$usercount,"data"=>$Users);
        echo json_encode($data);


    }
    //添加信息的界面
    function showadd(){
        $role = new Role();
        $Role = $role->fetchAll();
        $this->assign("Role",$Role);
        $this->render();
    }
    //添加信息
    public function add(){
        //获取提交信息
        $name= $_POST["name"];
        $_name= $_POST["_name"];
        $email =$_POST['email'];
        $sex =$_POST['sex'];
        //$status = $_POST['status'];
        $role = $_POST['role'];
        $user = new User();
        $data = array("name"=>$name,"username"=>$_name,"email"=>$email,"sex"=>$sex,"status"=>1,"role"=>$role);
        $result = $user ->add($data);
        echo $user->newId();//获取最新id
        //echo json_encode([])


    }
    public function edit($id){
        //根据i查找详细信息
        $user = new User();
        $User = $user -> where(['userid = ?'],[$id])->fetch();
        $role = new Role();
        $Role = $role->fetchAll();
        $roles =array();
        //处理分组名 ， 比如 $roles['1'->管理员 ，'2'->教师,..]
        foreach($Role as $k=>$value)
        {
            $roles[$value['roleid']]=$value['rolename'];
        }
        $Subject = (new Subject)->fetchAll();
        $this->assign("Subject",$Subject);
        $this->assign("Role",$roles);
        $TeacherSubject=[];
        //当前编辑的用户是不是老师
        if($User["role"]!=3){
            //查询管理了哪些科目
            $TeacherSubject=   (new TeacherSubject)->where(["teacherid = ?"],[$id])->fetchAll();
           
        }
        $this->assign("TeacherSubject",$TeacherSubject);
        $this->assign("User",$User);
        $this->render();



    }
    //给老师设置管理科目
    public function editSubject(){
        $subjects = isset($_POST['subject'])?$_POST['subject']:'';
        $teacherid = isset($_POST['id'])?$_POST['id']:'';
        
            //删除所有，再添加
            $TeacherSubject=   (new TeacherSubject)->where(["teacherid = ?"],[$teacherid])->fetchAll();
            foreach($TeacherSubject as  $value){
                echo  $subjectid = $value['subjectid'];
                 (new TeacherSubject)->delete($teacherid,$subjectid);
            }
            if($subjects){
            foreach($subjects as $value){
                (new TeacherSubject)->add(["teacherid"=>$teacherid,"subjectid"=>$value]);
            }
            }
    }
    public function saveEdit(){
        $id=$_POST['id'];
        $name= $_POST["name"];
        $email =$_POST['email'];
        $sex =$_POST['sex'];
        $status = isset($_POST['status'])?$_POST['status']:'';
        $role =isset( $_POST['role'])?$_POST['role']:'';
        $user = new User();
        $_email = $user ->where(["email = ?"],[$email])->fetch();//成功返回1，失败返回 false
        if($_email){
            echo json_encode(["msg"=>"邮箱已被使用"]);return;
        }
        if($role){
        $data = array("name"=>$name,"email"=>$email,"sex"=>$sex,"status"=>$status,"role"=>$role);
        }else{
            $data = array("name"=>$name,"email"=>$email,"sex"=>$sex);
        }
        $result = $user ->where(['userid = :userid '],[':userid'=>$id])->update($data);
        echo json_encode(['msg'=>"修改成功！"]);


    }
   
    function saveUserEdit(){
        $id=$_POST['id'];
        $name= $_POST["name"];
        $email =$_POST['email'];
        $sex =$_POST['sex'];
    
        $user = new User();
        $_email = $user ->where(["email = ?"],[$email])->fetch();//成功返回1，失败返回 false
        if($_email){
            echo json_encode(["msg"=>"邮箱已被使用"]);return;
        }
        $data = array("username"=>$name,"email"=>$email,"sex"=>$sex);
        $result = $user ->where(['userid = :userid '],[':userid'=>$id])->update($data);
        echo json_encode(['data'=>$result]);
    }
    public function del(){
        $id=$_POST['id'];
        $user = new User();
        $user->delete($id);
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
    //搜索用户
    public function seacherUser(){
      $condition = $_POST['inputUser'];
      $result = (new User)->search1(["name like ?"],[$condition]);
      $count =count($result);
      echo json_encode(['data'=>$result,'count'=> $count]);
        
    }
    public function seacherUserById(){
        $condition = $_POST['inputUser'];
        $result = (new User)->where(["name = ?"],[$condition])->fetch();
       // $count =count($result);
        echo json_encode(["data"=>$result,"count"=> 2]);
          
      }
    //修改密码
    public function editPassword(){
        $password1 = $_POST['password'];
        $password2= md5($_POST['password2']);
        $id=$_POST['id'];
        $user = new User();
        
        $data = array("password"=>$password2);
        $result = $user ->where(['userid = :userid '],[':userid'=>$id])->update($data);
        echo json_encode(['data'=>$result]);

      

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
    unset($exfn[0]);//删除第一行文字；
    $msg='';
    foreach($exfn as $k=>$value)//value---excel的一行
    {
        if($value[2]=="学生") $value[2]=3;
    $user = ["name"=>$value[0],"password"=>$value[1],"role"=>$value[2],"sex"=>$value[3]];
     $result=(new User)->add($user);
     if(!$result){
         $msg.=($value[0].",");
     }
  }  
  if($msg){
      $msg.="已存在，添加失败。";
  }  else{
    $msg.="添加成功！";
  }
 
   
         echo json_encode((object) ["data"=>$msg]);
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



}