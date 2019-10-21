<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class AppealController extends BackendController{
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('MembersAppeal');
        $this->_name = 'MembersAppeal';
    }
    public function _before_index(){
        $this->assign('count',parent::_pending('MembersAppeal',array('status'=>0)));
    }
    public function _before_search($map){
        $settr = I('get.settr',0,'intval');
        if($settr){
            $map['addtime'] = array('egt',strtotime('-'.$settr.' day'));
        }
        $this->order = $this->order = 'status asc, id desc';
        return $map;
    }
    public function set_status(){
        $id = I('request.id');
        $status = I('request.status',0,'intval');
        if(empty($id)){
            $this->error('请选择记录！');
        }
        !is_array($id) && $id = array($id);
        $r = $this->_mod->where(array('id'=>array('in',$id)))->setField('status',$status);
        if($r){
            $this->success('设置成功！响应行数'.$r);
        }else{
            $this->error('设置失败！');
        }
    }
    public function send_email(){
        $username = I('post.login_username');
        $password = I('post.login_password');
        $email = I('post.email');
        $id = I('post.id');
        if(!$id){
            $this->error('发送失败，参数错误！');
        }
        if(!$email || !fieldRegex($email,'email')){
            $this->error('发送失败，邮箱格式错误！');
        }
        $str = '您好！您的申诉已被受理，您的登录账号是：'.$username.'，您的登录密码是：'.$password;
        if(!$reg = D('Mailconfig')->send_mail(array('sendto_email'=>$email,'subject'=>C('qscms_site_name')." - 账号申诉回执",'body'=>$str))) $this->error($reg);
        $r = $this->_mod->where(array('id'=>array('eq',$id)))->setField('status',1);
        $this->success('回执邮件发送成功！');exit;
    }
}
?>