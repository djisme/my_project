<?php
namespace Common\Model;
use Think\Model;
class MembersChargeLogModel extends Model{
	protected $_validate = array(
		array('log_uid,log_username,log_value,log_amount,log_type','identicalNull','',0,'callback'),
		array('log_uid,log_type','identicalEnum','',0,'callback'),
		array('log_username','1,60','{%members_length_error_jobs_log_username}',0,'length'), // 会员名称
	);
	protected $_auto = array ( 
		array('log_addtime','time',1,'function'),
		array('log_ismoney',1),//是否收费
		array('log_mode',1),//添加方式
		array('log_utype',1),//会员类型
	);
}
?>