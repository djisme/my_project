<?php
/**
 * 企业列表
 */
namespace Common\qscmstag;
defined('THINK_PATH') or exit();
class company_listTag {
    protected $params = array();
    protected $map = array();
    protected $order;
    protected $limit;
    protected $string;
    function __construct($options) {
        $array = array(
            '列表名'            =>  'listname',
            '显示数目'          =>  'row',
            '开始位置'          =>  'start',
            '企业名长度'        =>  'companynamelen',
            '描述长度'          =>  'brieflylen',
            '填补字符'          =>  'dot',
            '行业'              =>  'trade',
            '企业性质'          =>  'nature',
            '关键字'            =>  'key',
            '排序'              =>  'displayorder',
            '分页显示'          =>  'paged',
            '公司页面'          =>  'companyshow',
            '去除id'            =>  'except_id',
            '列表页'            =>  'listpage'
        );
        foreach ($options as $key => $value) {
            $this->params[$array[$key]] = $value;
        }
        $this->params['listname']=isset($this->params['listname'])?$this->params['listname']:"list";
        $this->limit = isset($this->params['row'])?intval($this->params['row']):10;
        $this->limit>20 && $this->limit=20;
        $this->params['start']=isset($this->params['start'])?intval($this->params['start']):0;
        $this->params['companynamelen']=isset($this->params['companynamelen'])?intval($this->params['companynamelen']):15;
        $this->params['dot']=isset($this->params['dot'])?$this->params['dot']:'';
        $this->params['companyshow']=isset($this->params['companyshow'])?$this->params['companyshow']:'QS_companyshow';
        $this->params['listpage']=isset($this->params['listpage'])?$this->params['listpage']:'QS_companylist';
        $this->order = 'real_refreshtime desc,id desc';
        $this->field = '*,if(refreshtime>UNIX_TIMESTAMP(NOW()),(refreshtime-100000000),refreshtime) as real_refreshtime';
        $this->_where_trade();
        $this->params['except_id'] && $this->map['id'] = array('neq',intval($this->params['except_id']));
        if(isset($this->params['nature']) && intval($this->params['nature'])>0){
            $this->map['nature'] = array('eq',intval($this->params['nature']));
        }
        if(isset($this->params['key']) && trim($this->params['key'])<>''){
            $this->params['key'] = urldecode(urldecode($this->params['key']));
            $this->map['companyname'] = array('like','%'.trim($this->params['key']).'%');
        }
        //$this->map['nature'] = array('neq',0);
        if(C('SUBSITE_VAL.s_id') > 0) $this->map['subsite_id'] = C('SUBSITE_VAL.s_id');
        $this->params['dot'] = isset($this->params['dot']) ? trim($this->params['dot']) : '...';
    }
    public function run(){
        if($this->params['paged']){
            $count = M('CompanyProfile')->where($this->map);
            $total = $count->count();
            $pager = pager($total, $this->limit);
            $pager->showname = $this->params['listpage'];
            $page = $pager->fshow();
            $this->params['start']>0 && $pager->firstRow = $this->params['start'];
            $this->limit = $pager->firstRow.','.$pager->listRows;
        }else{
            $this->limit = $this->params['start'].','.$this->limit;
            $total = 0;
            $page = '';
        }
        
        $result = M('CompanyProfile')->field($this->field)->where($this->map)->order($this->order)->limit($this->limit)->select();
        $list = array();
        foreach ($result as $key => $value) {
            $row = $value;
            $row['companyname_']=$row['companyname'];
            $row['companyname']=cut_str($row['companyname'],$this->params['companynamelen'],0,$this->params['dot']);
            $row['refreshtime_cn'] = $row['refreshtime']>time()?daterange(time(),$row['real_refreshtime']):daterange(time(),$row['refreshtime']);
            $row['url'] = url_rewrite($this->params['companyshow'],array('id'=>$row['id']));
            $row['comjobs_url'] = url_rewrite('QS_companyjobs',array('id'=>$row['id']));
            $row['contents']=str_replace('&nbsp;','',$row['contents']);
            if ($this->params['brieflylen']>0){
                $row['briefly']=cut_str(strip_tags($row['contents']),$this->params['brieflylen'],0,$this->params['dot']);
            }else{
                $row['briefly']=strip_tags($row['contents']);
            }
            if ($row['logo'])
            {
                $row['logo']=attach($row['logo'],'company_logo');
            }
            else
            {
                $row['logo']=attach('no_logo.png','resource');
            }
            $count_map['uid'] = $row['uid'];
            if(C('qscms_jobs_display')==1){
                $count_map['audit'] = 1;
            }
            $row['jobs_count'] = D('Jobs')->where($count_map)->count();
            $list[] = $row;
        }
        $return['page'] = $page;
        $return['total'] = $total;
        $return['list'] = $list;
        return $return;
    }

    protected function _where_trade(){
        $data = $this->params['trade'];
        if($data){
            if (strpos($data,',')){
                $arr = explode(',',$data);
                $sqlin = implode(',',array_slice($arr,0,10));
                if (fieldRegex($sqlin,'in')){
                    $this->map['trade'] = array('in',$sqlin);
                }
            }else{
                $this->map['trade'] = array('eq',intval($data));
            }
        }
    }
}