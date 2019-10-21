<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class ClearcacheController extends BackendController {
    public function _initialize() {
        parent::_initialize();
    }
    public function index(){
        if(IS_POST){
            if (in_array("tplcache",I('post.type')))
            {           
                $this->check_dir_access(RUNTIME_PATH);
                rmdirs(RUNTIME_PATH.'Cache',true);
            }
            if (in_array("datacache",I('post.type')))
            {
                $this->check_dir_access(RUNTIME_PATH);
                rmdirs(RUNTIME_PATH.'Data',true);
                $this->check_dir_access(QSCMS_DATA_PATH.'static');
                rmdirs(QSCMS_DATA_PATH.'static');
                $this->check_dir_access(STATISTICS_PATH);
                rmdirs(STATISTICS_PATH);
                $this->check_dir_access(QSCMS_DATA_PATH.'wxpay');
                rmdirs(QSCMS_DATA_PATH.'wxpay');
            }
            if (in_array("logcache",I('post.type')))
            {
                $this->check_dir_access(RUNTIME_PATH);
                rmdirs(RUNTIME_PATH.'Logs',true);
                 //rmdirs(APP_PATH.'Home/View/default/Ad',true);
            }
            if (in_array("pagecache",I('post.type')))
            {           
                $this->check_dir_access(QSCMS_DATA_PATH.'html');
                rmdirs(QSCMS_DATA_PATH.'html');
            }
            if(APP_DEBUG === false){
                @unlink(RUNTIME_PATH.'common~runtime.php');
            }
            $this->success('清除缓存成功！');
            exit();
        }
        $this->display();
    }
    protected function check_dir_access($dir){
        if(!is_writable($dir)){
            $this->error('您没有权限操作'.$dir.'！');
        }
    }
}
?>