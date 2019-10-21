<?php
/**
 * 分站
 *
 * @author brivio
 */
namespace Common\qscmstag;
defined('THINK_PATH') or exit();
class subsiteTag {
    public function run(){
        return D('Subsite')->get_subsite_domain();
    }
}