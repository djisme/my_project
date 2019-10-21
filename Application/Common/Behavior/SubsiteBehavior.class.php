<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://74cms.com All rights reserved.
// +----------------------------------------------------------------------
/**
 * 分站标识
 * @category   Extend
 * @package  Extend
 * @subpackage  Behavior
 */
namespace Common\Behavior;
class SubsiteBehavior{
    public function run(&$params) {
        if(false === $apply = F('apply_list')) $apply = D('Apply')->apply_cache();
        if(!$apply['Subsite'] || MODULE_NAME == 'Admin' || CONTROLLER_NAME == 'Admin') return;
        $subsite_list = D('Subsite')->get_subsite_cache();
        if(false === $config = F('config')){
            $config = D('Config')->config_cache();
        }
        if($subsite_domain = cookie('_subsite_domain')){
            $cookie_domain = str_replace('http://','',$subsite_domain);
            $cookie_domain = str_replace('https://','',$cookie_domain);
        }else{
            $cookie_domain = $_SERVER['HTTP_HOST'];
        }
        if($subsite_list[$cookie_domain]){
            C('SUBSITE_DOMAIN',C('HTTP_TYPE').$cookie_domain);
        }else{
            $domain = C('PLATFORM')=='mobile' && $config['qscms_wap_domain'] ? $config['qscms_wap_domain'] : $config['qscms_site_domain'];
            C('SUBSITE_DOMAIN',$domain);
        }
        $home_domain = str_replace('http://','',$config['qscms_site_domain']);
        $home_domain = str_replace('https://','',$home_domain);
        if($subsite_list[$_SERVER['HTTP_HOST']]){
        	C('SUBSITE_VAL',$subsite_list[$_SERVER['HTTP_HOST']]);
            C('SUBSITE_TYPE',true);
        }else{
            $domain_config = D('SubsiteConfig')->get_subsite_config();
            $url = $domain_config[$_SERVER['HTTP_HOST']];
        	if(!IS_AJAX && (!$url || ($url['module'] != strtolower(MODULE_NAME)) || ($url['controller'] && $url['controller'] != strtolower(CONTROLLER_NAME)) || ($url['action'] && $url['action'] != strtolower(ACTION_NAME)))){
                C('SUBSITE_EMPTY',true);
            }else{
                C('SUBSITE_VAL',$subsite_list[$cookie_domain]);
            }
            //if(IS_AJAX || $url && $url['module'] == strtolower(MODULE_NAME) && $url['controller'] && $url['controller'] == strtolower(CONTROLLER_NAME) && $url['action'] && $url['action'] == strtolower(ACTION_NAME)){
            //    C('SUBSITE_VAL',$subsite_list[$home_domain]);
            //}else{
            //    C('SUBSITE_EMPTY',true);
            //}
        }
        return;
    }
}