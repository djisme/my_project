<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class AdminController extends BackendController{
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Admin');
    }
    public function _before_index() {
        $this->list_relation = true;
    }
    public function _before_add() {
        if(false === $roles = F('admin_role_list')){
            $roles = D('AdminRole')->role_cache();
        }
        $this->assign('roles', $roles);
    }
    public function _before_insert($data='') {
        if(trim($data['password'])==''){
            unset($data['password']);
            unset($data['pwd_hash']);
        }else{
            $data['password'] = md5(md5($data['password']).$data['pwd_hash'].C("PWDHASH"));
        }
        return $data;
    }
    public function _before_edit() {
        $this->_before_add();
    }
    public function _before_update($data=''){
        return $this->_before_insert($data);
    }
    public function _before_delete(){
        $id = I('request.id',0,'intval');
        if($id==1){
            $this->error('超级管理员不允许被删除！');
        }
    }
    /**
     * [details 管理员详情]
     */
    public function details(){
    	$this->_before_add();
    	$this->edit();
    }
    public function set_subsite(){
        $aid = I('post.aid',0,'intval');
        $sid = I('post.sid','','trim');
        !$aid && $this->ajaxReturn(0,'请选择管理员！');
        $reg = M('Admin')->where(array('id'=>$aid))->setfield('subsite',implode(',',$sid));
        false === $reg && $this->ajaxReturn(0,'分站管理员设置失败请重新操作！');
        $this->ajaxReturn(1,'分站管理员设置成功！');
    }
}
?>