<?php 
namespace app\models;
use fastphp\base\Model;
use fastphp\db\Db;
class Subject_Kownledgepoint extends Model
{
/**
     * 自定义当前模型操作的数据库表名称，
     * 如果不指定，默认为类名称的小写字符串，
     * 这里就是 item 表
     * @var string
     */
    protected $table = 'subject_kownledgepoint';
    //protected $primary = 'subjectid';
    /**
     * 搜索功能，因为Sql父类里面没有现成的like搜索，
     * 所以需要自己写SQL语句，对数据库的操作应该都放s
     * 在Model里面，然后提供给Controller直接调用
     * @param $title string 查询的关键词
     * @return array 返回的数据
     */
    public function search($keyword=array())
    {
        $sql = "select * from `$this->table` where `title` like :keyword";
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, [':keyword' => "%$keyword%"]);
        $sth->execute();

        return $sth->fetchAll();
    }
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
    public function delete($kownledgepointid,$subjectid='')
    {
        $sql = sprintf("delete from `%s` where `%s` = :%s and `%s` = :%s", $this->table, 'kownledgepointid', 'kownledgepointid','subjectid','subjectid');
        $sth = Db::pdo()->prepare($sql);
        $sth = $this->formatParam($sth, ['kownledgepointid' => $kownledgepointid,'subjectid'=> $subjectid]);
        $sth->execute();

        return $sth->rowCount();
    }

    
}