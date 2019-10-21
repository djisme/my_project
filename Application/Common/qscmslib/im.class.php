<?php
/**
 * 第三方即时通讯
 *
 * @author andery
 */
namespace Common\qscmslib;
class im {
    private $_error = '';
    public function __construct() {
        $this->_appKey = C('qscms_rongyun_appkey');
        $this->_appSecret = C('qscms_rongyun_appsecret');
        include_once QSCMSLIB_PATH . 'rongcloud/rongcloud.php';
        $om_class = 'RongCloud';
        $this->_om = new $om_class($this->_appKey, $this->_appSecret);
    }
    public function token($user){
        $userT = M('RongyunToken')->where(array('uid'=>$user['uid']))->find();
        if(!$userT || !$userT['token']){
            $reg = $this->_om->user()->getToken($user['uid'], $user['username'], $user['avatars']);
            $reg = json_decode($reg,true);
            if($reg['code'] == 200 && $token = $reg['token']){
                if($userT){
                    M('RongyunToken')->where(array('uid'=>$user['uid']))->setfield('token',$reg['token']);
                }else{
                    M('RongyunToken')->add(array('uid'=>$user['uid'],'token'=>$reg['token']));
                }
            }
        }else{
            $token = $userT['token'];
        }
        return $token;
    }
    public function checkOnline($uid){
        $reg = $this->_om->user()->checkOnline($uid);
        $reg = json_decode($reg,true);
        if($reg['code'] == 200){
            return $reg['status'];
        }
    }
    /**
     * 错误
     */
    public function getError(){
        return $this->_error;
    }
}