<?php
namespace Home\Controller;
use Common\Controller\FrontendController;
class MembersController extends FrontendController{
	public function _initialize() {
		parent::_initialize();
        //访问者控制
        if(!$this->visitor->is_login) {
            if(in_array(ACTION_NAME, array('index','pms','sign_in'))){
                IS_AJAX && $this->ajaxReturn(0, L('login_please'),'',1);
                //非ajax的跳转页面
                $this->redirect('members/login');
            }
        }else{
            $urls = array('1'=>'company/index','2'=>'personal/index');
            !IS_AJAX && !in_array(ACTION_NAME, array('logout','varify_email','choose_members','personal_add','company_add')) && $this->redirect($urls[C('visitor.utype')],array('uid'=>$this->visitor->info['uid']));
        }
	}
	public function index(){}
    /**
     * [login 用户登录]
     */
	public function login() {
        if(!I('get.org','','trim') && C('PLATFORM') == 'mobile' && $this->apply['Mobile']){
            redirect(build_mobile_url(array('c'=>'Members','a'=>'login')));
        }
        if(IS_AJAX && IS_POST){
            $expire = I('post.expire',1,'intval');
            $index_login = I('post.index_login',0,'intval');
            $url = I('post.url','','trim');
            if (C('qscms_captcha_open')==1 && (C('qscms_captcha_config.user_login')==0 || (session('?error_login_count') && session('error_login_count')>=C('qscms_captcha_config.user_login')))){
                if(true !== $reg = \Common\qscmslib\captcha::verify()) $this->ajaxReturn(0,$reg);
            }
            $passport = $this->_user_server();
            if($mobile = I('post.mobile','','trim')){
                if(!fieldRegex($mobile,'mobile')) $this->ajaxReturn(0,'手机号格式错误！');
                $smsVerify = session('login_smsVerify');
                !$smsVerify && $this->ajaxReturn(0,'验证码错误！');//验证码错误！
                if($mobile != $smsVerify['mobile']) $this->ajaxReturn(0,'手机号不一致！');//手机号不一致
                if(time()>$smsVerify['time']+600) $this->ajaxReturn(0,'验证码过期！');//验证码过期
                $user = M('Members')->where(array('mobile'=>$smsVerify['mobile']))->find();
                if($user){
                    $uid = $user['uid'];
                    if($user['utype'] == 1 && !$user['sitegroup_uid'] || !$user['uc_uid']){
                        $company = M('CompanyProfile')->field('companyname,contact,landline_tel')->where(array('uid'=>$user['uid']))->find();
                        $user = array_merge($user,$company);
                    }
                    if(!$user['sitegroup_uid'] && $passport->is_sitegroup()){
                        $temp = $passport->uc('sitegroup')->register($user);
                        $temp && $setsqlarr['sitegroup_uid'] = $temp['sitegroup_uid'];
                    }
                    if(!$user['uc_uid'] && !$passport->is_sitegroup() && $passport->is_uc()){
                        $temp = $passport->uc('ucenter')->register($user);
                        $temp && $setsqlarr['uc_uid'] = $temp['uc_uid'];
                    }
                    if(!$user['mobile_audit']){
                        $setsqlarr['mobile'] = $smsVerify['mobile'];
                        $setsqlarr['mobile_audit']=1;
                    }
                    if($setsqlarr){
                        if(false !== $passport->uc('sitegroup')->edit($user['uid'],array('mobile_audit'=>1))){
                            if(!$user['mobile_audit']){
                                D('Members')->update_user_info($setsqlarr,$user);
                                if($user['utype']=='1'){
                                    $rule=D('Task')->get_task_cache($user['utype'],22);
                                    D('TaskLog')->do_task($user,22);
                                }else{
                                    $rule=D('Task')->get_task_cache($user['utype'],7);
                                    D('TaskLog')->do_task($user,7);
                                }
                                write_members_log($user,'','手机验证通过（手机号：'.$smsVerify['mobile'].'）');
                            }
                        }
                    }
                    session('login_smsVerify',null);
                }elseif($passport->is_sitegroup() && false !== $sitegroup_user = $passport->uc('sitegroup')->get($smsVerify['mobile'], 'mobile')){
                    $this->_sitegroup_register($sitegroup_user,'mobile');
                }else{
                    $err = '帐号不存在！';
                }
            }else{
                $username = I('post.username','','trim');
                $password = I('post.password','','trim');
                if(false === $uid = $passport->uc('default')->auth($username, $password)){
                    $err = $passport->get_error();
                    if($err == L('auth_null')){
                        if($passport->is_sitegroup()){
                            if(false === $passport->uc('sitegroup')->auth($username, $password)){
                                $err = $passport->get_error();
                            }else{
                                $this->_sitegroup_register($passport->_user);
                            }
                        }elseif($passport->is_uc() && false !== $passport->uc('ucenter')->auth($username, $password)){
                            cookie('members_uc_info', $passport->_user);
                            $this->ajaxReturn(1,'5',U('members/apilogin_ucenter'));
                        }
                    }
                }else{
                    $user = $passport->_user;
                    if($user['utype'] == 1 && (!$user['sitegroup_uid'] || !$user['uc_uid'])){
                        $company = M('CompanyProfile')->field('companyname,contact,landline_tel')->where(array('uid'=>$user['uid']))->find();
                        $user = array_merge($user,$company);
                    }
                    if(!$user['sitegroup_uid'] && $passport->is_sitegroup()){
                        $temp = $passport->uc('sitegroup')->register($user);
                        $temp && M('Members')->where(array('uid'=>$user['uid']))->setfield('sitegroup_uid',$temp['sitegroup_uid']);
                    }
                    if(!$user['uc_uid'] && !$passport->is_sitegroup() && $passport->is_uc()){
                        $temp = $passport->uc('ucenter')->register($user);
                        $temp && M('Members')->where(array('uid'=>$user['uid']))->setfield('uc_uid',$temp['uc_uid']);
                    }
                }
            }
            if($uid){
                if(false === $this->visitor->login($uid, $expire)) $this->ajaxReturn(0,$this->visitor->getError());
                $urls = array('1'=>'company/index','2'=>'personal/index');
                $login_url = $url ? $url : U($urls[$this->visitor->info['utype']],array('uid'=>$this->visitor->info['uid']));
                //同步登陆
                $passport->uc('ucenter')->synlogin($uid,$expire);
                $this->ajaxReturn(1,'登录成功！',$login_url);
            }
            //记录登录错误次数
            if(C('qscms_captcha_open')==1){
                if(C('qscms_captcha_config.user_login')>0){
                    $error_login_count = session('?error_login_count')?(session('error_login_count')+1):1;
                    session('error_login_count',$error_login_count);
                    if(session('error_login_count')>=C('qscms_captcha_config.user_login')){
                        $verify_userlogin = 1;
                    }else{
                        $verify_userlogin = 0;
                    }
                }else{
                    $verify_userlogin = 1;
                }
            }else{
                $verify_userlogin = 0;
            }
            
            $this->ajaxReturn(0,$err,$verify_userlogin);
        }else{
            if($this->visitor->is_login){
                $urls = array('1'=>'company/index','2'=>'personal/index');
                $this->redirect($urls[C('visitor.utype')],array('uid'=>$this->visitor->info['uid']));
            }
            if(false === $oauth_list = F('oauth_list')){
                $oauth_list = D('Oauth')->oauth_cache();
            }
            $this->assign('oauth_list',$oauth_list);
            $this->assign('title','会员登录 - '.C('qscms_site_name'));
            $this->display();
        }
    }
    /**
     * 用户退出
     */
    public function logout() {
		$this->visitor->logout();
		//同步退出
		$passport = $this->_user_server();
		$synlogout = $passport->synlogout();
        $this->redirect('members/login');
    }
    /**
     * 兼职 门店 租房 普工 前台发布 登录会员类型选择
     */
    public function choose_members(){
        $this->display();
    }
    /**
     * 兼职 门店 租房 普工 前台发布 登录会员类型选择 选择个人
     */
    public function personal_add(){
        $data['uid'] = C('visitor.uid');
        $data['mobile'] = C('visitor.mobile');
        $data['utype'] = 2;
        $members = D('Members')->Where(array('utype'=>1,'mobile'=>$data['mobile']))->select();
        $members && $this->error("请勿重复选择会员类型");
        $utype=D('Members')->Where(array('uid'=>$data['uid']))->setField('utype',2);
        !$utype && $this->ajaxReturn(0,'更新会员类型失败');
        D('Members')->user_register($data);
        $this->visitor->login($data['uid'], $expire);
        $this->redirect('personal/index',array('uid'=>$data['uid']));
    }
    /**
     * 兼职 门店 租房 普工 前台发布 登录会员类型选择 选择企业
     */
    public function company_add(){
        $data['uid'] = C('visitor.uid');
        $data['mobile'] = C('visitor.mobile');
        $data['utype'] = 1;
        $members = D('Members')->Where(array('utype'=>2,'mobile'=>$data['mobile']))->select();
        $members && $this->error("请勿重复选择会员类型");
        if(IS_POST && IS_AJAX){
            $utype=D('Members')->Where(array('uid'=>$data['uid']))->setField('utype',1);
            !$utype && $this->ajaxReturn(0,'更新会员类型失败');
            $com_setarr['audit'] = 0;
            $com_setarr['companyname']=I('post.companyname','','trim,badword');
            $com_setarr['contact']=I('post.contact','','trim,badword');
            $com_setarr['telephone']=$data['mobile'];
            $com_setarr['uid'] =$data['uid'];
            $company_mod = D('CompanyProfile');
            if(false === $company_mod->create($com_setarr)) $this->ajaxReturn(0,$company_mod->getError());
            C('SUBSITE_VAL.s_id') && $company_mod->subsite_id = C('SUBSITE_VAL.s_id');
            $insert_company_id = $company_mod->add();
            if($insert_company_id){
                D('Members')->user_register($data);
                $this->visitor->login($data['uid'], $expire);
                $result['url'] = U('company/index');
                $this->ajaxReturn(1,'提交成功',$result);
            }
        }
        $this->display();
    }
    /**
     * [register 会员注册]
     */
    public function register(){
        if(!I('get.org','','trim') && C('PLATFORM') == 'mobile' && $this->apply['Mobile']){
            redirect(build_mobile_url(array('c'=>'Members','a'=>'register')));
        }
        if (C('qscms_closereg')){
            IS_AJAX && $this->ajaxReturn(0,'网站暂停会员注册，请稍后再次尝试！');
            $this->error("网站暂停会员注册，请稍后再次尝试！");
        }
        if(IS_POST && IS_AJAX){
            $data['reg_type'] = I('post.reg_type',0,'intval');//注册方式(1:手机，2:邮箱，3:微信)
            $array = array(1 => 'mobile',2 => 'email');
            if(!$reg = $array[$data['reg_type']]) $this->ajaxReturn(0,'正确选择注册方式！');
            $data['utype'] = I('post.utype',0,'intval');
            if($data['utype'] != 1 && $data['utype'] != 2) $this->ajaxReturn(0,'请正确选择会员类型!');
            if($data['reg_type'] == 1){
                $data['mobile'] = I('post.mobile',0,'trim');
            }
            if(C('qscms_register_password_open')){
                $data['password'] = I('post.password','','trim');
                !$data['password'] && $this->ajaxReturn(0,'请输入密码!');
                $passwordVerify = I('post.passwordVerify','','trim');
                $data['password'] != $passwordVerify && $this->ajaxReturn(0,'两次密码输入不一致!');
            }
            if('bind' == $ucenter = I('post.ucenter','','trim')){
                $uc_user = cookie('members_uc_info');
                $data = array_merge($data,$uc_user);
                $passwordVerify = $data['password'];
            }
            if($data['utype']==1){
                $com_setarr['audit'] = 0;
                $com_setarr['companyname']=I('post.companyname','','trim,badword');
                $com_setarr['contact']=I('post.contact','','trim,badword');
                $com_setarr['telephone']=I('post.mobile','','trim,badword');
                $company_mod = D('CompanyProfile');
                if(false === $company_mod->create($com_setarr)) $this->ajaxReturn(0,$company_mod->getError());
                $data = array_merge($data,$com_setarr);
            }
            $us = $uc_user ? 'default' : '';
            $passport = $this->_user_server($us);
            // 若手机号重复且勾选了解绑原帐号
            if($data['reg_type'] == 1 && $data['utype'] ==2){
                $data['unbind_mobile'] = I('post.unbind_mobile',0,'intval');
                if($data['unbind_mobile']){
                    $repeat = M('Members')->where(array('mobile'=>$data['mobile']))->select();
                    foreach ($repeat as $val){
                        if(false != D('UnbindMobile')->create($val)){
                            D('UnbindMobile')->add();
                        }
                        $update['mobile'] = '';
                        $update['mobile_audit'] = 0;
                        M('Members')->where(array('uid'=>$val['uid']))->save($update);
                    }
                }
            }
            if(false === $data = $passport->register($data)){
                if($user = $passport->get_status()) $this->ajaxReturn(1,'会员注册成功！',array('url'=>U('members/reg_email_activate',array('uid'=>$user['uid']))));
                $this->ajaxReturn(0,$passport->get_error());
            }
            // 添加企业信息
            if($data['utype']==1){
                $company_mod->uid=$data['uid'];
                C('SUBSITE_VAL.s_id') && $company_mod->subsite_id = C('SUBSITE_VAL.s_id');
                $insert_company_id = $company_mod->add();
                if($insert_company_id){
                    switch($com_setarr['audit']){
                        case 1:
                            $audit_str = '认证通过';break;
                        case 2:
                            $audit_str = '认证中';break;
                        case 3:
                            $audit_str = '认证未通过';break;
                        default:
                            $audit_str = '';break;
                    }
                    if($audit_str){
                        $auditsqlarr['company_id']=$insert_company_id;
                        $auditsqlarr['reason']='自动设置';
                        $auditsqlarr['status']=$audit_str;
                        $auditsqlarr['addtime']=time();
                        $auditsqlarr['audit_man']='系统';
                        M('AuditReason')->data($auditsqlarr)->add();
                    }
                }
            }
            if('bind' == I('post.org','','trim') && cookie('members_bind_info')){
                $user_bind_info = object_to_array(cookie('members_bind_info'));
                $user_bind_info['uid'] = $data['uid'];
                $oauth = new \Common\qscmslib\oauth($user_bind_info['type']);
                $oauth->bindUser($user_bind_info);
                $this->_save_avatar($user_bind_info['temp_avatar'],$data['uid']);//临时头像转换
                cookie('members_bind_info', NULL);//清理绑定COOKIE
            }
            session('reg_smsVerify',null);
            D('Members')->user_register($data);
            $incode = I('post.incode','','trim');
            //如果是推荐注册，赠送积分
            $this->_incode($incode);
            $this->_correlation($data);
            $points_rule = D('Task')->get_task_cache(2,1);
            $result['url'] = $data['utype']==2 ? U('personal/resume_add',array('points'=>$points_rule['points'],'first'=>1)) : U('members/index');
            $this->ajaxReturn(1,'会员注册成功！',$result);
        }
        /*else{
            $utype = I('get.utype',0,'intval');
            $utype == 0 && $type = 'reg';
            $utype == 1 && $type = 'reg_company';
            $utype == 2 && $type = 'reg_personal';
            //第三方登录
            if(false === $oauth_list = F('oauth_list')){
                $oauth_list = D('Oauth')->oauth_cache();
            }
            $this->assign('utype',$utype);//注册会员类型
            $this->assign('user_bind',$user_bind);
            $this->assign('oauth_list',$oauth_list);
            $this->assign('company_repeat',C('qscms_company_repeat'));//企业注册名称是否可以重复
            $this->_config_seo(array('title'=>'会员注册 - '.C('qscms_site_name')));
            $this->display($type);
        }*/
    }
    /**
     * [_incode 注册赠送积分]
     */
    protected function _incode($incode){
        if($incode){
            if(preg_match('/^[a-zA-Z0-9]{8}$/',$incode)){  
                $inviter_info = M('Members')->where(array('invitation_code'=>$incode))->find();
                if($inviter_info){
                    $task_id = $inviter_info['utype']==1?31:14;
                    D('TaskLog')->do_task($inviter_info,$task_id);
                }
            }
        }
    }
    
    /**
     * [send_sms 注册验证短信]
     */
    public function verify_sms(){
        $mobile=I('get.mobile','','trim');
        if(!fieldRegex($mobile,'mobile')) $this->ajaxReturn(0,'手机号格式错误！');
        $smsVerify = session('reg_smsVerify');
        if($mobile!=$smsVerify('mobile')) $this->ajaxReturn(0,'手机号不一致！');//手机号不一致
        if(time()>$smsVerify('time')+600) $this->ajaxReturn(0,'验证码过期！');//验证码过期
        $vcode_sms = I('get.mobile_vcode',0,'intval');
        $mobile_rand=substr(md5($vcode_sms), 8,16);
        if($mobile_rand!=$smsVerify('rand')) $this->ajaxReturn(0,'验证码错误！');//验证码错误！
        $this->ajaxReturn(1,'手机验证成功！');
    }
    // 注册发送短信/找回密码 短信
    public function reg_send_sms(){
        if(C('qscms_captcha_open') && C('qscms_captcha_config.varify_mobile') && true !== $reg = \Common\qscmslib\captcha::verify()) $this->ajaxReturn(0,$reg);
        if($uid = I('post.uid',0,'intval')){
            $mobile=M('Members')->where(array('uid'=>$uid))->getfield('mobile');
            !$mobile && $this->ajaxReturn(0,'用户不存在！');
        }else{
            $mobile = I('post.mobile','','trim');
            !$mobile && $this->ajaxReturn(0,'请填手机号码！');
        }
        if(!fieldRegex($mobile,'mobile')) $this->ajaxReturn(0,'手机号错误！');
        $sms_type = I('post.sms_type','reg','trim');
        $rand=getmobilecode();
        switch ($sms_type) {
            case 'reg':
                $sendSms['tpl']='set_register';
                $sendSms['data']=array('rand'=>$rand.'','sitename'=>C('qscms_site_name'));
                break;
            case 'gsou_reg':
                $sendSms['tpl']='set_register';
                $sendSms['data']=array('rand'=>$rand.'','sitename'=>C('qscms_site_name'));
                break;
            case 'getpass':
                $sendSms['tpl']='set_retrieve_password';
                $sendSms['data']=array('rand'=>$rand.'','sitename'=>C('qscms_site_name'));
                break;
            case 'login':
                if(!$uid=M('Members')->where(array('mobile'=>$mobile))->getfield('uid')){
                    if(false === $sitegroup_user = $passport->uc('sitegroup')->get($smsVerify['mobile'], 'mobile')){
                        $this->ajaxReturn(0,'您输入的手机号未注册会员');
                    }
                }
                $sendSms['tpl']='set_login';
                $sendSms['data']=array('rand'=>$rand.'','sitename'=>C('qscms_site_name'));
                break;
        }        
        $smsVerify = session($sms_type.'_smsVerify');
        if($smsVerify && $smsVerify['mobile']==$mobile && time()<$smsVerify['time']+180) $this->ajaxReturn(0,'180秒内仅能获取一次短信验证码,请稍后重试');
        $sendSms['mobile']=$mobile;
        if(true === $reg = D('Sms')->sendSms('captcha',$sendSms)){
            session($sms_type.'_smsVerify',array('rand'=>substr(md5($rand), 8,16),'time'=>time(),'mobile'=>$mobile));
            $this->ajaxReturn(1,'手机验证码发送成功！');
        }else{
            $this->ajaxReturn(0,$reg);
        }
    }
    /**
     * 检测用户信息是否存在或合法
     */
    public function ajax_check() {
        $type = I('post.type', 'trim', 'email');
        $param = I('post.param','','trim');
        if(in_array($type,array('username','mobile','email'))){
            $type != 'username' && !fieldRegex($param,$type) && $this->ajaxReturn(0,L($type).'格式错误！');
            $where[$type] = $param;
            $reg = M('Members')->field('uid,status')->where($where)->find();
            if($reg['uid'] && $reg['status'] != 0){
                $this->ajaxReturn(0,L($type).'已经注册');
            }else{
                $passport = $this->_user_server();
                $name = 'check_'.$type;
                if(false === $passport->$name($param)){
                    $this->ajaxReturn(0,$passport->get_error());
                }
            }
            $this->ajaxReturn(1);
        }elseif($type == 'companyname'){
            if(C('qscms_company_repeat')==0){
                $reg = M('CompanyProfile')->where(array('companyname'=>$param))->getfield('id');
                $reg ? $this->ajaxReturn(0,'企业名称已经注册') : $this->ajaxReturn(1);
            }else{
                $this->ajaxReturn(1);
            }
        }
    }
    /**
     * [waiting_weixin_login 循环检测微信是否扫码登录]
     */
    public function waiting_weixin_login(){
        $scene_id = session('login_scene_id');
        if($uid = F('/weixin/'.($scene_id%10).'/'.$scene_id)){
            if(false === $this->visitor->login($uid, 1)) $this->ajaxReturn(0,$this->visitor->getError());
            $urls = array('1'=>'company/index','2'=>'personal/index','3'=>'hunter/index','4'=>'train/index');
            $login_url = $url ? $url : U($urls[$this->visitor->info['utype']],array('uid'=>$this->visitor->info['uid']));
            session('login_scene_id',null);
            F('/weixin/'.($scene_id%10).'/'.$scene_id,null);
            $this->ajaxReturn(1,'微信扫码登录成功！',$login_url);
        }else{
            $this->ajaxReturn(0,'微信没有登录！');
        }
    }
    /**
     * [waiting_weixin_bind 循环检测微信是否扫码绑定]
     */
    public function waiting_weixin_bind(){
        $scene_id = session('bind_scene_id');
        if($openid = F('/weixin/'.($scene_id%10).'/'.$scene_id)){
            $reg = \Common\qscmslib\weixin::bind($openid,C('visitor'));
            $this->ajaxReturn($reg['state'],$reg['tip']);
        }else{
            $this->ajaxReturn(0,'微信没有绑定！');
        }
    }
    /**
     * [user_getpass 忘记密码]
     */
    public function user_getpass(){
        if(IS_POST){
            $type = I('post.type',0,'intval');
            $array = array(1 => 'mobile',2 => 'email');
            if(!$reg = $array[$type]) $this->error('请正确选择找回密码方式！');
            $retrievePassword = session('retrievePassword');
            if($retrievePassword['token'] != I('post.token','','trim')) $this->error('非法参数！');
            $mobile = I('post.mobile',0,'trim');
            if(!$user = M('Members')->field('uid,username')->where(array('mobile'=>$mobile,'mobile_audit'=>1))->find()) $this->error('该手机号没有绑定帐号！');
            $smsVerify = session('getpass_smsVerify');
            if($mobile != $smsVerify['mobile']) $this->error('手机号不一致！');//手机号不一致
            if(time()>$smsVerify['time']+600) $this->error('验证码过期！');//验证码过期
            $vcode_sms = I('post.mobile_vcode',0,'intval');
            $mobile_rand=substr(md5($vcode_sms), 8,16);
            if($mobile_rand!=$smsVerify['rand']) $this->error('验证码错误！');//验证码错误！
            $tpl = 'user_setpass';
            session('smsVerify',null);
        }
        $token=substr(md5(getmobilecode()), 8,16);
        session('retrievePassword',array('uid'=>$user['uid'],'token'=>$token));
        $this->assign('token',$token);
        $this->_config_seo(array('title'=>'找回密码 - '.C('qscms_site_name')));
        $this->display($tpl);
    }
     /**
     * [find_pwd 重置密码]
     */
    public function user_setpass(){
        if(IS_POST){
            $retrievePassword = session('retrievePassword');
            if($retrievePassword['token'] != I('post.token','','trim')) $this->error('非法参数！');
            $user['password']=I('post.password','','trim,badword');
            !$user['password'] && $this->error('请输入新密码！');
            if($user['password'] != I('post.password1','','trim,badword')) $this->error('两次输入密码不相同，请重新输入！');
            $passport = $this->_user_server();
            if(false === $passport->edit($retrievePassword['uid'],$user)) $this->error($passport->get_error());
            $tpl = 'user_setpass_sucess';
            session('retrievePassword',null);
        }else{
            parse_str(decrypt(I('get.key','','trim'),C('PWDHASH')),$data);
            !fieldRegex($data['e'],'email') && $this->error('找回密码失败,邮箱格式错误！','user_getpass');
            $end_time=$data['t']+24*3600;
            if($end_time<time()) $this->error('找回密码失败,链接过期!','user_getpass');
            $key_str=substr(md5($data['e'].$data['t']),8,16);
            if($key_str!=$data['k']) $this->error('找回密码失败,key错误!','user_getpass');
            if(!$uid = M('Members')->where(array('email'=>$data['e']))->getfield('uid')) $this->error('找回密码失败,帐号不存在!','user_getpass');
            $token=substr(md5(getmobilecode()), 8,16);
            session('retrievePassword',array('uid'=>$uid,'token'=>$token));
            $this->assign('token',$token);
        }
        $this->_config_seo(array('title'=>'找回密码 - '.C('qscms_site_name')));
        $this->display($tpl);
    }
    /**
     * 账号申诉
     */
    public function appeal_user(){
        $mod = D('MembersAppeal');
        if(IS_POST && IS_AJAX){
            if (false === $data = $mod->create()) {
                $this->ajaxReturn(0, $mod->getError());
            }
            if($this->apply['Subsite']){
                $where['mobile'] = I('post.mobile','','trim');
                $where['email'] = I('post.email','','trim');
                $where['_logic'] = 'OR';
                $subsite_id = M('Members')->where($where)->getfield('subsite_id');
                $data['subsite_id'] = $subsite_id?:(C('SUBSITE_VAL.s_id')?:0);
            }
            if (false !== $mod->add($data)) {
                $this->ajaxReturn(1, L('operation_success'));
            } else {
                $this->ajaxReturn(0, L('operation_failure'));
            }
        }
        $this->_config_seo(array('title'=>'账号申诉 - '.C('qscms_site_name')));
        $this->display();
    }
    /**
     * [binding ucenter绑定]
     */
    public function apilogin_ucenter(){
        $user_bind_info = object_to_array(cookie('members_uc_info'));
        if(!$this->visitor->is_login && !$user_bind_info) $this->redirect('members/login');
        $user_bind_info['keyavatar_big'] = attach('no_photo_male.png','resource');
        $this->assign('third_name','Ucenter');
        $this->assign('user_bind_info', $user_bind_info);
        $this->_config_seo();
        $this->display();
    }
    /**
     * [binding 第三方绑定]
     */
    public function apilogin_binding(){
        $user_bind_info = object_to_array(cookie('members_bind_info'));
        if(!$this->visitor->is_login && !$user_bind_info) $this->redirect('members/login');
        if(false === $oauth_list = F('oauth_list')){
            $oauth_list = D('Oauth')->oauth_cache();
        }
        $this->assign('third_name',$oauth_list[$user_bind_info['type']]['name']);
        $this->assign('user_bind_info', $user_bind_info);
        $this->_config_seo();
        $this->display();
    }
    /**
     * [oauth_reg 第三方登录注册]
     */
    public function oauth_reg(){
        if (cookie('members_bind_info')) {
            $user_bind_info = object_to_array(cookie('members_bind_info'));
        }else{
            $this->error('第三方授权失败，请重新操作！');
        }
        //第三方帐号绑定
        $username = I('post.username','','trim');
        $password = I('post.password','','trim');
        $passport = $this->_user_server();
        if(false === $uid = $passport->uc('default')->auth($username, $password)){
            if($err == L('auth_null') && $passport->is_sitegroup() && false !== $passport->uc('sitegroup')->auth($username, $password)){
                $sitegroup_user = $passport->_user;
                if($sitegroup_user['utype']==1){
                    $company_mod = D('CompanyProfile');
                    $company_mod->create($sitegroup_user);
                }
                if(false === $sitegroup_user = $passport->uc('default')->register($sitegroup_user)){
                    if($user = $passport->get_status()){
                        $uid = $user['uid'];
                        $email_activate = true;
                    }else{
                        $this->error($passport->get_error());
                    }
                }else{
                    $uid = $sitegroup_user['uid'];
                    // 添加企业信息
                    if($sitegroup_user['utype']==1){
                        $company_mod->uid=$sitegroup_user['uid'];
                        C('SUBSITE_VAL.s_id') && $company_mod->subsite_id = C('SUBSITE_VAL.s_id');
                        $insert_company_id = $company_mod->add();
                        if($insert_company_id){
                            switch($com_setarr['audit']){
                                case 1:
                                    $audit_str = '认证通过';break;
                                case 2:
                                    $audit_str = '认证中';break;
                                case 3:
                                    $audit_str = '认证未通过';break;
                                default:
                                    $audit_str = '';break;
                            }
                            if($audit_str){
                                $auditsqlarr['company_id']=$insert_company_id;
                                $auditsqlarr['reason']='自动设置';
                                $auditsqlarr['status']=$audit_str;
                                $auditsqlarr['addtime']=time();
                                $auditsqlarr['audit_man']='系统';
                                M('AuditReason')->data($auditsqlarr)->add();
                            }
                        }
                    }
                    D('Members')->user_register($sitegroup_user);
                    $this->_correlation($sitegroup_user);
                    $points_rule = D('Task')->get_task_cache(2,1);
                    $login = true;
                }
            }else{
                $this->error($passport->get_error());
            }
        }else{
            $user = $passport->_user;
            if($user['utype'] == 1 && !$user['sitegroup_uid'] || !$user['uc_uid']){
                $company = M('CompanyProfile')->field('companyname,contact,landline_tel')->where(array('uid'=>$user['uid']))->find();
                $user = array_merge($user,$company);
            }
            if(!$user['sitegroup_uid'] && $passport->is_sitegroup()){
                $temp = $passport->uc('sitegroup')->register($user);
                $temp && M('Members')->where(array('uid'=>$user['uid']))->setfield('sitegroup_uid',$temp['sitegroup_uid']);
            }
            if(!$user['uc_uid'] && !$passport->is_sitegroup() && $passport->is_uc()){
                $temp = $passport->uc('ucenter')->register($user);
                $temp && M('Members')->where(array('uid'=>$user['uid']))->setfield('uc_uid',$temp['uc_uid']);
            }
        }
        if(!$email_activate && !$login){
            if(false === $this->visitor->login($uid)) $this->error($this->visitor->getError());
            $passport->synlogin($uid);
        }
        $info = M('MembersBind')->where(array('type'=>$user_bind_info['type'],'uid'=>$uid))->find();
        if($info) $this->error('此会员已经绑定过第三方账号！');
        $oauth = new \Common\qscmslib\oauth($user_bind_info['type']);
        $bind_user = $oauth->_checkBind($user_bind_info['type'], $user_bind_info);
        if($bind_user['uid'] && $bind_user['uid'] != $uid) $this->error('此帐号已经绑定过本站！');
        $user_bind_info['uid'] = $uid;
        if(false === $oauth->bindUser($user_bind_info)) $this->error('帐号绑定失败，请重新操作！');
        if(!$this->visitor->get('avatars')) $this->_save_avatar($user_bind_info['temp_avatar'],$uid);//临时头像转换
        cookie('members_bind_info', NULL);//清理绑定COOKIE
        $urls = array(1=>'company/index',2=>'personal/index',3=>'members/reg_email_activate');
        $type = $email_activate ? 3 : $this->visitor->info['utype'];
        $this->redirect($urls[$type],array('uid'=>$uid));
    }
    /**
     * [_save_avatar 第三方头像保存]
     */
    protected function _save_avatar($avatar,$uid){
        if(!$avatar) return false;
        $path = C('qscms_attach_path').'avatar/temp/'.$avatar;
        $image = new \Common\ORG\ThinkImage();
        $date = date('ym/d/');
        $save_avatar=C('qscms_attach_path').'avatar/'.$date;//图片存储路径
        if(!is_dir($save_avatar)) mkdir($save_avatar,0777,true);
        $savePicName = md5($uid.time()).".jpg";
        $filename = $save_avatar.$savePicName;
        $size = explode(',',C('qscms_avatar_size'));
        copy($path, $filename);
        foreach ($size as $val) {
            $image->open($path)->thumb($val,$val,3)->save("{$filename}._{$val}x{$val}.jpg");
        }
        M('Members')->where(array('uid'=>$uid))->setfield('avatars',$date.$savePicName);
        @unlink($path);
    }
    /**
     * [save_username 修改帐户名]
     */
    public function save_username(){
        if(IS_POST){
            $user['username']=I('post.username','','trim,badword');
            $passport = $this->_user_server();
            if(false === $passport->edit(C('visitor.uid'),$user)) $this->ajaxReturn(0,$passport->get_error());
            $this->visitor->update();//刷新会话
            $this->ajaxReturn(1,'用户名修改成功！');
        }else{
            $data['html']=$this->fetch('ajax_modify_uname');
            $this->ajaxReturn(1,'修改用户名弹窗获取成功！',$data);
        }
    }
    /**
     * [save_password 修改密码]
     */
    public function save_password(){
        if(IS_POST){
            $oldpassword=I('post.oldpassword','','trim,badword');
            !$oldpassword && $this->ajaxReturn(0,'请输入原始密码!');
            $password=I('post.password','','trim,badword');
            !$password && $this->ajaxReturn(0,'请输入新密码！');
            if($password != I('post.password1','','trim,badword')) $this->ajaxReturn(0,'两次输入密码不相同，请重新输入！');
            $data['oldpassword'] = $oldpassword;
            $data['password'] = $password;
            $reg = D('Members')->save_password($data,C('visitor'));
            !$reg['state'] && $this->ajaxReturn(0,$reg['error']);
            $this->ajaxReturn(1,'密码修改成功！');
        }else{
            $data['html']=$this->fetch('ajax_modify_pwd');
            $this->ajaxReturn(1,'修改密码弹窗获取成功！',$data);
        }
    }
    
    /**
     * [user_mobile 获取手机验证弹窗]
     */
    public function user_mobile(){
        $audit = D('Members')->where(array('uid'=>C('visitor.uid')))->getField('mobile_audit');
        $this->assign('audit',$audit);
        $tpl=$this->fetch('ajax_auth_mobile');
        $this->ajaxReturn(1,'手机验证弹窗获取成功！',$tpl);
    }
    /**
     * [send_mobile_code 发送手机验证码]
     */
    public function send_mobile_code(){
        $mobile=I('post.mobile','','trim,badword');
        if(!fieldRegex($mobile,'mobile')) $this->ajaxReturn(0,'手机格式错误!');
        $user=M('Members')->field('uid,mobile,mobile_audit')->where(array('mobile'=>$mobile))->find();
        $user['uid'] && $user['uid']<>C('visitor.uid') && $this->ajaxReturn(0,'手机号已经存在,请填写其他手机号!');
        if($user['mobile'] && $user['mobile_audit'] == 1 && $user['mobile'] == $mobile) $this->ajaxReturn(0,"你的手机号 {$mobile} 已经通过验证！");
        if(session('verify_mobile.time') && (time()-session('verify_mobile.time'))<180) $this->ajaxReturn(0,'请180秒后再进行验证！');
        $rand=getmobilecode();
        $sendSms = array('mobile'=>$mobile,'tpl'=>'set_mobile_verify','data'=>array('rand'=>$rand.'','sitename'=>C('qscms_site_name')));
        if (true === $reg = D('Sms')->sendSms('captcha',$sendSms)){
            session('verify_mobile',array('mobile'=>$mobile,'rand'=>$rand,'time'=>time()));
            $this->ajaxReturn(1,'验证码发送成功！');
        }else{
            $this->ajaxReturn(0,$reg);
        }
    }
    /**
     * [verify_mobile_code 验证手机验证码]
     */
    public function verify_mobile_code(){
        $verifycode=I('post.verifycode',0,'intval');
        $verify = session('verify_mobile');
        if (!$verifycode || !$verify['rand'] || $verifycode<>$verify['rand']) $this->ajaxReturn(0,'验证码错误!');
        $setsqlarr['mobile'] = $verify['mobile'];
        $setsqlarr['mobile_audit']=1;
        $uid=C('visitor.uid');
        $user = M('Members')->where(array('uid'=>$uid,'mobile'=>$verify['mobile'],'mobile_audit'=>1))->find();
        if($user) $this->ajaxReturn(0,"你的手机 {$verify['mobile']} 已经通过验证！");
        $passport = $this->_user_server();
        if(false === $passport->edit($uid,$setsqlarr)) $this->ajaxReturn(0,'手机验证失败!');
        D('Members')->update_user_info($setsqlarr,C('visitor'));
        if(C('visitor.utype')=='1'){
            $r = D('TaskLog')->do_task(C('visitor'),22);
        }else{
            $r = D('TaskLog')->do_task(C('visitor'),7);
        }
        write_members_log(C('visitor'),'','手机验证通过（手机号：'.$verify['mobile'].'）');
        session('verify_mobile',null);
        $this->ajaxReturn(1,'手机验证通过!',array('mobile'=>$verify['mobile'],'points'=>$r['state']==1?$r['data']:0));
    }
    
    /**
     * [sign_in 签到]
     */
    public function sign_in(){
        if(IS_AJAX){
            $reg = D('Members')->sign_in(C('visitor'));
            if($reg['state']){
                write_members_log(C('visitor'),'','成功签到');
                $this->ajaxReturn(1,'成功签到！',$reg['points']);
            }else{
                $this->ajaxReturn(0,$reg['error']);
            }
        }
    }
    /**
     * 推荐注册
     */
    public function invitation_reg(){
        $taskid = C('visitor.utype')==1?31:14;
        $task_info = D('Task')->get_task_cache(C('visitor.utype'),$taskid);
        $this->assign('task_info',$task_info);
        $invitation_code = D('Members')->where(array('uid'=>C('visitor.uid')))->getField('invitation_code');
        $invitation_url = C('qscms_site_domain').U('Members/register',array('incode'=>$invitation_code));
        $this->assign('invitation_url',$invitation_url);
        if(C('visitor.utype')==1){
            $css = '../../public/css/company/company_ajax_dialog.css';
        }else{
            $css = '../../public/css/personal/personal_ajax_dialog.css';
        }
        $this->assign('css',$css);
        $html = $this->fetch('ajax_invitation_reg');
        $this->ajaxReturn(1,'获取数据成功！',$html);
    }
    /**
     * 获取注册协议
     */
    public function agreement(){
        $agreement = htmlspecialchars_decode(M('Text')->where(array('name'=>'agreement'))->getField('value'),ENT_QUOTES);
        $this->assign('agreement',$agreement);
        $tpl = $this->fetch('Members/agreement');
        $this->ajaxReturn(1,'获取数据成功！',$tpl);
    }
}
?>
