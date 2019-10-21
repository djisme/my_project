<?php
/*
* 74cms 计划任务 职位订阅发送邮件
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
class JobSubscribe{
	public function run(){
		$list = M('PersonalJobsSubscribe')->where(array('status'=>1))->select();
		if(!$list) return false;
		$mailconfig = D('Mailconfig')->get_cache();
		foreach ($list as $key => $val) {
			if(0 == $period_time = strtotime('today') - $val['update_time']) continue;
			if(($period_time / 24 / 3600) % intval(C('qscms_subscribe_period')) == 0){
				$where = array(
		    		'排序' => 'rtime',
		    		'显示数目' => '5',
		    		'关键字' => $val['key'],
		    		'行业' => $val['trade'],
		    		'职位分类' => $val['intention_jobs_id'],
		    		'地区分类' => $val['district'],
		    		'工资' => $val['wage']
		    	);
		    	$jobs_mod = new \Common\qscmstag\jobs_listTag($where);
		    	$jobs = $jobs_mod->run();
		    	if($jobs['list']){
		    		$logo = C('qscms_logo_home') ? attach(C('qscms_logo_home'),'resource') : APP_NAME.'/Home/View/'.C('qscms_template_dir').'/public/images/logo.gif';
	    			$logo = C('qscms_site_domain').C('qscms_site_dir').$logo;
	    			$html = '<table width="700" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;color:#555; font:16px/26px \'微软雅黑\',\'宋体\',Arail; ">
					<tbody><tr>
		        	<td style="height:62px; background-color:#FCFCFC; padding:10px 0 0 10px;">
		            	<a target="_blank" href="'.C('qscms_site_domain').C('qscms_site_dir').'"><img src="'.$logo.'" width="200" height="45" style="border:none;"/></a>
		            </td>
		        	</tr>
		        	<tr style="background-color:#fff;">
		        	<td style="padding:30px 38px;">
		            <div>亲爱的<span style="color:#017FCF;"><a href="mailto:'.$val['email'].'" target="_blank">'.$val['email'].'</a></span>，你好!</div>';
			    	$html .='<div style="text-indent:2em;">你在<a style="color:#017FCF;" href="'.C('qscms_site_domain').C('qscms_site_dir').'" target="_blank">'.C('qscms_site_name').'</a>上订阅了"<span style="color:#017FCF;">'.$val['title'].'</span>"相关职位信息，经我们精心的挑选，现将筛选结果发送给你，希望我们的邮件能够对你有所帮助。祝职业更上一层楼！</div>
	                <div style="text-indent:2em;"></div>
	            	<div style="border-bottom:1px solid #e6e6e6; font-weight:bold; margin:20px 0 0 0; padding-bottom:5px;">以下是你订阅的职位：</div>
	            	<ul style="list-style:none; margin:0; padding:0;">';
	            	foreach ($jobs['list'] as $v) {
						$logo = M('company_profile')->field('logo,district_cn')->where(array('id'=>$v['company_id']))->find();
						if($logo['logo']==""){
							$company_logo = C('qscms_site_domain').C('qscms_site_dir').attach('no_logo.gif','resource');
						}else{
							if(false === strpos($logo['logo'], 'http://')){
								$company_logo = C('qscms_site_domain').C('qscms_site_dir').attach($logo['logo'],'company_logo');
							}else{
								$company_logo = attach($logo['logo'],'company_logo');
							}
						}
						$html .='<li style="list-style:none;padding:15px 10px 15px 0;border-bottom:1px solid #e6e6e6; overflow:hidden;">
					    <a target="_blank" href="'.$v['company_url'].'">
					    <img width="80" height="80" style="border:none; float:left; margin-right:15px;" src="'.$v['logo'].'">
					    </a>
					    <div>
					    <a target="_blank" style="float:left; color:#017FCF; text-decoration:underline;" href="'.$v['jobs_url'].'">
					    '.$v['jobs_name'].'
					    </a>
					    <a target="_blank" style="float:right; color:#017FCF; text-decoration:underline;" href="'.$v['jobs_url'].'">
					    查看详情
					    </a><br>
					    <div style="font-weight:700;">'.$v['companyname'].'</div>
					    <div>工作地区：'.$v["district_cn"].'</div>
					    </div>
					    </li>';
	            	}
	            	$html .='</ul>
	                <a target="_blank" style="float:right; text-decoration:underline; font-weight:700; margin:15px 0;color:#017FCF;" href="'.url_rewrite('QS_jobslist').'">查看所有职位</a>
	            	</td>
	        		</tr>';
	        		//$html .='<div style="text-indent:2em;">你在<a style="color:#017FCF;" href="'.C('qscms_site_domain').C('qscms_site_dir').'" target="_blank">'.C('qscms_site_name').'</a>上订阅了"<span style="color:#017FCF;">'.$val['title'].'</span>"相关职位信息，经我们精心的挑选，并没有找到与您意向相符的职位，您可以<a href="'.C('qscms_site_domain').C('qscms_site_dir').'">重新选择</a>。祝职业更上一层楼！</div>';
			    	$html .='<tr><td style="text-align:center; color:#c9cbce; font-size:14px; padding:5px 0;">公司网址：<a style="color:#017FCF;" target="_blank" href="'.C('qscms_site_domain').C('qscms_site_dir').'">'.C('qscms_site_domain').C('qscms_site_dir').'</a></td></tr>
			        <tr><td style="line-height:30px;text-align:right;font-size:14px;"> 为保证邮箱正常接收，请将<a href="mailto:'.$mailconfig['smtpfrom'].'" target="_blank">'.$mailconfig['smtpfrom'].'</a>添加进你的通讯录</td></tr>
			    	</tbody></table>';
			    	$send_mail['sendto_email']=$val['email'];
			        $send_mail['subject']=C('qscms_site_name')."向您发送了您订阅的职位信息";
			        $send_mail['body']=$html;
			        D('Mailconfig')->send_mail($send_mail);
		    	}
		    }
		}
	}
}
?>