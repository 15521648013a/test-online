<?php 
namespace app\models;
use fastphp\base\Model;
use fastphp\db\Db;
class User extends Model{
    protected $table = 'user';
    protected $primary = 'userid';

    public function search1($like=array(),$param=array(),$condition=''){
        if($like){
             $this->filter .= ' where ';
             if($condition){
                $this->filter.= implode(' or ', $like);
             }else
             $this->filter.= implode(' and ', $like);
             foreach($param as &$value){
              $value="%$value%";

             }
             $this->param = $param;
        }
       return $this->fetchAll();
   }
    ///array(['id in','name in '])
    public function in($in =array(),$param=array()){
        if($in){
            $this->filter .= ' where ';$count=0;
            foreach($in as $k=>$v){
                if($param[$k]){
                    $count=count($param[$k]);//在多少个数中查询
                    $this->filter.= $v.' in ( ';
                    while($count--)
                    $this->filter.= '?,';//count 个 ？
                    $this->filter=rtrim($this->filter,',');//删除最后一个，
                    $this->filter.=') and ';
                }
            }
            $this->filter=rtrim($this->filter,'and ');//删除最后一个and


        }
        $paramData=[];//绑定数据，对应占位符？
        foreach($param as $k=>$v){
            if(is_array($v)){
                foreach($v as $value){
                    $paramData[]=$value;
                }
            }
             

        }
        $sql = "select * from `$this->table`".$this->filter;
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, $paramData);
        $sth->execute();
        $this->filter='';
        return $sth->fetchAll();


    }

}