<?php
namespace Common\Model;
use Think\Model;
class MailTemplatesModel extends Model{
	protected $_validate = array(
		array('name,value','identicalNull','',0,'callback'),
		array('name','1,100','{%mail_templates_length_error_name}',0,'length'), // 名称

	);
	protected $_auto = array ( 
		
	);	

	/**
     * 读取系统参数生成缓存文件
     */
    public function config_cache() {
        $mail_templates = $this->where(array('status'=>1))->getField('alias,title,value');
        F('mail_templates', $mail_templates);
        return $mail_templates;
    }
    /**
     * 后台有更新则删除缓存
     */
    protected function _before_write($data, $options) {
        F('mail_templates', NULL);
    }
    /**
     * 后台有删除也删除缓存
     */
    protected function _after_delete($data,$options){
        F('mail_templates', NULL);
    }
}
?>