<?php
/*
 *简历模型类
 */
namespace Common\Model;
use Think\Model\RelationModel;
class AdvResumeModel extends RelationModel{
	protected $_user = array();
	protected $_validate = array(
		array('title','1,24','{%resume_title_length_error}',0,'length'), // 简历标题
		array('fullname','1,15','{%resume_fullname_length_error}',0,'length'), // 姓名
		array('sex',array(1,2),'{%resume_sex_format_error}',0,'in'), // 性别
		array('marriage',array(1,2,3),'{%resume_marriage_between_error}',0,'in'), // 婚姻状况
		array('telephone,fullname,nature,birthdate,experience,wage,education,current','identicalNull','',0,'callback'),
		array('nature,birthdate,experience,wage,education,major,current,height','identicalEnum','',2,'callback'),
		array('telephone','mobile','{%resume_telephone_format_error}',2), // 手机号
		array('email','email','{%resume_email_format_error}',2), // 邮箱
		array('email','2,60','{%resume_email_length_error}',2,'length'), // 邮箱
		//array('telephone','_repetition_mobile','{%resume_repetition_mobile}',2,'callback'),
		//array('email','_repetition_email','{%resume_repetition_email}',2,'callback'),
		array('residence','0,30','{%resume_residence_length_error}',0,'length'), // 现居住地
		array('householdaddress','2,60','{%resume_householdaddress_length_error}',2,'length'), // 户口所在地
		array('specialty','0,1000','{%resume_specialty_length_error}',0,'length'), // 自我描述
		array('height','0,3','{%resume_height_length_error}',0,'length'), // 身高
		array('qq','number','{%resume_error_qq}',2),
		array('qq','0,11','{%resume_error_qq}',2,'length'),
		array('weixin','4,30','{%resume_length_error_weixin}',2,'length'),
	);
	protected $_auto = array (
		array('title','_title',1,'callback'),
		array('display',1),//是否显示
		array('display_name',1) , // 显示简历名称
		array('audit',2), // 简历审核
		array('email_notify',1), // 邮件接收通知
		array('photo',0), // 是否为照片简历
		array('photo_audit',2), // 照片审核
		array('addtime','time',1,'function'), //添加时间 
		array('refreshtime','time',1,'function'), //简历刷新时间
		array('stime','time',1,'function'),
		array('photo_display',1), // 是否显示照片
		array('entrust',0), // 简历委托
		array('talent',0), // 高级人才
		array('complete_percent',0), // 简历完整度
		array('click',1), // 查看次数
		array('tpl','default'),//简历模板
		array('resume_from_pc',0), // 简历来自PC(1->是)
		array('marriage',1,1)
	);
	protected function _title(){
		return '我的简历'.date('Ymd',time());
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
	/*
		获取简历列表 get_resume_list
		@data array 简历条件 
		@countinterview 面试邀请数
		@countdown 下载数
		@countapply 申请职位数
		@views  关注我的数量
	*/
	public function get_resume_list($data){
		$init = array('where'=>array(),'field'=>'*','order'=>'','countinterview'=>false,'countdown'=>false,'countapply'=>false,'views'=>false,'stick'=>false,'strong_tag'=>false);
		$init = array_merge($init,$data);
        $list = $this->field($init['field'])->where($init['where'])->order($init['order'])->limit($init['limit'])->select();
        foreach ($list as $key => $value)
		{
			$value['number']="N".str_pad($value['id'],7,"0",STR_PAD_LEFT);
			if ($init['countinterview'])
			{
				$value['countinterview'] = M('CompanyInterview')->where(array('resume_uid'=>$value['uid'],'resume_id'=>$value['id']))->count();
			}
			if ($init['countdown'])
			{
				$value['countdown'] = M('CompanyDownResume')->where(array('resume_uid'=>$value['uid'],'resume_id'=>$value['id']))->count();
			}
			if ($init['countapply'])
			{
				$value['countapply'] = M('PersonalJobsApply')->where(array('personal_uid'=>$value['uid'],'resume_id'=>$value['id']))->count();
			}
			if ($init['views'])
			{
				$value['views'] = M('ViewResume')->where(array('resumeid'=>$value['id']))->count('id');
			}
			if ($init['stick'])
			{
				$value['stick_info'] = D('PersonalServiceStickLog')->check_stick_log(array('resume_id'=>$value['id']));
			}
			if ($init['strong_tag'])
			{
				$tag_log = D('PersonalServiceTagLog')->check_tag_log(array('resume_id'=>$value['id']));
				$tag_log['tag_name'] = D('PersonalServiceTagCategory')->where(array('id'=>$tag_log['tag_id']))->getField('name');
				$value['tag_info'] = $tag_log;
			}
			if ($value['audit'] == 2)
			{
                $value['_audit'] = C('qscms_resume_display') == 2 ? 1 : $value['audit'];
            } else {
                $value['_audit'] = $value['audit'];
            }
            if ($init['_audit'] == 1)
            {
                $value['_audit'] == 1 && $resume_list[] = $value;
            } else {
                $resume_list[] = $value;
            }
		}
		return $resume_list;
	}
	public function resume_one($uid){
		$where['uid']=$uid;
		$resume_one = $this->where($where)->find(); 
        return $resume_one;
	}
	public function get_resume_one($pid){
		$where['id']=$pid;
		$resume_one = $this->where($where)->find();
        return $resume_one;
	}
	// 统计简历数
	public function count_resume($data){
		$resume_num = $this->where($data)->count(); 
        return $resume_num;
	}
	public function get_resume_basic($uid,$id){
		$id=intval($id);
		$uid=intval($uid);
		$where['uid']=$uid;
		$where['id']=$id;
		$info = $this->where($where)->find();
		if(!$info) return false;
		$info['age']=date("Y")-$info['birthdate'];
		$info['number']="N".str_pad($info['id'],7,"0",STR_PAD_LEFT);
		$info['lastname']=$info['fullname'];
		return $info;
	}
	public function count_personal_attention_me($uid){
		$id_arr = array();
		$id_str = "";
		$total = 0;
		$where['uid']=$uid;
		$personal_resume = $this->where($where)->select(); 
		if($personal_resume){
		foreach ($personal_resume as $key => $value) {
			$id_arr[] = $value["id"];
		}
		$view_resume = M('ViewResume'); // 实例化User对象
		$count_attention_me = $view_resume->where(array('resumeid'=>array('in',$id_arr)))->count();
		}
        return $count_attention_me;
	}
	/**
	 * [refresh_resume 刷新简历更新‘简历刷新时间’，记录操作日志]
	 */
	public function refresh_resume($pid,$user){
		$uid=$user['uid'];
		$time=time();
		if (!is_array($pid)) $pid=array($pid);
		$pid_num = count($pid);
		$sqlin=implode(",",$pid);
		$data = array('refreshtime'=>$time,'stime'=>$time);
		$where['id']=array('in',$sqlin);
		$where['uid']=$uid;
		if (!$this->where($where)->save($data)) return false;
		if (!M('ResumeSearchPrecise')->where($where)->save($data)) return false;
		if (!M('ResumeSearchFull')->where($where)->save($data)) return false;
		$where['stick'] = 1;
		if($rids = $this->where($where)->getfield('id',true)){
			$data = array('stime'=>$time+100000000);
			$where = array('id'=>array('in',$rids));
			if (!$this->where($where)->save($data)) return false;
			if (!M('ResumeSearchPrecise')->where($where)->save($data)) return false;
			if (!M('ResumeSearchFull')->where($where)->save($data)) return false;
		}
		$r = D('TaskLog')->do_task($user,6);
		//写入会员日志
		foreach ($pid as $k => $v) {
			write_members_log($user,'resume','刷新简历（简历id：'.$v.'）',false,array('resume_id'=>$v));
		}
		
		// 刷新日志
		$refresh_log['_t'] = 'RefreshLog';
		$refresh_log['uid'] = $uid;
		$refresh_log['type'] = '2001';
		$refresh_log['mode'] = 0;
		$refresh_log['addtime']=time();
		setLog($refresh_log);
		return $r;
	}

	//删除简历
	public function del_resume($user,$pid){
		if (!is_array($pid)) $pid=array($pid);
		$sqlin = implode(',',$pid);
		$where['id']=array('in',$pid);
		$where['uid']=$user['uid'];
		$_where['pid']=array('in',$pid);
		$_where['uid']=$user['uid'];
		if(false === $pid_num = $this->where($where)->delete()) return false;
		if(false === M('ResumeEducation')->where($_where)->delete()) return false;
		if(false === M('ResumeTraining')->where($_where)->delete()) return false;
		if(false === M('ResumeWork')->where($_where)->delete()) return false;
		if(false === M('ResumeCredent')->where($_where)->delete()) return false;
		if(false === M('ResumeLanguage')->where($_where)->delete()) return false;
		if(false === M('ResumeSearchPrecise')->where($where)->delete()) return false;
		if(false === M('ResumeSearchFull')->where($where)->delete()) return false;
		if(false === M('ViewResume')->where(array('resumeid'=>array('in',$sqlin)))->delete()) return false;
		if(false === M('ResumeEntrust')->where(array('resume_id'=>array('in',$sqlin)))->delete()) return false;

        //写入会员日志
        foreach ($pid as $k => $v) {
        	write_members_log($user,'resume','删除简历（简历id：'.$v.'）',false,array('resume_id'=>$v));
        }
		return true;
	}
	/*
		创建简历
	*/
	public function add_resume($data,$user){
		$this->_user = $user;
		if(false === $d = $this->create($data)){
			return array('state'=>0,'error'=>$this->getError());
		}else{
			$data['title']=='' && $data['title'] = '我的简历'.date('Ymd');
			if($user['mobile_audit'] == 1) $this->telephone = $d['mobile'] = $user['mobile'];
            if($user['email_audit'] == 1) $this->email = $d['email'] = $user['email'];
			$category = D('Category')->get_category_cache();
			$major_category = D('CategoryMajor')->get_major_list();
            $sex = array('1'=>'男','2'=>'女');
            $marriage = array('1'=>'未婚','2'=>'已婚','3'=>'保密');
            //意向行业
            if($d['trade']){
            	foreach(explode(',',$d['trade']) as $val) {
	                $trade_cn[] = $category['QS_trade'][$val];
	            }
            }else{
            	$trade_cn = array();
            }
            
            //意向地区
            $city = get_city_info($data['district']);
            $this->district = $data['district'] = $city['district'];

            //意向职位
            $jobs = D('CategoryJobs')->get_jobs_cache('all');
            foreach(explode(',',$data['intention_jobs_id']) as $val) {
                $val = explode('.',$val);
                $intention[] = $val[2] ? $jobs[$val[1]][$val[2]] : ($val[1] ? $jobs[$val[0]][$val[1]] : $jobs[0][$val[0]]);
            }
            $this->uid             = $d['uid']                = $user['uid'];
            $this->sex_cn          = $d['sex_cn']             = $sex[$d['sex']];
            $this->marriage_cn     = $d['marriage_cn']        = $marriage[$d['marriage']];
            $this->education_cn    = $d['education_cn']       = $category['QS_education'][$d['education']];
            $this->experience_cn   = $d['experience_cn']      = $category['QS_experience'][$d['experience']];
            $this->wage_cn         = $d['wage_cn']            = $category['QS_wage_k'][$d['wage']];
            $this->current_cn      = $d['current_cn']         = $category['QS_current'][$d['current']];
            $this->nature_cn       = $d['nature_cn']          = $category['QS_jobs_nature'][$d['nature']];
            $this->trade_cn        = $d['trade_cn']           = implode(',',$trade_cn);
            $this->district_cn     = $d['district_cn']        = $city['district_cn'];
            $this->intention_jobs  = $d['intention_jobs']     = implode(',',$intention);
            $this->audit           = 2;
            $this->photo_img	   = $user['is_avatars'] ? $user['avatar'] : '';
            $d['major'] && $this->major_cn        = $d['major_cn']           = $major_category[$d['major']]['categoryname'];
            $this->resume_from_pc  = 1;
            C('SUBSITE_VAL.s_id') && $this->subsite_id = $d['subsite_id'] = C('SUBSITE_VAL.s_id');
			if(false === $insert_id = $this->add()) return array('state'=>0,'error'=>'数据添加失败！');
		}
		return array('state'=>1,'id'=>$insert_id);
	}
	/*
	**	修改简历
	*/
	public function save_resume($data,$pid,$user){
		$this->_user = $user;
		$data['id']=$pid;
		$data['uid']=$user['uid'];
		if(C('qscms_audit_edit_resume')!='-1'){
			$data['audit']=intval(C('qscms_audit_edit_resume'));
		}else{
			$resume = $this->where(array('id'=>$pid))->field('audit')->find();
			if($resume['audit']==3){
              $data['audit'] = 2;
			}
		}
		if(false === $d = $this->create($data)){
			return array('state'=>0,'error'=>$this->getError());
		}else{
			if($user['mobile_audit'] == 1) $this->telephone = $d['mobile'] = $user['mobile'];
        	if($user['email_audit'] == 1) $this->email = $d['email'] = $user['email'];
        	$category = D('Category')->get_category_cache();
	        $major_category = D('CategoryMajor')->get_major_list();
	        $sex = array('1'=>'男','2'=>'女');
	        $marriage = array('1'=>'未婚','2'=>'已婚','3'=>'保密');
	        $d['sex']             && $this->sex_cn           = $d['sex_cn']            = $sex[$d['sex']];
	        $d['major']           && $this->major_cn         = $d['major_cn']          = $major_category[$d['major']]['categoryname'];
	        $d['marriage']        && $this->marriage_cn      = $d['marriage_cn']       = $marriage[$d['marriage']];
	        $d['education']       && $this->education_cn     = $d['education_cn']      = $category['QS_education'][$d['education']];
	        $d['experience']      && $this->experience_cn    = $d['experience_cn']     = $category['QS_experience'][$d['experience']];
	        $d['wage']            && $this->wage_cn          = $d['wage_cn']           = $category['QS_wage_k'][$d['wage']];
	        $d['current']         && $this->current_cn       = $d['current_cn']        = $category['QS_current'][$d['current']];
	        $d['nature']          && $this->nature_cn        = $d['nature_cn']         = $category['QS_jobs_nature'][$d['nature']];
	        C('SUBSITE_VAL.s_id') && $this->subsite_id		 = $d['subsite_id']		   = C('SUBSITE_VAL.s_id');
	        //意向行业
	        if(isset($data['trade'])){
	        	if($data['trade']){
			        foreach(explode(',',$data['trade']) as $val) {
			            $trade_cn[] = $category['QS_trade'][$val];
			        }
			        $this->trade_cn = $d['trade_cn'] = implode(',',$trade_cn);
			    }else{
			    	$this->trade_cn = $d['trade_cn'] = '';
			    }
	        }
	        
	        //意向地区
	        if($data['district']){
	        	$city = get_city_info($data['district']);
	            $this->district = $data['district'] = $city['district'];
	            $this->district_cn = $d['district_cn'] = $city['district_cn'];
		    }
		    $attach = '';
	        //意向职位
	        if($data['intention_jobs_id']){
		        $jobs = D('CategoryJobs')->get_jobs_cache('all');
		        foreach(explode(',',$data['intention_jobs_id']) as $val) {
		            $val = explode('.',$val);
		            if(isset($val[2]) && $val[2]>0){
		            	$intention[] = $jobs[$val[1]][$val[2]];
		            }
		            else if(isset($val[1]) && $val[1]>0)
		            {
		            	$intention[] = $jobs[$val[0]][$val[1]];
		            }
		            else
		            {
		            	$intention[] = $jobs[0][$val[0]];
		            }
		            // $intention[] = $val[2] ? $jobs[$val[1]][$val[2]] : $jobs[$val[0]][$val[1]];
		        }
		        $this->intention_jobs = $d['intention_jobs'] = implode(',',$intention);
		        if(C('apply.Allowance')){
		        	$check_result = D('Allowance/AllowanceEditIntentionLog')->check_intention_jobs($data['intention_jobs_id'],$d['intention_jobs'],$pid);
		        	if(!$check_result){
		        		if(false===$allowance_config=F('allowance_config')){
							$allowance_config = D('Allowance/AllowanceConfig')->config_cache();
						}
		        		$attach = '意向职位'.$allowance_config['resume_intentionjobs_edit_timespace'].'小时内只能修改一次';
		        		unset($this->intention_jobs);
		        		unset($this->intention_jobs_id);
		        	}
		        }
		    }
			if(false === $this->save()){
				return array('state'=>0,'error'=>'更新失败！');
			}
		}
		$data = array_merge($data,$d);
		//写入会员日志
		write_members_log($user,'resume','修改高级简历（简历id：'.$pid.'）',false,array('resume_id'=>$pid));
		return array('state'=>1,'id'=>$pid,'attach'=>$attach);
	}
	/**
	 * 根据uid删除简历
	 */
	public function admin_del_resume_for_uid($uid){
		if (!is_array($uid)) $uid=array($uid);
		$sqlin=implode(",",$uid);
		$return=0;
		if (fieldRegex($sqlin,'in'))
		{
			$resumelist = $this->where(array('uid'=>array('in',$sqlin)))->select();
			foreach ($resumelist as $key => $value) {
				$rid[] = $value['id'];
			}
			if (empty($rid))
			{
			return true;
			}
			else
			{
			return $this->admin_del_resume($rid);
			}		
		}
	}
	/**
	 * 删除简历
	 */
	public function admin_del_advresume($id){
		if (!is_array($id)) $id=array($id);
		$sqlin=implode(",",$id);
		$return=0;
		if (fieldRegex($sqlin,'in'))
		{
			$return = $this->where(array('id'=>array('in',$sqlin)))->delete();
			if(false === M('AdvResumeEducation')->where(array('pid'=>array('in',$sqlin)))->delete()) return false;
			if(false === M('AdvResumeTraining')->where(array('pid'=>array('in',$sqlin)))->delete()) return false;
			if(false === M('AdvResumeWork')->where(array('pid'=>array('in',$sqlin)))->delete()) return false;
			if(false === M('AdvResumeCredent')->where(array('pid'=>array('in',$sqlin)))->delete()) return false;
			if(false === M('AdvResumeLanguage')->where(array('pid'=>array('in',$sqlin)))->delete()) return false;
		}
		return $return;
	}

	/**
	 * 保存word简历
	 * 传值时注意：如果$id是数组，说明传值是did，需要先查出简历id；如果$id不是数组，那么$id就是简历id
	 */
	public function save_as_doc_word($id,$mod,$user,$zip=0){
		if (is_array($id) && $mod)//如果是did
		{
			// 批量导出为word  先查询简历id
			$sqlin=implode(",",$id);
			if (!fieldRegex($sqlin,'in')) return false;
			$idarr = $mod->where(array('did'=>array('in',$sqlin)))->field('resume_id')->select();
			foreach ($idarr as $key=>$value) {
				$idarr[$key]=$value['resume_id'];
			}
			$id=$idarr;
		}
		else//如果是简历id
		{
			$id=array($id);
		}
		$sqlin=implode(",",$id);
		if (!fieldRegex($sqlin,'in')) return false;
		$result = $this->where(array('id'=>array('in',$sqlin)))->select();
		if(!$result){
			return false;
		}
		$list = array();
		foreach ($result as $n)
		{
			$val = $n;
			$val['education_list']=D('ResumeEducation')->get_resume_education($val['id'],$val['uid']);
			$val['work_list']=D('ResumeWork')->get_resume_work($val['id'],$val['uid']);
			$val['training_list']=D('ResumeTraining')->get_resume_training($val['id'],$val['uid']);
			$val['age']=date("Y")-$val['birthdate'];
			$val['tagcn']=preg_replace("/\d+/", '',$val['tag']);
			$val['tagcn']=preg_replace('/\,/','',$val['tagcn']);
			$val['tagcn']=preg_replace('/\|/','&nbsp;&nbsp;&nbsp;',$val['tagcn']);
		
			// 最近登录时间
			$last_login_time = D('Members')->where(array('uid'=>array('eq',$val['uid'])))->getField('last_login_time');
			$val['last_login_time']=date('Y-m-d',$last_login_time);
			$down_resume = D('CompanyDownResume')->check_down_resume($val['id'],$user['uid']);
			if(!$down_resume){
				if ($val['display_name']=="2")
		        {
		            $val['fullname']="N".str_pad($val['id'],7,"0",STR_PAD_LEFT);
		        }
		        elseif($val['display_name']=="3")
		        {
		            if($val['sex']==1){
		            	$val['fullname']=cut_str($val['fullname'],1,0,"先生");
		            }elseif($val['sex'] == 2){
		            	$val['fullname']=cut_str($val['fullname'],1,0,"女士");
		            }
		        }
		     }else{
		     	$val['fullname'] = $val['fullname'];
		     }   
			$val['has_down'] = false;
	        $val['is_apply'] = false;
	        $val['label_id'] = 0;
	        $val['show_contact'] = $this->_get_show_contact($val,$val['has_down'],$val['is_apply'],$val['label_id'],$user);
	        if($val['show_contact']===false){
	            $val['telephone'] = contact_hide($val['telephone'],2);
	            $val['email'] = contact_hide($val['email'],3);
	        }
			$avatar_default = $val['sex']==1?'no_photo_male.png':'no_photo_female.png';
	        if ($val['photo']=="1")
	        {   
	            $val['photosrc']=C("qscms_site_domain").attach($val['photo_img'],'avatar');
	        }
	        else
	        {
	            $val['photosrc']=C("qscms_site_domain").attach($avatar_default,'resource');
	        }
			$list[] = $val;
		}
		$controller = new \Common\Controller\BaseController;
		if($zip){
			$path = QSCMS_DATA_PATH.'upload/resume_tmp/'.C('visitor.uid').'/';
	        if(is_dir($path)){//如果目录已存在，先删掉，以防将之前的文档也打包
	        	rmdirs($path);
	        }
	        mkdir($path,0777,true);
			foreach ($list as $key => $value) {
				$word = new \Common\qscmslib\word(); 
			    $wordname = $value['fullname']."的个人简历.doc"; 
			    $wordname = iconv("UTF-8", "GBK", $wordname); 
			    $html = $controller->assign_resume_tpl(array('list'=>array($value)),'Emailtpl/word_resume');
			    echo $html;
			    $word->save($path.$wordname); 
			}
			$savename = '来自'.C('qscms_site_name').'的简历.zip';
			if(strtoupper(substr(PHP_OS,0,3))==='WIN'){
				$filename = $path.iconv("UTF-8", "GBK", $savename); 
			}else{
				$filename = $path.$savename; 
			}
	        $zip = new \Common\qscmslib\phpzip;
	        $done = $zip->zip($path.'/',$filename);
	        if ($done)
	        {        
	        	//写入会员日志
	        	foreach ($id as $k => $v) {
	        		write_members_log($user,'resume','保存word简历（简历id：'.$v.'）',false,array('resume_id'=>$v));
	        	}
				
				return array('zip'=>1,'name'=>$savename,'dir'=>'resume_tmp/'.C('visitor.uid'),'path'=>$path);
				// 
	        }
		}else{
			$html = $controller->assign_resume_tpl(array('list'=>$list),'Emailtpl/word_resume');
			//写入会员日志
			foreach ($id as $k => $v) {
        		write_members_log($user,'resume','保存word简历（简历id：'.$v.'）',false,array('resume_id'=>$v));
        	}
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
			header("Content-Type: application/doc");
			$ua = $_SERVER["HTTP_USER_AGENT"];
			$filename="{$val['fullname']}的个人简历.doc";
			$filename = urlencode($filename);
			$filename = str_replace("+", "%20", $filename);
			if (preg_match("/MSIE/", $ua)) {
			    header('Content-Disposition: attachment; filename="' . $filename . '"');
			} else if (preg_match("/Firefox/", $ua)) {
			    header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
			} else {
			    header('Content-Disposition: attachment; filename="' . $filename . '"');
			}
			echo $html;
		}
	}
	/**
     * 是否显示联系方式
     */
    protected function _get_show_contact($val,&$down,&$apply,&$label_id,$user){
        $show_contact = false;
        //情景1：游客访问
        if(!$user){
            C('qscms_showresumecontact')==0 && $show_contact = true;
        }
        //情景2：个人会员访问并且是该简历发布者
        else if($user['utype']==2 && $user['uid']==$val['uid'])
        {
            $show_contact = true;
        }
        //情景3：企业会员访问
        else if($user['utype']==1)
        {
            //情景3-1：其他企业会员
            if(C('qscms_showresumecontact')==1){
                $show_contact = true;
            }
            //情景3-2：下载过该简历
            $down_resume = D('CompanyDownResume')->check_down_resume($val['id'],$user['uid']);
            if($down_resume){
                $label_id = $down_resume['is_reply'];
                $show_contact = true;
                $down = true;
            }
            //情景3-3：该简历申请过当前企业发布的职位
            $jobs_apply = D('PersonalJobsApply')->check_jobs_apply($val['id'],$user['uid']);
           /*  if($jobs_apply){
                $label_id = $jobs_apply['is_reply'];
                $show_contact = true;
                $apply = true;
            } */
            //情景3-3：该简历申请过当前企业发布的职位
            $setmeal=D('MembersSetmeal')->get_user_setmeal($user['uid']);
            if($jobs_apply && $setmeal['show_apply_contact']=='1'){
                $show_contact = true;
            }
        }
        return $show_contact;
    }

	/**
	 * 单条简历发送
	 */
	public function send_to_email_one($resume_id,$email){
		$resume_mod = new \Common\qscmstag\resume_showTag(array('简历id'=>$resume_id));
    	$resume = $resume_mod->run();
		$resume['tag_arr']=$resume['tag_cn'];
		$controller = new \Common\Controller\BaseController;
		$html = $controller->assign_resume_tpl(array('resume_basic'=>$resume),'Emailtpl/outward_resume');
		D('Mailconfig')->send_mail(array('sendto_email'=>$email,'subject'=>$resume['fullname'].'的简历','body'=>$html));
	}
	/**
	 * 发送到邮箱
	 */
	public function send_to_email($resume_id,$email){
		!is_array($resume_id) && $resume_id = explode(',',$resume_id);
		foreach ($resume_id as $key => $value) {
			$this->send_to_email_one($value,$email);
		}
		return true;
	}
	/**
	 * 标记简历
	 */
	public function company_label_resume($did,$mod_name,$company_uid,$state){
		if($mod_name=='PersonalJobsApply'){
			$data['personal_look'] = 2;
			$data['reply_time'] = time();
			$old_apply_info = D('PersonalJobsApply')->where(array('did'=>$did,'is_reply'=>0))->find();
		}
		$data['is_reply'] = $state;
		$num = D($mod_name)->where(array('did'=>$did,'company_uid'=>$company_uid))->save($data);
		if(false === $num){
			return array('state'=>0,'msg'=>'标记失败');
		}else{
			$user = D('Members')->get_user_one(array('uid'=>$company_uid));
			$r = false;
			if($mod_name=='PersonalJobsApply'){
				$apply_info = D('PersonalJobsApply')->where(array('did'=>$did))->find();
				//处理时间为3天内
				if($old_apply_info && $data['reply_time']-$apply_info['apply_addtime']<=3600*24*3){
					$userinfo = D('Members')->get_user_one(array('uid'=>$company_uid));
					$r = D('TaskLog')->do_task($userinfo,28);
				}
				//写入会员日志
				write_members_log($user,'resume','标记简历（记录id：'.$did.'）');
			}else{
				//写入会员日志
				write_members_log($user,'resume','标记简历（记录id：'.$did.'）');
			}
			return array('state'=>1,'msg'=>'标记成功','task'=>$r);
		}
	}
	/**
	 * 获取用户简历
	 */
	public function get_user_resume($uid,$audit=1){
		$map['uid'] = $uid;
		$map['audit'] = $audit;
		return $this->where($map)->getField('id,title');
	}
	/**
	 * 完善简历送红包
	 */
	public function perfected_resume_allowance($complete_percent,$userinfo){
		if(C('qscms_perfected_resume_allowance_open')==1 && C('qscms_perfected_resume_allowance_percent')>0 && $complete_percent>=C('qscms_perfected_resume_allowance_percent')){
			$perfected_info = M('MembersPerfectedAllowance')->where(array('uid'=>$userinfo['uid']))->find();
			if($perfected_info && $perfected_info['status']==0){
				$userbind = D('MembersBind')->get_members_bind(array('uid'=>$userinfo['uid'],'type'=>'weixin'));
				if(!$userbind){
					$perfected_info['nobind']==0 && M('MembersPerfectedAllowance')->where(array('uid'=>$userinfo['uid']))->save(array('nobind'=>1,'reason'=>'未绑定微信'));
					if($perfected_info['notice']==1){
						if(strtolower(MODULE_NAME)=='home'){
							return array('status'=>2,'msg'=>'未绑定微信','data'=>$perfected_info['value']);
						}else{
							return array('status'=>0,'msg'=>'未绑定微信','data'=>$perfected_info['value']);
						}
					}else{
						return array('status'=>0,'msg'=>'未绑定微信','data'=>$perfected_info['value']);
					}
				}else{
					$perfected_info['nobind']==1 && M('MembersPerfectedAllowance')->where(array('uid'=>$userinfo['uid']))->save(array('nobind'=>0));
				}
				include QSCMSLIB_PATH . "pay/wxpay/wxpay.class.php";
				$pay_type = D('Common/Payment')->get_cache();
	        	$setting = $pay_type['wxpay'];
		        $payObj = new \wxpay_pay($setting);
		        $data['openid'] = $userbind['openid'];
		        $data['partner_trade_no'] = 'PraUid'.$userinfo['uid'].'T'.time();
		        $data['amount'] = $perfected_info['value'];
		        $result = $payObj->payment($data);
		        if($result){
		        	M('MembersPerfectedAllowance')->where(array('uid'=>$userinfo['uid']))->save(array('status'=>1,'reason'=>'','notice'=>0));
		        	return array('status'=>1,'msg'=>'红包已发放','data'=>$data['amount']);
		        }else{
		        	M('MembersPerfectedAllowance')->where(array('uid'=>$userinfo['uid']))->save(array('status'=>0,'reason'=>$payObj->getError()));
		        	return array('status'=>0,'msg'=>$payObj->getError(),'data'=>'');
		        }
			}else{
				return array('status'=>0,'msg'=>'不通知','data'=>'');
			}
		}else{
			return array('status'=>0,'msg'=>'未开启','data'=>'');
		}
	}
}
?>