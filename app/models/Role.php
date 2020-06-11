<?php 
namespace app\models;
use fastphp\base\Model;
use fastphp\db\Db;
class Role extends Model
{
    protected $table = 'role';
    protected $primary = 'roleid';

}