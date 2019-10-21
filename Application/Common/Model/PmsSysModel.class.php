<?php
namespace Common\Model;
use Think\Model;
class PmsSysModel extends Model{
	protected $_validate = array(
		array('message','identicalNull','',0,'callback'),
	);
	protected $_auto = array ( 
		array('dateline','time',1,'function'),
		array('spms_usertype',0),
		array('spms_type',1),
		array('replyuid',0),
	);
	/**
	 * [sys_cache 缓存当前系统消息最新时间]
	 */
	public function sys_cache(){
		if(C('SUBSITE_VAL')){
			 $where['subsite_id'] = C('SUBSITE_VAL.s_id');
			 $cache = '_'.C('SUBSITE_VAL.s_id');
		}
		if($data = $this->where($where)->order('dateline desc')->getfield('dateline',true)){
			$sysMgs = array('time'=>$data[0],'count'=>count($data));
			F('sysMsg'.$cache,$sysMgs);
			return $sysMgs;
		}
		return array('time'=>0,'count'=>0);
	}
	/**
     * 后台有更新则删除缓存
     */
    protected function _before_write($data, $options) {
    	if(C('apply.Subsite')){
    		$subsite_list = D('Subsite')->get_subsite_domain();
    		foreach ($subsite_list as $key => $val) {
    			F('sysMsg_'.$val['s_id'], NULL);
    		}
		}
        F('sysMsg',NULL);
    }
    /**
     * 后台有删除也删除缓存
     */
    protected function _after_delete($data,$options){
        $this->_before_write();
    }
}
?>