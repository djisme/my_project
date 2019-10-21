<?php
namespace Common\Model;
use Think\Model;
class ViewJobsModel extends Model{
	protected $_validate = array(
		array('uid,jobsid','identicalNull','',0,'callback'),
		array('uid,jobsid','identicalEnum','',0,'callback'),
	);
	protected $_auto = array ( 
		array('addtime','time',3,'function'), //查看时间 
	);
	/*
		个人 浏览过的职位
		@$data array 查询条件 为 view_jobs 表中条件
		$data['uid']=>uid; 个人uid
		$data['addtime']=array('gt',$time);
		
		@$pagesiz 每页显示条数 
		
		返回值 数组
		$rst['list'] 列表数据 array
		$rst['page'] 分业
	*/
	public function get_view_jobs($data,$pagesize=10)
	{
		$db_pre = C('DB_PREFIX');
		$this_t = $db_pre.'view_jobs';
		// 处理字段重复标识属于哪个表
		foreach ($data as $key => $val) {
			$where[$this_t.'.'.$key]=$val;
		}
		if(C('visitor.utype')==2)//个人查看
		{
			$join = 'left join '.$db_pre .'jobs j on j.id='.$this_t.'.jobsid';
			$count = $this->where($where)->join($join)->count();
			$pager =  pager($count, $pagesize);
			$rst['list'] = $this->where($where)->join($join)->join($join1)->field($this_t.'.*,j.district,j.jobs_name,j.uid as company_uid,j.audit,j.companyname,j.company_id,j.deadline,j.display')->order('id desc')->limit($pager->firstRow . ',' . $pager->listRows)->select();
			foreach ($rst['list'] as $key => $val)
			{
				$val['close'] = 0;
				if (!$val['jobs_name'])
				{
					$jobs = M('JobsTmp')->where(array('id'=>$val['jobsid']))->find();
					$val['jobs_name']=$jobs['jobs_name'];
					$val['jobsid']=$jobs['id'];
					$val['company_uid'] = $jobs['uid'];
					$val['company_id'] = $jobs['company_id'];
					$val['audit'] = $jobs['audit'];
					$val['district'] = $jobs['district'];
					$val['deadline'] = $jobs['deadline'];
					$val['display'] = $jobs['display'];
					$val['companyname'] = $jobs['companyname'];
					$val['close'] = 1;
				}
				$subsite_id = get_jobs_subsite_id($val);
				$val['url'] = url_rewrite("QS_jobsshow",array('id'=>$val['jobsid']),$subsite_id);
				$val['company_url'] = url_rewrite("QS_companyshow",array('id'=>$val['company_id']));
				if ($val['audit'] == 2)
                {
                    $val['_audit'] = C('qscms_jobs_display') == 2 ? 1 : $val['audit'];
                } else {
                    $val['_audit'] = $val['audit'];
                }
				if($val['close'] == 1 || $val['_audit']==3 || $val['_audit']==2 || $val['display']==2){
					$val['status'] = '';
					$val['status_cn'] = '已关闭';
				}else{
					$val['status'] = 1;
					$val['status_cn'] = '发布中';
				}
				// 是否收藏过此职位
				$is_favorites = M('PersonalFavorites')->where(array('personal_uid'=>C('visitor.uid'),'jobs_id'=>$val['jobsid']))->getField('did');
				if($is_favorites) $val['is_favorites']=1;
				// 是否申请过该职位
				$is_apply = M('PersonalJobsApply')->where(array('personal_uid'=>C('visitor.uid'),'jobs_id'=>$val['jobsid']))->getField('did');
				if($is_apply) $val['is_apply']=1;
				$rst['list'][$key] = $val;
			}
		}
		else//企业查看
		{
			$join = $db_pre .'resume i on i.uid='.$this_t.'.uid';
			$where['i.def'] = 1;
			$uids = $this->where($where)->join($join)->group($this_t.'.uid')->getfield($this_t.'.uid',true);
			$count = count($uids);
			$pager =  pager($count, $pagesize);
			$rst['list'] = $this->where($where)->join($join)->field($this_t.'.*,i.id as resumeid,i.uid as resume_uid,i.fullname,i.display_name,i.sex,i.sex_cn,i.education_cn,i.experience_cn,i.major_cn,i.birthdate,i.intention_jobs')->order($this_t.'.id desc')->group($this_t.'.uid')->limit($pager->firstRow . ',' . $pager->listRows)->select();
			foreach ($rst['list'] as $key => $val)
			{
				if($val['resume_uid'])
				{
					$favorites = M('CompanyFavorites')->where(array('resume_id'=>$val['resumeid'],'company_uid'=>$val['jobs_uid']))->find();
					if($favorites){
						$val['hasfavorites'] = 1;
					}else{
						$val['hasfavorites'] = 0;
					}
					if ($val['display_name']=="2")
					{
						$val['fullname']="N".str_pad($val['resume_uid'],7,"0",STR_PAD_LEFT);
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
		}
		$rst['count'] = $count;
		$rst['page'] = $pager->fshow();
		return $rst;
	}
	/*
		删除 浏览过的职位
		@$yid id 删除id 多个用,分割
		
		返回值
		state 删除状态 0失败，1成功
		error 错误信息
		num   删除条数
	*/
	public function del_view_jobs($yid)
	{
		if (!is_array($yid)) $yid=array($yid);
		$sqlin=implode(",",$yid);
		if (!fieldRegex($sqlin,'in')) return array('state'=>0,'error'=>'删除id错误！');
		$where['id']=array('in',$sqlin);
		$num = $this->where($where)->delete();
		if (false===$num) return array('state'=>0,'error'=>'删除失败！');
	  	return array('state'=>1,'num'=>$num);
	}
}
?>