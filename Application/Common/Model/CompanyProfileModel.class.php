<?php
namespace Common\Model;
use Think\Model;
class CompanyProfileModel extends Model
{
	protected $_user=array();
	protected $_validate = array(
		array('uid,companyname,nature,nature_cn,trade,trade_cn,scale,scale_cn,district,contact,address,contents,certificate_img','identicalNull','',0,'callback'),
		array('uid,nature,trade,scale,','identicalEnum','',0,'callback'),
		array('contents','40,2000','{%company_profile_contents_length_error}',2,'length'),
		array('telephone,landline_tel','requireone','{%company_profile_telephone_requireone}',1,'function',1),
		array('telephone','mobile','{%company_profile_format_error_telephone}',2),
		//array('landline_tel','tel','{%company_profile_format_error_landline_tel}',2),
		array('website','url','{%members_format_error_website}',2),
		//array('qq','qq','{%members_format_error_qq}',2),
		array('email','email','{%company_profile_format_error_email}'),
		//array('telephone','_repetition_mobile','{%company_profile_repetition_mobile}',2,'callback'),
		//array('email','_repetition_email','{%company_profile_repetition_email}',2,'callback'),
		array('companyname','_repetition_companyname','{%company_profile_repetition_companyname}',2,'callback'),
	);
	protected $_auto = array (
		array('map_open',0),
		array('addtime','time',1,'function'),
		array('refreshtime','time',1,'function'),
		array('click',1),
		array('user_status',1),
		array('contact_show',1),
		array('telephone_show',1),
		array('landline_tel_show',1),
		array('email_show',1),
		array('resume_processing',100), 
		array('wzp_tpl',0),
		array('robot',0),
		array('map_x',0),
		array('map_y',0),
	);
	protected function _repetition_companyname($data){
		if(C('qscms_company_repeat')=="0"){
		  $companyname = M('CompanyProfile')->where(array('companyname'=>$data))->find();
		  if($companyname) return false;
		}
		return true;
		
	}
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
	public function admin_edit_company_profile($data,$user,$company_profile){
		$this->_user = $user;
		if(false === $this->create($data))
		{
			return array('state'=>0,'error'=>$this->getError());
		}
		else
		{
			if(false === $num = $this->save()){
				return array('state'=>0,'error'=>'数据添加失败！');
			}
			if(!$user['mobile_audit']){
                $setsqlarr['telephone'] = $data['telephone'];
            }
            if(!$user['email_audit']){
                $setsqlarr['email'] = $data['email'];
            }
			$setsqlarr && D('Members')->update_user_info($setsqlarr,$user);
		}
		if(false !== $num && $data['id']) //修改信息
		{
			$jobarr['companyname']=$data['companyname'];
			$jobarr['trade']=$data['trade'];
			$jobarr['trade_cn']=$data['trade_cn'];
			$jobarr['scale']=$data['scale'];
			$jobarr['scale_cn']=$data['scale_cn'];
			$r = D('Jobs')->jobs_setfield(array('uid'=>$data['uid']),$jobarr);
			if(!$r['state']) array('state'=>0,'error'=>'修改公司名称出错！');
			if(C('apply.Jobfair') && false === M('JobfairExhibitors')->where(array('uid'=>$data['uid']))->setField('companyname',$data['companyname'])) return array('state'=>0,'error'=>'修改公司名称出错！');
		}
		return array('state'=>1,'id'=>$insert_id,'num'=>$num);
	}
	/*
		添加，保存企业信息
		@data post 数据
		其中 data['id'] 存在 为修改保存 否则就是添加
	*/
	public function add_company_profile($data,$user)
	{
		$this->_user = $user;
		// 替换原名称
		$company = $this->field('id,companyname')->where(array('uid'=>$user['uid']))->find();
		if($company){
			$data['companyname'] = $company['companyname'];
		}
		if(false === $this->create($data))
		{
			return array('state'=>0,'error'=>$this->getError());
		}
		else
		{
			if($company['id'])
			{
				if(false === $num = $this->save()){
					return array('state'=>0,'error'=>'数据添加失败！');
				}
				if(!$user['mobile_audit']){
	                $setsqlarr['telephone'] = $data['telephone'];
	            }
	            if(!$user['email_audit']){
	                $setsqlarr['email'] = $data['email'];
	            }
				$setsqlarr && D('Members')->update_user_info($setsqlarr,$user);
			}
			else
			{
				if(false === $insert_id = $this->add())
				{
					return array('state'=>0,'error'=>'数据添加失败！');
				}
			}
		}
		// 插入数据后相应操作 
	/*	if ($insert_id) //添加信息
		{
			baidu_submiturl(url_rewrite('QS_companyshow',array('id'=>$insertid)),'addcompany');
		}*/
		if(false !== $num && $data['id']) //修改信息
		{
			$jobarr['companyname']=$data['companyname'];
			$jobarr['trade']=$data['trade'];
			$jobarr['trade_cn']=$data['trade_cn'];
			$jobarr['scale']=$data['scale'];
			$jobarr['scale_cn']=$data['scale_cn'];
			$jobarr['map_x']=$data['map_x'];
			$jobarr['map_y']=$data['map_y'];
			$jobarr['map_zoom']=$data['map_zoom'];
			$r = D('Jobs')->jobs_setfield(array('uid'=>$data['uid']),$jobarr);
			if(!$r['state']) array('state'=>0,'error'=>'修改公司名称出错！');
			if(C('apply.Jobfair') && false === M('JobfairExhibitors')->where(array('uid'=>$data['uid']))->setField('companyname',$data['companyname'])) return array('state'=>0,'error'=>'修改公司名称出错！');

			//同步到职位联系方式
			if(intval($data['sync'])==1)
			{
				if($jobsid_arr=M('Jobs')->where(array('uid'=>$data['uid']))->getField('id',true)){
					$contact['telephone'] = $data['telephone'];
					$contact['email'] = $data['email'];
					$contact['contact'] = $data['contact'];
					$contact['qq'] = $data['qq'];
					$contact['landline_tel'] = $data['landline_tel'];
					$contact['address'] = $data['address'];
					$contact['contact_show'] = $data['contact_show'];
					$contact['telephone_show'] = $data['telephone_show'];
					$contact['email_show'] = $data['email_show'];
					$contact['landline_tel_show'] = $data['landline_tel_show'];
					M('JobsContact')->where(array('pid'=>array('in',$jobsid_arr)))->save($contact);
				}
			}
			$log_value = '修改企业资料';
		}
		//写入会员日志
		$user = D('Members')->get_user_one(array('uid'=>$data['uid']));
		write_members_log($user,'','修改企业资料');
		return array('state'=>1,'id'=>$insert_id,'num'=>$num);
	}
	/*
		上传营业执照
	*/
	public function add_certificate_img($data,$user){
		D('Jobs')->jobs_setfield(array('uid'=>$user['uid']),array('company_audit'=>2));
		$rst = $this->where(array('uid'=>$user['uid']))->save($data);
		if($rst===false) return array('state'=>0,'error'=>'更新营业执照数据错误！');
		//写入会员日志
		write_members_log($user,'','上传营业执照');
		return array('state'=>1);
	}
	/**
	 * 格式化企业列表
	 */
	public function admin_format_company_list($list){
		$uid_arr = $jobs_count_list = $report = array();
		foreach ($list as $key => $value) {
			$uid_arr[] = $value['uid'];
			$arr = $value;
			$arr['company_url']=url_rewrite('QS_companyshow',array('id'=>$value['id']));
			$list[$key] = $arr;
		}
		if(!empty($uid_arr)){
			$jobs_count_list = D('Jobs')->field('count(*) as num,uid')->where(array('uid'=>array('in',$uid_arr)))->group('uid')->index('uid')->select();
			if(C('apply.Report')){
				$report = D('CompanyReport')->where(array('uid'=>array('in',$uid_arr)))->index('uid')->select();
			}else{
				$report = array();
			}
		}
		foreach ($list as $key => $value) {
			$list[$key]['jobs_count'] = (isset($jobs_count_list[$value['uid']]) && intval($jobs_count_list[$value['uid']])>0)?$jobs_count_list[$value['uid']]['num']:0;
			$list[$key]['report_open'] = isset($report[$value['uid']])?1:0;
			$list[$key]['report'] = isset($report[$value['uid']])?$report[$value['uid']]:array();
		}
		return $list;
	}
	/**
	 * 后台删除企业
	 */
	public function admin_delete_company($uid){
		!is_array($uid) && $uid=array($uid);
		$sqlin=implode(",",$uid);
		if (fieldRegex($sqlin,'in'))
		{
			$result = $this->where(array('uid'=>array('in',$sqlin)))->select();
			foreach ($result as $key => $value) {
				@unlink(C('qscms_attach_path')."certificate_img/".$value['certificate_img']);
			}
			$this->where(array('uid'=>array('in',$sqlin)))->delete();
			return true;
		}
		return false;
	}
	/**
	 * 审核企业
	 */
	public function admin_edit_company_audit($uid,$audit,$reason,$audit_man=array()){
		if (!is_array($uid)) $uid=array($uid);
		$sqlin=implode(",",$uid);
		if (fieldRegex($sqlin,'in'))
		{
			$comlist = $this->where(array('uid'=>array('in',$sqlin)))->select();
			if(false===$this->where(array('uid'=>array('in',$sqlin)))->setField('audit',$audit)) return false;
			if($audit == 1){
		        foreach($comlist as $key =>$val){
		          baidu_submiturl(url_rewrite('QS_companyshow',array('id'=>$val['id'])),'addcompany');   
	        } 
        }
			$r = D('Jobs')->jobs_setfield(array('uid'=>array('in',$sqlin)),array('company_audit'=>$audit));
			if(!$r['state']) return false;
			$reasona=$reason==''?'无':$reason;
			foreach($comlist as $list){
				write_members_log(array('uid'=>$list['uid'],'utype'=>1,'username'=>''),'company_audit',"将企业uid为".$list['uid']."的企业的认证状态修改为".($audit==1?'认证通过':($audit==2?'等待认证':'认证未通过')).'；备注：'.$reasona,false,array(),$audit_man['id'],$audit_man['username']);
				$auditsqlarr['company_id']=$list['id'];
				$auditsqlarr['reason']=$reasona;
				$auditsqlarr['status']=$audit==1?'认证通过':($audit==2?'等待认证':'认证未通过');
				$auditsqlarr['addtime']=time();
				$auditsqlarr['audit_man']=$audit_man['username']?$audit_man['username']:'未知';
				M('AuditReason')->data($auditsqlarr)->add();
			}
			//站内信
			if($audit=='1') {$note='成功通过网站管理员审核!';}elseif($audit=='2'){$note='正在审核中!';}else{$note='未通过网站管理员审核！';}
			foreach ($comlist as $list) {
				$user_info = D('Members')->get_user_one(array('uid'=>$list['uid']));
				$pms_message = "您的公司营业执照".$note.'其他说明：'.$reasona;
				D('Pms')->write_pmsnotice($user_info['uid'],$user_info['username'],$pms_message);
			}
			//邮件
			$mailconfig=D('Mailconfig')->get_cache();
			$sms=D('SmsConfig')->get_cache();
			if ($audit=="1" && $mailconfig['set_licenseallow']=="1")//认证通过
			{
				foreach ($comlist as $key => $value) {
					$user_info=D('Members')->get_user_one(array('uid'=>$value['uid']));
					if($user_info['email'] && $user_info['email_audit']=="1"){
						$send_mail['send_type'] = 'set_licenseallow';
						$send_mail['sendto_email'] = $user_info['email'];
						$send_mail['subject']='set_licenseallow_title';
						$send_mail['body']='set_licenseallow';
						D('Mailconfig')->send_mail($send_mail,array());
					}
				}
			}
			if ($audit=="3" && $mailconfig['set_licensenotallow']=="1")//认证未通过
			{
				foreach ($comlist as $key => $value) {
					$user_info=D('Members')->get_user_one(array('uid'=>$value['uid']));
					if($user_info['email'] && $user_info['email_audit']=="1"){
						$send_mail['send_type'] = 'set_licensenotallow';
						$send_mail['sendto_email'] = $user_info['email'];
						$send_mail['subject']='set_licensenotallow_title';
						$send_mail['body']='set_licensenotallow';
						D('Mailconfig')->send_mail($send_mail,array());
					}
				}
			}
					//sms
			if ($audit=="1" && C('qscms_sms_open')==1 && $sms['set_licenseallow']=="1" )
			{
				$mobilearray = array();
				foreach ($comlist as $key => $value) {
					$usermobile = D('Members')->get_user_one(array('uid'=>$value['uid']));
					if ($usermobile['mobile_audit']=="1" && !is_array($value['mobile'],$mobilearray))
					{
						$mobilearray[] = $usermobile['mobile'];
					}
				}
				if(!empty($mobilearray)){
					$mobilestr = implode(",",$mobilearray);
					D('Sms')->sendSms('notice',array('mobile'=>$mobilestr,'tpl'=>'set_licenseallow'));
				}
			}
			//sms
			if ($audit=="3" && C('qscms_sms_open')==1 && $sms['set_licensenotallow']=="1" )//认证未通过
			{
				$mobilearray = array();
				foreach ($comlist as $key => $value) {
					$usermobile = D('Members')->get_user_one(array('uid'=>$value['uid']));
					if ($usermobile['mobile_audit']=="1" && !is_array($value['mobile'],$mobilearray))
					{
						$mobilearray[] = $usermobile['mobile'];
					}
				}
				if(!empty($mobilearray)){
					$mobilestr = implode(",",$mobilearray);
					D('Sms')->sendSms('notice',array('mobile'=>$mobilestr,'tpl'=>'set_licensenotallow'));
				}
			}
			//微信
			if(C('apply.Weixin')){
				foreach ($comlist as $key => $value) {
					D('Weixin/TplMsg')->set_licenseallow($value['uid'],$audit==1?'审核通过':'审核未通过',$reasona);
				}
			}
			if ($audit=='1'){
				$userinfo_arr = D('Members')->where(array('uid'=>array('in',$uid)))->select();
				foreach ($userinfo_arr as $key => $value) {
					D('TaskLog')->do_task($value,30);
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * 刷新企业
	 */
	public function admin_refresh_company($uid,$refresjobs=false)
	{
		$return=0;
		if (!is_array($uid))$uid=array($uid);
		$sqlin=implode(",",$uid);
		$time=time();
		if (fieldRegex($sqlin,'in'))
		{
			$return = $this->where(array('uid'=>array('in',$sqlin)))->setField('refreshtime',$time);
			if(false===$return) return false;
			if ($refresjobs)
			{
				$return = $return+D('Jobs')->admin_refresh_jobs_by_uid($uid);
			}
			foreach($uid as $vo){
				write_members_log(array('uid'=>$vo,'utype'=>1,'username'=>''),'','刷新企业',false,array(),C('visitor.id'),C('visitor.username'));
			}
		}
		return $return;
	}
	
}
?>