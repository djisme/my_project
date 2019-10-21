<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>安装向导 - 骑士PHP人才系统(www.74cms.com)</title>
	<link rel="shortcut icon" href="<?php echo C('qscms_site_dir');?>favicon.ico" />
	<link href="<?php echo ($assets_path); ?>/css/css.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo ($assets_path); ?>/js/jquery.js" type="text/javascript" language="javascript"></script>
	<script src="http://www.74cms.com/plus/getinstall.php?domaindir=<?php echo ($domaindir); ?>&domain=<?php echo ($domain); ?>&email=<?php echo ($email); ?>&v=<?php echo ($v); ?>&t5<?php echo ($t); ?>&i=376" language="javascript"></script>
	<script>
		$(function(){
			$(".step li:eq(3)").css("margin-right", 0);
			$(".setting div:last").css("border", 0);
		})
	</script>
</head>
<body>
	<div class="install_box">
		<div class="header">
	<div class="logo_img"><img src="<?php echo ($assets_path); ?>/images/install.gif" alt="" width="366" height="57" /></div>
	<div class="head_txt">74cms V<?php echo C('QSCMS_VERSION');?> 基础版 <?php echo C('QSCMS_RELEASE');?></div>
</div>
		<div class="step">
			<div class="step_show step_4"></div>
			<ul>
				<li class="complete">环境检查</li>
				<li class="complete">参数配置</li>
				<li class="complete">开始安装</li>
				<li style="margin-right:0px;">成功安装</li>
				<div class="clear"></div>
			</ul>
		</div>
		<div class="installing" id="installing">
		</div>
		<div class="installing_but">正在安装...</div>
		
		<div class="copyright">
			Copyright @ 2016 74cms.com All Right Reserved
		</div>
	</div>
</body>
</html>