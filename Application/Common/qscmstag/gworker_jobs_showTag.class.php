<?php
/**
 * 职位详情
 */
namespace Common\qscmstag;
defined('THINK_PATH') or exit();
class gworker_jobs_showTag {
	protected $params = array();
	protected $map = array();
    function __construct($options) {
    	$array = array(
    		'列表名'			=>	'listname',
    		'职位id'			=>	'id'
    	);
    	foreach ($options as $key => $value) {
    		$this->params[$array[$key]] = $value;
    	}
        $this->map['id'] = array('eq',intval($this->params['id']));
    	$this->params['listname']=isset($this->params['listname'])?$this->params['listname']:"info";
    }
    public function run(){
        $model = D('Gworker/GworkerJobs');
        $val = $model->where($this->map)->find();
        if(!$val){
            $controller = new \Common\Controller\BaseController;
            $controller->_empty();
        }
        if(C('SUBSITE_TYPE')){
            $subsite_id = get_jobs_subsite_id($val);
            check_url($subsite_id);
        }
        $val['amount']=$val['amount']=="0"?'若干':$val['amount'];
        $subsite_id = get_jobs_subsite_id($val);
        $val['url']=url_rewrite('QS_gworker_show',array('id'=>$val['id']),$subsite_id);
        $val['welfare'] = htmlspecialchars_decode($val['welfare'],ENT_QUOTES);
        $val['intro'] = htmlspecialchars_decode($val['intro'],ENT_QUOTES);
        $val['demand'] = htmlspecialchars_decode($val['demand'],ENT_QUOTES);
        $val['refreshtime_cn']=daterange(time(),$val['refreshtime'],'Y-m-d');
        $storetag = $val['tag'] ? explode(",", $val['tag']) : array();
        $tagArr = array();
        if (!empty($storetag)) {
            foreach ($storetag as $key => $value) {
                $arr = explode("|", $value);
                $tagArr[] = $arr[1];
            }
        }
        $val['tag'] = $tagArr;
        $model->where(array('id' => $val['id']))->setInc('click', 1);
        return $val;
    }
}