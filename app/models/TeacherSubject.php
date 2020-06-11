<?php 
namespace app\models;
use fastphp\base\Model;
use fastphp\db\Db;
class TeacherSubject extends Model
{
/**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 item 表
     * @var string
     */
    protected $table = 'teachersubject';
    //protected $primary = 'teachersubjectid';
    /**
     * 搜索功能，因为Sql父类里面没有现成的like搜索，
     * 所以需要自己写SQL语句，对数据库的操作应该都放
     * 在Model里面，然后提供给Controller直接调用
     * @param $title string 查询的关键词
     * @return array 返回的数据
     */
     //重写删除方法
     public function delete($teacherid,$subjectid='')
     {
         $sql = sprintf("delete from `%s` where `%s` = :%s and `%s` = :%s", $this->table, 'teacherid', 'teacherid','subjectid','subjectid');
         $sth = Db::pdo()->prepare($sql);
         $sth = $this->formatParam($sth, ['subjectid' => $subjectid,'teacherid'=> $teacherid]);
         $sth->execute();
 
         return $sth->rowCount();
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

        return $sth->fetchAll();


    }

    
}