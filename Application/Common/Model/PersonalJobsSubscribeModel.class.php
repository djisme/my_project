<?php
namespace Common\Model;
use Think\Model;
class PersonalJobsSubscribeModel extends Model{
	protected $_validate = array(
		array('intention_jobs_id,district','identicalNull','',1,'callback',3),
		array('intention_jobs_id,trade,wage','identicalEnum','',2,'callback'),
		array('title','0,60','{%title_length_error}',2,'length'),
		array('key','0,40','{%key_length_error}',2,'length'),
	);
	protected $_auto = array(
		array('addtime','time',1,'function'),
		array('update_time','_update_time',3,'callback'),
		array('status',1,1,'string'),
		array('title','title_str',3,'callback'),
	);
	protected function title_str($data){
		return $data ? $data : '职位订阅'.date('Ymd');
	}
	protected function _update_time($data){
		return strtotime('today');
	}
	public function edit_subscribe($user,$id){
		if(false !== $this->create()){
			if($id==0){
				$count_current = $this->where(array('uid'=>$user['uid']))->count();
				if(C('qscms_subscribe_num')<=$count_current){
					return array('state'=>false,'error'=>'系统限制最大订阅器数为'.C('qscms_subscribe_num').'！');
				}
				$name = 'add';
			}else{
				$this->id = $id;
				$name = 'save';
			}
			$this->uid = $user['uid'];
			$this->email = $user['email'];
			$category = D('Category')->get_category_cache();
			
			//获取意向行业中文
			foreach(explode(',',$this->trade) as $val) {
				$trade_cn[] = $category['QS_trade'][$val];
			}
			$this->trade_cn = implode(',',$trade_cn);
			//意向职位
			$jobs = D('CategoryJobs')->get_jobs_cache('all');
			foreach(explode(',',$this->intention_jobs_id) as $val) {
				$val = explode('.',$val);
				$intention[] = $val[2] ? $jobs[$val[1]][$val[2]] : ($val[1] ? $jobs[$val[0]][$val[1]] : $jobs[0][$val[0]]);
			}
			$this->intention_jobs = implode(',',$intention);
			//地区
            $city = get_city_info($this->district);
            $this->district = $city['district'];
            $this->district_cn = $city['district_cn'];
			//年薪范围
			$this->wage_cn = $category['QS_wage'][$this->wage]?$category['QS_wage'][$this->wage]:'';
			C('SUBSITE_VAL.s_id') && $this->subsite_id = C('SUBSITE_VAL.s_id');
			if(false !== $id = $this->$name()){
				return array('state'=>true,'id'=>$id);
			}else{
				return array('state'=>false,'error'=>'添加职位订阅器失败,请重新操作!');
			}
		}else{
			return array('state'=>false,'error'=>$this->getError());
		}
	}
	public function get_subscribe($where,$pagesize=10){
		$rst['count'] = $this->where($where)->count('id');
		if($rst['count']){
			$pager =  pager($rst['count'], $pagesize);
			$rst['list'] = $this->field('id,title,key,intention_jobs,district_cn,wage_cn,status')->where($where)->order('id desc')->limit($pager->firstRow . ',' . $pager->listRows)->select();
			$rst['page'] = $pager->fshow();
		}
		return $rst;
	}
}
?>