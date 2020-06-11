<?php 
namespace app\models;
use fastphp\base\Model;
use fastphp\db\Db;
class ClassMember extends Model
{
    protected $table = 'classmember';
    //protected $primary = 'classid';
    public function search1($like=array(),$param=array(),$others=array(),$otherParam=array()){
        if($like){
             $this->filter .= ' where ';
             $this->filter.= implode(' and ', $like);
             foreach($param as &$value){
              $value="%$value%";

             }
             $this->param = $param;
             //echo $this->param[0];
        }
        if(count($others)){
            //额外条件
            $this->filter.=" and ";
            $this->filter.= implode(' and ', $others);
            //$count = count()
            //$new = $this->param;  //echo $new[0];
            //$this->param =[];
            foreach($otherParam as $k=>&$value){
                //echo $k;
                //$f =$k+1;
                $this->param[]=$value;//坑；
                 
              
            }
            ///$this->param = $param;
          //  echo  $this->param[0]; echo  $this->param[1]; echo  $this->param[2]; echo  $this->param[3]; 
        }else {
            return false;
        }
       return $this->fetchAll();
   }
    //重写删除方法
    public function delete($userid,$classid='')
    {
        $sql = sprintf("delete from `%s` where `%s` = :%s and `%s` = :%s", $this->table, 'userid', 'userid','classid','classid');
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, ['classid' => $classid,'userid'=> $userid]);
        $sth->execute();

        return $sth->rowCount();
    }
}