<?php 
namespace Common\Model;
use Think\Model;
class SubsiteConfigModel extends Model
{
	public function get_subsite_config(){
		if(false === $subsite_config = F('subsite_config')){
			$subsite_config = $this->where(array('status'=>1))->getfield('domain,module,controller,action');
			F('subsite_config',$subsite_config);
		}
		return $subsite_config;
	}
	public function get_subsite_alias(){
		$subsite_config = $this->where(array('status'=>1))->getfield('alias,domain');
		if(C('apply.Mall')){
			$where['module'] = 'Mall';
			C('apply.Subsite') && $where['subsite_id'] = 0;
			$mall_alias = M('Page')->where($where)->getfield('alias',true);
			foreach ($mall_alias as $val) {
				$subsite_config[$val] = $subsite_config['QS_mall'];
				$subsite_config[str_replace('QS_','QS_m_',$val)] = $subsite_config['QS_m_mall'];
			}
		}
		if(C('apply.Jobfair')){
			$where['module'] = 'Jobfair';
			C('apply.Subsite') && $where['subsite_id'] = 0;
			$jobfair_alias = M('Page')->where($where)->getfield('alias',true);
			foreach ($jobfair_alias as $val) {
				$subsite_config[$val] = $subsite_config['QS_jobfair'];
				$subsite_config[str_replace('QS_','QS_m_',$val)] = $subsite_config['QS_m_jobfair'];
			}
		}
		F('subsite_alias',$subsite_config);
		return $subsite_config;
	}
	/**
     * 后台有更新则删除缓存
     */
    protected function _before_write($data, $options) {
        F('subsite_config', NULL);
        F('subsite_alias', NULL);
    }
    /**
     * 后台有删除也删除缓存
     */
    protected function _after_delete($data,$options){
        F('subsite_config', NULL);
        F('subsite_alias', NULL);
    }
}
?>