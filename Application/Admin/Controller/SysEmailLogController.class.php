<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class SysEmailLogController extends BackendController{
	public function _initialize() {
        parent::_initialize();
    }
    public function _before_search($data){
        $key_type = I('request.key_type',0,'intval');
        $key = I('request.key','','trim');
        if($key_type && $key){
            switch ($key_type){
                case 1:
                    $data['subject'] = array('like','%'.$key.'%');
                    break;
                case 2:
                    $data['send_to'] = $key;
                    break;
                case 3:
                    $data['send_from'] = $key;
                    break;
            }
        }
        return $data;
    }
    public function show(){
        $this->_tpl = 'show';
        parent::edit();
    }
}
?>