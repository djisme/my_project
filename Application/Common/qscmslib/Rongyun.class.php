<?php
namespace Common\qscmslib;
use Common\qscmslib\RongyunServerAPI;
class Rongyun
{
    public static function getToken($uid)
    {
        $token = M('RongyunToken')->find($uid);
        if(!$token){
        	$serverApi = new RongyunServerAPI(C('qscms_rongyun_appkey'),C('qscms_rongyun_appsecret'));
            $userInfo = D('Members')->find($uid);
        	if($userInfo['avatars']==''){
                $members_info = D('Resume')->where(array('uid'=>$uid,'def'=>1))->find();
                $avatar_default = $members_info['sex']==1?'no_photo_male.png':'no_photo_female.png';
                $userInfo['avatars']=C('qscms_site_domain').rtrim(C('qscms_site_dir'),'/').attach($avatar_default,'resource');
			}
			$resultJson = $serverApi->getToken($uid,$userInfo['username'],$userInfo['avatars']);
			$resultArr = json_decode($resultJson,1);
			Rongyun::recordToken($uid,$resultArr['token']);
			return $resultArr['token'];
        }else{
        	return $token['token'];
        }
    }
    public static function recordToken($uid,$token){
        $data['uid'] = $uid;
        $data['token'] = $token;
        M('RongyunToken')->add($data);
		return true;
    }
    public static function userRefresh($uid,$username){
    	Rongyun::getToken($uid);
    	$serverApi = new RongyunServerAPI(C('qscms_rongyun_appkey'),C('qscms_rongyun_appsecret'));
        $userInfo = D('Members')->find($uid);
        if($userInfo['avatars']==''){
            $members_info = D('Resume')->where(array('uid'=>$uid,'def'=>1))->find();
            $avatar_default = $members_info['sex']==1?'no_photo_male.png':'no_photo_female.png';
            $avatar=C('qscms_site_domain').rtrim(C('qscms_site_dir'),'/').attach($avatar_default,'resource');
        }else{
            $avatar = $userInfo['avatars'];
        }
    	$serverApi->userRefresh($uid,$username,$avatar);
    	return true;
    }
}
?>