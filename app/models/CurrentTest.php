<?php 
namespace app\models;
use fastphp\base\Model;
use fastphp\db\Db;
class CurrentTest extends Model
{
    protected $table = 'currenttest';
    protected $primary = 'currentTestId';
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
    public function delete($userid,$testid='')
    {
        $sql = sprintf("delete from `%s` where `%s` = :%s and `%s` = :%s", $this->table, 'userid', 'userid','testid','testid');
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, ['testid' => $testid,'userid'=> $userid]);
        $sth->execute();

        return $sth->rowCount();
    }
}