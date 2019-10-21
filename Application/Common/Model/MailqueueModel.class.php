<?php
namespace Common\Model;
use Think\Model;
class MailqueueModel extends Model{
	protected $_validate = array(
		array('m_addtime,m_sendtime,m_mail,m_subject,m_body','identicalNull','',0,'callback'),
		array('m_addtime,m_sendtime','identicalEnum','',0,'callback'),
		array('m_mail','1,80','{%hunter_jobs_length_error_contact}',0,'length'), // 收件人
		array('m_subject','1,200','{%hunter_jobs_length_error_contact}',0,'length'), // 邮件主题
	);
	protected $_auto = array ( 
		array('m_type',1),//发送状态
		array('m_addtime','time',1,'function'),//添加时间
	);
	public function replace_label($str,$user='')
	{
		$str=str_replace('{sitename}',C('qscms_site_name'),$str);
		$str=str_replace('{sitedomain}',C('qscms_site_domain').C('qscms_site_dir'),$str);
		$str=str_replace('{address}',C('qscms_address'),$str);
		$str=str_replace('{tel}',C('qscms_top_tel'),$str);
		$str=str_replace('{username}',$user['username'],$str);
		if ($user['last_login_time'])
		{
		$str=str_replace('{lastlogintime}',date("Y-m-d",$user['last_login_time']),$str);
		}	
		return $str;
	}
}
?>