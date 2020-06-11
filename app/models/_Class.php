<?php 
namespace app\models;
use fastphp\base\Model;
use fastphp\db\Db;
class _Class extends Model
{
    protected $table = 'class';
    protected $primary = 'classid';
    public function search1($like=array(),$param=array(),$others=array(),$otherParam=array()){
        if($like){
             $this->filter .= ' where ';
             $this->filter.= implode(' and ', $like);
             foreach($param as &$value){
              $value="%$value%";

             }
             $this->param = $param;
        }
        if(count($others)){
            //额外条件
            $this->filter.=" and ";
            $this->filter.= implode(' and ', $others);
            foreach($otherParam as $value){
                $param[]=$value;
            }
            $this->param = $param;
        }else {
            return false;
        }
       return $this->fetchAll();
   }
}