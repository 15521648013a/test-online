<?php
namespace fastphp\base;

use fastphp\db\Sql;

class Model extends Sql
{
    protected $model;
 
    public function __construct()
    {
        // 获取数据库表名
        if (!$this->table) {//如何不存在或为空，则根据model 类名取同名的表名。

            // 获取模型类名称
            $this->model = get_class($this);
           
           // 删除类名最后的 Model 字符
           // $this->model = substr($this->model, 0, -5);

           //获取不含命名空间的类名
           $this->model=basename(str_replace('\\', '/', $this->model));

           // 数据库表名与类名一致，且转为小写
            $this->table = strtolower($this->model);
            
        }
    }
    public function show(){
      
         echo $this->table;//测试表名
    }
}