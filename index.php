<?php
// 应用目录为当前目录
define('APP_PATH', __DIR__ . '/');
$name= __DIR__;
$split= explode('\\',$name);
$itemName = array_pop($split); 
define('ITEMNAME',$itemName);
define('HOST',$_SERVER["HTTP_HOST"]);//服务器域名
function url($string=''){
   return dirname($_SERVER['SCRIPT_NAME']).'/index.php/'.$string;
}
define('STATIC_PATH',dirname($_SERVER['SCRIPT_NAME']) );// 路径 

// 开启调试模式
define('APP_DEBUG', true);

// 加载框架文件
require(APP_PATH . 'fastphp/Fastphp.php');

// 加载配置文件
$config = require(APP_PATH . 'config/config.php');

// 实例化框架类
(new fastphp\Fastphp($config))->run();
