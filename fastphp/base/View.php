<?php
namespace fastphp\base;

/**
 * 视图基类
 */
class View
{
    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($controller, $action)
    {
        $this->_controller = strtolower($controller);
        $this->_action = strtolower($action);
    }
 
    // 分配变量
    public function assign($name, $value)
    {
        $this->variables[$name] = $value;
        //extract($this->variables);//转为变量
    }
 
    // 渲染显示
    public function render($view='')
    {
        extract($this->variables);
        $defaultHeader = APP_PATH . 'app/views/header.php';
        $defaultFooter = APP_PATH . 'app/views/footer.php';
        //echo $this->_action; echo $view;
        $controllerHeader = APP_PATH . 'app/views/' . $this->_controller . '/header.php';
        $controllerFooter = APP_PATH . 'app/views/' . $this->_controller . '/footer.php';
        if($view=='')
        $controllerLayout = APP_PATH . 'app/views/' . $this->_controller . '/' . $this->_action . '.php';//默认
        else   $controllerLayout = APP_PATH . 'app/views/' . $this->_controller . '/' . $view. '.php';
        // 页头文件
        if (is_file($controllerHeader)) {
            include ($controllerHeader);
        } else {
            include ($defaultHeader);
        }

        //判断视图文件是否存在
        if (is_file($controllerLayout)) {
            include ($controllerLayout);
        } else {
            echo "<h1>无法找到视图文件</h1>";
        }
        
        // 页脚文件
        if (is_file($controllerFooter)) {
            include ($controllerFooter);
        } else {
            include ($defaultFooter);
        }
    }
    //添加多个文件
    public function renderfile($view =[]){
    //在view文件夹下
    extract($this->variables);
    $defaultHeader = APP_PATH . 'app/views/header.php';
    $defaultFooter = APP_PATH . 'app/views/footer.php';
    //echo $this->_action; echo $view;
    $controllerHeader = APP_PATH . 'app/views/' . $this->_controller . '/header.php';
    $controllerFooter = APP_PATH . 'app/views/' . $this->_controller . '/footer.php';
  
    // 页头文件
    if (is_file($controllerHeader)) {
        include ($controllerHeader);
    } else {
        include ($defaultHeader);
    }
    //试卷头部
    $testhead =    APP_PATH . 'app/views/' . $this->_controller . '/' .  'testhead.php';
    include ($testhead);
    //文件数组 
    foreach($view as $value){
    //判断视图文件是否存在
    $controllerLayout = APP_PATH . 'app/views/' . $this->_controller . '/' . $value . '.php';
    if (is_file($controllerLayout)) {
        include ($controllerLayout);
    } else {
        echo "<h1>无法找到视图文件</h1>";
    }
}
    //试卷尾部
    $testfoot =    APP_PATH . 'app/views/' . $this->_controller . '/' .  'testfoot.php';
    include ($testfoot);
    // 页脚文件
    if (is_file($controllerFooter)) {
        include ($controllerFooter);
    } else {
        include ($defaultFooter);
    }

    }
    public function rendertest($view=''){
        $controllerLayout = APP_PATH . 'app/views/' . $this->_controller . '/' . $view. '.php';
        include ($controllerLayout);

    }

}
