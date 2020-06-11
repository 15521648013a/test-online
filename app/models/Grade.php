<?php 
namespace app\models;
use fastphp\base\Model;
use fastphp\db\Db;
class Grade extends Model
{
    protected $table = 'testrecord';
    protected $primary = 'recordid';
    public function search1($like=array(),$param=array()){
        if($like){
             $this->filter .= ' where ';
             $this->filter.= implode(' and ', $like);
             foreach($param as &$value){
              $value="%$value%";

             }
             $this->param = $param;
        }
       return $this->fetchAll();
   }
}