<?php
namespace Common\Model;
use Think\Model;
class MembersModel extends Model{
	protected $_validate = array(
		array('username,password,consultant,remind_email_time,imei','identicalNull','',0,'callback'),
		array('username','_username_length','{%members_format_error_username}',0,'callback'),
		array('password','3,18','{%members_format_error_password}',2,'length'),
		array('mobile','mobile','{%members_format_error_mobile}',2),
		array('email','email','{%members_format_error_email}',2),
		array('repassword','password','{%members_format_error_repassword}',0,'confirm'),

		//新加数据时验证手机号或邮箱号必填一项
		//array('mobile,email','requireone','{%members_unique_error_mobile_email}',0,'function',1),
		array('mobile','','{%members_unique_error_mobile}',2,'unique'),
		//array('email','','{%members_unique_error_email}',2,'unique'), 
		array('username','','{%members_unique_error_username}',0,'unique'),
	);
	protected $_auto = array (
		array('pwd_hash','randstr',1,'callback'),
		array('reg_time','time',1,'function'),
		array('reg_ip','get_client_ip',1,'callback'),
		array('reg_address','get_address',1,'callback'),
		array('email_audit',0),//邮箱验证
		array('mobile_audit',0),//手机验证
		array('weixin_audit',0),//微信是否绑定
		array('status',1),//会员状态
		array('robot',0),//是否是采集
		array('sms_num',0),//短信数量
	);
	protected function _username_length($data){
		$leng = mb_strlen($data,'utf-8');
		if(3 <= $leng && $leng <= 18) return true;
		return false;
	}
	/*
		获取IP地址以及端口号
	*/
	protected function get_client_ip($type = 0,$adv=false) {
	    $type       =  $type ? 1 : 0;
	    static $ip  =   NULL;
	    if ($ip !== NULL) return $ip[$type];
	    if($adv){
	        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	            $pos    =   array_search('unknown',$arr);
	            if(false !== $pos) unset($arr[$pos]);
	            $ip     =   trim($arr[0]);
	        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
	            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
	        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
	            $ip     =   $_SERVER['REMOTE_ADDR'];
	        }
	    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
	        $ip     =   $_SERVER['REMOTE_ADDR'];
	    }
	    // IP地址合法验证
	    $long = sprintf("%u",ip2long($ip));
	    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	    $port = $_SERVER['REMOTE_PORT'];
	    return $ip[$type].':'.$port;
    }
	/*
		根据ip 获取地址
	*/
	protected function get_address()
	{
		$Ip = new \Common\ORG\IpLocation('UTFWry.dat');
		$rst = $Ip->getlocation();
		return $rst['country'];
	}
	/*
		pwd_hash
		获取随机字符串
	*/
	public function randstr($length=6,$no_special_sign=false)
	{
		$hash='';
		$chars= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'; 
		if(!$no_special_sign){
			$chars .= '@#!~?:-='; 
		}
		$max=strlen($chars)-1;   
		mt_srand((double)microtime()*1000000);   
		for($i=0;$i<$length;$i++)   {   
			$hash.=$chars[mt_rand(0,$max)];   
		}   
		return $hash;
	}
	/**
	 * [make_md5_pwd 密码生成]
	 * @param  [type] $password [前台输入密码]
	 * @param  [type] $randstr  [随机码]
	 */
	public function make_md5_pwd($password,$randstr){
		$encrypt_method = QSCMSLIB_PATH.'encrypt_method/'.C('PWD_ENCRYPT_METHOD').'.php';
		require($encrypt_method);
		return $return;
	}
	/*
		注册送福利
		@data array
	*/
	public function user_register($user){
		$userinfo =$this->get_user_one(array('uid'=>$user['uid']));
		D('MembersPoints')->add_members_points($user['uid'],0);
        D('MembersSetmeal')->add_members_setmeal($user['uid'],0);
		if($user['utype'] == 1){
			// 企业分配顾问
			D('Consultant')->set_consultant($user);
			D('MembersSetmeal')->set_members_setmeal($user['uid'],C('qscms_reg_service'));
			D('TaskLog')->do_task($userinfo,17);
			if($user['mobile_audit'] && $user['mobile']){
				D('TaskLog')->do_task($user,22);
			}
			$setsqlarr['tplid'] = 5;
			$setsqlarr['uid'] = $user['uid'];
			D('CompanyTpl')->add_company_tpl($setsqlarr);
		}elseif($user['utype'] == 2){
			D('TaskLog')->do_task($userinfo,1);
			if($user['mobile_audit'] && $user['mobile']){
				D('TaskLog')->do_task($user,7);
			}
			$setsqlarr['tplid'] = 1;
			$setsqlarr['uid'] = $user['uid'];
			D('ResumeTpl')->add_resume_tpl($setsqlarr);
		}
	}
	/**
	 * 	更新会员数据,同步简历信息(送福利)
	 * 	$data  数组
	 *  	mobile/telephone/phone            手机/联系电话
	 *  	email                             邮箱
	 *  	email_audit                       邮箱认证
	 *  	mobile_audit                      手机认证
	 *  	realname/fullname                 真实姓名
	 *  	display_name                      姓名显示方式(1:完全公开，2:显示编号，3:隐藏姓名)
	 *  	photo_display                     头像是否公开(1:公开，2:保密)
	 *  	sex                               性别(1:男，2:女)
	 *  	birthday                          出生年
	 *  	residence                         现居住地
	 *  	education                         学历
	 *  	major                             专业
	 *  	experience                        工作经验
	 *  	height                            身高
	 *  	householdaddress                  籍贯
	 *  	marriage                          婚姻状况
	 *  $user   数组    当前登录用户信息(uid,utype,username,avatars,mobile,email,email_audit,mobile_audit,status)
	 *  $where  数组    不为空时，可针对性修改某一条简历及相关索引表
	 *  return true/错误
	*/
	public function update_user_info($data,$user,$where){
		if(!$data) return '更新数据不能为空！';
		$options = array('mobile','telephone','phone','email','email_audit','mobile_audit','realname','fullname','display_name','photo','photo_display','photo_audit','sex','birthday','birthdate','residence','education','major','experience','height','householdaddress','marriage','photo_img','avatars','qq','weixin');
		if($where){
			$only = array('current');
			$options = array_merge($options,$only);
		}else{
			$only = array();
		}
		$this->_sync(array('mobile','telephone','phone'),$data);
		$this->_sync(array('realname','fullname'),$data);
		$this->_sync(array('birthday','birthdate'),$data);
		$this->_sync(array('photo_img','avatars'),$data);
		foreach($options as $val){
			$data[$val] = $data[$val];
		}
		if(!$data) return '更新数据不能为空！';
		$d = array();
		//更新用户表
		foreach(array('mobile','email','email_audit','mobile_audit','avatars') as $val){
			$data[$val] && $d[$val] = $data[$val];
		}
		if(!$d['mobile_audit']) unset($d['mobile']);//只有当手机号认证通过时才可添加到members表
		if($d){
			if(false === $this->where(array('uid'=>$user['uid']))->save($d)) return '用户表更新失败！';
			$visitor = new \Common\qscmslib\user_visitor();//刷新会话
			$visitor->update();
			$d = array();
		}
		//获取枚举类数据对应中文内容
		$category = D('Category')->get_category_cache();
		if($data['sex']){
			$sex = array('1'=>'男','2'=>'女');
        	$data['sex_cn']=$sex[$data['sex']];
		}
		if($data['marriage']){
			$marriage = array('1'=>'未婚','2'=>'已婚','3'=>'保密');
			$data['marriage_cn']=$marriage[$data['marriage']];
		}
		if($data['experience']){
			$data['experience_cn']=$category['QS_experience'][$data['experience']];
		}
		if($data['education']){
			$data['education_cn']=$category['QS_education'][$data['education']];
		}
		if($data['major']){
			$major_category = D('CategoryMajor')->get_major_list();
			$data['major_cn'] = $major_category[$data['major']]['categoryname'];
		}
		if($data['current']){
			$data['current_cn'] = $category['QS_current'][$data['current']];
		}

		$temp = array();
		foreach(array('email','display_name','photo_display','photo_audit','sex','sex_cn','residence','education','education_cn','major','major_cn','experience','experience_cn','height','marriage','marriage_cn','householdaddress','qq','weixin') as $val){
			isset($data[$val]) && $temp[$val] = $data[$val];
		}
		
		$where['uid'] = $user['uid'];
		if($user['utype'] == 2){
			//更新简历表 
			foreach(array_merge(array('telephone','fullname','birthdate','photo_img','photo','photo_audit','photo_display','mobile_audit','qq','weixin'),$only) as $val){
				isset($data[$val]) && $d[$val] = $data[$val];
			}
			if($d = array_merge($d,$temp)){
				$resume_list = M('Resume')->field('id,uid,display,audit,refreshtime,stime,key_precise,key_full')->where($where)->select();
				foreach(array('sex'=>'sex','nat'=>'nature','bir'=>'birthdate','mar'=>'marriage','exp'=>'experience','wage'=>'wage','edu'=>'education','major'=>'major','photo'=>'photo','talent'=>'talent','level'=>'level','cur'=>'current','mob'=>'mobile_audit') as $key=>$val) {
					if(isset($d[$val])){
						$search[] = '/'.$key.'(\d+)/';
						$replace[] = $key.$d[$val];
					}
				}
				foreach ($resume_list as $key => $val) {
					if($search){
						$d['key_precise'] = $val['key_precise'] = preg_replace($search,$replace,$val['key_precise']);
						$d['key_full'] = $val['key_full'] = preg_replace($search,$replace,$val['key_full']);
					}
					if(false === M('Resume')->where(array('id'=>$val['id'],'uid'=>$user['uid']))->save($d)) return '简历信息表更新失败！';
					$search && D('Resume')->resume_index(false,$val);
				}
				$d = $temp = array();
			}
			if($data['fullname']){
				if(false === M('ResumeEntrust')->where($where)->setfield('fullname',$data['fullname'])) return '简历委托投递表更新失败！';
			}
		}else{
			//更新企业信息表
			foreach(array('telephone','email') as $val){
				isset($data[$val]) && $d[$val] = $data[$val];
			}
			$reg = D('CompanyProfile')->save($d);
			if(!$reg['state']) return '企业信息表更新失败！';
		}
		return true;
	}
	/**
	 * 查找数组元素是否设值，将值赋予其它元素
	 */
	protected function _sync($key,&$value){
		foreach($key as $val){
			if(isset($value[$val])){
				$temp = $value[$val];
				break;
			}
		}
		if(isset($temp)){
			foreach($key as $val){
				$value[$val] = $temp;
			}
		}
	}
	/**
     * [sign_in 签到]
     */
    public function sign_in($user){
        //积分操作
        if($user['utype']==1){
        	$r = D('TaskLog')->do_task($user,18);
        	$points = $r['data'];
            if($r['state']==1){
                $user_points=D('MembersPoints')->get_user_points($user['uid']);
                $operator="+";
                /* 写入会员日志 */
                $members_log['_t']='members_sign_in';
                $members_log['log_uid']=$user['uid'];
                $members_log['log_utype']=$user['utype'];
                $members_log['log_username']=$user['username'];
                $members_log['log_type']=9001;
                $members_log['log_value']= date("Y-m-d")." 签到，({$operator}{$r['data']})，(剩余:{$user_points})";
                $members_log['log_mode']=1;
                $members_log['log_op_type']=1014;
                $members_log['log_op_type_cn']="会员签到";
                $members_log['log_op_used']="{$operator}{$points}";
                $members_log['log_op_leave']=$user_points;
                setLog($members_log);
                return array('state'=>1,'points'=>$points);
            }
        }elseif($user['utype']==2){
            $r = D('TaskLog')->do_task($user,3);
            $points = $r['data'];
            if($r['state']==1){
                $user_points=D('MembersPoints')->get_user_points($user['uid']);
                $operator="+";
                /* 写入会员日志 */
                $members_log['_t']='members_sign_in';
                $members_log['log_uid']=$user['uid'];
                $members_log['log_utype']=$user['utype'];
                $members_log['log_username']=$user['username'];
                $members_log['log_type']=9001;
                $members_log['log_value']= date("Y-m-d")." 签到，({$operator}{$points})，(剩余:{$user_points})";
                setLog($members_log);
                return array('state'=>1,'points'=>$points);
            }
        }
        return array('state'=>0,'error'=>'你今天已经签到过了');
    }
	/*
		获取单条 信息
		@$data  字段名=>值
	*/
	public function get_user_one($data){
		return $this->where($data)->find();
	}
	/*
		修改密码
	*/
	public function save_password($data,$user){
		$passport = new \Common\qscmslib\passport();
		if(false === $passport->edit($user['uid'],array('password'=>$data['password']),$data['oldpassword'])){
			return array('state'=>0,'error'=>$passport->get_error());
		}
		$visitor = new \Common\qscmslib\user_visitor();
		$visitor->update();//刷新会话(更新session 和 cookie)
		//站内信
		$pms_message = '密码修改成功，新密码为：'.$data['password'];
		D('Pms')->write_pmsnotice($user['uid'],$user['username'],$pms_message);
		//发送邮件
		if(false === $mailconfig = F('mailconfig')) $mailconfig = D('Mailconfig')->get_cache();//邮箱系统配置参数
		if ($user['email_audit'] && $mailconfig['set_editpwd']=='1'){
			$send_mail['send_type']='set_editpwd';
			$send_mail['sendto_email']=$user['email'];
			$send_mail['subject']='set_editpwd_title';
			$send_mail['body']='set_editpwd';
			$replac_mail['account']=$user['username'];
			$replac_mail['time']=date('Y-m-d H:i');
			$last_login_info = M('MembersLog')->where(array('log_type'=>1001,'log_uid'=>$user['uid']))->find();
			$replac_mail['ipaddress']=$last_login_info['log_address'];
			$replac_mail['ip']=$last_login_info['log_ip'];
			$replac_mail['jumpurl'] = rtrim(C('qscms_site_domain').C('qscms_site_dir'),'/').U('Home/Members/login');
			D('Mailconfig')->send_mail($send_mail,$replac_mail);
		}
		//sms
		if(false === $sms = F('sms_config')) $sms = D('SmsConfig')->config_cache();
		if ($user['mobile_audit'] && C('qscms_sms_open') == 1 && $sms['set_editpwd']==1){
			$sendSms['mobile']=$user['mobile'];
			$sendSms['tpl']='set_editpwd';
			$sendSms['data']=array('newpassword'=>$data['password']);
			D('Sms')->sendSms('notice',$sendSms);
		}
		//微信
		if(false === $module_list = F('apply_list')) $module_list = D('Apply')->apply_cache();
		if($module_list['Weixin']){
			D('Weixin/TplMsg')->set_editpwd($user['uid'],$user['username'],$data['password']);
		}
		return array('state'=>1,'error'=>'修改成功！');
	}
	/**
	 * 删除会员
	 */
	public function delete_member($uid){
		$uid = is_array($uid)?$uid:array($uid);
		$sqlin = implode(",",$uid);
		$return = $this->where(array('uid'=>array('in',$sqlin)))->delete();
		if(false === $return) return false;
		$return = M('MembersBind')->where(array('uid'=>array('in',$sqlin)))->delete();
		if(false === $return) return false;
		return true;
	}
	/**
	 * 后台异步获取用户资料
	 */
	public function admin_ajax_get_user_info($id){
		$info = $this->get_user_one(array('uid'=>$id));
        if (empty($info))
        {
        	return array('state'=>0,'msg'=>'会员信息不存在！可能已经被删除！');
        }
        $html="用户名：{$info['username']}&nbsp;&nbsp;<span style=\"color:#0033CC\">(uid:{$info['uid']})</span><br/>";
        if (!empty($info['mobile']))
        {
        $mobile_audit=$info['mobile_audit']=="1"?'<span style="color:#009900">[已验证]</span>':'<span style="color:#FF9900">[未验证]</span>';
        $info['mobile']=$info['mobile'].$mobile_audit;
        }
        else
        {
        $info['mobile']='----';
        }
        $html.="手机：{$info['mobile']}<br/>";
        $email_audit=$info['email_audit']=="1"?'<span style="color:#009900">[已验证]</span>':'<span style="color:#FF9900">[未验证]</span>';
        $html.="邮箱：{$info['email']}{$email_audit}<br/>";
        $info['reg_time']=$info['reg_time']?date("Y/m/d H:i",$info['reg_time']):'----';
        $html.="注册时间：{$info['reg_time']}<br/>";
        $info['reg_ip']=$info['reg_ip']?$info['reg_ip']:'----';
        $html.="注册IP：{$info['reg_ip']}<br/>";
        $info['last_login_time']=$info['last_login_time']?date("Y/m/d H:i",$info['last_login_time']):'----';
        $html.="最后登录时间：{$info['last_login_time']}<br/>";
        $info['last_login_ip']=$info['last_login_ip']?$info['last_login_ip']:'----';
        $html.="最后登录IP：{$info['last_login_ip']}<br/>";
        if ($info['utype']=="1")
        {
            $points = D('MembersPoints')->get_user_points($id);
            $html.=C('qscms_points_byname')."：{$points['points']}".C('qscms_points_quantifier')."<br/>";
            $com = M('CompanyProfile')->where(array('uid'=>$id))->field('companyname')->find();
            if (empty($com['companyname']))
            {
            $com['companyname']="未填写";
            }
            $html.="公司名称：{$com['companyname']}<br/>";
            $totaljob = M('Jobs')->where(array('uid'=>$id))->count();
            $html.="发布职位：{$totaljob}条<br/>";
            $setmeal = M('MembersSetmeal')->where(array('uid'=>$id))->find();
            if (!empty($setmeal))
            {
                $html.="服务套餐：{$setmeal['setmeal_name']}<br/>";
                if($setmeal['endtime']=='0')
                {
                    $html.="服务期限：".date("Y/m/d",$setmeal['starttime'])."-- 至今";
                }
                else
                {
                    $html.="服务期限：".date("Y/m/d",$setmeal['starttime'])."--".date("Y/m/d",$setmeal['endtime']);
                }
            }
        }
        if ($info['utype']=="2")
        {
            $totalresume = M('Resume')->where(array('uid'=>$id))->count();
            $html.="发布简历：{$totalresume}条<br/>";
        }
        return array('state'=>1,'msg'=>$html);
	}
	protected function _after_update($data,$options) {
		if(C('qscms_rongyun_appkey') && C('qscms_rongyun_appsecret')){
			$userinfo = D('Members')->find($data['uid']);
			\Common\qscmslib\Rongyun::userRefresh($userinfo['uid'],$userinfo['username']);
		}
	}
	public function add_auth_info($mobile){
        $info = $this->where(array('mobile'=>$mobile))->find();
        if(!$info){
            $data['mobile'] = $mobile;
            $data['secretkey'] = $this->randstr(16,true);
            $data['mobile_audit'] = 1;
            $passport = new \Common\qscmslib\passport('default');
            if(!$info = $passport->register($data)) return false;
        }
        return $info;
    }
}
?>