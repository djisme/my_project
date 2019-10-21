<?php
namespace Admin\Controller;
use Common\Controller\ConfigbaseController;
class SetPerController extends ConfigbaseController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Config');
        $this->_name = 'Config';
    }
    public function index(){
        if(IS_POST){
            parent::_edit();
        }else{
            parent::edit();
        }
    }
    public function config_task(){
        $model = D('Task');
        if(IS_POST){
            $idArr = I('post.id');
            $pointsArr = I('post.points');
            $timesArr = I('post.times');
            foreach ($idArr as $key => $val) {
                $data['points'] = $pointsArr[$key];
                $data['times'] = $timesArr[$key];
                $data['status'] = intval($_POST['status_'.$val]);
                $model->where(array('id' => $val))->save($data);
                unset($data);
            }
            $this->success(L('operation_success'));
            exit;
        }
        $list = $model->where(array('utype'=>2))->select();
        $this->assign('list',$list);
        $this->display();
    }
	public function login_remind(){
        parent::_edit();
        $this->display();
    }
	 public function search(){
        parent::_edit();
        $this->display();
    }
	public function set_audit(){
        parent::_edit();
        $this->display();
    }
    /**
     * [jobs_subscribe_config 职位订阅器配置]
     */
    public function jobs_subscribe_config(){
        parent::_edit();
        $this->display();
    }
}