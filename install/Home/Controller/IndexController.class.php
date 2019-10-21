<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function _initialize() {
        if(file_exists(MAIN_PROJECT_PATH.'data/install.lock') && ACTION_NAME!='step4')
		{
			exit('您已经安装过本系统，如果想重新安装，请删除data目录下install.lock文件');
		}
		$home = F('install_version','',APP_PATH . 'Data/');
		$this->home = $home;
		C('QSCMS_VERSION',$home['version']);
		C('QSCMS_RELEASE',$home['update_time']);
		$this->assign('assets_path',__ROOT__.'/install/Public');
    }
    public function index(){
    	$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    	$location = strstr($php_self,'install.php');
    	$site_dir = str_replace($location, "", $php_self);
    	$this->display();
    }
    public function step1(){
    	$system_info = array();
		$system_info['version'] = C('QSCMS_VERSION');
		$system_info['os'] = PHP_OS;
		$system_info['ip'] = $_SERVER['SERVER_ADDR'];
		$system_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
		$system_info['php_ver'] = PHP_VERSION;
		$system_info['max_filesize'] = ini_get('upload_max_filesize');
		if (PHP_VERSION<5.3) exit("安装失败，请使用PHP5.3及以上版本");
		$need_check_dirs = $this->get_need_check_dirs();
		$dir_check = $this->check_dirs($need_check_dirs);
		$mbstring = extension_loaded('mbstring');
		$pdo = extension_loaded('PDO');
		$pdo_mysql = extension_loaded('pdo_mysql');
		$enable_next = $mbstring && $pdo && $pdo_mysql && !$this->check_status?1:0;
		session('enable_next',$enable_next);
		$this->assign("dir_check", $dir_check);
		$this->assign("system_info", $system_info);
		$this->assign("enable_next", $enable_next);
		$this->assign("mbstring", $mbstring?'<span style="color:green;">√已加载</span>':'<span style="color:red;">×未加载</span>');
    	$this->assign("pdo", $pdo?'<span style="color:green;">√已加载</span>':'<span style="color:red;">×未加载</span>');
    	$this->assign("pdo_mysql", $pdo_mysql?'<span style="color:green;">√已加载</span>':'<span style="color:red;">×未加载</span>');
    	$this->display();
    }
    public function step2(){
    	if(session('enable_next')!=1){
    		$this->redirect('step1');
    	}
    	$this->display();
    }
    public function step3(){
    	if(session('enable_next')!=1){
    		$this->redirect('step1');
    	}
    	$dbtype = 'mysql';
    	$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    	$location = strstr($php_self,'install.php');
    	$site_dir = str_replace($location, "", $php_self);
    	$dbhost = isset($_POST['dbhost']) ? trim($_POST['dbhost']) : '';
	 	$dbname = isset($_POST['dbname']) ? trim($_POST['dbname']) : '';
	 	$dbuser = isset($_POST['dbuser']) ? trim($_POST['dbuser']) : '';
	 	$dbpass = isset($_POST['dbpass']) ? trim($_POST['dbpass']) : '';
	 	$dbport = isset($_POST['dbport']) ? intval($_POST['dbport']) : 3306;
	 	$pre  = isset($_POST['pre']) ? trim($_POST['pre']) : 'qs_';
	 	$dbcharset = QISHI_CHARSET;
	 	$district = isset($_POST['default_district']) ? trim($_POST['default_district']) : '';
	 	$admin_name = isset($_POST['admin_name']) ? trim($_POST['admin_name']) : '';
	    $admin_pwd = isset($_POST['admin_pwd']) ? trim($_POST['admin_pwd']) : '';
	    $admin_pwd1 = isset($_POST['admin_pwd1']) ? trim($_POST['admin_pwd1']) : '';
	    $admin_email = isset($_POST['admin_email']) ? trim($_POST['admin_email']) : '';
		if($dbhost == '' || $dbname == ''|| $dbuser == ''|| $admin_name == ''|| $admin_pwd == '' || $admin_pwd1 == '' || $admin_email == '' || $district == '')
		{
			$this->error('您填写的信息不完整，请核对');
		}
		if($admin_pwd != $admin_pwd1)
		{
			$this->error('您两次输入的密码不一致');
		}
		if (!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$admin_email))
		{
			$this->error('电子邮箱格式错误！');
		}
		if(!$db = @mysql_connect($dbhost, $dbuser, $dbpass))
		{
			$this->error('连接数据库错误，请核对信息是否正确');
		}
		if (mysql_get_server_info()<5.0) exit("安装失败，请使用mysql5以上版本");
		if (mysql_get_server_info() > '4.1')
		{
			mysql_query("CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET ".QISHI_CHARSET, $db);
		}
		else
		{
			mysql_query("CREATE DATABASE IF NOT EXISTS `{$dbname}`", $db);
		}
		mysql_query("CREATE DATABASE IF NOT EXISTS `{$dbname}`;",$db);
		if(!mysql_select_db($dbname))
		{
			$this->error('选择数据库错误，请检查是否拥有权限或存在此数据库');
		}
		mysql_query("SET NAMES '".QISHI_CHARSET."',character_set_client=binary,sql_mode='';",$db);
		ob_end_clean();
		$html ="";
		$html.= "<script type=\"text/javascript\">\n";
		$html.= "$('#installing').append('<p>数据库创建成功！...</p>');\n";
		$html.= "var div = document.getElementById('installing');";
		$html.= "div.scrollTop = div.scrollHeight;";
		$html.= "</script>";
		echo $html;
		ob_flush();
		flush();
		$mysql_version = mysql_get_server_info($db);
		$QS_pwdhash=$this->randstr(16);
		$content = '<?'."php\n";
		$content .= "return array (\n";
		$content .= "'PWDHASH'=> '{$QS_pwdhash}'\n";
		$content .= ");";
		$fp = @fopen(MAIN_PROJECT_PATH . 'Application/Common/Conf/pwdhash.php', 'wb+');
		if (!$fp)
		{
			$this->error('打开配置文件失败');
		}
		if (!@fwrite($fp, trim($content)))
		{
			$this->error('写入配置文件失败');
		}
		@fclose($fp);

		$content = '<?'."php\n";
		$content .= "return array (\n";
		$content .= "'DB_TYPE'=> '{$dbtype}',\n";
		$content .= "'DB_HOST'=> '{$dbhost}',\n";
		$content .= "'DB_NAME'=> '{$dbname}',\n";
		$content .= "'DB_USER'=> '{$dbuser}',\n";
		$content .= "'DB_PWD'=> '{$dbpass}',\n";
		$content .= "'DB_PORT'=> '{$dbport}',\n";
		$content .= "'DB_PREFIX'=> '{$pre}',\n";
		$content .= "'DB_CHARSET'=> '{$dbcharset}'\n";
		$content .= ");";
		$fp = @fopen(MAIN_PROJECT_PATH . 'Application/Common/Conf/db.php', 'wb+');
		if (!$fp)
		{
			$this->error('打开配置文件失败');
		}
		if (!@fwrite($fp, trim($content)))
		{
			$this->error('写入配置文件失败');
		}
		@fclose($fp);
		$site_domain = "http://".$_SERVER['HTTP_HOST'];
		if(is_writable(MAIN_PROJECT_PATH.'data'))
		{
			$fp = @fopen(MAIN_PROJECT_PATH.'data/install.lock', 'wb+');
			fwrite($fp, 'OK');
			fclose($fp);
		}
		$this->assign("domain", $site_domain);
		$this->assign("domaindir", $site_domain.$site_dir);
		$this->assign("v", C('QSCMS_VERSION'));
		$this->assign("t", 2);
		$this->assign("email", $admin_email);
		$this->display();
	  	if(!$fp = @fopen(APP_PATH.'Data/sql-structure.sql','rb'))
		{
			$this->error('打开文件sql-structure.sql出错，请检查文件是否存在');
		}
		$query = '';
		while(!feof($fp))
	    {
			$line = rtrim(fgets($fp,1024)); 
			if(strstr($line,'||-_-||')!=false) {
				$line = ltrim(rtrim($line,"||-_-||"),"||-_-||");
				$line = str_replace(QSCMS_PRE,$pre,$line);
				$html ="";
				$html.= "<script type=\"text/javascript\">\n";
				$html.= "$('#installing').append('<p>建立数据表 ".$line."...成功</p>');\n";
				$html.= "var div = document.getElementById('installing');";
				$html.= "div.scrollTop = div.scrollHeight;";
				$html.= "</script>";
				echo $html;
				ob_flush();
				flush();
			}else{
				if(preg_match('/;$/',$line)) 
				{
					$query .= $line."\n";
					$query = str_replace(QSCMS_PRE,$pre,$query);
					if ( $mysql_version >= 4.1 )
					{
						mysql_query(str_replace("TYPE=MyISAM", "ENGINE=MyISAM  DEFAULT CHARSET=".QISHI_CHARSET,  $query), $db);
					}
					else
					{
						mysql_query($query, $db);
					}
					$query='';
				 }
				 else if(!ereg('/^(//|--)/',$line))
				 {
				 	$query .= $line;
				 }
			}
		}
		@fclose($fp);	
		$query = '';
		if(!$fp = @fopen(APP_PATH.'Data/sql-data.sql','rb'))
		{
			$this->error('打开文件sql-data.sql出错，请检查文件是否存在');
		}
		while(!feof($fp))
		{
			 $line = rtrim(fgets($fp,1024));
			 if(ereg(";$",$line))
			 {
			 	$query .= $line;
				$query = str_replace(QSCMS_PRE,$pre,$query);
				mysql_query($query,$db);
				$query='';
			 }
			 else if(!ereg("^(//|--)",$line))
			 {
				$query .= $line;
			 }
		}
		@fclose($fp);	
		$html ="";
		$html.= "<script type=\"text/javascript\">\n";
		$html.= "$('#installing').append('<p>基础数据添加成功！...</p>');\n";
		$html.= "var div = document.getElementById('installing');";
		$html.= "div.scrollTop = div.scrollHeight;";
		$html.= "</script>";
		echo $html;
		ob_flush();
		flush();
		$query = '';
		if(!$fp = @fopen(APP_PATH.'Data/sql-hrtools.sql','rb'))
		{
			$this->error('打开文件sql-hrtools.sql出错，请检查文件是否存在');
		}
		while(!feof($fp))
		{
			 $line = rtrim(fgets($fp,1024));
			 if(ereg(";$",$line))
			 {
			 	$query .= $line;
				$query = str_replace(QSCMS_PRE,$pre,$query);
				mysql_query($query,$db);
				$query='';
			 }
			 else if(!ereg("^(//|--)",$line))
			 {
				$query .= $line;
			 }
		}
		@fclose($fp);
		$html ="";
		$html.= "<script type=\"text/javascript\">\n";
		$html.= "$('#installing').append('<p>hr工具箱数据添加成功！...</p>');\n";
		$html.= "var div = document.getElementById('installing');";
		$html.= "div.scrollTop = div.scrollHeight;";
		$html.= "</script>";
		echo $html;
		ob_flush();
		flush();	
		$query = '';
		if(!$fp = @fopen(APP_PATH.'Data/sql_category_district.sql','rb'))
		{
			$this->error('打开文件sql_category_district.sql出错，请检查文件是否存在');
		}
		while(!feof($fp))
		{
			 $line = rtrim(fgets($fp,1024));
			 if(ereg(";$",$line))
			 {
			 	$query .= $line;
				$query = str_replace(QSCMS_PRE,$pre,$query);
				mysql_query($query,$db);
				$query='';
			 }
			 else if(!ereg("^(//|--)",$line))
			 {
				$query .= $line;
			 }
		}
		@fclose($fp);	
		$html ="";
		$html.= "<script type=\"text/javascript\">\n";
		$html.= "$('#installing').append('<p>地区数据添加成功！...</p>');\n";
		$html.= "var div = document.getElementById('installing');";
		$html.= "div.scrollTop = div.scrollHeight;";
		$html.= "</script>";
		echo $html;
		ob_flush();
		flush();
		$query = '';
		if(!$fp = @fopen(APP_PATH.'Data/sql-hotword.sql','rb'))
		{
			$this->error('打开文件sql-hotword.sql出错，请检查文件是否存在');
		}
		while(!feof($fp))
		{
			 $line = rtrim(fgets($fp,1024));
			 if(ereg(";$",$line))
			 {
			 	$query .= $line;
				$query = str_replace(QSCMS_PRE,$pre,$query);
				mysql_query($query,$db);
				$query='';
			 }
			 else if(!ereg("^(//|--)",$line))
			 {
				$query .= $line;
			 }
		}
		@fclose($fp);
		$html ="";
		$html.= "<script type=\"text/javascript\">\n";
		$html.= "$('#installing').append('<p>热门关键词数据添加成功！...</p>');\n";
		$html.= "var div = document.getElementById('installing');";
		$html.= "div.scrollTop = div.scrollHeight;";
		$html.= "</script>";
		echo $html;
		ob_flush();
		flush();	
		session('site_dir',$site_dir);
		session('site_domain',$site_domain);
		mysql_query("UPDATE `{$pre}config` SET value = '{$site_dir}' WHERE name = 'site_dir'", $db);
		mysql_query("UPDATE `{$pre}config` SET value = '{$site_domain}' WHERE name = 'site_domain'", $db);
		$city = get_city_info($district);
		mysql_query("UPDATE `{$pre}config` SET value = '{$city['district']}' WHERE name = 'default_district'", $db);
		mysql_query("UPDATE `{$pre}config` SET value = '{$city['district_spell']}' WHERE name = 'default_district_spell'", $db);
		$pwd_hash=$this->randstr();
		$admin_md5pwd=md5(md5($admin_pwd).$pwd_hash.$QS_pwdhash);
		$timestamp = time();
		mysql_query("INSERT INTO `{$pre}admin` (id,username, email, password,pwd_hash, role_id,add_time, last_login_time, last_login_ip) VALUES (1, '$admin_name', '$admin_email', '$admin_md5pwd', '$pwd_hash', 1, '$timestamp', '$timestamp', '')",$db);
		$home = $this->home;
		mysql_query("INSERT INTO `{$pre}apply` (`id`, `alias`, `module_name`, `version`, `is_create_table`, `is_insert_data`, `is_exe`, `is_delete_data`, `update_time`, `setup_time`, `explain`, `versioning`, `status`) VALUES (NULL, 'Home', '骑士人才系统核心模块', '".$home['version']."', 0, 0, 0, 0, '".$home['update_time']."', ".time().", '此模块为骑士人才系统基础模块，安装其他周边模块必须在此模块的基础上增加，模块包含了系统的招聘求职等核心功能。', '基础版升级日志', 1)",$db);
		unset($dbhost,$dbuser,$dbpass,$dbname);
		copy(APP_PATH.'Data/install_version.php',MAIN_PROJECT_PATH . 'Application/Home/Install/version.php');
		$this->qscms_version();
		$this->clearcache();
		$html ="";
		$html.= "<script type=\"text/javascript\">\n";
		$html.= "window.location.href='".U('step4')."';";
		$html.= "</script>";
		echo $html;
    }
    public function step4(){
    	if(session('enable_next')!=1){
    		$this->redirect('step1');
    	}
    	$this->assign('home_url',session('site_domain').session('site_dir'));
    	$this->assign('admin_url',session('site_domain').session('site_dir').'?m=admin');
    	$this->display();
    }
    protected function clearcache(){
        rmdirs(MAIN_PROJECT_PATH.'data/Runtime/');
        rmdirs(MAIN_PROJECT_PATH.'data/apply');
        rmdirs(MAIN_PROJECT_PATH.'data/online_updater',true);
        rmdirs(MAIN_PROJECT_PATH.'data/static');
        rmdirs(MAIN_PROJECT_PATH.'data/statistics');
        rmdirs(MAIN_PROJECT_PATH.'data/wxpay');
    }
    protected function qscms_version(){
    	$config['QSCMS_VERSION'] = $this->home['version'];
        $config['QSCMS_RELEASE'] = $this->home['update_time'];
        $this->update_config($config);
    }
    protected function update_config($new_config) {
        $config_file = MAIN_PROJECT_PATH.'Application/Common/Conf/url.php';
        if (is_writable($config_file)) {
            $config = require $config_file;
            $config = array_merge($config, $new_config);
            file_put_contents($config_file, "<?php \nreturn " . stripslashes(var_export($config, true)) . ";", LOCK_EX);
            @unlink(RUNTIME_FILE);
            return true;
        } else {
            return false;
        }
    }
    protected function get_need_check_dirs(){
    	return array(
    		'install',
    		'data',
    		'data/apply',
    		'data/avatar',
    		'data/backup',
    		'data/backup/database',
    		'data/backup/tpl',
    		'data/baiduxml',
    		'data/cron_log',
    		'data/html',
    		'data/rewrite',
    		'data/Runtime',
    		'data/scws',
    		'data/sqllog',
    		'data/static',
    		'data/statistics',
    		'data/upload',
    		'data/wxpay',
    		'Application/Home/View',
    		'Application/Home/Install',
    		'Application/Common/Conf/config.php',
    		'Application/Common/Conf/db.php',
    		'Application/Common/Conf/pwdhash.php',
    		'Application/Common/Conf/sub_domain.php',
    		'Application/Common/Conf/sys_url.php',
    		'Application/Common/Conf/url.php',
    		'Application/Home/Conf/config.php',
    		);
    }
    protected function check_dirs($dirs)
	{
	    $checked_dirs = array();
	    foreach ($dirs AS $k=> $dir)
	    {
			$checked_dirs[$k]['dir'] = $dir;
	        if (!file_exists(MAIN_PROJECT_PATH.$dir))
	        {
	            $checked_dirs[$k]['read'] = '<span style="color:red;">目录不存在</span>';
				$checked_dirs[$k]['write'] = '<span style="color:red;">目录不存在</span>';
				!$s && $s = 1;
	        }
			else
			{		
	        if (is_readable(MAIN_PROJECT_PATH.$dir))
	        {
	            $checked_dirs[$k]['read'] = '<span style="color:green;">√可读</span>';
	        }else{
	            $checked_dirs[$k]['read'] = '<span sylt="color:red;">×不可读</span>';
	            !$s && $s = 1;
	        }
	        if(is_writable(MAIN_PROJECT_PATH.$dir)){
	        	$checked_dirs[$k]['write'] = '<span style="color:green;">√可写</span>';
	        }else{
	        	$checked_dirs[$k]['write'] = '<span style="color:red;">×不可写</span>';
	        	!$s && $s = 1;
	        }
			}
	    }
	    $s && $this->check_status = true;
	    return $checked_dirs;
	}
	protected function randstr($length=6)
	{
		$hash='';
		$chars= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz@#!~?:-=';   
		$max=strlen($chars)-1;   
		mt_srand((double)microtime()*1000000);   
		for($i=0;$i<$length;$i++)   {   
		$hash.=$chars[mt_rand(0,$max)];   
		}   
		return $hash;   
	}
	protected function ajaxReturn($status=1, $msg='', $data='', $dialog='') {
        parent::ajaxReturn(array(
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'dialog' => $dialog,
        ));
    }
    public function detection_db(){
    	$dbtype = 'mysql';
    	$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    	$location = strstr($php_self,'install.php');
    	$site_dir = str_replace($location, "", $php_self);
    	$dbhost = isset($_POST['dbhost']) ? trim($_POST['dbhost']) : '';
	 	$dbname = isset($_POST['dbname']) ? trim($_POST['dbname']) : '';
	 	$dbuser = isset($_POST['dbuser']) ? trim($_POST['dbuser']) : '';
	 	$dbpass = isset($_POST['dbpass']) ? trim($_POST['dbpass']) : '';
		if($dbhost && $dbname && $dbuser)
		{
			if(!$db = @mysql_connect($dbhost, $dbuser, $dbpass))
			{
				$this->ajaxReturn('连接数据库错误，请核对信息是否正确');
			}
			if(mysql_select_db($dbname))
			{
				$this->ajaxReturn(1,'数据库已存在，继续安装将会覆盖原有数据，是否继续？');
			}
			$this->ajaxReturn(0);
		}else{
			$this->ajaxReturn(0,'您填写的信息不完整，请核对');
		}
    }
}