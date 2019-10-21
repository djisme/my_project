<?php
/**
 * 访问者
 *
 * @author andery
 */
namespace Common\qscmslib;
class user_visitor {
    protected $_session;
    public $is_login = false; //登陆状态
    public $info = null;
    public function __construct() {
        $this->_session = '_qscms'.md5(C('PWDHASH'));
        if (session('?'.$this->_session)) {
        	//已经登陆
        	$this->info = session($this->_session);
        	$this->is_login = true;
        } elseif ($user_info = (array)cookie($this->_session)) {
            $field = 'uid,utype,username,email,mobile,password,last_login_ip';
            C('apply.Subsite') && $field.=',subsite_id';
            $user_info = M('Members')->field($field)->where(array('uid'=>$user_info[md5('uid')], 'password'=>$user_info[md5('password')]))->find();
            if ($user_info) {
                $user_info['last_login_time'] = time();
                M('Members')->where(array('uid'=>$user_info['uid']))->setField('last_login_time',$user_info['last_login_time']);
                //记住登陆状态
                $this->assign_info($user_info);
                $this->is_login = true;
            }else{
                $this->is_login = false;
            }
        } else {
            $this->is_login = false;
        }
    }
    /**
     * 登陆会话
     */
    public function assign_info($user_info) {
        session($this->_session, $user_info);
        $this->info = $user_info;
        $this->is_login = true;
        // 登录自动刷新简历
        if (C('qscms_login_refresh_resume') && $user_info['utype'] == 2){
            $where['uid'] = $user_info['uid'];
            $where['def'] = 1;
            $where['audit'] = array('neq',3);
            $where['display'] = 1;
            $rid = M('Resume')->where($where)->getField('id');
            $rid && D('Resume')->refresh_resume($rid,$user_info);
        }
        if(C('qscms_rongyun_appkey') && C('qscms_rongyun_appsecret')){
            \Common\qscmslib\Rongyun::getToken($user_info['uid']);
        }
    }
    /**
     * 增加会话
     */
    public function assign_add($key,$val) {
    	$this->info[$key] = $val;
    	session($this->_session,$this->info);
    }
    /**
     * 记住密码
     */
    public function remember($user_info, $remember = true) {
        if ($remember) {
            $time = 3600 * 24 * 7; //7天记住密码
            cookie($this->_session, array(md5('uid')=>$user_info['uid'], md5('password')=>$user_info['password']), $time);
        }
    }
    /**
     * 获取用户信息
     */
    public function get($key = null) {
        $info = null;
        if (is_null($key) && $this->info['uid']) {
            $info = M('Members')->find($this->info['uid']);
        } else {
            if (isset($this->info[$key])) {
                return $this->info[$key];
            } else {
                //获取用户表字段
                $fields = M('Members')->getDbFields();
                if (!is_null(array_search($key, $fields))) {
                    $info = M('Members')->where(array('uid' => $this->info['uid']))->getField($key);
                }
            }
        }
        return $info;
    }
    /**
     * 登陆
     */
    public function login($uid,$remember = true) {
        $user_mod = M('Members');
        //更新用户信息
        $utype = $user_mod->where(array('uid'=>$uid))->getField('utype');
        $user_unlogin_remind_days = $utype==1?C('qscms_com_unlogin_remind_cycle'):C('qscms_per_unlogin_remind_cycle');
        $data = array('last_login_time'=>time(),'last_login_ip'=> get_client_ip(),'remind_email_time'=>strtotime("+".$user_unlogin_remind_days." day"),'remind_email_ex_time'=>0);
        $user_mod->where(array('uid' => $uid))->save($data);
        $field = 'uid,utype,username,email,mobile,password,last_login_time,last_login_ip';
        C('apply.Subsite') && $field.=',subsite_id';
        if(!$user_info = $user_mod->field($field)->find($uid)){
            $this->_error = L('login_failed');
            return false;
        }
        //保持状态
        $this->assign_info($user_info);
        $this->remember($user_info, $remember);
        //写入会员日志
        $log_source = C('PLATFORM')=='mobile' ? '触屏版' : '网页版';
        write_members_log($user_info,'login','用户登录',$log_source);
        //记录路由
        C('apply.Analyze') && $this->record_route_group($user_info);
    }
    /**
     * 记录路由组
     */
    public function record_route_group($user){
        session('last_page_info',null);
        session('route_group_id',null);
        $logincount = D('MembersLog')->where(array('log_uid'=>$user['uid'],'log_type'=>'login','log_addtime'=>array('between',array(strtotime('today'),strtotime('tomorrow')))))->count();
        $data['utype'] = $user['utype'];
        $data['name'] = date('Y年m月d日').'第'.$logincount.'次登录';
        $data['uid'] = $user['uid'];
        $data['username'] = $user['username'];
        $data['addtime'] = time();
        $data['endtime'] = 0;
        $data['during'] = 0;
        C('apply.Subsite') && $data['subsite_id'] = $this->info['subsite_id'];
        $insert_id = M('MembersRouteGroup')->add($data);
        if($insert_id){
            session('route_group_id',$insert_id);
        }
    }
    /**
     * [assign_update 刷新session]
     */
    public function update(){
        $field = 'uid,utype,username,password,email,mobile,last_login_time';
        C('apply.Subsite') && $field.=',subsite_id';
        $user_info = M('Members')->field($field)->where(array('uid'=>$this->info['uid']))->find();
        if($user_info) {
            //记住登陆状态
            $this->assign_info($user_info);
            cookie($this->_session,array(md5('uid')=>$user_info['uid'], md5('password')=>$user_info['password']));
        }
    }
    public function getError(){
        return $this->_error;
    }
    /**
     * 退出
     */
    public function logout() {
        C('visitor',null);
        $this->info = null;
        $this->is_login = false;
        session($this->_session, null);
        cookie($this->_session, null);
    }
}