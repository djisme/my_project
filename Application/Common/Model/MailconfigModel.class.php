<?php
namespace Common\Model;
use Think\Model;
class MailconfigModel extends Model{
	protected $_validate = array(
		array('name,value','identicalNull','',0,'callback'),
		array('name','1,100','{%mailconfig_length_error_name}',0,'length'), // 名称
	);
	protected $_auto = array ( 
		
	);
	/**
     * 读取系统参数生成缓存文件
     */
    public function config_cache() {
        $config = array();
        $res = $this->where()->getField('name,value');
        foreach ($res as $key=>$val) {
        	$un_result=unserialize($val);
        	$config[$key] = $un_result ? $un_result : $val;
        }
        F('mailconfig', $config);
        return $config;
    }
    /**
     * [get_cache 读取缓存]
     */
    public function get_cache(){
        if(false === $config = F('mailconfig')){
            $config = $this->config_cache();
        }
        return $config;
    }
    /*
        邮件发送相关
        @data array send_type->发送类型 sendto_email->邮箱 alias->主题  body->内容
    */
    public function send_mail($data,$replac=array()){
        if(C('apply.Subsite') && C('SUBSITE_VAL.s_id') > 0){
            C('SUBSITE_VAL.s_title') && $config['qscms_site_title'] = $config['qscms_site_name'] = C('SUBSITE_VAL.s_title');
            C('SUBSITE_VAL.s_domain') && $config['qscms_site_domain'] = C('SUBSITE_VAL.s_domain');
            C($config);
        }
        $Email = new \Common\qscmslib\email();
        if(!$data['send_type']) {
            if(false === $Email->smtp_mail($data['sendto_email'],$data['subject'],$data['body'])) return $Email->getError();
            return true;
        }else{
            if(false === $mail_templates = F('mail_templates')) $mail_templates = D('MailTemplates')->config_cache();
            $title= $Email->label_replace($mail_templates[$data['send_type']]['title'],$replac);
            $body= $Email->label_replace($mail_templates[$data['send_type']]['value'],$replac);
            if(false === $Email->smtp_mail($data['sendto_email'],$title,$body)) return $Email->getError();
            return true;
        }
    }
    /**
     * 后台有更新则删除缓存
     */
    protected function _before_write($data, $options) {
        F('mailconfig', NULL);
    }
    /**
     * 后台有删除也删除缓存
     */
    protected function _after_delete($data,$options){
        F('mailconfig', NULL);
    }
}
?>