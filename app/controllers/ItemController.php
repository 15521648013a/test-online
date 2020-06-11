<?php
namespace app\controllers;
use fastphp\base\Controller;
use app\models\Item1;
use app\models\Afw;
use app\models\Ite as Ite;
class ItemController extends Controller
{
    // 首页方法，测试框架自定义DB查询
    public function index()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

        if ($keyword) {
            $items = (new Item())->search($keyword);
        } else {
            // 查询所有内容，并按倒序排列输出
            // where()方法可不传入参数，或者省略
            $items = (new Item1())->where()->order(['id DESC'])->fetchAll();
        }
        //session_start();
        $this->assign('title', '全部条目');
        $this->assign('keyword', $keyword);
        $this->assign('items', $items);
        $this->assign("username",$_SESSION["username"]);
        $this->render();
    }
    public function welcome(){
        
        $this->render();

    }
   
}