<?php
namespace Home\Controller;
use Common\Controller\FrontendController;
class IndexController extends FrontendController{
	public function _initialize() {
        parent::_initialize();
    }
	/**
	 * [index 首页]
	 */
	public function index(){
		if(!I('get.org','','trim') && C('PLATFORM') == 'mobile' && $this->apply['Mobile']){
            redirect(build_mobile_url());
		}
		if(false === $oauth_list = F('oauth_list')){
            $oauth_list = D('Oauth')->oauth_cache();
        }
        $this->assign('verify_userlogin',$this->check_captcha_open(C('qscms_captcha_config.user_login'),'error_login_count'));
		$this->assign('oauth_list',$oauth_list);
		$this->display();
	}
	/**
	 * [ajax_user_info ajax获取用户登录信息]
	 */
	public function ajax_user_info(){
		if(IS_AJAX){
			!$this->visitor->is_login && $this->ajaxReturn(0,'请登录！');
			$uid = C('visitor.uid');
			if(C('visitor.utype') == 1){
				$info = M('CompanyProfile')->field('companyname,logo')->where(array('uid'=>$uid))->find();
				$views = M('ViewJobs')->where(array('jobs_uid'=>C('visitor.uid')))->group('uid')->getfield('uid',true);
				$info['views'] = count($views);
				$join = 'join '.C('DB_PREFIX') .'jobs j on j.id='.C('DB_PREFIX').'personal_jobs_apply.jobs_id';
				$info['apply'] = M('PersonalJobsApply')->join($join)->where(array('company_uid'=>$uid,'is_reply'=>array('eq',0)))->count();
			}else{
				$info['realname'] = M('Resume')->where(array('uid'=>$uid,'def'=>1))->limit(1)->getfield('fullname');
				$info['pid'] = M('Resume')->where(array('uid'=>$uid,'def'=>1))->getfield('id');
				$info['countinterview'] = M('CompanyInterview')->where(array('resume_uid'=>$uid))->count();
				//谁看过我
				$rids = M('Resume')->where(array('uid'=>$uid))->getField('id',true);
				if($rids){
					$info['views'] = M('ViewResume')->where(array('resumeid'=>array('in',$rids)))->count();
				}else{
					$info['views'] = 0;
				}
			}
			$issign = D('MembersHandsel')->check_members_handsel_day(array('uid'=>$uid,'htype'=>'task_sign'));
        	$this->assign('issign',$issign ? 1 : 0);
			$this->assign('info',$info);
			$hour=date('G');
			if($hour<11){
				$am_pm = '早上好';
	        }
	        else if($hour<13)
	        {
	        	$am_pm = '中午好';
	        }
	        else if($hour<17)
	        {
	        	$am_pm = '下午好';
	        }
	        else
	        {
	        	$am_pm = '晚上好';
	        }
	        $this->assign('am_pm',$am_pm);
			$data['html'] = $this->fetch('ajax_user_info');
        	$this->ajaxReturn(1,'',$data);
		}
	}
	/**
	 * [index 首页搜索跳转]
	 */
	public function search_location(){
		$act = I('post.act','','trim');
		$key = I('post.key','','trim');
		$this->ajaxReturn(1,'',url_rewrite($act,array('key'=>$key)));
	}
	/**
	 * 保存到桌面
	 */
	public function shortcut(){
		$Shortcut = "[InternetShortcut]
		URL=".C('qscms_site_domain').C('qscms_site_dir')."?lnk
		IDList= 
		IconFile=".C('qscms_site_domain').C('qscms_site_dir')."favicon.ico
		IconIndex=100
		[{000214A0-0000-0000-C000-000000000046}]
		Prop3=19,2";
		header("Content-type: application/octet-stream"); 
		$ua = $_SERVER["HTTP_USER_AGENT"];
		$filename=C('qscms_site_name').'.url';
		$filename = urlencode($filename);
		$filename = str_replace("+", "%20", $filename);
		if (preg_match("/MSIE/", $ua)) {
		    header('Content-Disposition: attachment; filename="' . $filename . '"');
		} else if (preg_match("/Firefox/", $ua)) {
		    header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
		} else {
		    header('Content-Disposition: attachment; filename="' . $filename . '"');
		}
		exit($Shortcut);
	}
	/**
     * 首页滚动动态
     */
	public function ajax_scroll(){
	    $where['display'] = 1;
	    $where['audit'] = array('eq',1);
        $add_resume = M('Resume')->where($where)->order('addtime DESC')->limit(5)->getField("id,fullname,sex,addtime as time,'add' as type");
        $where['id'] = array('not in',array_keys($add_resume));
        $refresh_resume = M('Resume')->where($where)->order('refreshtime DESC')->limit(5)->getField("id,fullname,sex,refreshtime  as time,'refresh' as type");
        foreach(array_merge($add_resume,$refresh_resume) as $val){
            if($val['sex']==1){
                $val['fullname']=cut_str($val['fullname'],1,0,"先生");
            }elseif($val['sex'] == 2){
                $val['fullname']=cut_str($val['fullname'],1,0,"女士");
            }else{
                $val['fullname']=cut_str($val['fullname'],1,0,"**");
            }
            $val['utype'] = 2;
            $val['url'] = url_rewrite('QS_resumeshow',array('id'=>$val['id']));
            $val['time_cn'] = daterange(time(),$val['time'],'Y-m-d');
            $resume[] = $val;
        }
        unset($where['id']);
        $add_job = M('Jobs')->where($where)->order('addtime DESC')->limit(5)->getField("id,jobs_name,companyname,company_id,addtime as time,'add' as type");
        $where['id'] = array('not in',array_keys($add_job));
        $refresh_job = M('Jobs')->where($where)->order('refreshtime DESC')->limit(5)->getField("id,jobs_name,companyname,company_id,refreshtime as time,'refresh' as type");
        foreach(array_merge($add_job,$refresh_job) as $val){
            $val['utype'] = 1;
            $val['job_url'] = url_rewrite('QS_jobsshow',array('id'=>$val['id']));
            $val['company_url'] = url_rewrite('QS_companyshow',array('id'=>$val['company_id']));
            $val['time_cn'] = daterange(time(),$val['time'],'Y-m-d');
            $job[] = $val;
        }
        $tmp = array_merge($resume, $job);
        //$time = array_column($tmp,'time');
        foreach($tmp as $item) {
            $time[] = $item['time'];
        }
        rsort($time);
        foreach($time as $val) {
            foreach($tmp as $k => $v) {
                if($val == $v['time']) {
                    $data[] = $v;
                    unset($tmp[$k]);
                }
            }
        }
        count($data)==0 && $this->ajaxReturn(0,'暂无数据！');
        $this->ajaxReturn(1,'获取动态成功！',$data);
	}
}
?>