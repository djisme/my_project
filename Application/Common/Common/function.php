<?php
/**
 * Passport 加密函数
 *
 * @param        string          等待加密的原字串
 * @param        string          私有密匙(用于解密和加密)
 *
 * @return       string          原字串经过私有密匙加密后的结果
 */
function encrypt($txt, $key = '_qscms') {
    // 使用随机数发生器产生 0~32000 的值并 MD5()
    srand((double)microtime() * 1000000);
    $encrypt_key = md5(rand(0, 32000));
    // 变量初始化
    $ctr = 0;
    $tmp = '';
    // for 循环，$i 为从 0 开始，到小于 $txt 字串长度的整数
    for($i = 0; $i < strlen($txt); $i++) { 
        // 如果 $ctr = $encrypt_key 的长度，则 $ctr 清零
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        // $tmp 字串在末尾增加两位，其第一位内容为 $encrypt_key 的第 $ctr 位，
        // 第二位内容为 $txt 的第 $i 位与 $encrypt_key 的 $ctr 位取异或。然后 $ctr = $ctr + 1
        $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
    }
    // 返回结果，结果为 passport_key() 函数返回值的 base64 编码结果
    return base64_encode(passport_key($tmp, $key));
}

/**
 * Passport 解密函数
 *
 * @param        string          加密后的字串
 * @param        string          私有密匙(用于解密和加密)
 *
 * @return       string          字串经过私有密匙解密后的结果
 */
function decrypt($txt, $key = '_qscms') {
    // $txt 的结果为加密后的字串经过 base64 解码，然后与私有密匙一起，
    // 经过 passport_key() 函数处理后的返回值
    $txt = passport_key(base64_decode($txt), $key);
    // 变量初始化
    $tmp = '';
    // for 循环，$i 为从 0 开始，到小于 $txt 字串长度的整数
    for ($i = 0; $i < strlen($txt); $i++) {
        // $tmp 字串在末尾增加一位，其内容为 $txt 的第 $i 位，
        // 与 $txt 的第 $i + 1 位取异或。然后 $i = $i + 1
        $tmp .= $txt[$i] ^ $txt[++$i];
    }
    // 返回 $tmp 的值作为结果
    return $tmp;
}
/**
 * Passport 密匙处理函数
 *
 * @param        string          待加密或待解密的字串
 * @param        string          私有密匙(用于解密和加密)
 *
 * @return       string          处理后的密匙
 */
function passport_key($txt, $encrypt_key) {
    // 将 $encrypt_key 赋为 $encrypt_key 经 md5() 后的值
    $encrypt_key = md5($encrypt_key);
    // 变量初始化
    $ctr = 0;
    $tmp = '';
    // for 循环，$i 为从 0 开始，到小于 $txt 字串长度的整数
    for($i = 0; $i < strlen($txt); $i++) {
        // 如果 $ctr = $encrypt_key 的长度，则 $ctr 清零
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        // $tmp 字串在末尾增加一位，其内容为 $txt 的第 $i 位，
        // 与 $encrypt_key 的第 $ctr + 1 位取异或。然后 $ctr = $ctr + 1
        $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
    }
    // 返回 $tmp 的值作为结果
    return $tmp;
}
function addslashes_deep($value) {
    $value = is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    return $value;
}

function stripslashes_deep($value) {
    if (is_array($value)) {
        $value = array_map('stripslashes_deep', $value);
    } elseif (is_object($value)) {
        $vars = get_object_vars($value);
        foreach ($vars as $key => $data) {
            $value->{$key} = stripslashes_deep($data);
        }
    } else {
        $value = stripslashes($value);
    }

    return $value;
}

function todaytime() {
    return mktime(0, 0, 0, date('m'), date('d'), date('Y'));
}

function admin_date($date){
    $ld = mktime(0,0,0,1,1,date("Y",time())); //年
    if($date >= $ld){
        $fdate = date('m-d H:i', $date);
    }else{
        $fdate = date('Y-m-d', $date);
    }
    return $fdate;
}
/**
 * 友好时间
 */
function sub_day($endday,$staday,$range=''){
    $value = $endday - $staday;
    if($value < 0)
    {
        return '';
    }
    elseif($value >= 0 && $value < 59)
    {
        return ($value+1)."秒";
    }
    elseif($value >= 60 && $value < 3600)
    {
        $min = intval($value / 60);
        return $min."分钟";
    }
    elseif($value >=3600 && $value < 86400)
    {
        $h = intval($value / 3600);
        return $h."小时";
    }
    elseif($value >= 86400 && $value < 86400*30)
    {
        $d = intval($value / 86400);
        return intval($d)."天";
    }
    elseif($value >= 86400*30 && $value < 86400*30*12)
    {
        $mon  = intval($value / (86400*30));
        return $mon."月";
    }
    else{   
        $y = intval($value / (86400*30*12));
        return $y."年";
    }
}
/**
 * 时间格式变换
 */
function daterange($endday,$staday,$format='Y-m-d',$color='',$range=3){
    $value = $endday - $staday;
    if($value < 0)
    {
        return '';
    }
    elseif($value >= 0 && $value < 59)
    {
        $return=($value+1)."秒前";
    }
    elseif($value >= 60 && $value < 3600)
    {
        $min = intval($value / 60);
        $return=$min."分钟前";
    }
    elseif($value >=3600 && $value < 86400)
    {
        $h = intval($value / 3600);
        $return=$h."小时前";
    }
    elseif($value >= 86400)
    {
        $d = intval($value / 86400);
        if ($d>$range)
        {
        return date($format,$staday);
        }
        else
        {
        $return=$d."天前";
        }
    }
    if ($color)
    {
    $return="<span id=\"r_time\" style=\"color:{$color}\">".$return."</span>";
    }
    return $return;  
}
/**
 * 友好时间
 */
function fdate($time) {
    if (!$time) return false;
    $fdate = '';
    $d = time() - intval($time);
    $ld = time() - mktime(0, 0, 0, 0, 0, date('Y')); //年
    $md = time() - mktime(0, 0, 0, date('m'), 0, date('Y')); //月
    $byd = time() - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd = time() - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd = time() - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td = time() - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd = time() - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日 H:i', $time);
                break;
            case $d < $td:
                $fdate = '后天' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = $d . '秒前';
                break;
            case $d < 3600:
                $fdate = floor($d / 60) . '分钟前';
                break;
            case $d < $dd:
                $fdate = '今天' . date('H:i', $time);
                break;
            case $d < $yd:
                $fdate = '昨天' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m-d H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m-d H:i', $time);
                break;
            default:
                $fdate = date('Y-m-d', $time);
                break;
        }
    }
    return $fdate;
}
function PA($data){
    $data = str_replace('AM','上午',$data);
    $data = str_replace('PM','下午',$data);
    return $data;
}
/**
 * [ddate 时间差]
 */
function ddate($s,$e){
    $starttime = strtotime($s);
    $endtime = strtotime($e);
    $startyear = date('Y',$starttime);
    $startmonth = date('m',$starttime);
    $endyear = date('Y',$endtime);
    $endmonth = date('m',$endtime);
    $return = '';
    $return_year = $endyear - $startyear;
    $return_month = $endmonth - $startmonth;
    if($return_month<0){
        $return_month += 12;
        $return_year -= 1;
    }

    if($return_year>0){
        $return .= $return_year.'年';
    }
    if($return_month>0){
        $return .= $return_month.'个月';
    }
    return $return;
}
/**
 * 对象转换成数组
 */
function object_to_array($obj) {
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {
        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}
/**
 * 获取图片
 */
function attach($attach, $type) {
    if(empty($attach)) return false;
    if (false === strpos($attach, 'http://') && false === strpos($attach, 'https://')) {
        //本地附件
        return C('qscms_site_domain').__ROOT__ . '/data/upload/' . $type . '/' . $attach;
        //远程附件
        //todo...
    } else {
        //URL链接
        return $attach;
    }
}
/**
 * 获取图片
 */
function att($attach, $type) {
    if(empty($attach)) return false;
    if (false === strpos($attach, 'http://')) {
        //本地附件
        return '/data/upload/' . $type . '/' . $attach;
        //远程附件
        //todo...
    } else {
        //URL链接
        return $attach;
    }
}
/**
 * [badword 敏感词处理]
 * @param  [string] $data [文本内容]
 * @return [string]       [替换后的内容]
 */
function badword($data){
    if(!C('qscms_badword_status')) return $data;
    //敏感词处理
    return D('Badword')->check($data);
}
/**
 * [requireone 验证数组内容必填一项：一维数组]
 */
function requireone($d){
    $t = array_filter($d);
    return !empty($t);
}
/**
 * [ad 广告位初始化]
 * @param  [string] $tpl [广告位名称]
 * @return [html]        [广告位终端]
 * @return [num]         [广告数量]
 */
function ad($tpl,$num='',$type='pc'){
    W('Common/Banner/index',array($tpl,$num,$type));
}
/**
 * [P 模板跳转 参数处理]
 * @param array $data [数组]
 */
function P($data = array()){
    $get = $_GET;
    unset($get['_URL_']);
    unset($get['p']);
    unset($get['page']);
    return U(CONTROLLER_NAME.'/'.ACTION_NAME,array_merge($get,$data));
}
/*
    字段单独验证 
*/
function fieldRegex($value,$rule) 
{
    $validate = array(
        'require'   =>  '/.+/',
        'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
        'mobile'    =>  '/^(13|15|14|16|17|18|19)\d{9}$/',
        'tel'        =>  '/^(([0\\+]\\d{2,3}-)?(0\\d{2,3})-)?(\\d{7,8})(-(\\d{3,}))?$/',
        'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
        'currency'  =>  '/^\d+(\.\d+)?$/',
        'number'    =>  '/^\d+$/',
        'zip'       =>  '/^\d{6}$/',
        'integer'   =>  '/^[-\+]?\d+$/',
        'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
        'english'   =>  '/^[A-Za-z]+$/',
        'img'       =>  '(.*)\\.(jpg|bmp|gif|ico|pcx|jpeg|tif|png|raw|tga)$/',
        'in'        =>  '/^(\d{1,10},)*(\d{1,10})$/',
        'qq'        =>  '/^[1-9]*[1-9][0-9]*$/'
    );
    // 检查是否有内置的正则表达式
    if(isset($validate[strtolower($rule)]))
        $rule       =   $validate[strtolower($rule)];
    return preg_match($rule,$value)===1;
}
function contact_hide($data,$IsWhat = 2){
    if($IsWhat == 1){
        return preg_replace('/([0[0-9]{2,3}[-]?[1-9]]|[1-9])[0-9]{2,4}([0-9]{3}[-]?[0-9]?)/i','$1****$2',$data);
    }elseif($IsWhat == 2){
        return  preg_replace('/(1[34578]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$data);
    }elseif($IsWhat == 3){
        $email_array = explode("@", $data);
        $n = mb_strlen($email_array[0],'utf-8');
        return str_pad(substr($email_array[0],0,intval($n/2)),$n,'*').$email_array[1];
    }else{
        $n = mb_strlen($data,'utf-8');
        $str = str_pad('',intval($n/2),'*');
        return str_replace(substr($data,intval($n/4),intval($n/2)),$str,$data);
    }
}
function setLog($params){
    $tableArr = array('SysLog','SysEmailLog','MembersLog','MembersChargeLog','RefreshLog','AdminLog','MembersSetmealLog');
    if(!$params['_t'])
    {
        return array('state'=>false,'error'=>'参数错误！');
    }
    if(!in_array($params['_t'],$tableArr))
    {
        return array('state'=>false,'error'=>'表名错误！');
    }
    $mod = D($params['_t']);
    $params['__hash__'] = $_POST['__hash__'];
    C('SUBSITE_VAL.s_id') && $params['subsite_id'] = C('SUBSITE_VAL.s_id');

    if(false===$mod->create($params)){
        return array('state'=>false,'error'=>$mod->getError());
    }
    else
    {
        if(false !== $rid = $mod->add()){
            return array('state'=>true);
        }else{
            return array('state'=>false,'error'=>'日志记录失败！');
        }
    }
}
/**
 * 写后台日志
 */
function admin_write_log($user){
    $log['log_url'] = $_SERVER["REQUEST_URI"];
    $log['log_ip'] = get_client_ip();
    $Ip = new \Common\ORG\IpLocation('UTFWry.dat');
    $rst = $Ip->getlocation();
    $log['log_address'] = $rst['country'];
    $log['log_addtime'] = time();
    $log['operater_id'] = $user['id'];
    $log['operater'] = $user['username'];
    M('AdminLog')->add($log);
}
//会员操作日志
function write_members_log($user,$type='',$txt,$source = false,$params=array(),$operater_id=0,$operater=''){
    $members_log = $params;
    $members_log['_t']='MembersLog';
    $members_log['log_uid']=$user['uid'];
    $members_log['log_utype']=$user['utype'];
    $members_log['log_username']=$user['username'];
    $members_log['log_type']=$type;
    $members_log['log_value'] = $txt;
    if($source){
        $members_log['log_source']=$source;
    }else if(C('LOG_SOURCE')){
        $source = C('LOG_SOURCE');
        switch($source){
            case 2:
                $members_log['log_source']='触屏版';break;
            default:
                $members_log['log_source']='网页版';break;
        }
    }else{
        $members_log['log_source']='网页版';
    }
    $members_log['operater_id'] = $operater_id;
    $members_log['operater'] = $operater;
    setLog($members_log);
    unset($members_log);
}
//截取字符
function cut_str($sourcestr,$cutlength, $start=0,$dot='')
{
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr);
    $mb_str_length = mb_strlen($sourcestr,'utf-8');
    while(($n < $cutlength) && ($i <= $str_length))
    {
        $temp_str = substr($sourcestr,$i,1);
        $ascnum = ord($temp_str);
        if($ascnum >= 224){
            $returnstr = $returnstr.substr($sourcestr,$i,3);
            $i = $i + 3;
            $n++;
        }
        elseif($ascnum >= 192){
            $returnstr = $returnstr.substr($sourcestr,$i,2);
            $i = $i + 2;
            $n++;
        }
        elseif(($ascnum >= 65) && ($ascnum <= 90)){
            $returnstr = $returnstr.substr($sourcestr,$i,1);
            $i = $i + 1;
            $n++;
        }
        else{
            $returnstr = $returnstr.substr($sourcestr,$i,1);
            $i = $i + 1;
            $n = $n + 0.5;
        }
    }
    if ($mb_str_length > $cutlength){
        $returnstr = $returnstr.$dot;
    }
    return $returnstr;
}
// 分词
function get_tags($title, $num=10,$mode = false,$s = false)
{
    vendor('pscws4.pscws4#class');
    $pscws = new \PSCWS4();
    $pscws->set_dict(QSCMS_DATA_PATH . 'scws/dict.utf8.xdb');
    $pscws->set_rule(QSCMS_DATA_PATH . 'scws/rules.utf8.ini');
    $pscws->set_ignore(true);
    $pscws->send_text($title);
    if($mode){
        //开启二元分词
        $pscws->set_multi(2);
        while (false !== $tag = $pscws->get_result()) {
            foreach ($tag as $key => $val) {
                $tags[] = $val['word'];
            }
        }
    }else{
        //不开启二元分词，返回系统计算出来的最关键词汇列表
        $words = $pscws->get_tops($num);
        $tags = array();
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
    }
    $pscws->close();
    return $s ? $tags : array_map("fulltextpad",$tags);
}
/**
 * 前台分页统一
 */
function pager($count, $pagesize) {
    $pager = new Common\ORG\Page($count, $pagesize);
    $pager->rollPage = 5;
    $pager->setConfig('first', '首页');
    $pager->setConfig('prev', '上一页');
    $pager->setConfig('next', '下一页');
    $pager->setConfig('last', '最后一页');
    if(C('PLATFORM')=='mobile'){
        $pager->setConfig('theme', '%upPage% <span>%nowPage%/%totalPage%</span> %downPage%');
    }else{
        $pager->setConfig('theme', '%first% %upPage% %linkPage% %downPage% %end%');
    }
    return $pager;
}
/**
 *  解析 url
*/
function parseUrl($data)
{  
    $parse_url=parse_url(parse_url_request_url());
    $parse_url=$parse_url['query'];
    parse_str($parse_url,$urlarray);
    foreach($data as $key=>$val)
    {
        $urlarray[$key]=$val;
    }
    return '?'.http_build_query($urlarray);
}
function parse_url_request_url(){     
    if (isset($_SERVER['REQUEST_URI']))     
    {        
        $url = $_SERVER['REQUEST_URI'];    
    }
    else
    {    
        if(isset($_SERVER['argv']))        
        {           
            $url = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];      
        }         
        else        
        {          
            $url = $_SERVER['PHP_SELF'] .'?'.$_SERVER['QUERY_STRING'];
        }  
    }    
    return $url; 
}
/**
 * [url_rewrite 伪静态]
 * @param  [type]    $alias     [description]
 * @param  [array]   $get       [传值]
 * @param  boolean   $rewrite   [是否开启伪静态]
 * @return [string]             [链接]
 */
function url_rewrite($alias=NULL,$get=NULL,$subsite_id=0,$rewrite=true){
    $page_list = D('Page')->page_cache();
    $url = $url_prefix = '';
    if($get['key'] && C('qscms_key_urlencode')==1){
        $get['key'] = urlencode($get['key']);
    }
    if(C('apply.Subsite')){
        $url_prefix = subsite_url($alias,$url,$subsite_id);
        preg_match("/^((https|http|ftp|rtsp|mms):\/\/)?([^(\/|\?)]+)/i", $url_prefix, $matches);
        $http_host = $matches[3];
    }else{
        $http_host = $_SERVER['HTTP_HOST'];
    }
    if($page_list[$alias]['url']=='0' || $rewrite==false){//原始链接
        $domain_rules = C('APP_SUB_DOMAIN_RULES');
        $sub_domain = $domain_rules[$http_host];
        if($sub_domain){
            strtolower($sub_domain) != strtolower($page_list[$alias]['module']) && $m = $page_list[$alias]['module'].'/';
        }elseif(MODULE_NAME != C('DEFAULT_MODULE') || strtolower($page_list[$alias]['module']) != strtolower(MODULE_NAME)){
            $m = $page_list[$alias]['module'].'/';
        }
        if($m){
            $url = U($m.$page_list[$alias]['controller'].'/'.$page_list[$alias]['action'],$get,true,false,true);
        }else{
            $url = U($page_list[$alias]['controller'].'/'.$page_list[$alias]['action'],$get,true,false,true,true);
        }
    }else{
        if($url = $page_list[$alias]['rewrite']){
            if($page_list[$alias]['pagetpye']=='2' && empty($get['page'])){
                $get['page']=1;
            }
            if($get){
                foreach($get as $k=>$val){
                    $data['($'.$k.')'] = $val;
                }
                $url = strtr($url,$data);
            }
            $url = preg_replace('/\(\$(\w+)\)/','',$url);
            $url = __ROOT__.'/'.$url.C('URL_HTML_SUFFIX');
        }else{
            $domain_rules = C('APP_SUB_DOMAIN_RULES');
            $sub_domain = $domain_rules[$http_host];
            if($sub_domain){
                strtolower($sub_domain) != strtolower($page_list[$alias]['module']) && $m = $page_list[$alias]['module'].'/';
            }elseif(MODULE_NAME != C('DEFAULT_MODULE') || strtolower($page_list[$alias]['module']) != strtolower(MODULE_NAME)){
                $m = $page_list[$alias]['module'].'/';
            }
            if($m){
                $url = U($m.$page_list[$alias]['controller'].'/'.$page_list[$alias]['action'],$get);
            }else{
                $url = U($page_list[$alias]['controller'].'/'.$page_list[$alias]['action'],$get,true,false,false,true);
            }
        }
    }
    return $url_prefix . $url;
    //return C('apply.Subsite') ? subsite_url($alias,$url,$subsite_id) : $url;
}
function subsite_url($alias,$url,$subsite_id){
    $alias_k = $alias;
    if(C('PLATFORM')=='mobile') $alias_k = str_replace('QS_','QS_m_',$alias);
    if(false === $subsite_alias = F('subsite_alias')) $subsite_alias = D('SubsiteConfig')->get_subsite_alias();
    if(!$domain = $subsite_alias[$alias_k]){
        if(in_array($alias,array('QS_resumeshow','QS_companyshow','QS_help','QS_helplist','QS_helpshow','QS_mall_index','QS_goods_list','QS_goods_show','QS_mall_charts'))){
            $domain = C('PLATFORM')=='mobile' && C('qscms_wap_domain') ? C('qscms_wap_domain') : C('qscms_site_domain');
            stripos(C('qscms_site_domain'),'http') === false && $domain = 'http://'.$domain;
        }else{
            if($subsite_id > 0){
                if(false === $subsite = F('subsite_domain_list')) $subsite = D('Subsite')->get_subsite_domain();
                $domain = C('PLATFORM')=='mobile' && $subsite[$subsite_id]['s_m_domain'] ? $subsite[$subsite_id]['s_m_domain'] : $subsite[$subsite_id]['s_domain'];
                $domain = 'http://'.$domain;
            }
        }
    }else{
        $domain = 'http://'.$domain;
    }
    //$dir = str_replace('/','',C('qscms_site_dir'));
    //$dir = $dir ? C('qscms_site_dir') : '';
    //return $domain ? $domain . $dir . $url : C('SUBSITE_DOMAIN') . $dir . $url;
    return $domain ? $domain . $url : C('SUBSITE_DOMAIN') . $url;
}
function check_url($subsite_id){
    !$subsite_id && $subsite_id = 0;
    if(C('SUBSITE_VAL.s_id') != $subsite_id){
        if(C('qscms_subsite_method') == 1){
            if(false === $subsite = F('subsite_domain_list')) $subsite = D('Subsite')->get_subsite_domain();
            $domain = C('PLATFORM')=='mobile' && $subsite[$subsite_id]['s_m_domain'] ? $subsite[$subsite_id]['s_m_domain'] : $subsite[$subsite_id]['s_domain'];
            $url = 'http://'.$domain.$_SERVER['REQUEST_URI'];
            redirect301($url);
        }else{
            redirect404();
        }
    }
}
function redirect301($url){
    send_http_status(301);
    redirect($url);
}
function redirect404(){
    $controller = new \Common\Controller\BaseController;
    $controller->_empty();
}
function https_request($url,$data = null){
    if(function_exists('curl_init')){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION,1);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }else{
        return false;
    }
}
//百度链接提交
function baidu_submiturl($urls,$param){
	$_SUBURL=D('BaiduSubmiturl')->get_cache();
	if($_SUBURL['token'] && $_SUBURL[$param]=='1' && function_exists('curl_init')){
		if(!is_array($urls)){
			$urls = array($urls);
		}
		foreach ($urls as $key => $value) {
			if(stripos($value, 'http://')===false){
				$urls[$key] = C('qscms_site_domain').$value;
			}
		}
		$site_domain = str_replace('http://','',C('qscms_site_domain'));
		$api = 'http://data.zz.baidu.com/urls?site='.$site_domain.'&token='.$_SUBURL['token'];
		$ch = curl_init();
		$options =  array(
				CURLOPT_URL => $api,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => implode("\n", $urls),
				CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
		);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
	}
}
function convert_datefm($date,$format,$separator="-"){
    if ($format=="1"){
        return date("Y-m-d", $date);
    }else{
        if (!preg_match("/^[0-9]{4}(\\".$separator.")[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/",$date))  return false;
        $date=explode($separator,$date);
        return mktime(0,0,0,$date[1],$date[2],$date[0]);
    }
}
/**
 * 删除目录
 */
function rmdirs($dir,$rm_self=false)  
{  
    $d = dir($dir);  
    while (false !== ($child = $d->read())){  
        if($child != '.' && $child != '..'){  
            if(is_dir($dir.'/'.$child)){
                rmdirs($dir.'/'.$child,true);  
            } 
            else{
                unlink($dir.'/'.$child);  
            } 
        }  
    }  
    $d->close();  
    $rm_self && rmdir($dir);  
}
/**
 * 获取随机字符串
 */
function get_rand_char($num)
{
    if (empty($num))
    {
        return false;
    }
    $string = 'ABCDEFGHIGKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $str = '';
    for ($i = 0; $i < $num; $i++)
    {
        $pos = rand(0, 51);
        $str .= $string{$pos};
    }
    return $str;
}
/**
 * 获取随机手机验证码
 */
function getmobilecode(){
    $rand=mt_rand(1000, 9999);
    return $rand;
}
/**
 * 获取子目录
 */
function getsubdirs($dir) {
    $subdirs = array();
    if(!$dh = opendir($dir)) return $subdirs;
    while ($f = readdir($dh)) {
        if($f =='.' || $f =='..') continue;
        $path = $dir.'/'.$f;  //如果只要子目录名, path = $f;
        $subdir=$f;
        if(is_dir($path)) {
                $subdirs[] = $subdir;
        }
    }
    closedir($dh);
    return $subdirs;
}
/**
 * 生成excel
 */
function create_excel($top_str,$data){
    header("Content-Type: application/vnd.ms-execl");
    header("Content-Disposition: attachment; filename=myExcel.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $top_str;
    foreach ($data as $k => $v) {
        foreach ($v as $k1 => $v1) {
            echo $v1;
            echo ($k1+1)<count($v)?"\t":"";
        }
        echo "\t\n";
    }
}
/**
 *计算坐标点周围某段距离的正方形的四个点
 *@param lng float 经度
 *@param lat float 纬度
 *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
 *@return array 正方形的四个点的经纬度坐标
 */
function square_point($lng, $lat,$distance = 0.5){
    $earth_radius = 6378.138;
    $dlng =  2 * asin(sin($distance / (2 * $earth_radius)) / cos(deg2rad($lat)));
    $dlng = rad2deg($dlng);
    $dlat = $distance/$earth_radius;
    $dlat = rad2deg($dlat);
    return array(
        'lt'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
        'rt'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
        'lb'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
        'rb'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
    );
}
//检查缓存
function check_cache($cache,$dir,$days=1){
    $cachename=STATISTICS_PATH.$dir.$cache;
    if(!is_dir(STATISTICS_PATH.$dir)) mkdir(STATISTICS_PATH.$dir);
    if (!is_writable(STATISTICS_PATH.$dir)){
        exit("请先将“".STATISTICS_PATH.$dir."”目录设置可读写！");
    }
    if (file_exists($cachename)){
        $filemtime=filemtime($cachename);
        if ($filemtime>strtotime("-".$days." day")){
            return file_get_contents($cachename);
        }
    }
    return false;
}
//写入缓存
function write_cache($cache,$dir,$json){
    if(!is_dir(STATISTICS_PATH.$dir)) mkdir(STATISTICS_PATH.$dir);
    $cachename=STATISTICS_PATH.$dir.$cache;
    if (!file_put_contents($cachename, $json, LOCK_EX)){
        $fp = @fopen($cachename, 'wb+');
        if (!$fp){
            exit('生cache文件失败，请设置“'.STATISTICS_PATH.$dir.'”的读写权限');
        }
        if (!@fwrite($fp, $json)){
            exit('生cache文件失败，请设置“'.STATISTICS_PATH.$dir.'”的读写权限');
        }
        @fclose($fp);
    }
}
/**
 * [get_first 后台取get传值第一个元素key及val]
 */
function get_first(){
    if(!$_GET) return '';
    return '&'.key($_GET).'='.current($_GET);
}
/**
 * 获取跳转到触屏的地址
 * $data = array(
 *      'c'=>'',
 *      'a'=>'',
 *      'params'=>'a=1&b=2&c=3',
 * );
 */
function build_mobile_url($data=array()){
	
    $config = F('Mobile/Conf/config','',APP_PATH);
	
    $url = C('qscms_site_domain').C('qscms_site_dir');
    if(empty($data)){
        if($config['URL_MODEL']==0)
        {
            $url = C('qscms_wap_domain')?C('qscms_wap_domain'):(C('qscms_site_domain').C('qscms_site_dir').'?m=Mobile');
        }
        else if($config['URL_MODEL']==1)
        {
            $url = C('qscms_wap_domain')?C('qscms_wap_domain'):(C('qscms_site_domain').C('qscms_site_dir').'index.php/Mobile');
        }
        else if($config['URL_MODEL']==2)
        {
            $url = C('qscms_wap_domain')?C('qscms_wap_domain'):(C('qscms_site_domain').C('qscms_site_dir').'Mobile');
        }
    }else{
        if($config['URL_MODEL']==0)
        {
            if(C('qscms_wap_domain')){
                $url = C('qscms_wap_domain').'?c='.$data['c'].'&a='.$data['a'].($data['params']?('&'.$data['params']):'');
            }else{
                $url = C('qscms_site_domain').C('qscms_site_dir').'?m=Mobile&c='.$data['c'].'&a='.$data['a'].($data['params']?('&'.$data['params']):'');
            }
        }
        else if($config['URL_MODEL']==1)
        {
            if($data['params']){
                $data['params'] = str_replace("&", "/", $data['params']);
                $data['params'] = str_replace("=", "/", $data['params']);
            }
            if(C('qscms_wap_domain')){
                $url = C('qscms_wap_domain').'/index.php/'.$data['c'].'/'.$data['a'].($data['params']?('/'.$data['params']):'');
            }else{
                $url = C('qscms_site_domain').C('qscms_site_dir').'index.php/Mobile/'.$data['c'].'/'.$data['a'].($data['params']?('/'.$data['params']):'');
            }
        }
        else if($config['URL_MODEL']==2)
        {
            if($data['params']){
                $data['params'] = str_replace("&", "/", $data['params']);
                $data['params'] = str_replace("=", "/", $data['params']);
            }
            if(C('qscms_wap_domain')){
                $url = C('qscms_wap_domain').'/'.$data['c'].'/'.$data['a'].($data['params']?('/'.$data['params']):'');
            }else{
                $url = C('qscms_site_domain').C('qscms_site_dir').'Mobile/'.$data['c'].'/'.$data['a'].($data['params']?('/'.$data['params']):'');
            }
        }
    }
    return $url;
}
function GetIp(){  
    $realip = '';  
    $unknown = 'unknown';  
    if (isset($_SERVER)){  
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){  
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
            foreach($arr as $ip){  
                $ip = trim($ip);  
                if ($ip != 'unknown'){  
                    $realip = $ip;  
                    break;  
                }  
            }  
        }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){  
            $realip = $_SERVER['HTTP_CLIENT_IP'];  
        }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){  
            $realip = $_SERVER['REMOTE_ADDR'];  
        }else{  
            $realip = $unknown;  
        }  
    }else{  
        if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){  
            $realip = getenv("HTTP_X_FORWARDED_FOR");  
        }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){  
            $realip = getenv("HTTP_CLIENT_IP");  
        }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){  
            $realip = getenv("REMOTE_ADDR");  
        }else{  
            $realip = $unknown;  
        }  
    }  
    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;  
    return $realip;  
}  
function GetIpLookup($ip = ''){  
    if(empty($ip)){  
        $ip = GetIp();  
    }  
    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
    if(empty($res)){ return false; }  
    $jsonMatches = array();  
    preg_match('#\{.+?\}#', $res, $jsonMatches);  
    if(!isset($jsonMatches[0])){ return false; }  
    $json = json_decode($jsonMatches[0], true);  
    if(isset($json['ret']) && $json['ret'] == 1){  
        $json['ip'] = $ip;  
        unset($json['ret']);  
    }else{  
        return false;  
    }  
    return $json;  
}
function del_punctuation($str){
    if(!$str) return '';
    $char = "`·。、！？：；﹑•＂…‘’“”〝〞∕¦‖—　〈〉﹞﹝「」‹›〖〗】【»«』『〕〔》《﹐¸﹕︰﹔！¡？¿﹖﹌﹏﹋＇´ˊˋ―﹫︳︴¯＿￣﹢﹦﹤‐­˜﹟﹩﹠﹪﹡﹨﹍﹉﹎﹊ˇ︵︶︷︸︹︿﹀︺︽︾ˉ﹁﹂﹃﹄︻︼（）";
    $pattern = array(
        "/[[:punct:]]/i",
        '/['.$char.']/u',
        '/[ ]{2,}/'
    );
    $str = preg_replace($pattern, '', $str);
    return $str;
}
function multimerge($a, $b){
    if (is_array($b) && count($b)) {
        foreach ($b as $k => $v) {
            if (is_array($v) && count($v)) {
                $a[$k] = in_array($k,array('SESSION_OPTIONS')) ? multimerge($a[$k], $v) : $v;
            } else {
                $a[$k] = $v;
            }
        }
    } else {
        $a = $b;
    }
    return $a;
}
function emoji_unicode($str){
    $str = json_encode($str);
    $str = preg_replace("#(\\\(ue|ud)[0-9a-f]{3})#ie","addslashes('\\1')",$str);
    $str = json_decode($str);
    return $str;
}
function unicode_emoji($str){
    $str = preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2','UTF-8', pack('H4', '\\1'))",$str);
    return $str;
}
function _I($data){
    if(C('DEFAULT_FILTER')) {
        $filters    =   explode(',',C('DEFAULT_FILTER'));
        foreach($filters as $filter){
            $data = is_array($data)?array_map($filter,$data):$filter($data);
        }
    }
    return $data;
}
/**
 * 查询手机号归属地
 */
function get_mobile_address($mobile){
    $url = 'http://www.74cms.com/index.php?m=Home&c=Plus&a=get_mobile_address&mobile='.$mobile;
    $result = https_request($url);
    $result_arr = json_decode($result,true);
    if($result_arr['status']==1){
        return $result_arr['data'];
    }else{
        return false;
    }
}
/**
 * [get_jobs_subsite_id 获取职位所属分站subsite_id]
 * @return [数组] [jobs] 职位信息
 */
function get_jobs_subsite_id($jobs){
    if(!C('apply.Subsite')) return '';
    if(false === $subsite_district = F('subsite_district')) $subsite_district = D('Subsite')->subsite_district_cache();
    $a = array_filter(explode('.',$jobs['district']));
    for($i=count($a)-1;$i>=0;$i--){
        if($subsite_id = $subsite_district[$a[$i]]){
            break;
        }
    }
    return $subsite_id?:0;
}
/**
 * [get_city_info 获取地区信息(地区id组合，地区中文组合)]
 */
function get_city_info($ids){
    if(!$ids) return array('district'=>'','district_cn'=>'');
    $city = D('CategoryDistrict')->get_district_cache('all');
    if(false === $city_cate = F('city_search_cate')) $city_cate = D('CategoryDistrict')->city_search_cache();
    $spell = D('CategoryDistrict')->city_cate_cache();
    foreach(explode(',',$ids) as $val) {
        $a = array_filter(explode('_',$city_cate[$val]));
        $c = count($a);
        if(!$c){
            continue;
        }elseif($c == 1){
            $district_cn[] = $city[0][$a[0]];
        }elseif($c > 1){
            $district_cn[] = $city[$a[$c-2]][$a[$c-1]];
        }
        foreach($a as $key=>$v){
            $b[] = $key == 0 ? $city[0][$v] : $city[$a[$key-1]][$v];
            $s[] = $spell['id'][$v]['spell'];
        }
        $district_cn_all[] = implode('/',$b);
        $district[] = implode('.',$a);
        $district_spell[] = implode('.',$s);
    }
    return array('district'=>implode(',',$district),'district_cn_all'=>implode(',',$district_cn_all),'district_cn'=>implode(',',$district_cn),'district_spell'=>implode(',',$district_spell));
}