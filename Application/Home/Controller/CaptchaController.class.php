<?php
namespace Home\Controller;
use Common\Controller\FrontendController;
class CaptchaController extends FrontendController{
    public function _empty() {
        $type = I('get.type','pc','trim');
        \Common\qscmslib\captcha::generate($type);
    }
    /*
	校验验证码
    */
    public function checkCode(){
        $type = I('get.type','pc','trim');
        if(true === $reg = \Common\qscmslib\captcha::verify($type)){
            $this->ajaxReturn(1,'验证通过！');
        }else{
            $this->ajaxReturn(0,$reg);
        }
    }
}