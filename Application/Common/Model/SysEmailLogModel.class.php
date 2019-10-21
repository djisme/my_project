<?php
namespace Common\Model;
use Think\Model;
class SysEmailLogModel extends Model{
	protected $_validate = array(
		array('send_from,send_to,subject,body,state','identicalNull','',0,'callback'),
	);
	protected $_auto = array ( 
		array('sendtime','time',1,'function'),
	);
}
?>