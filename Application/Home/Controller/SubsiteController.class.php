<?php
namespace Home\Controller;
use Common\Controller\FrontendController;
class SubsiteController extends FrontendController{
	// 初始化函数
	public function _initialize(){
		parent::_initialize();
	}
	public function index(){
		$passport = $this->_user_server();
		if(!$passport->is_sitegroup() && C('apply.Subaite')){
			if($district = D('Subsite')->get_subsite_domain()){
				$ipInfos = GetIpLookup();
				foreach ($district as $key => $val) {
					if(strpos($val['s_districtname'],$ipInfos['district'])){
						$temp = $val;
						$district_org = $ipInfos['district'];
						break;
					}
					if(strpos($val['s_districtname'],$ipInfos['city'])){
						$temp = $val;
						$district_org = $ipInfos['city'];
						break;
					}
					if(strpos($val['s_districtname'],$ipInfos['province'])){
						$temp = $val;
						$district_org = $ipInfos['province'];
						break;
					}
				}
				if(C('SUBSITE_VAL.s_id') != $temp['s_id']){
					$this->assign('subsite_org',$temp);
					$this->assign('district_org',$district_org);
				}
				unset($district[0]);
				foreach ($district as $key => $val) {
					$district_list[$val['s_ordid']][] = $val;
				}
				$count = count($district_list);
				$mod = (int)($count/2);
				$this->assign('mod_val',$count%2 ? $mod+1 : $mod);
				$this->assign('district',$district);
				$this->assign('district_list',$district_list);
			}else{
				$this->error('请添加分站信息！');
			}
		}else{
			$sub = $passport->uc('sitegroup')->get_subsite();
			$count = count($sub['subsite']);
			$mod = (int)($count/2);
			foreach ($sub['subsite'] as $key => $val) {
				$sitegroup_list[$val['ordid']][] = $val;
			}
			$this->assign('mod_val',$count%2 ? $mod+1 : $mod);
            $this->assign('sitegroup',$sub['subsite']);
            $this->assign('sitegroup_org',$sub['subsite_org']);
            $this->assign('sitegroup_list',$sitegroup_list);
		}
		$this->display();
	}
	public function set(){
		$sid = I('request.sid',0,'intval');
		$district = D('Subsite')->get_subsite_domain();
		if($district[$sid]){
			$domain = 'http://';
			if(C('PLATFORM')=='mobile' && $this->apply['Mobile']){
				if($district[$sid]['s_m_domain']){
					$domain .= $district[$sid]['s_m_domain'];
				}else{
					$domain .= $district[$sid]['s_domain'];
					$action = 'mobile/index/index';
				}
			}else{
				$domain .= $district[$sid]['s_domain'];
			}
		}else{
			if(C('PLATFORM')=='mobile' && $this->apply['Mobile']){
				if(C('qscms_wap_domain')){
					$domain = C('qscms_wap_domain');
				}else{
					$domain = C('qscms_site_domain');
					$action = 'mobile/index/index';
				}
			}else{
				$domain = C('qscms_site_domain');
			}
		}
		cookie('_subsite_domain',$domain);
		redirect($domain.U('subsite/set_subsite',array('key'=>encrypt(http_build_query(array('k'=>$domain,'a'=>$action)))),'','',true));
	}
	public function set_subsite(){
		$key = I('request.key','','trim');
		if($key){
			parse_str(decrypt($key),$data);
			$domain = $data['k'];
			$action = $data['a'] ? U($data['a']) : '';
		}else{
			if(C('PLATFORM')=='mobile' && $this->apply['Mobile']){
				$domain = C('qscms_wap_domain')?:C('qscms_site_domain');
			}else{
				$domain = C('qscms_site_domain');
			}
		}
		cookie('_subsite_domain',$domain);
		redirect($domain.$action);
	}
}
?>