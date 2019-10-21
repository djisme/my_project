<?php

class ucenter_passport
{

    private $_error = 0;

    public function __construct() {
        include_once (APP_PATH . 'Ucenter/Conf/uc.php');
        include_once (APP_PATH . 'Ucenter/qscmslib/Api/uc_client/client.php');
    }
    /**
     * 获取插件基本信息
     */
    public function get_info() {
        return array(
            'code' => 'ucenter', //插件代码必须和文件名保持一致
            'name' => 'UCenter', //整合插件名称
            'desc' => 'UCenter',
            'version' => '3.2', //整合插件的版本
            'author' => '74cms', //开发者
            // 插件默认配置
            'config' => array(
                'uc_config' => array(
                    'text' => '应用的 UCenter 配置信息',
                    'type' => 'textarea',
                    'width' => '400',
                    'height' => '250',
                )
            )
        );
    }

    private function _ucenter_init() {
        //$conf = C('qscms_integrate_config');
        //eval($conf['uc_config']);
    }

    /**
     * 注册新用户
     */
    public function register($data) {
        $uc_uid = uc_user_register($data['username'], $data['password'], $data['email']);
        if ($uc_uid < 0) {
            switch ($uc_uid) {
                case -1:
                    $this->_error = '用户名不合法';
                    break;
                case -2:
                    $this->_error = '用户名中包含不允许注册的词语';
                    break;
                case -3:
                    $this->_error = '该用户名已经被注册';
                    break;
                case -4:
                    $this->_error = '邮箱格式错误';
                    break;
                case -5:
                    $this->_error = '您的邮箱地址不允许注册';
                    break;
                case -6:
                    $this->_error = '该邮箱已被注册';
                    break;
            }
            return false;
        }
        //返回本地数据
        $data['uc_uid'] = $uc_uid;
        return $data;
    }

    /**
     * 编辑用户信息
     */
    public function edit($uid,$data,$old_password,$force = false) {
        // UCenter修改
        $new_pwd = $new_email = '';
        if (isset($data['password'])) {
            $new_pwd  = $data['password'];
        }
        if (isset($data['email'])) {
            $new_email = $data['email'];
        }
        $uc_uid = M('user')->where(array('id'=>$uid))->getField('uc_uid');
        $info = $this->get($uc_uid);
        if (empty($info)) {
            $this->_error('no_such_user');
            return false;
        }
        $result = uc_user_edit($info['username'], $old_password, $new_pwd, $new_email, $force);
        if ($result != 1) {
            switch ($result) {
                case 0:
                case -7:
                    break;
                case -1:
                    $this->_error = L('auth_failed');
                    break;
                case -4:
                    $this->_error = L('email_error');
                    break;
                case -5:
                    $this->_error = L('blocked_email');
                    break;
                case -6:
                    $this->_error = L('email_exists');
                    break;
                case -8:
                    $this->_error = L('user_protected');
                    break;
                default:
                    $this->_error = L('unknow_error');
                    break;
            }
            return false;
        }
        //修改本地
        if (isset($data['password'])) {
            $data['password'] = md5($data['password']);
        }
        return $data;
    }
    /**
     * [unbind_mobile 手机号解绑]
     */
    public function unbind_mobile($mobile){
        return true;
    }
    /**
     * 删除用户
     */
    public function delete() {
        return true;
    }

    public function get($flag, $is_name = false) {
        $user_info = uc_get_user($flag, $is_name);
        if (empty($user_info)) {
            $this->_error = '用户不存在';
            return false;
        }
        list($uc_uid, $username, $email) = $user_info;
        $uid = M('user')->where(array('uc_uid'=>$uc_uid))->getField('id');
        return array(
            'uid' => $uid,
            'username' =>  $username,
            'email'     =>  $email,
            'uc_uid' => $uc_uid
        );
    }

    /**
     * 验证用户
     */
    public function auth($username, $password) {
        $result = uc_user_login($username, $password);
        if ($result[0] < 0) {
            switch ($result[0]) {
                case -1:
                    $this->_error = '用户不存在';
                    break;
                case -2:
                    $this->_error = '密码错误';
                    break;
                case -3:
                    $this->_error = '安全问题错误';
                    break;
                default:
                    $this->_error = '未知错误';
                    break;
            }
            return false;
        }
        return array('uc_uid'=>$result[0], 'username'=>$result[1], 'password'=>$result[2], 'email'=>$result[3]);
    }
    /**
     * [get_subsite 获取站群系统分站列表]
     */
    public function get_subsite(){
        return true;
    }
    /**
     * 同步登陆
     */
    public function synlogin($uid) {
        $uc_uid = M('Members')->where(array('uid'=>$uid))->getField('uc_uid');
        $synlogin = uc_user_synlogin($uc_uid);
        cookie('members_uc_action',$synlogin);
        return $synlogin;
    }

    public function synlogout() {
        $synlogin = uc_user_synlogout();
        cookie('members_uc_action',$synlogin);
        return $synlogin;
    }
    /**
     * 检测用户邮箱唯一
     */
    public function check_email($email) {
        $ucresult = uc_user_checkemail($email);
        if($ucresult > 0) return true;
        if($ucresult == -4) {
            $this->_error = '邮箱格式有误';
        } elseif($ucresult == -5) {
            $this->_error = '邮箱不允许注册';
        } elseif($ucresult == -6) {
            $this->_error = '该邮箱已经被注册';
        }
        return false;
    }
    /**
     * 检测手机唯一
     */
    public function check_mobile($mobile) {
        return $mobile;
    }
    /**
     * 检测用户名唯一
     */
    public function check_username($username) {
        $ucresult = uc_user_checkname($username);
        if($ucresult > 0) return true;
        if($ucresult == -1) {
            $this->_error = '用户名不合法';
        } elseif($ucresult == -2) {
            $this->_error = '包含要允许注册的词语';
        } elseif($ucresult == -3) {
            $this->_error = '用户名已经存在';
        }
        return false;
    }
    public function get_error() {
        return $this->_error;
    }
}