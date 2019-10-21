<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class SubscribeController extends BackendController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('JobsSubscribe');
    }
    public function _before_search($map){
        $addtime = I('get.addtime',0,'intval');
        $key = I('get.key','','trim');
        $key_type = I('get.key_type',0,'intval');
        $order="addtime DESC";
        if ($key && $key_type>0)
        {
            if($key_type==1){
                $where['intention_jobs']  = array('like', '%'.$key.'%');
                $where['search_name']  = array('like','%'.$key.'%');
                $where['_logic'] = 'or';
                $map['_complex'] = $where;
            }
            if($key_type==2)$map['district_cn']=array('like', '%'.$key.'%');
            if($key_type==3)$map['email']=$key;
        }
        else
        {
            if (!$addtime)
            {
                $settr=strtotime("-".$addtime." day");
                $map['addtime'] = array('gt',$settr);
            }
        }
        return $map;
    }
    public function index(){
        $this->_name = 'JobsSubscribe';
        parent::index();
    }
    public function delete(){
        $this->_name = 'JobsSubscribe';
        parent::delete();
    }
}