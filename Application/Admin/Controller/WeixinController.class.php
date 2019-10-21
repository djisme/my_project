<?php
namespace Admin\Controller;
use Common\Controller\ConfigbaseController;
class WeixinController extends ConfigbaseController{
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Config');
    }
	/**
	 * [config 公众号配置]
	 */
	public function index(){
		$this->_edit();//调用父类_edit方法
        $this->display();
	}
}
?>