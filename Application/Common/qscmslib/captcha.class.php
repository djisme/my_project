<?php

/**
 * 第三方验证码
 *
 * @author andery
 */
namespace Common\qscmslib;
class captcha {
    static function generate($type='pc',$verifyName='verify'){
        $captcha =  C('qscms_captcha');
        if(!$captcha) exit('验证码错误，请先配置验证参数！');
        $GtSdk = new \Common\qscmslib\captcha\GeetestLib($captcha['id'], $captcha['key']);
        $randval = \Common\ORG\String::randString(4);
        $data = array(
            'user_id' => $randval,
            'client_type' => $type == 'pc' ? 'web' : 'h5', #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            'ip_address' => get_client_ip()
        );
        session($verifyName, array('id'=>$randval,'gtserver'=>$GtSdk->pre_process($data,1)));
        echo $GtSdk->get_response_str();
    }
    static function verify($type='pc',$verifyName='verify'){
        $captcha = C('qscms_captcha');
        if(!$captcha) return '验证码错误，请先配置验证参数！';
        $GtSdk = new \Common\qscmslib\captcha\GeetestLib($captcha['id'], $captcha['key']);
        $session = session($verifyName);
        $data = array(
            'user_id' => $session['id'],
            'client_type' => $type == 'pc' ? 'web' : 'h5', #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            'ip_address' => get_client_ip()
        );
        if ($session['gtserver'] == 1) {
            if ($result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data)) {
                session($verifyName,NULL);
                return true;
            } else{
                return '验证码错误！';
            }
        }else{
            if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
                session($verifyName,NULL);
                return true;
            }else{
                return '验证码错误！';
            }
        }
    }
}