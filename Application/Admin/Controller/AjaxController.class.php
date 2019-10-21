<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class AjaxController extends BackendController{
	public function _initialize() {
        parent::_initialize();
    }
    public function userinfo(){
    	$uid = I('get.uid',0,'intval');
    	if(!$uid){
    		$this->ajaxReturn(0,'参数错误！');
    	}
    	$userinfo = D('Members')->get_user_one(array('uid'=>$uid));
    	$manage_url = $userinfo['utype']==1?U('Company/management',array('id'=>$userinfo['uid'])):U('Personal/management',array('id'=>$userinfo['uid']));
    	if($userinfo['utype']==1)
        {
            $consultant = D('Consultant')->find($userinfo['consultant']);
            $this->assign('consultant',$consultant);
            $company_profile = D('CompanyProfile')->where(array('uid'=>$userinfo['uid']))->find();
            !$company_profile && $company_profile['companyname'] = $userinfo['username'];
            $this->assign('company_profile',$company_profile);
        }else{
            $userinfo['realname'] = M('Resume')->where(array('uid'=>$uid,'def'=>1))->limit(1)->getfield('fullname');
            $this->assign('resume_manage',U('personal/management',array('id'=>$userinfo['uid'])));
        }
        $this->assign('userinfo',$userinfo);
    	$this->assign('manage_url',$manage_url);
        $html = $this->fetch('userinfo');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    public function business(){
        $uid = I('get.uid',0,'intval');
        if(!$uid){
            $this->ajaxReturn(0,'参数错误！');
        }
        $userinfo = D('Members')->get_user_one(array('uid'=>$uid));
        $manage_url = $userinfo['utype']==1?U('Company/management',array('id'=>$userinfo['uid'])):U('Personal/management',array('id'=>$userinfo['uid']));
        if($userinfo['utype']==1)
        {
            $consultant = D('Consultant')->find($userinfo['consultant']);
            $this->assign('consultant',$consultant);
            $company_profile = D('CompanyProfile')->where(array('uid'=>$userinfo['uid']))->find();
            !$company_profile && $company_profile['companyname'] = $userinfo['username'];
            $this->assign('company_profile',$company_profile);
        }
        $this->assign('userinfo',$userinfo);
        $this->assign('manage_url',$manage_url);
        $html = $this->fetch('business');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    public function ajax_message(){
        $uid = I('request.uid',0,'intval');
        $userinfo = D('Members')->get_user_one(array('uid'=>$uid));
        $this->assign('userinfo',$userinfo);
        $html = $this->fetch('message');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    public function ajax_send_sms(){
        $uid = I('request.uid',0,'intval');
        $mobile = I('request.mobile','','trim');
        $service_info = D('Sms')->where(array('alias'=>C('qscms_sms_other_service')))->find();
        if(!$service_info){
            $this->ajaxReturn(0,'短信配置错误，请修改后再发送！');
        }
        if(IS_POST){
            if (!$uid)
            {
            $this->error('用户UID错误！');
            }
            $setsqlarr['s_mobile']=$mobile?$mobile:$this->error('手机不能为空！');
            $setsqlarr['s_body']=I('request.txt','','trim')?:$this->error('短信内容不能为空！');
            $setsqlarr['s_addtime']=time();
            $setsqlarr['s_uid']=$uid;
            $setsqlarr['s_tplid']=I('request.tplid','','trim');
            if($setsqlarr['s_tplid']){
                $data = array('mobile'=>$setsqlarr['s_mobile'],'tpl'=>$setsqlarr['s_body'],'tplId'=>$setsqlarr['s_tplid'],'data'=>array());
                $service = C('qscms_sms_other_service');
                $sms = new \Common\qscmslib\sms($service);
                $r = $sms->sendTemplateSMS('other',$data);
            }else{
                $r = D('Sms')->sendSms('other',array('mobile'=>$setsqlarr['s_mobile'],'tplStr'=>$setsqlarr['s_body']));
            }
            if(true===$r){
                $setsqlarr['s_sendtime']=time();
                $setsqlarr['s_type']=2;//发送成功
                D('Smsqueue')->add($setsqlarr);
                unset($setsqlarr);
                $this->success('发送成功！');
            }
            else
            {
                $setsqlarr['s_sendtime']=time();
                $setsqlarr['s_type']=3;//发送失败
                D('Smsqueue')->add($setsqlarr);
                unset($setsqlarr);
                $this->error('发送失败，错误未知！');
            }
        }else{
            $this->assign('uid',$uid);
            $this->assign('mobile',$mobile);
            $this->assign('service_info',$service_info);
            $this->assign('need_tpl',$service_info['replace']?1:0);
            $html = $this->fetch('send_sms');
            $this->ajaxReturn(1,'获取数据成功！',$html);
        }
    }
    public function ajax_send_email(){
        $uid = I('request.uid',0,'intval');
        $email = I('request.email','','trim');
        if(IS_POST){
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
        }else{
            $this->assign('uid',$uid);
            $this->assign('email',$email);
            $html = $this->fetch('send_email');
            $this->ajaxReturn(1,'获取数据成功！',$html);
        }
    }
    public function ajax_send_pms(){
        $tousername = I('request.tousername','','trim');
        if(IS_POST){
            if (!$tousername)
            {
                $this->error('用户名填写错误！');exit;
            }
            else
            {
                $s=0;
                $msg=I('post.msg','','trim');
                $time=time();
                $data = array();
                $userinfo = D('Members')->where(array('username'=>$tousername))->find();
                if (intval($userinfo['uid'])>0)
                {
                    $data['msgtype'] = 1;
                    $data['msgtouid'] = $userinfo['uid'];
                    $data['msgtoname'] = $userinfo['username'];
                    $data['message'] = $msg;
                    $data['dateline']=$time;
                    $data['replytime']=$time;
                    $data['new']=1;
                }
                D('Pms')->add($data);
                $this->success("发送成功！");exit;
            }
        }else{
            $this->assign('tousername',$tousername);
            $html = $this->fetch('send_pms');
            $this->ajaxReturn(1,'获取数据成功！',$html);
        }
    }
    /**
     * 帐号申诉发送邮件
     */
    public function appeal_send_email(){
        $id = I('request.id',0,'intval');
        $email = I('request.email','','trim');
        if(!$id) $this->ajaxReturn(0,'请选择申诉记录！');
        if(!$email) $this->ajaxReturn(0,'邮箱不能为空！');
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量审核职位
     */
    public function jobs_audit(){
        $ids = I('request.id');
        $uids = I('request.uid');
        if(!$ids) $this->ajaxReturn(0,'请选择职位！');
        $this->assign('ids',$ids);
        $this->assign('uids',$uids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量审核简历
     */
    public function resumes_audit(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择简历！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量审核简历照片
     */
    public function resumes_photo_audit(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择简历！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量审核公司
     */
    public function company_audit(){
        $ids = I('request.y_id');
        if(!$ids) $this->ajaxReturn(0,'请选择企业！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量审核投诉
     */
    public function complain_audit(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择投诉！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量处理举报
     */
    public function report_audit(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择发票记录！');
        $type = I('request.type',1,'intval');
        $this->assign('ids',$ids);
        $this->assign('type',$type);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量处理意见建议
     */
    public function feedback_audit(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择意见建议！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量处理帐号申诉
     */
    public function appeal_audit(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择申诉记录！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量处理发票
     */
    public function set_invoice(){
        $ids = I('request.order_id');
        if(!$ids) $this->ajaxReturn(0,'请选择发票记录！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量设置企业顾问
     */
    public function set_consultant(){
        $ids = I('request.y_id');
        if(!$ids) $this->ajaxReturn(0,'请选择企业会员！');
        $consultants = M('Consultant')->select();
        if(!$consultants) $this->ajaxReturn(0,'没有企业顾问可设置！');
        if(is_array($ids)){
            $ids=implode(",",$ids);
        }
        $this->assign('ids',$ids);
        $this->assign('consultants',$consultants);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量刷新企业
     */
    public function refresh_company(){
        $ids = I('request.y_id');
        if(!$ids) $this->ajaxReturn(0,'请选择企业！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量删除企业
     */
    public function delete_company(){
        $ids = I('request.y_id');
        if(!$ids) $this->ajaxReturn(0,'请选择企业！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量备注手机解绑记录
     */
    public function set_unbind_mobile(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择记录！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 审核日志
     */
    public function audit_log(){
        $type = I('get.type','jobs_id','trim');
        $id = I('get.id',0,'intval');
        switch($type){
            case 'jobs_id':
            case 'resume_id':
            case 'company_id':
                $list = D('AuditReason')->where(array($type=>$id,'famous'=>0))->order('id desc')->select();
                break;
            default:
                $list = null;
                break;
        }
        $this->assign('list',$list);
        $html = $this->fetch('audit_log');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 个人会员日志
     */
    public function personal_log(){
        $uid = I('get.uid',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$uid))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 简历审核日志
     */
    public function resume_audit_log(){
        $id = I('get.id',0,'intval');
        $list = D('MembersLog')->where(array('resume_id'=>$id,'log_type'=>'resume_audit'))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 简历日志
     */
    public function resume_log(){
        $uid = I('get.uid',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$uid))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 高级简历日志
     */
    public function adv_resume_log(){
        $uid = I('get.resumeid',0,'intval');
        $list = D('MembersLog')->where(array('resume_id'=>$uid))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 增值服务日志
     */
    public function increment_log(){
        $uid = I('get.uid',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$uid,'log_type'=>'increment'))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 推广日志
     */
    public function promotion_log(){
        $uid = I('get.uid',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$uid,'log_type'=>'promotion'))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 订单日志
     */
    public function order_log(){
        $uid = I('get.uid',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$uid,'log_type'=>'order'))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 套餐日志
     */
    public function setmeal_log(){
        $uid = I('get.uid',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$uid,'log_type'=>'setmeal'))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 积分日志
     */
    public function points_log(){
        $uid = I('get.uid',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$uid,'log_type'=>'points'))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 企业日志
     */
    public function company_log(){
        $uid = I('get.uid',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$uid))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 企业审核日志
     */
    public function company_audit_log(){
        $uid = I('get.uid',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$uid,'log_type'=>'company_audit'))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 职位日志
     */
    public function jobs_log(){
        $id = I('get.id',0,'intval');
        $list = D('MembersLog')->where(array('jobs_id'=>$id))->order('log_id desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('_log_tpl');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 登录日志
     */
    public function login_log(){
        $id = I('get.id',0,'intval');
        $list = D('MembersLog')->where(array('log_uid'=>$id,'log_type'=>1001))->order('log_addtime desc')->limit('5')->select();
        $this->assign('list',$list);
        $html = $this->fetch('login_log');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 套餐详情
     */
    public function setmeal_detail(){
        $uid = I('get.uid',0,'intval');
        $info = D('MembersSetmeal')->get_user_setmeal($uid);
        $list = M('MembersChargeLog')->field('log_uid,log_addtime,log_value,log_mode')->where(array('log_uid'=>$uid))->select();
        $this->assign('info',$info);
        $this->assign('list',$list);
        $html = $this->fetch('setmeal_detail');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量删除企业会员
     */
    public function delete_company_members(){
        $ids = I('request.tuid');
        if(!$ids) $this->ajaxReturn(0,'请选择会员！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 批量删除个人会员
     */
    public function delete_person_members(){
        $ids = I('request.tuid');
        if(!$ids) $this->ajaxReturn(0,'请选择会员！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 审核图片
     */
    public function img_audit(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择图片！');
        $type = I('request.utype',0,'intval');
        if($type == 1){
            $controller = 'CompanyImg';
        }elseif($type == 2){
            $controller = 'ResumeImg';
        }else{
            $this->ajaxReturn(0,'用户类型错误！');
        }
        $this->assign('controller',$controller);
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 委托投递记录
     */
    public function entrust_apply_log(){
        $id = I('get.id',0,'intval');
        $list = M('PersonalJobsApply')->where(array('is_apply'=>1,'resume_id'=>$id))->order('did desc')->select();
        $this->assign('list',$list);
        $html = $this->fetch('entrust_apply_log');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * [ajax_subsite description]
     */
    public function ajax_subsite(){
        $uid = I('get.id',0,'intval');
        $subsites = D('Admin')->where(array('id'=>$uid))->getfield('subsite');
        $this->assign('subsites',explode(',',$subsites));
        $html = $this->fetch('subsite');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * [affair_stat 待处理事务统计]
     */
    public function affair_stat(){
        $affair = I('request.affair','','trim');
        foreach ($affair as $key => $val) {
            $name = '_affair_'.$val;
            $d = $this->$name();
            $d && $list[$val] = $d;
        }
        $this->ajaxReturn(1,'获取成功！',$list);
    }
    /**
     * 发送邮件营销
     */
    public function send_mail_queue(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择项目！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 删除邮件营销
     */
    public function delete_mail_queue(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择项目！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 发送短信营销
     */
    public function send_sms_queue(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择项目！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 删除短信营销
     */
    public function delete_sms_queue(){
        $ids = I('request.id');
        if(!$ids) $this->ajaxReturn(0,'请选择项目！');
        $this->assign('ids',$ids);
        $html = $this->fetch();
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
	/**
	 * 页面管理批量设置链接
	 */
	public function page_set_url(){
		$html = $this->fetch();
		$this->ajaxReturn(1,'获取数据成功！',$html);
	}
	/**
	 * 页面管理批量设置链接
	 */
	public function page_set_caching(){
		$html = $this->fetch();
		$this->ajaxReturn(1,'获取数据成功！',$html);
	}
	/**
	 * 短信营销导入号码
	 */
	public function import_mobile_num(){
		$html = $this->fetch();
		$this->ajaxReturn(1,'获取数据成功！',$html);
	}
	
	/**
     * 简历导入
     */
    public function resume_import(){
        $html = $this->fetch("resume_import");
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * [_affair_resume 待处理简历]
     */
    protected function _affair_resume(){
        $count = parent::_pending('Resume',array('audit'=>2));
        return $count ?:false;
    }
    /**
     * [_affair_resume_img 待处理简历作品]
     */
    protected function _affair_resume_img(){
        $count = parent::_pending('ResumeImg',array('audit'=>2));
        return $count ?:false;
    }
    /**
     * [_affair_photo 待处理简历照片]
     */
    protected function _affair_photo(){
        $count = parent::_pending('Resume',array('photo_img'=>array('neq',''),'photo_audit'=>2),'uid');
        return $count ?:false;
    }
    /**
     * [_affair_jobs 待处理职位]
     */
    protected function _affair_jobs(){
        $count = parent::_pending('Jobs',array('audit'=>2));
        $count1 = parent::_pending('JobsTmp',array('audit'=>2));
        $count = $count + $count1;
        return $count ?:false;
    }
    /**
     * [_affair_company 待认证企业]
     */
    protected function _affair_company_audit(){
        $count = parent::_pending('CompanyProfile',array('audit'=>2));
        return $count ?:false;
    }
    /**
     * [_affair_company_img 待处理企业风采]
     */
    protected function _affair_company_img(){
        $count = parent::_pending('CompanyImg',array('audit'=>2));
        return $count ?:false;
    }
    /**
     * [_affair_company_order 待处理企业线下支付订单]
     */
    protected function _affair_company_order(){
        $count = parent::_pending('Order',array('payment'=>'remittance','is_paid'=>1));
        return $count ?:false;
    }
    /**
     * [_affair_report 待处理举报信息]
     */
    protected function _affair_report(){
        $count = parent::_pending('Report',array('audit'=>1));
        $count1 = parent::_pending('ReportResume',array('audit'=>1));
        $count = $count + $count1;
        return $count ?:false;
    }
    /**
     * [_affair_feedback 待处理意见与建议]
     */
    protected function _affair_feedback(){
        $count = parent::_pending('Feedback',array('audit'=>1));
        return $count ?:false;
    }
    /**
     * [_affair_appeal 待处理帐号申诉]
     */
    protected function _affair_appeal(){
        $count = parent::_pending('MembersAppeal',array('status'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_exhibitors 待处理参会企业]
     */
    protected function _affair_exhibitors(){
        $count = parent::_pending('JobfairExhibitors',array('audit'=>2));
        return $count ?:false;
    }
    /**
     * [_affair_mall_order 待处理商城订单]
     */
    protected function _affair_mall_order(){
        $count = parent::_pending('MallOrder',array('status'=>1));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待处理发票]
     */
    protected function _affair_order_invoice(){
        $count = parent::_pending('OrderInvoice',array('audit'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待处理发票]
     */
    protected function _affair_resume_entrust(){
        $count = parent::_pending('Resume',array('entrust'=>array('gt',0)));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待审核兼职]
     */
    protected function _affair_parttime(){
        $count = parent::_pending('ParttimeJobs',array('audit'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待审核门店职位]
     */
    protected function _affair_store_jobs(){
        $count = parent::_pending('StorerecruitJobs',array('audit'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待审核门店转租]
     */
    protected function _affair_store_rent(){
        $count = parent::_pending('Storetransfer',array('audit'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待审核门店求租]
     */
    protected function _affair_store_seek(){
        $count = parent::_pending('Storetenement',array('audit'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待审核店铺图片]
     */
    protected function _affair_store_rent_img(){
        $count = parent::_pending('StoretransferImg',array('display'=>1,'audit'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待审核房屋出租]
     */
    protected function _affair_house_rent(){
        $count = parent::_pending('HouseRent',array('audit'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待审核房屋求租]
     */
    protected function _affair_house_seek(){
        $count = parent::_pending('HouseSeek',array('audit'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_order_invoice 待审核房屋照片]
     */
    protected function _affair_house_rent_img(){
        $count = parent::_pending('HouseRentImg',array('display'=>1,'audit'=>0));
        return $count ?:false;
    }
    /**
     * [_affair_gworker 待处理申请]
     */
    protected function _affair_gworker(){
        $count = parent::_pending('GworkerPublishApply',array('status'=>0));
        return $count ?:false;
    }
    /**
     * [affair 主菜单待处理事务统计]
     */
    public function affair(){
        $affair = I('request.affair','','trim');
        foreach ($affair as $key => $val) {
            $name = '_total_affair_'.$val;
            $d = $this->$name();
            $d && $list[$val] = $d;
        }
        $this->ajaxReturn(1,'获取成功！',$list);
    }
    protected function _subsite($mod,$where){
        $field = M($mod)->getDbFields();
        if($this->apply['Subsite'] && in_array('subsite_id',$field) && C('visitor.subsite')){
            $where['subsite_id'] = array('in',C('visitor.subsite'));
        }
        return M($mod)->where($where)->find();
    }
    /**
     * [_total_affair_personal 个人事务]
     */
    protected function _total_affair_personal(){
        $s = $this->_subsite('Resume',array('audit'=>2));
        if($s) return 1;
        $s = $this->_subsite('ResumeImg',array('audit'=>2));
        if($s) return 1;
        $s = $this->_subsite('Resume',array('photo_img'=>array('neq',''),'photo_audit'=>2));
        return false;
    }
    /**
     * [_total_affair_company 企业事务]
     */
    protected function _total_affair_company(){
        $s = $this->_subsite('Jobs',array('audit'=>2));
        if($s) return 1;
        $s = $this->_subsite('JobsTmp',array('audit'=>2));
        if($s) return 1;
        $s = $this->_subsite('CompanyProfile',array('audit'=>2));
        if($s) return 1;
        $s = $this->_subsite('CompanyImg',array('audit'=>2));
        if($s) return 1;
        $s = $this->_subsite('OrderInvoice',array('audit'=>2));
        if($s) return 1;
        $s = $this->_subsite('Order',array('payment'=>'remittance','is_paid'=>1));
        if($s) return 1;
        return false;
    }
    /**
     * [_total_affair_content 待处理内容]
     */
    protected function _total_affair_content(){
        $s = $this->_subsite('Report',array('audit'=>1));
        if($s) return 1;
        $s = $this->_subsite('ReportResume',array('audit'=>1));
        if($s) return 1;
        $s = $this->_subsite('Feedback',array('audit'=>1));
        if($s) return 1;
        $s = $this->_subsite('MembersAppeal',array('status'=>0));
        if($s) return 1;
        return false;
    }
    /**
     * [_total_affair_jobfair 待处理招聘会]
     */
    protected function _total_affair_jobfair(){
        $s = $this->_subsite('JobfairExhibitors',array('audit'=>2));
        if($s) return 1;
        return false;
    }
    /**
     * [_total_affair_mall 待处理商城]
     */
    protected function _total_affair_mall(){
        $s = $this->_subsite('MallOrder',array('status'=>1));
        if($s) return 1;
        return false;
    }
    /**
     * [_total_affair_parttime 兼职事务]
     */
    protected function _total_affair_parttime(){
        $s = $this->_subsite('ParttimeJobs',array('audit'=>0));
        if($s) return 1;
        return false;
    }
    /**
     * [_total_affair_store 门店事务]
     */
    protected function _total_affair_store(){
        $s = $this->_subsite('StorerecruitJobs',array('audit'=>0));
        if($s) return 1;
        $s = $this->_subsite('Storetransfer',array('audit'=>0));
        if($s) return 1;
        $s = $this->_subsite('Storetenement',array('audit'=>0));
        if($s) return 1;
        $s = $this->_subsite('StoretransferImg',array('display'=>1,'audit'=>0));
        if($s) return 1;
        return false;
    }
    /**
     * [_total_affair_store 租房事务]
     */
    protected function _total_affair_house(){
        $s = $this->_subsite('HouseRent',array('audit'=>0));
        if($s) return 1;
        $s = $this->_subsite('HouseSeek',array('audit'=>0));
        if($s) return 1;
        $s = $this->_subsite('HouseRentImg',array('display'=>1,'audit'=>0));
        if($s) return 1;
        return false;
    }
    /**
     * 点评简历
     */
    public function comment_resume(){
        $id = I('request.id',0,'intval');
        !$id && $this->ajaxReturn(0,'参数错误！');
        if(IS_POST){
            $data['comment_content'] = I('post.comment_content','','trim');
            $data['talent'] = I('post.talent',0,'intval');
            D('Resume')->where(array('id'=>$id))->save($data);
            $this->success('保存成功！');
        }else{
            $resume = D('Resume')->find($id);
            $this->assign('resume',$resume);
            $html = $this->fetch('comment_resume');
            $this->ajaxReturn(1,'获取数据成功！',$html);
        }
    }
}
?>