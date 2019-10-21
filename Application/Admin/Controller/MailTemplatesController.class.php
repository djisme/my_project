<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class MailTemplatesController extends BackendController{
	public function _initialize() {
        parent::_initialize();
    }
    public function _before_search($data){
        $data['status'] = 1;
        $this->order = 'id asc';
        $this->pagesize = 100;
        return $data;
    }
    /**
     * 模板同步
     */
    public function sync(){
        $alias = I('post.alias');
        $tpl_dir = QSCMSLIB_PATH.'mailtpl/';
        $model = D('MailTemplates');
        if(!empty($alias)){
            $num = 0;
            foreach ($alias as $key => $value) {
                $tpl = file_get_contents($tpl_dir.$value.'.html');
                if($tpl!==false){
                    $tpl = htmlspecialchars($tpl);
                    $model->where(array('alias'=>$value))->setField('value',$tpl);
                    $num++;
                }
            }
            $this->success('成功同步'.$num.'个模板！');
        }else{
            $this->error('请选择要同步的模板！');
        }
    }
}
?>