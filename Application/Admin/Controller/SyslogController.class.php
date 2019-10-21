<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class SyslogController extends BackendController{
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Syslog');
    }
    public function _before_search($data){
        if($data['settr']){
            $data['log_addtime'] = array('gt',strtotime("-".intval($data['settr'])." day"));
            unset($data['settr']);
        }
        return $data;
    }
    public function pidel_syslog(){
        if(IS_POST){
            $l_type = I('post.l_type');
            count($l_type) <= 0 && $this->error('请选择错误类型！');
            $starttime = convert_datefm(I('post.starttime','','trim'),2);
            !$starttime && $this->error('请填写开始时间！');
            $endtime = convert_datefm(I('post.endtime','','trim'),2);
            !$endtime && $this->error('请填写结束时间！');  
            if($starttime>$endtime) $this->error('开始时间不能大于结束时间！');
            if(false !== $reg = $this->_mod->where(array('l_type'=>array('in',$l_type),'l_time'=>array('between',array($starttime,$endtime))))->delete()){
                $this->success("删除成功，共删除{$reg}行数据!");
            }else{
                $this->error('删除失败，请重新操作！');
            }
        }else{
            $this->display();
        }
    }
}
?>