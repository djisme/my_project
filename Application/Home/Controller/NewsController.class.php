<?php
namespace Home\Controller;
use Common\Controller\FrontendController;
class NewsController extends FrontendController{
	public function _initialize() {
		parent::_initialize();
		/*
		//标题、描述、关键词
		$this->_config_seo();*/
	}
	//新闻资讯
	public function index(){
		if(!I('get.org','','trim') && C('PLATFORM') == 'mobile' && $this->apply['Mobile']){
            redirect(build_mobile_url(array('c'=>'News','a'=>'index')));
		}
		$this->display();
	}
	
	//新闻详情
	public function news_show(){
		if(!I('get.org','','trim') && C('PLATFORM') == 'mobile' && $this->apply['Mobile']){
            redirect(build_mobile_url(array('c'=>'News','a'=>'show','params'=>'id='.intval($_GET['id']))));
		}
		$this->display();
	}
	//资讯列表
	public function news_list(){
		if(!I('get.org','','trim') && C('PLATFORM') == 'mobile' && $this->apply['Mobile']){
            redirect(build_mobile_url(array('c'=>'News','a'=>'index')));
		}
		$this->display();
	}
	public function ajax_new_article_list(){
		$page = I('get.page',0,'intval');
		$start = $page*5;
		$this->assign('start',$start);
		$html = $this->fetch('ajax_new_article_list');
		if($html){
			$this->ajaxReturn(1,'',$html);
		}else{
			$this->ajaxReturn(0);
		}
	}
}
?>