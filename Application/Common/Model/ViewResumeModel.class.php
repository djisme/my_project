<?php
namespace Common\Model;
use Think\Model;
class ViewResumeModel extends Model{
	protected $_validate = array(
		array('uid,jobsid','identicalNull','',0,'callback'),
		array('uid,jobsid','identicalEnum','',0,'callback'),
	);
	protected $_auto = array ( 
		array('addtime','time',3,'function'), //查看时间 
	);
	/*
		个人 谁在关注我
		@$data array 查询条件 为 view_resume 表中条件
		$data['resumeid']=>resumeid;
		$data['addtime']=array('gt',$time);
		@$pagesiz 每页显示条数 
		返回值 数组
		$rst['list'] 列表数据 array
		$rst['page'] 分页
	*/
	public function get_view_resume($data,$pagesize=10)
	{
		$db_pre = C('DB_PREFIX');
		$this_t = $db_pre.'view_resume';
		// 处理字段重复标识属于哪个表
		foreach ($data as $key => $val) {
			$where[$this_t.'.'.$key]=$val;
		}
		$join = $db_pre .'resume r on r.id='.$this_t.'.resumeid';
		$join2 = $db_pre.'company_profile c on c.uid='.$this_t.'.uid';
		$count = $this->where($where)->join($join)->join($join2)->count();
		$pager =  pager($count, $pagesize);
		$rst['list'] = $this->where($where)->join($join)->join($join2)->field($this_t.'.*,r.id as resume_id,r.birthdate,r.uid as resume_uid,r.title,r.display_name,r.sex,r.sex_cn,r.education_cn,r.experience_cn,r.fullname,r.intention_jobs,c.id as company_id,c.companyname')->order('id desc')->limit($pager->firstRow . ',' . $pager->listRows)->select();
		foreach ($rst['list'] as $key => $val)
		{
			if($val['resume_uid'])
			{
				if(C('visitor.utype')==2)
				{
					$val['company_url'] = url_rewrite("QS_companyshow",array('id'=>$val['company_id']));
					$downlog = M('CompanyDownResume')->where(array('resume_id'=>$val['resumeid'],'resume_uid'=>$val['resume_uid'],'company_uid'=>$val['uid']))->find();
					if($downlog){
						$val['hasdown'] = 1;
					}else{
						$val['hasdown'] = 0;
					}
				}else
				{
					$favorites = M('CompanyFavorites')->where(array('resume_id'=>$val['resume_id'],'company_uid'=>$val['uid']))->find();
					if($favorites){
						$val['hasfavorites'] = 1;
					}else{
						$val['hasfavorites'] = 0;
					}
				}
				if ($val['display_name']=="2")
				{
					$val['fullname']="N".str_pad($val['id'],7,"0",STR_PAD_LEFT);
				}
				elseif ($val['display_name']=="3")
				{
					if($val['sex']==1){
						$val['fullname']=cut_str($val['fullname'],1,0,"先生");
					}
					elseif($val['sex']==2){
						$val['fullname']=cut_str($val['fullname'],1,0,"女士");
					}
				}
				$y=date("Y");
				if(intval($val['birthdate']) == 0)
				{
					$val['age']='';
				}
				else
				{
					$val['age']=$y-$val['birthdate'];
				}
				$val['resume_url'] = url_rewrite("QS_resumeshow",array('id'=>$val['resumeid']));
				
			}
			$rst['list'][$key] = $val;
		}
		$rst['count'] = $count;
		$rst['page'] = $pager->fshow();
		return $rst;
	}
	/*
		(触屏版)  浏览过的简历 或者 谁看过我
		@$data array 查询条件 为 view_resume 表中条件
		$data['resumeid']=>resumeid;
		$data['addtime']=array('gt',$time);
		$data['r.education']=array('gt',$education);
		$data['r.experience']=array('gt',$experience);
		$data['i.d0']=array('eq',$category); 
		$data['i.d1']=array('eq',$subclass); 
		@$pagesiz 每页显示条数 
		返回值 数组
		$rst['list'] 列表数据 array
		$rst['page'] 分页
	*/
	public function m_aa($data,$pagesize=10){
		$db_pre = C('DB_PREFIX');
		$this_t = $db_pre.'view_resume';
		foreach ($data as $key => $val) {
			$key != 'hasdown' && $where[$this_t.'.'.$key]=$val;
		}
		//if($data['hasdown']) $where['i.company_uid']=array('is',null);//$join3.=' and i.resume_id<>null';
		$join = $db_pre .'resume r on r.id='.$this_t.'.resumeid';
		$join3 = $db_pre .'company_down_resume i on i.resume_id='.$this_t.'.resumeid';
		$join3.=' and i.company_uid='.$this_t.'.uid';
		$count = $this->where($where)->join($join)->join($join3)->count();
		$pager =  pager($count, $pagesize);
		$rst['list'] = $this->where($where)->join($join)->join($join3)->field()->limit($pager->firstRow . ',' . $pager->listRows)->select();
	}
	public function m_get_view_resume($data,$pagesize=10)
	{
		$db_pre = C('DB_PREFIX');
		$this_t = $db_pre.'view_resume';
		// 处理字段重复标识属于哪个表
		foreach ($data as $key => $val) {
			$key != 'hasdown' && $where[$this_t.'.'.$key]=$val;
		}
		$join = $db_pre .'resume r on r.id='.$this_t.'.resumeid';
		$join2 = $db_pre.'company_profile c on c.uid='.$this_t.'.uid';
		$join3 = 'left join '.$db_pre .'company_down_resume i on i.resume_id='.$this_t.'.resumeid and i.company_uid='.$this_t.'.uid';
		if($data['hasdown'] == '0') $where['i.did']=array('is',null);
		if($data['hasdown']) $where['i.did']=array('is not',null);
		$count = $this->where($where)->join($join)->join($join2)->join($join3)->count();
		$pager =  pager($count, $pagesize);
		$rst['list'] = $this->where($where)->join($join)->join($join2)->join($join3)->field($this_t.'.*,r.id as resume_id,r.uid as resume_uid,r.title,r.fullname,r.display_name,r.sex,r.sex_cn,r.birthdate,r.education_cn,r.experience_cn,r.major_cn,r.intention_jobs,c.id as company_id,c.companyname,i.did as is_hasdown')->order('id desc')->limit($pager->firstRow . ',' . $pager->listRows)->select();
		foreach ($rst['list'] as $key => $val)
		{
			if($val['resume_uid'])
			{
				// 个人会员中心 谁看过我
				if(C('visitor.utype')==2)
				{
					if($val['is_hasdown']){
						$val['hasdown'] = 1;
					}else{
						$val['hasdown'] = 0;
					}
				}
				// 企业会员中心 浏览过的简历
				else
				{
					// 对姓名进行处理
					if($val['display_name']=="3")
					{
						if($val['sex']==1)
						{
							$val['fullname']=cut_str($val['fullname'],1,0,"先生");
						}
						elseif($val['sex']==2)
						{
							$val['fullname']=cut_str($val['fullname'],1,0,"女士");
						}
					}
					elseif($val['display_name']=="2")
					{
						$val['fullname']="N".str_pad($val['resumeid'],7,"0",STR_PAD_LEFT);
					}
					// 年龄
					if(intval($val['birthdate']) == 0)
					{
						$val['age']='';
					}
					else
					{
						$y=date("Y");
						$val['age']=$y-$val['birthdate'];
					}
				}
			}
			$rst['list'][$key] = $val;
		}
		$rst['page'] = $pager->fshow();
		return $rst;
	}
	/*
		删除 关注我的
		@$yid id 删除id
		
		返回值
		state 删除状态 0失败，1成功
		error 错误信息
		num   删除条数
	*/
	public function del_view_resume($yid)
	{
		if (!is_array($yid)) $yid=array($yid);
		$sqlin=implode(",",$yid);
		if (!preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin)) return array('state'=>0,'error'=>'删除id错误！');
		$where['id']=array('in',$sqlin);
		$num = $this->where($where)->delete();
		if (false===$num) return array('state'=>0,'error'=>'删除失败！');
	  	return array('state'=>1,'num'=>$num);
	}
}
?>