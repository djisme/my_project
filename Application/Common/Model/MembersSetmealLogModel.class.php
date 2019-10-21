<?php
namespace Common\Model;
use Think\Model;
class MembersSetmealLogModel extends Model{
	protected $_validate = array(
		array('log_uid,log_utype,log_username,log_value','identicalNull','',0,'callback'),
	);
	protected $_auto = array (
		array('log_addtime','time',1,'function'),
		array('log_ip','get_client_ip',1,'function'),
		array('log_address','get_address',1,'callback'),
	);
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
		获取会员套餐日志 
		@$data members_log 中的查询条件
	*/
	public function get_members_setmeal_log($data,$pagesize=10)
	{
		$rst['count'] = $this->where($data)->count();
		if($rst['count']){
			$pager =  pager($rst['count'], $pagesize);
			$rst['list'] = $this->where($data)->order('log_id desc')->limit($pager->firstRow . ',' . $pager->listRows)->select();
			$rst['page'] = $pager->fshow();
		}
		return $rst;
	}
}
?>