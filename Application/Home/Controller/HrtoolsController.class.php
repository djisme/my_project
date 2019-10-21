<?php
namespace Home\Controller;
use Common\Controller\FrontendController;
class HrtoolsController extends FrontendController{
	public function _initialize(){
        parent::_initialize();
    }
	public function index(){
		$this->display();
	}
	public function hrtools_list(){
		$this->display();
	}
}
?>