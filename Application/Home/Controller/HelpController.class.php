<?php
namespace Home\Controller;
use Common\Controller\FrontendController;
class HelpController extends FrontendController{
    //帮助首页
    public function index() {
        $this->display();
    }
    //帮助列表页
    public function help_list(){
        $_GET['key'] = I('request.key','','trim');
        $this->display();
    }
    //帮助详细页
    public function help_show(){
        $this->display();
    }
}