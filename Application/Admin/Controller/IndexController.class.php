<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class IndexController extends BackendController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Menu');
    }
    public function index() {
        session('error_login_count_admin',0);
        if(false === $menus = F("admin_menu/{$_SESSION['admin']['role_id']}/sub_menu_0")){
            $menus = $this->_mod->sub_menu_cache(0);
        }
        $this->assign('menus', $menus['menu']);
        $this->display();
    }
    public function panel() {
        $message = array();
        if(ini_get('register_globals')){
            $message[] = array(
                'type' => 'warning',
                'content' => '您的php.ini中register_globals为On，强烈建议你设为Off，否则将会出现严重的安全隐患和数据错乱！',
            );
        }
        if (is_dir('./install')) {
            $message[] = array(
                'type' => 'error',
                'content' => "您还没有删除 install 文件夹，出于安全的考虑，我们建议您删除 install 文件夹。",
            );
        }
        /*if (APP_DEBUG == true) {
            $message[] = array(
                'type' => 'error',
                'content' => "您网站的 DEBUG 没有关闭，出于安全考虑，我们建议您关闭程序 DEBUG。",
            );
        }
        if(strpos(__GROUP__,'admin')){
            $message[] = array(
                'type' => 'error',
                'content' => "您的网站管理中心目录为 ./admin ，出于安全的考虑，我们建议您修改目录名。",
            );
        }*/
        $this->assign('message', $message);
        $system_info = array(
            'server_os' => PHP_OS,
            'web_server' => $_SERVER["SERVER_SOFTWARE"],
            'php_version' => PHP_VERSION,
            'mysql_version' => mysql_get_server_info()
        );
        $home_version = F('Home/Install/version','',APP_PATH);
        $system_info['version'] = $home_version['version'];
        $this->assign('system_info', $system_info);
        $this->assign('charts',STATISTICS_PATH.'userreg_30_days.xml');
        $this->display();
    }
    public function login() {
        if (IS_POST) {
            $username = I('post.username','','trim');
            $password = I('post.password','','trim');
            $verify_code = I('post.verify_code','','trim');
            !$username && $err = '用户名不能为空!';
            if(!$err && !$password) $err = '密码不能为空!';

            if (!$err && C('qscms_captcha_open')==1 && (C('qscms_captcha_config.admin_login')==0 || (session('?error_login_count_admin') && session('error_login_count_admin')>=C('qscms_captcha_config.admin_login')))){
                if(true !== $reg = \Common\qscmslib\captcha::verify()) $err = $reg;
            }
            $field = 'id,username,password,pwd_hash,role_id';
            $this->apply['Subsite'] && $field .= ',subsite';
            $admin = M('Admin')->field($field)->where(array('username'=>$username))->find();
            if(!$err && !$admin) $err='管理员帐号不存在';
            if(!$err && $admin['password'] != md5(md5($password).$admin['pwd_hash'].C('PWDHASH'))) $err='用户名或密码错误!';
            if(!$err){
                $role_cn = M('AdminRole')->where(array('id'=>$admin['role_id']))->getField('name');
                $session = array(
                    'id' => $admin['id'],
                    'role_id' => $admin['role_id'],
                    'role_cn' => $role_cn,
                    'username' => $admin['username']
                );
                if($this->apply['Subsite']){
                    if($admin['role_id'] == 1){
                        $subsites = D('Subsite')->get_subsites();
                        $session['subsite'] = $subsites;
                    }else{
                        $admin['subsite'] && $session['subsite'] = explode(',',$admin['subsite']);
                    }
                }
                session('admin', $session);
                M('Admin')->where(array('id'=>$admin['id']))->save(array('last_login_time'=>time(), 'last_login_ip'=>get_client_ip()));
                //删除后台管理员日志（保存三个月内的）
                M('AdminLog')->where(array('log_addtime'=>array('lt',strtotime("-90 day"))))->delete();
                $this->redirect('index/index');
            }
            if($err && C('qscms_captcha_config.admin_login')>0 && C('qscms_captcha_open')==1){
                $error_login_count_admin = session('?error_login_count_admin')?(session('error_login_count_admin')+1):1;
                session('error_login_count_admin',$error_login_count_admin);
            }

            $this->assign('err',$err);
        }
        $this->assign('verify_userlogin_admin',$this->check_captcha_open(C('qscms_captcha_config.admin_login'),'error_login_count_admin'));
        $this->display();
    }
    public function logout() {
        session('admin', null);
        $this->redirect('index/login');
    }
    public function verify_code() {
        $config = C('qscms_captcha_config');
        if($config['lang'] = 'en'){
            \Common\ORG\Image::BuildImageVerify($config,'captcha');
        }else{
            \Common\ORG\Image::GBVerify($config,'captcha');
        }
    }
    /**
     * [top_menu 顶部导航]
     */
    public function top_menu(){
        if(false === $menus = F("admin_menu/{$_SESSION['admin']['role_id']}/sub_menu_0")){
            $menus = $this->_mod->sub_menu_cache(0);
        }
        $this->assign('menus',$menus['menu']);
        $this->display();
    }
    /**
     * [left_menu 左侧导航]
     */
    public function left_menu(){
        $menuid = I('request.menuid',0,'intval');
        if ($menuid) {
            if(false === $menus = F("admin_menu/{$_SESSION['admin']['role_id']}/sub_menu_{$menuid}")){
                $menus = $this->_mod->sub_menu_cache($menuid);
            }
            $menus = $menus['menu'];
        } else {
            if(false === $menus = F('console_menu')){
                $menus = $this->_mod->console_cache();
            }
        }
        $this->assign('menus',$menus);
        $this->display();
    }
    /**
     * [total 待处理事件统计]
     * @return [type] [description]
     */
    public function total(){
        //今日
        $users = M('Members')->where(array('reg_time'=>array('egt',strtotime("today"))))->group('utype')->getfield('utype,count(uid) as num');
        $total['personal_users'] = $users[2]?$users[2]:'0';
        $total['company_users'] = $users[1]?$users[1]:'0';
        $total['resumes'] = M('Resume')->where(array('addtime'=>array('egt',strtotime("today"))))->count('id');
        $total['jobs'] = M('Jobs')->where(array('addtime'=>array('egt',strtotime("today"))))->count('id');
        $total['jobsTemp'] = M('JobsTmp')->where(array('addtime'=>array('egt',strtotime("today"))))->count('id');
        $total['jobs'] = intval($total['jobs']) + intval($total['jobsTemp']);
        $total['resume_refresh'] = M('RefreshLog')->where(array('type'=>2001,'addtime'=>array('egt',strtotime("today"))))->count();
        $total['resume_down'] = M('CompanyDownResume')->where(array('down_addtime'=>array('egt',strtotime("today"))))->count('did');
        $order = M('Order')->where(array('addtime'=>array('egt',strtotime("today"))))->group('utype')->getfield('utype,count(id) as num');
        $total['personal_order'] = $order[2]?:0;
        $total['company_order'] = $order[1]?:0;
        $total['interview'] = M('CompanyInterview')->where(array('interview_addtime'=>array('egt',strtotime("today"))))->count('did');
        $total['jobs_apply'] = M('PersonalJobsApply')->where(array('apply_addtime'=>array('egt',strtotime("today"))))->count();
        $total['jobs_refresh'] = M('RefreshLog')->where(array('type'=>1001,'addtime'=>array('egt',strtotime("today"))))->count();
        //昨日
        $users = M('Members')->where(array('reg_time'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->group('utype')->getfield('utype,count(uid) as num');
        $total['yesterday_personal_users'] = $users[2]?$users[2]:'0';
        $total['yesterday_company_users'] = $users[1]?$users[1]:'0';
        $total['yesterday_resumes'] = M('Resume')->where(array('addtime'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->count('id');
        $total['yesterday_jobs'] = M('Jobs')->where(array('addtime'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->count('id');
        $total['yesterday_jobsTemp'] = M('JobsTmp')->where(array('addtime'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->count('id');
        $total['yesterday_jobs'] = intval($total['yesterday_jobs']) + intval($total['yesterday_jobsTemp']);
        $total['yesterday_resume_refresh'] = M('RefreshLog')->where(array('type'=>2001,'addtime'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->count();
        $total['yesterday_resume_down'] = M('CompanyDownResume')->where(array('down_addtime'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->count('did');
        $order = M('Order')->where(array('addtime'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->group('utype')->getfield('utype,count(id) as num');
        $total['yesterday_personal_order'] = $order[2] ?: 0;
        $total['yesterday_company_order'] = $order[1] ?: 0;
        $total['yesterday_interview'] = M('CompanyInterview')->where(array('interview_addtime'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->count('did');
        $total['yesterday_jobs_apply'] = M('PersonalJobsApply')->where(array('apply_addtime'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->count();
        $total['yesterday_jobs_refresh'] = M('RefreshLog')->where(array('type'=>1001,'addtime'=>array('BETWEEN',array(strtotime("yesterday"),strtotime("today")))))->count();
        //待处理事项
        $jobs_audit = M('JobsTmp')->where(array('audit'=>2))->count('id');
        $jobs_audit1 = M('Jobs')->where(array('audit'=>2))->count('id');
        $total['jobs_audit'] = $jobs_audit + $jobs_audit1;
        $total['company_audit'] = M('CompanyProfile')->where(array('audit'=>2))->count('id');
        $total['resume_audit'] = M('Resume')->where(array('audit'=>2))->count('id');
        $join = 'left join '.C('DB_PREFIX') .'resume r on r.id='.C('DB_PREFIX').'resume_img.resume_id';
        $total['resume_img_audit'] = M('ResumeImg')->join($join)->where(array(C('DB_PREFIX').'resume_img.audit'=>2))->count();
        $total['feedback'] = M('Feedback')->where(array('audit'=>array('eq',1)))->count('id');
        $report = M('Report')->where(array('audit'=>array('eq',1)))->count('id');
        $report1 = M('ReportResume')->where(array('audit'=>array('eq',1)))->count('id');
        $total['report'] = $report + $report1;
        //30天用户注册量
        $this->write_xml();
        $this->ajaxReturn(1,'待处理事件',$total);
    }
    protected function write_xml(){
        if(false === check_cache('userreg_30_days.xml')){
            $datelist=array();
            for ($i = 30; $i>=1; $i--){
                $day = date("m/d",strtotime("-{$i} day"));
                $datelist[$day]=0;
            }
            $user = M('Members')->where(array('reg_time'=>array('egt',strtotime("-30 day"))))->getfield('uid,reg_time');
            foreach ($user as $key => $val) {
                $date = date("m/d",$val);
                $datelist[$date]++;
            }
            $content = "<graph divlinecolor='FEDD69' numdivlines='4' showAreaBorder='1' areaBorderColor='cccccc' numberPrefix='' showNames='1' numVDivLines='29' vDivLineAlpha='30' formatNumberScale='0' rotateNames='1'  decimalPrecision='0' bgColor='' yAxisName=''  showAlternateVGridColor='0' canvasBorderThickness='0' decimalPrecision='0' areaBorderColor='cccccc'>
>\n";
            $content .= "<categories fontSize='9'>\n";
            foreach($datelist as $key => $value){
                $content .= "<category name='{$key}'/>\n";
            }
            $content .= "</categories>\n";
            $content .= "<dataset  color='00CC00' showValues='0' areaAlpha='30' showAreaBorder='1' areaBorderThickness='1' areaBorderColor='006600'>\n";
            foreach($datelist as $key => $value)
            {
                $content .= "<set value='{$value}' />\n";
            }
            $content .= "</dataset>\n";
            $content .= "</graph>\n";
            write_cache('userreg_30_days.xml','',$content);
        }
    }
    /**
     * [often description]
     */
    public function often() {
        if (isset($_POST['do'])) {
            $id_arr = isset($_POST['id']) && is_array($_POST['id']) ? $_POST['id'] : '';
            $this->_mod->where(array('ofen'=>1))->save(array('often'=>0));
            $id_str = implode(',', $id_arr);
            $this->_mod->where('id IN('.$id_str.')')->save(array('often'=>1));
            $this->success(L('operation_success'));
        } else {
            $r = $this->_mod->admin_menu(0);
            $list = array();
            foreach ($r as $v) {
                $v['sub'] = $this->_mod->admin_menu($v['id']);
                foreach ($v['sub'] as $key=>$sv) {
                    $v['sub'][$key]['sub'] = $this->_mod->admin_menu($sv['id']);
                }
                $list[] = $v;
            }
            $this->assign('list', $list);
            $this->display();
        }
    }
    public function map() {
        $r = $this->_mod->admin_menu(0);
        $list = array();
        foreach ($r as $v) {
            $v['sub'] = $this->_mod->admin_menu($v['id']);
            foreach ($v['sub'] as $key=>$sv) {
                $v['sub'][$key]['sub'] = $this->_mod->admin_menu($sv['id']);
            }
            $list[] = $v;
        }
        $this->assign('list', $list);
        $this->display();
    }
}