<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class UcController extends BackendController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('UcConfig');
    }
    public function index(){
        if(IS_POST){
            foreach (I('post.') as $key => $val) {
                $this->_mod->where(array('name' => $key))->save(array('value' => $val));
            }
            $this->success(L('operation_success'));
            exit;
        }
        $ucdata = $this->_mod->select();
        $uc = array();
        foreach ($ucdata as $key => $value) {
            $uc[$value['name']] = $value['value'];
        }
        $this->assign('uc',$uc);
        $this->display();
    }
    /**
     * 开启/关闭
     */
    public function setOpen(){
        $uc_open = I('post.uc_open',0,'intval');
        D('Config')->where(array('name'=>'uc_open'))->save(array('value'=>$uc_open));
        $this->admin_write_log_unify();
        $this->success(L('operation_success'));
        exit;
    }
}
?>