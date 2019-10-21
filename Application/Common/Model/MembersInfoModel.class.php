<?php
namespace Common\Model;
use Think\Model;
class MembersInfoModel extends Model{
	protected $_user = array();
	protected $_validate = array(
		array('uid,realname,sex,birthday,education,experience,phone','identicalNull','',0,'callback'),
		array('uid,sex,education,major,experience,marriage,height','identicalEnum','',2,'callback'),
		array('phone','mobile','{%members_info_error_telephone}',2), // 联系电话
		array('email','email','{%members_info_error_email}',2), // 联系邮箱
		array('realname','1,60','{%members_info_length_error_realname}',2,'length'),
		array('residence','0,60','{%members_info_length_error_residence}',2,'length'),
		array('householdaddress','1,60','{%members_info_length_error_householdaddress}',2,'length'),
		array('height','0,3','{%members_info_length_error_height}',0,'length'), // 身高
		//array('phone','_repetition_mobile','{%members_info_repetition_mobile}',2,'callback'),
		//array('email','_repetition_email','{%members_info_repetition_email}',2,'callback'),
		array('qq','qq','{%members_info_error_qq}',2),
		array('qq','0,11','{%members_info_error_qq}',2,'length'),
		array('weixin','4,30','{%members_info_length_error_weixin}',2,'length'),
	);
	protected $_auto = array (
		array('height',0,3),
		array('photo_audit',1),
	);
	protected function _repetition_email($data){
		$uid = M('Members')->where(array('email'=>$data))->getfield('uid');
		if($uid && $uid != $this->_user['uid']) return false;
		return true;
	}
	protected function _repetition_mobile($data){
		$uid = M('Members')->where(array('mobile'=>$data))->getfield('uid');
		if($uid && $uid != $this->_user['uid']) return false;
		return true;
	}
	/*
		获取用户信息
		@data array 
		例如 arary('uid'=>1)
	*/
	public function get_userprofile($data){
		$info = $this->where($data)->find();
		if($info){
			$avatar = D('Members')->where(array('uid'=>$info['uid']))->getField('avatars');
			$avatar_default = $info['sex']==1?'no_photo_male.png':'no_photo_female.png';
			if (!$avatar)
	        {
	            $info['avatar'] = attach($avatar_default,'resource');
	        }
	        else
	        {
	            $info['avatar'] = attach($avatar,'avatar');
	        }
		}
        return $info;
	}

	/*
		添加用户信息
	*/
	public function add_userprofile($data,$user){
		$this->_user = $user;
		if(false === $this->create($data)) return array('state'=>0,'error'=>$this->getError());
		if(false === $this->add()) return array('state'=>0,'error'=>'用户信息添加失败！');
		$user = D('Members')->get_user_one(array('uid'=>$data['uid']));
        D('TaskLog')->do_task($user,2);
		return array('state'=>1);
	}
	/**
	 * [save_userprofile 修改用户信息]
	 * @param  [type] $data [新用户数据]
	 * @param  [type] $user [当前登录用户信息]
	 */
	public function save_userprofile($data,$user){
		$this->_user = $user;
		if(false === $this->create($data)) return array('state'=>0,'error'=>$this->getError());
		if(false === $this->where(array('uid'=>$user['uid']))->save()) array('state'=>0,'error'=>'用户信息更新失败！');
		return array('state'=>1);
	}
}
?>