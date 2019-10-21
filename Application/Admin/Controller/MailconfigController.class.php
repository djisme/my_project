<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class MailconfigController extends BackendController{
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Mailconfig');
    }
    public function index(){
    	if(IS_POST){
    		if (intval($_POST['method'])==1 && $_POST['api_type']=='normal'){
    			foreach ($_POST['smtpservers'] as $key => $val) {
    				if (empty($_POST['smtpservers'][$key]) || empty($_POST['smtpusername'][$key]) || empty($_POST['smtppassword'][$key]) || empty($_POST['smtpfrom'][$key]) || empty($_POST['smtpport'][$key])){
    					$this->error('您填写的资料不完整');
    				}
    			}
				$_POST['smtpservers']=implode('|-_-|',$_POST['smtpservers']);
				$_POST['smtpusername']=implode('|-_-|',$_POST['smtpusername']);
				$_POST['smtppassword']=implode('|-_-|',$_POST['smtppassword']);
				$_POST['smtpfrom']=implode('|-_-|',$_POST['smtpfrom']);
				$_POST['smtpport']=implode('|-_-|',$_POST['smtpport']);
			}else{
				unset($_POST['smtpservers'],$_POST['smtpusername'],$_POST['smtppassword'],$_POST['smtpfrom'],$_POST['smtpport']);
			}
			if($_POST['api_type']=='aliyun'){
				unset($_POST['smtpservers'],$_POST['smtpusername'],$_POST['smtppassword'],$_POST['smtpfrom'],$_POST['smtpport']);
				$_POST['method'] = 4;
			}
    		foreach (I('post.') as $key => $val) {
	        	$reg = $this->_mod->where(array('name' => $key))->save(array('value' => $val));
	        	if(false === $reg){
	        		IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
	        		$this->error(L('operation_failure'));
	        	}
	        }
	        $this->success(L('operation_success'));
    	}else{
    		if(false === $mailconfig = F('mailconfig')) $mailconfig =$this->_mod->config_cache();
    		$mailconfig['smtpservers']=explode('|-_-|',$mailconfig['smtpservers']);
			$mailconfig['smtpusername']=explode('|-_-|',$mailconfig['smtpusername']);
			$mailconfig['smtppassword']=explode('|-_-|',$mailconfig['smtppassword']);
			$mailconfig['smtpfrom']=explode('|-_-|',$mailconfig['smtpfrom']);
			$mailconfig['smtpport']=explode('|-_-|',$mailconfig['smtpport']);
			foreach ($mailconfig['smtpservers'] as $key => $val) {
				$list[]=array('smtpservers'=>$val,'smtpusername'=>$mailconfig['smtpusername'][$key],'smtppassword'=>$mailconfig['smtppassword'][$key],'smtpfrom'=>$mailconfig['smtpfrom'][$key],'smtpport'=>$mailconfig['smtpport'][$key]);
			}
			$this->assign('list',$list);
	    	$this->assign('info',$mailconfig);
	    	$this->display();
    	}
    }
    public function rule(){
    	if(IS_POST){
    		foreach (I('post.') as $key => $val) {
	        	$reg = $this->_mod->where(array('name' => $key))->save(array('value' => intval($val)));
	        	if(false === $reg){
	        		IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
	        		$this->error(L('operation_failure'));
	        	}
	        }
	        $this->success(L('operation_success'));
    	}else{
    		if(false === $mailconfig = F('mailconfig')) $mailconfig =$this->_mod->config_cache();
	    	$this->assign('info',$mailconfig);
	    	$this->display();
    	}
    }
    /**
     * [testing 测试]
     */
    public function testing(){
    	if(IS_POST){
    		$email = I('post.email','','trim');
	    	!$email && $this->error('邮箱地址不能为空！');
	    	!fieldRegex($email,'email') && $this->error('请输入正确的邮箱地址！');
	    	$str = '您好！这是一封检测邮件服务器设置的测试邮件。收到此邮件，意味着您的邮件服务器设置正确！您可以进行其它邮件发送的操作了！';
	    	if(true !== $reg = D('Mailconfig')->send_mail(array('sendto_email'=>$email,'subject'=>C('qscms_site_name')." - 会员注册",'body'=>$str))) $this->error($reg);
	    	$this->success('测试邮件发送成功！');exit;
    	}
    	$this->display();
    }
    public function send(){
    	$uid = I('request.uid',0,'intval');
    	$email = I('request.email','','trim');
    	$map['m_uid'] = array('eq',$uid);
		$order = 'm_id desc';
		$total = M('Mailqueue')->where($map)->order($order)->count();
        $pager = pager($total, 10);
        $page = $pager->fshow();
        $maillog = M('Mailqueue')->where($map)->order($order)->select();
		if (empty($url))
		{
			$url=U('send',array('email'=>$email,'uid'=>$uid));
		}
		$this->assign('url',$url);
		$this->assign('maillog',$maillog);
		$this->assign('page',$page);
		$this->display('send_mail');
    }
    public function send_mail(){
    	$uid = I('request.uid',0,'intval');
        $url = I('request.url','','trim');
    	$email = I('request.email','','trim');
        $subject = I('request.subject','','trim');
        $body = I('request.body','','trim');
		if (!$uid)
		{
		$this->error('用户UID错误！');
		}
		$setsqlarr['m_mail']=$email?$email:$this->error('邮件地址必须填写！');
		if(!$email || !fieldRegex($setsqlarr['m_mail'],'email'))
	    {
		$this->error('邮箱格式错误！');
	    }
		$setsqlarr['m_subject']=$subject?$subject:$this->error('邮件标题必须填写！');	
		$setsqlarr['m_body']=$body?$body:$this->error('邮件内容必须填写！');
		$setsqlarr['m_addtime']=time();
		$setsqlarr['m_uid']=$uid;
		if(D('Mailconfig')->send_mail(array('sendto_email'=>$setsqlarr['m_mail'],'subject'=>$setsqlarr['m_subject'],'body'=>$setsqlarr['m_body']))){
			$setsqlarr['m_sendtime']=time();
			$setsqlarr['m_type']=2;//发送成功
			D('Mailqueue')->add($setsqlarr);
			unset($setsqlarr);
			$this->success('发送成功！',$url);
		}
		else
		{
			$setsqlarr['m_sendtime']=time();
			$setsqlarr['m_type']=3;//发送失败
			D('Mailqueue')->add($setsqlarr);
			unset($setsqlarr);
			$this->error('发送失败，错误未知！',$url);
		}
    }
    public function again_send(){
    	$id = I('request.id',0,'intval');
		if (empty($id))
		{
		$this->error("请选择要发送的项目！");
		}
		$result = D('Mailqueue')->find($id);
		$map['m_id'] = $id;
		if(D('Mailconfig')->send_mail(array('sendto_email'=>$result['m_mail'],'subject'=>$result['m_subject'],'body'=>$result['m_body']))){
			$setsqlarr['m_sendtime']=time();
			$setsqlarr['m_type']=2;//发送成功
			D('Mailqueue')->where($map)->save($setsqlarr);
			$this->success('发送成功');
		}else{
			$setsqlarr['m_sendtime']=time();
			$setsqlarr['m_type']=3;
			D('Mailqueue')->where($map)->save($setsqlarr);
			$this->error('发送失败');
		}
    }
    public function del(){
    	$id = I('request.id',0,'intval');
		if (empty($id))
		{
		$this->error("请选择要发送的项目！");
		}
		if(!is_array($id)) $id=array($id);
		$sqlin=implode(",",$id);
		if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
		{
			D('Mailqueue')->where(array('m_id'=>array('in',$sqlin)))->delete();
			$this->success("删除成功");
		}
    }
}
?>