<?php 
namespace Common\Model;
use Think\Model;
class SubsiteModel extends Model
{
	protected $_validate = array(
		array('s_sitename,s_domain,s_m_domain,s_tpl,s_district','identicalNull','',0,'callback'),
		array('s_order','number','',2,'regex'),
		array('s_district','in','{%subsite_s_district_format}',2,'regex'),
		array('s_sitename','0,10','{%subsite_s_sitename_length}',0,'length'),
		array('s_domain','0,50','{%subsite_s_domain_length}',0,'length'),
		array('s_tpl','0,30','{%subsite_s_tpl_length}',0,'length'),
		array('s_m_domain','0,50','{%subsite_s_m_domain_length}',0,'length'),
		array('s_title','0,200','{%subsite_s_title_length}',0,'length'),
		array('s_keywords','0,200','{%subsite_s_keywords_length}',0,'length'),
		array('s_description','0,600','{%subsite_s_description_length}',0,'length')
	);
	protected $_auto = array (
		array('s_tpl','default')
	);
	public function get_subsites(){
		if(false === $subsite = F('subsite_ids')){
			$subsite = $this->where(array('s_effective'=>1))->getfield('s_id',true);
			$subsite[] = 0;
		}
		F('subsite_ids',$subsite);
		return $subsite;
	}
	public function get_subsite_cache(){
		if(false === $subsite = F('subsite_list')) $subsite = $this->subsite_cache();
		return $subsite;
	}
	public function subsite_cache(){
		$data = $this->where(array('s_effective'=>1))->order('s_order desc,s_id')->getfield('s_id,s_domain,s_m_domain,s_sitename,s_district,s_districtname,s_logo_home,s_logo_user,s_logo_other,s_title,s_keywords,s_description,s_tpl,s_m_tpl,s_level,s_map_ak,s_map_zoom,s_map_center_x,s_map_center_y');
		if(false === $config = F('config')) $config = D('Config')->config_cache();
		$home_domain = str_replace('http://','',$config['qscms_site_domain']);
		$home_domain = str_replace('https://','',$home_domain);
		$home = array('s_domain'=>$home_domain,'s_id'=>0,'s_sitename'=>'总站','s_level'=>2);

        if(preg_match('/com.cn|net.cn|gov.cn|org.cn$/',$home_domain) === 1){
            $domain = array_slice(explode('.', $home_domain), -3, 3);
        }else{
            $domain = array_slice(explode('.', $home_domain), -2, 2);
        }
        $domain = implode('.',$domain);
        if($domain != $home_domain){
        	$home1 = array('s_domain'=>$domain,'s_id'=>0,'s_sitename'=>'总站','s_level'=>2);
        }

		if($config['qscms_wap_domain']){
			$wap_domain = str_replace('http://','',$config['qscms_wap_domain']);
			$wap_domain = str_replace('https://','',$wap_domain);
			$subsite_list[$wap_domain] = array('s_domain'=>$home_domain,'s_m_domain'=>$wap_domain,'s_id'=>0,'s_sitename'=>'总站','s_level'=>2);
			$home['s_m_domain'] = $wap_domain;
			$home1 && $home1['s_m_domain'] = $wap_domain;
		}
		$subsite_list[$home_domain] = $home;
		$domain && $subsite_list[$domain] = $home1;
		foreach ($data as $key => $val) {
			$subsite_list[$val['s_domain']] = $val;
			$val['s_m_domain'] && $subsite_list[$val['s_m_domain']] = $val;
		}
		F('subsite_list',$subsite_list);
		return $subsite_list;
	}
	public function subsite_district_cache(){
		$data = $this->field('s_id,s_district')->where(array('s_effective'=>1))->order('s_order desc,s_id')->select();
		foreach ($data as $key => $val) {
			if(strpos($val['s_district'],',')){
				foreach (explode(',',$val['s_district']) as $_val) {
					$subsite_district[$_val] = $val['s_id'];
				}
			}else{
				$subsite_district[$val['s_district']] = $val['s_id'];
			}
		}
		F('subsite_district',$subsite_district);
		return $subsite_district;
	}
	public function get_subsite_domain(){
		if(false === $subsite = F('subsite_domain_list')){
			$subsite_list = $this->where(array('s_effective'=>1))->order('s_order desc,s_id')->getfield('s_id,s_sitename,s_domain,s_district,s_districtname,s_m_domain,s_tpl,s_spell,s_ordid');
			$home_domain = str_replace('http://','',C('qscms_site_domain'));
			$home_domain = str_replace('https://','',$home_domain);
			$home = array('s_id'=>'0','s_domain'=>$home_domain,'s_tpl'=>C('qscms_template_dir'),'s_sitename'=>'总站');
			if(C('qscms_wap_domain')){
				$wap_domain = str_replace('http://','',C('qscms_wap_domain'));
				$wap_domain = str_replace('https://','',$wap_domain);
				$home['s_m_domain'] = $wap_domain;
			}
			$subsite[0] = $home;
			foreach ($subsite_list as $key => $val) {
				$subsite[$key] = $val;
			}
			F('subsite_domain_list',$subsite);
		}
		return $subsite;
	}
	/**
	 * [_after_insert 添加成功]
	 */
	public function _after_insert($data, $options){
		$page_mod = D('page');
		if(C('apply.Subsite') && in_array('subsite_id',$page_mod->getDbFields()) && $this->get_subsite_domain()){
			$page = $page_mod->field('id',true)->where(array('subsite_id'=>0))->select();
			foreach ($page as $key=>$val) {
				$page[$key]['subsite_id'] = $data['s_id'];
			}
			$page_mod->addAll($page);
			$data['s_tpl'] = $data['s_tpl']?:'default';
		}
	}
	/**
     * 后台有更新则删除缓存
     */
    protected function _before_write($data, $options) {
        F('subsite_list', NULL);
        F('subsite_domain_list',NULL);
    }
    /**
     * 后台有删除也删除缓存
     */
    protected function _after_delete($data,$options){
        F('subsite_list', NULL);
        F('subsite_domain_list',NULL);
        //删除分站数据
        $where['subsite_id'] = $options['where']['s_id'];
        D('Page')->where($where)->delete();
        D('AdCategory')->where($where)->delete();
        D('Ad')->where($where)->delete();
        D('PmsSys')->where($where)->delete();
        D('Article')->where($where)->delete();
        D('Explain')->where($where)->delete();
        D('Link')->where($where)->delete();
        D('Notice')->where($where)->delete();
    }
}
?>