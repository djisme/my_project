<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class OauthController extends BackendController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Oauth');
    }
    public function _after_select($info){
        $info['config'] = unserialize($info['config']);
        return $info;
    }
    public function _before_update($data){
        $data['config'] = serialize($data['config']);
        return $data;
    }
    /**
     * qq账号登录
     */
    public function index(){
        $_GET['id'] = 2;
        $this->edit();
    }
    /**
     * 新浪微博账号登录
     */
    public function sina(){
        $_GET['id'] = 1;
        $this->edit();
    }
    /**
     * 淘宝账号登录
     */
    public function taobao(){
        $_GET['id'] = 3;
        $this->edit();
    }
    /**
     * 微信账号登录
     */
    public function weixin(){
        $_GET['id'] = 4;
        $user = M('MembersBind')->where(array('type'=>'weixin','unionid'=>''))->find();
        if(IS_POST && $user && 1 == I('request.status',0,'intval')){
            $this->error('请先同步微信数据！');
        }
        $this->assign('is_sync',$user?1:0);
        $this->edit();
    }
}