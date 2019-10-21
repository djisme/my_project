<?php
/*
* 74cms 计划任务 用户未登录邮件提醒
* ============================================================================
* 版权所有: 骑士网络，并保留所有权利。
* 网站地址: http://www.74cms.com；
* ----------------------------------------------------------------------------
* 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
* 使用；不允许对程序代码以任何形式任何目的的再发布。
* ============================================================================
*/
defined('THINK_PATH') or exit();
ignore_user_abort(true);
class RemindLogin{
	public function run(){
		$site_domain = C('apply.Subsite')?C('qscms_site_domain'):'';
		if(C('qscms_com_unlogin_remind_open')==1 || C('qscms_per_unlogin_remind_open')==1){
			if(C('qscms_com_unlogin_remind_open')==1){
				//最新简历
				$html_resume = '<table width="700" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;color:#555; font:16px/26px \'微软雅黑\',\'宋体\',Arail; ">
				    	<tbody><tr>
				        	<td style="height:62px; background-color:#FCFCFC; padding:10px 0 0 10px;">
				            	<a target="_blank" href="'.C('qscms_site_domain').C('qscms_site_dir').'"><img src="'.C("qscms_site_domain").attach('logo.gif','images').'" width="200" height="45" style="border:none;"/></a>
				            </td>
				        </tr>
				        <tr style="background-color:#fff;">
				        	<td style="padding:30px 38px;">
				            	<div>亲爱的用户，你好!</div>';
				$html_resume .='<div style="text-indent:2em;">由于您长时间未登录<a style="color:#017FCF;" href="'.C('qscms_site_domain').C('qscms_site_dir').'" target="_blank">'.C('qscms_site_name').'</a>，经我们精心的挑选，现将筛选的最新简历发送给你，希望我们的邮件能够对你有所帮助。祝职业更上一层楼！</div><div style="border-bottom:1px solid #e6e6e6; font-weight:bold; margin:20px 0 0 0; padding-bottom:5px;">以下是最新的简历：</div>
				            	<ul style="list-style:none; margin:0; padding:0;">';
				$resume = D('Resume')->order('addtime desc')->limit(5)->select();
				if($resume){
					foreach ($resume as $k=>$v) {
						$resume_url = url_rewrite('QS_resumeshow',array('id'=>$v['id']));
						if($v['photo_img']==""){
							$photo_img = C('qscms_site_domain').C('qscms_site_dir').attach('nologo.jpg','resource');
						}else{
							$photo_img = C('qscms_site_domain').C('qscms_site_dir').attach($v['photo_img'],'avatar');
						}
						$html_resume .='<li style="list-style:none;padding:15px 10px 15px 0;border-bottom:1px solid #e6e6e6; overflow:hidden;">
							    <a target="_blank" href="'.$resume_url.'">
							    <img width="80" height="80" style="border:none; float:left; margin-right:15px;" src="'.$photo_img.'">
							    </a>
							    <div>
							    <a target="_blank" style="float:left; color:#017FCF; text-decoration:underline;" href="'.$resume_url.'">
							    '.$v['fullname'].'
							    </a>
							    <a target="_blank" style="float:right; color:#017FCF; text-decoration:underline;" href="'.$resume_url.'">
							    查看详情
							    </a><br>
							    <div>学历：'.$v["education_cn"].'</div><br>
							    <div>工作经验：'.$v["experience_cn"].'</div>
							    </div>
							    </li>';
					}
				}
				$html_resume .='</ul>
				                <a target="_blank" style="float:right; text-decoration:underline; font-weight:700; margin:15px 0;color:#017FCF;" href="'.$site_domain.url_rewrite('QS_resumelist').'">查看所有简历</a>
				            </td>
				        </tr>';
				$html_resume .='
				        <tr>
				        	<td style="text-align:center; color:#c9cbce; font-size:14px; padding:5px 0;">公司网址：<a style="color:#017FCF;" target="_blank" href="'.C('qscms_site_domain').C('qscms_site_dir').'">'.C('qscms_site_domain').C('qscms_site_dir').'</a>   </td>
				        </tr>
				    </tbody></table>';
				$time = time();
				$user_unlogin_time = C('qscms_com_unlogin_remind_cycle')?C('qscms_com_unlogin_remind_cycle'):30;
				$last_time = strtotime("-".$user_unlogin_time." day");
				$result = D('Members')->where(array('last_login_time'=>array('lt',$last_time),'remind_email_time'=>array('lt',$time),'utype'=>1,'remind_email_ex_time'=>array('lt',C('qscms_com_unlogin_remind_time'))))->select();
				foreach ($result as $key => $row) {
					$email = $row['email'];
					if($row['email_audit']==0){
						continue;
					}
					D('Mailconfig')->send_mail(array('sendto_email'=>$email,'subject'=>C('qscms_site_name').'向您发送了长时间未登录网站提醒','body'=>$html_resume));
					//更新邮箱提醒时间
					$remind_email_time = strtotime("+".$user_unlogin_time." day");
					D('Members')->where(array('uid'=>$row['uid']))->setField('remind_email_time',$remind_email_time);
				}
			}
			if(C('qscms_per_unlogin_remind_open')==1){
				//最新职位
				$html_jobs = '<table width="700" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;color:#555; font:16px/26px \'微软雅黑\',\'宋体\',Arail; ">
				    	<tbody><tr>
				        	<td style="height:62px; background-color:#FCFCFC; padding:10px 0 0 10px;">
				            	<a target="_blank" href="'.C('qscms_site_domain').C('qscms_site_dir').'"><img src="'.C("qscms_site_domain").attach('logo.gif','images').'" width="200" height="45" style="border:none;"/></a>
				            </td>
				        </tr>
				        <tr style="background-color:#fff;">
				        	<td style="padding:30px 38px;">
				            	<div>亲爱的用户，你好!</div>';
				$html_jobs .='<div style="text-indent:2em;">由于您长时间未登录<a style="color:#017FCF;" href="'.C('qscms_site_domain').C('qscms_site_dir').'" target="_blank">'.C('qscms_site_name').'，经我们精心的挑选，现将筛选的最新职位发送给你，希望我们的邮件能够对你有所帮助。祝职业更上一层楼！</div><div style="border-bottom:1px solid #e6e6e6; font-weight:bold; margin:20px 0 0 0; padding-bottom:5px;">以下是最新的职位：</div>
				            	<ul style="list-style:none; margin:0; padding:0;">';
				if(C('qscms_jobs_display')==1){
					$list_map['audit'] = 1;
				}else{
					$list_map['id'] = array('gt',0);
				}
				$jobs = D('Jobs')->where($list_map)->order('addtime desc')->limit(5)->select();
				if($jobs){
					foreach ($jobs as $k=>$v) {
						$subsite_id = get_jobs_subsite_id($v);
						$jobs_url = url_rewrite('QS_jobsshow',array('id'=>$v['id']),$subsite_id);
						$company_url = url_rewrite('QS_companyshow',array('id'=>$v['company_id']));
						$logo = D('CompanyProfile')->where(array('id'=>$v['company_id']))->field('logo,district_cn')->find();
						if($logo['logo']==""){
							$company_logo = C('qscms_site_domain').C('qscms_site_dir').attach('no_logo.gif','resource');
						}else{
							if(false === strpos($logo['logo'], 'http://')){
								$company_logo = C('qscms_site_domain').C('qscms_site_dir').attach($logo['logo'],'company_logo');
							}else{
								$company_logo = attach($logo['logo'],'company_logo');
							}
						}
						$html_jobs .='<li style="list-style:none;padding:15px 10px 15px 0;border-bottom:1px solid #e6e6e6; overflow:hidden;">
							    <a target="_blank" href="'.$company_url.'">
							    <img width="80" height="80" style="border:none; float:left; margin-right:15px;" src="'.$company_logo.'">
							    </a>
							    <div>
							    <a target="_blank" style="float:left; color:#017FCF; text-decoration:underline;" href="'.$jobs_url.'">
							    '.$v['jobs_name'].'
							    </a>
							    <a target="_blank" style="float:right; color:#017FCF; text-decoration:underline;" href="'.$jobs_url.'">
							    查看详情
							    </a><br>
							    <div style="font-weight:700;">'.$v['companyname'].'</div>
							    <div>工作地区：'.$v["district_cn"].'</div>
							    </div>
							    </li>';
					}
				}
				$html_jobs .='</ul>
				                <a target="_blank" style="float:right; text-decoration:underline; font-weight:700; margin:15px 0;color:#017FCF;" href="'.$site_domain.url_rewrite('QS_jobslist').'">查看所有职位</a>
				            </td>
				        </tr>';
				$html_jobs .='
				        <tr>
				        	<td style="text-align:center; color:#c9cbce; font-size:14px; padding:5px 0;">公司网址：<a style="color:#017FCF;" target="_blank" href="'.C('qscms_site_domain').C('qscms_site_dir').'">'.C('qscms_site_domain').C('qscms_site_dir').'</a>   </td>
				        </tr>
				    </tbody></table>';
				$time = time();
				$user_unlogin_time = C('qscms_per_unlogin_remind_cycle')?C('qscms_per_unlogin_remind_cycle'):30;
				$last_time = strtotime("-".$user_unlogin_time." day");
				$result = D('Members')->where(array('last_login_time'=>array('lt',$last_time),'remind_email_time'=>array('lt',$time),'utype'=>2,'remind_email_ex_time'=>array('lt',C('qscms_per_unlogin_remind_time'))))->select();
				foreach ($result as $key => $row) {
					$email = $row['email'];
					if($row['email_audit']==0){
						continue;
					}
					D('Mailconfig')->send_mail(array('sendto_email'=>$email,'subject'=>C('qscms_site_name').'向您发送了长时间未登录网站提醒','body'=>$html_jobs));
					//更新邮箱提醒时间
					$remind_email_time = strtotime("+".$user_unlogin_time." day");
					D('Members')->where(array('uid'=>$row['uid']))->setField('remind_email_time',$remind_email_time);
				}
			}
		}
	}
}
?>