<?php 
namespace app\models;
use fastphp\base\Model;
use fastphp\db\Db;
class ClassTest extends Model
{
    protected $table = 'classtest';
    protected $primary1 = 'classid';
    protected $primary2 = 'testid';
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
   //重写删除方法
   public function delete($testid,$classid='')
    {
        $sql = sprintf("delete from `%s` where `%s` = :%s and `%s` = :%s", $this->table, $this->primary1, $this->primary1,$this->primary2, $this->primary2);
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [$this->primary2 => $testid,$this->primary1=> $classid]);
        $sth->execute();

        return $sth->rowCount();
    }
}