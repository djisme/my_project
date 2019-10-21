<?php
// +----------------------------------------------------------------------
// | 74CMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.74cms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 
// +----------------------------------------------------------------------
// | ModelName: 二维码生成
// +----------------------------------------------------------------------
namespace Home\Controller;
use Common\Controller\FrontendController;
class QrcodeController extends FrontendController{
	/**
	 * [index 跟据传值，生成二维码]
	 */
	public function index(){
		if($url = I('get.url','','trim')){
            ob_clean();
            $url = htmlspecialchars_decode($url,ENT_QUOTES);
            $download = I('get.download',0,'intval');
            $code = new \Common\ORG\Qrcode();
            if($download==1){
                header("Content-type:application/x-png");
                header("Content-Disposition:attachment;filename=二维码.png");
                echo $code->png($url, false, 'H', 4,1);
            }else{
                $code->png($url, false, 'H', 8,1);
            }
		}
	}
	/**
     * [get_weixin_qrcode 微信二维码生成，注册/登录/绑定]
     */
    public function get_weixin_qrcode(){
        if(!C('qscms_weixin_apiopen')) $this->ajaxReturn(0,'未配置微信参数！');
        $type = I('get.type','login','trim');
        !in_array($type,array('reg','login','bind')) && $this->ajaxReturn(0,'请正确选择二维码生成类型！');
        if($type == 'reg'){
            !C('qscms_weixin_scan_reg') && $this->ajaxReturn(0,'微信二维码注册未开启！');
            $this->ajaxReturn(1,'微信注册二维码生成！',\Common\qscmslib\weixin::qrcode_img('register',140,140));
        }elseif($type == 'login'){
            !C('qscms_weixin_scan_login') && $this->ajaxReturn(0,'微信二维码登录未开启！');
            $this->ajaxReturn(1,'微信登录二维码生成！',\Common\qscmslib\weixin::qrcode_img('login',140,140));
        }else{
            !C('qscms_weixin_scan_bind') && $this->ajaxReturn(0,'微信二维码绑定未开启！');
            $this->ajaxReturn(1,'微信绑定二维码生成！',\Common\qscmslib\weixin::qrcode_img('bind',240,240));
        }
    }
    public function get_font_img(){
        $str = I('request.str','','trim');
        $str = decrypt($str,C('PWDHASH'));
        \Common\ORG\Image::buildString($str,array(100,50),'','png',0,false);
    }
}
?>