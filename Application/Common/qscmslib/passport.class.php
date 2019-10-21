<?php
/**
 * 用户基类
 *
 * @author andery
 */
namespace Common\qscmslib;
class passport
{
    private $source_arr = array(1=>'网页端',2=>'手机端',3=>'微信小程序');
    private $_error = 0;
    private $_us = null;
    private $_status = false;
    public $_user;
    public function __construct($name) {
        if(!$name || ($name && $name != 'default')){
            $name = $this->is_sitegroup() ? 'sitegroup' : ($this->is_uc() ? 'ucenter' : 'default');
        }
        include_once QSCMSLIB_PATH . 'passport/' . $name . '.php';
        $class = $name . '_passport';
        $this->_us  = new $class();
    }
    public function uc($name) {
        if(!$name || ($name && $name != 'default')){
            $name = $this->is_sitegroup() ? 'sitegroup' : ($this->is_uc() ? 'ucenter' : 'default');
        }
        include_once QSCMSLIB_PATH . 'passport/' . $name . '.php';
        $class = $name . '_passport';
        $this->_us  = new $class();
        return $this;
    }
    /**
     * [is_sitegroup 检测是否安装集群系统]
     */
    public function is_sitegroup(){
        if(C('qscms_sitegroup_open') && C('qscms_sitegroup_domain') && C('qscms_sitegroup_secret_key') && C('qscms_sitegroup_id')) return true;
        return false;
    }
    /**
     * [is_uc 检测是否安装UC]
     */
    public function is_uc(){
        if(C('apply.Ucenter') && C('qscms_uc_open')) return true;
        return false;
    }
    /**
     * 新注册用户名生成
     */
    protected function _uname($data){
        if($data['reg_type']==1){
            do {
                $data['username']= strtolower(C('qscms_reg_prefix')).substr($data['mobile'],-4).strtolower(get_rand_char(4));
                $exist_num = D('Members')->where(array('username'=>$data['username']))->count();
            } while ($exist_num>0);
            // 手机注册 手机验证状态
            $data['mobile_audit']=1;
        }elseif($data['reg_type']==2){
            $email_str = str_replace('@', '', $data['email']);
            $email_str = str_replace('.', '', $email_str);
            $data['username']= strtolower(C('qscms_email_reg_prefix').$email_str);
        }else{
            $data['username']=strtolower(C('qscms_third_reg_prefix').D('Members')->randstr());
        }
        return $data;
    }
    /**
     * [_password 微信注册用户密码生成]
     */
    public function _password($username){
        if(C('qscms_reg_password_tpye') == 1){
            $password = $username;
        }elseif(C('qscms_reg_password_tpye') == 2){
            $password = D('Members')->randstr();
        }else{
            $password = C('qscms_reg_weixin_password');
        }
        return $password;
    }
    /**
     * 注册新用户
     */
    public function register($data) {
        !$data['username'] && $data = $this->_uname($data);
        !$data['password'] && $data['password'] = $this->_password($data['username']);
        if (!$add_data = $this->_us->register($data)) {
            $this->_error = $this->_us->get_error();
            return false;
        }
        //添加到本地
        $reg = !$add_data['uid'] ? $this->_local_add($add_data) : $add_data;
        cookie('members_uc_info',NULL);
        if(false === $reg && $this->_error == L('members_unique_error_email')){
            $user = M('Members')->where(array('email'=>$add_data['email']))->find();
            if($user['status'] != 0 && $user['utype'] != 2) return false;
            $this->_status = $user;
        }
        return $reg;
    }

    /**
     * 修改用户资料
     * $force  是否强制修改
     */
    public function edit($uid,$data,$old_password='',$force = false) {
        if (!$edit_data = $this->_us->edit($uid,$data,$old_password,$force)) {
            $this->_error = $this->_us->get_error();
            return false;
        }
        //本地修改
        return $this->_local_edit($uid,$edit_data);
    }

    /**
     * 删除用户
     */
    public function delete($uids,$type) {
        if (!$this->_us->delete($uids,$type)) {
            $this->_error = $this->_us->get_error();
            return false;
        }
        return $this->_local_delete($uid);
    }
    /**
     * 获取用户信息
     */
    public function get($flag, $is_name) {
        return $this->_us->get($flag, $is_name);
    }
    /**
     * 登陆验证
     */
    public function auth($username,$password) {
        $this->_user = $this->_us->auth($username,$password,$regist);
        if (!$this->_user) {
            $this->_error = $this->_us->get_error();
            return false;
        }
        /*if (is_array($uid)) {
            $uid = $this->_local_sync($uid);
        }*/
        return $this->_user['uid'];
    }
    /**
     * [unbind_mobile 手机号解绑]
     */
    public function unbind_mobile($mobile){
        return $this->_us->unbind_mobile($mobile);
    }
    /**
     * [get_subsite 获取站群系统分站列表]
     */
    public function get_subsite(){
        return $this->_us->get_subsite();
    }
    /**
     * 同步登陆
     */
    public function synlogin($uid,$expire=0) {
        return $this->_us->synlogin($uid,$expire);
    }
    /**
     * 同步退出
     */
    public function synlogout() {
        return $this->_us->synlogout();
    }
    /**
     * 检测用户邮箱唯一
     */
    public function check_email($email) {
        return $this->_us->check_email($email);
    }
    /**
     * 检测手机唯一
     */
    public function check_mobile($mobile) {
        return $this->_us->check_mobile($mobile);
    }
    /**
     * 检测用户名唯一
     */
    public function check_username($username) {
        return $this->_us->check_username($username);
    }
    /**
     * 本地用户添加
     */
    private function _local_add($add_data) {
        $user_mod = D('Members');
        if (false !== $user_mod->create($add_data)){
            $user_mod->password= $user_mod->make_md5_pwd($add_data['password'],$user_mod->pwd_hash);
            $user_mod->invitation_code = $user_mod->randstr(8,true);
            C('SUBSITE_VAL') && $add_data['subsite_id'] = $user_mod->subsite_id = C('SUBSITE_VAL.s_id');
            $user_mod->reg_source = C('LOG_SOURCE')?C('LOG_SOURCE'):1;
            $user_mod->reg_source_cn = $this->source_arr[$user_mod->reg_source];
            if (!$uid = $user_mod->add()) {
                $this->_error = $user_mod->getError();
                return false;
            } else {
                //写入会员日志
                if($add_data['utype']) write_members_log(array('uid'=>$uid,'utype'=>$add_data['utype'],'username'=>$add_data['username']),'','用户注册');
                $add_data['uid'] = $uid;
                return $add_data;
            }
        }
        $this->_error = $user_mod->getError();
        return false;
    }
    /**
     * 本地用户编辑
     */
    private function _local_edit($uid, $data) {
        $user_mod = D('Members');
        $data['uid'] = $uid;
        if(false !== $user_mod->create($data)){
            if (isset($data['password'])){
                $data['pwd_hash'] = $data['pwd_hash']?:$user_mod->where(array('uid'=>$uid))->getfield('pwd_hash');
                $user_mod->password = $user_mod->make_md5_pwd($data['password'],$data['pwd_hash']);
            }
            if (false !== $user_mod->where(array('uid'=>$uid))->save()) {
                return true;
            }
            $this->_error = $user_mod->getError();
            return false;
        }
        $this->_error = $user_mod->getError();
        return false;
    }

    /**
     * 本地用户删除
     */
    private function _local_delete($uid) {
        return true;
    }

    private function _local_get($flag, $is_name = false) {
        if ($is_name) {
            $map = array('username' => $flag);
        } else {
            $map = array('uid' => intval($flag));
        }
        return M('Members')->where($map)->find();
    }

    /**
     * 本地用户同步
     */
    private function _local_sync($user_info) {
        $local_info = $this->_local_get($user_info['username'], true);
        if (empty($local_info)) {
            $local_info['uid'] = $this->_local_add($user_info); //新增本地用户
        } else {
            $this->_local_edit($local_info['uid'], $user_info); //更新本地用户
        }
        return $local_info['uid'];
    }
    public function get_error() {
        return $this->_error;
    }

    public function get_status(){
        return $this->_status;
    }
}