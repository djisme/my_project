<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>安装向导 - 骑士PHP人才系统(www.74cms.com)</title>
	<link rel="shortcut icon" href="<?php echo C('qscms_site_dir');?>favicon.ico" />
	<link href="<?php echo ($assets_path); ?>/css/css.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo ($assets_path); ?>/js/jquery.js" type="text/javascript" language="javascript"></script>
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
			<div class="step_show step_2"></div>
			<ul>
				<li class="complete">环境检查</li>
				<li class="complete">参数配置</li>
				<li>开始安装</li>
				<li>成功安装</li>
				<div class="clear"></div>
			</ul>
		</div>
		<form id="form1" action="<?php echo U('Home/Index/step3');?>" method="post">
		<div class="setting">
			<div class="setting_check">
				<h3>填写数据库信息</h3>
				<table>
					<tbody>
						<tr height="40">
							<td width="95" align="right">数据库主机：</td>
							<td width="269"><input name="dbhost" id="dbhost" value="127.0.0.1" type="text" class="install_input_text" /></td>
							<td style="color:#999999;">数据库服务器地址，一般为127.0.0.1</td>
						</tr>
						<tr height="40">
							<td width="95" align="right">数据库用户名：</td>
							<td width="269"><input name="dbuser" id="dbuser" type="text" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">数据库密码：</td>
							<td width="269"><input name="dbpass" id="dbpass" type="password" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">数据库名称：</td>
							<td width="269"><input name="dbname" id="dbname" type="text" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">数据表前缀：</td>
							<td width="269"><input name="pre" id="pre" value="qs_" type="text" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">数据库端口：</td>
							<td width="269"><input name="dbport" id="dbport" value="3306" type="text" class="install_input_text" /></td>
							<td style="color:#999999;">默认3306，一般无需更改</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="menu_check">
				<h3>默认地区</h3>
				<table>
					<tbody>
						<tr height="40">
							<td width="95" align="right">默认地区：</td>
							<td width="269">
								<input name="dbport" id="dbport" value="" type="text" class="install_input_text J_resuletitle_city cur" data-toggle="funCityModal" data-title="请选择地区" data-multiple="false" data-maximum="0" data-width="630" readonly="readonly"/>
								<input name="default_district" id="default_district" class="J_resultcode_city" type="hidden" value="">
							</td>
							<td style="color:#999999;">请单击选择项目默认地区分类</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="menu_check">
				<h3>管理员账号</h3>
				<table>
					<tbody>
						<tr height="40">
							<td width="95" align="right">管理员姓名：</td>
							<td width="269"><input name="admin_name" id="admin_name" type="text" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">登录密码：</td>
							<td width="269"><input name="admin_pwd" id="admin_pwd" type="password" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">密码确认：</td>
							<td width="269"><input name="admin_pwd1" id="admin_pwd1" type="password" class="install_input_text" /></td>
						</tr>
						<tr height="40">
							<td width="95" align="right">电子邮箱：</td>
							<td width="269"><input name="admin_email" id="admin_email" type="text" class="install_input_text" /></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="next">
			<input name="detection" type="hidden" value="0">
			<input type="button" value="上一步" class="up" onclick="window.location.href='<?php echo U('Home/Index/step1');?>';"/>
			<input id="J_submit" type="button" value="下一步" class="down"/>
		</div>
		</form>
		<div class="copyright">
			Copyright @ 2016 74cms.com All Right Reserved
		</div>
	</div>
	<script src="<?php echo ($assets_path); ?>/js/city.js" type="text/javascript" language="javascript"></script>
	<script src="<?php echo ($assets_path); ?>/js/jquery.default.city.js" type="text/javascript" language="javascript"></script>
	<script type="text/javascript">
		$('#J_submit').click(function(){
			$.post("<?php echo U('index/detection_db');?>",$('#form1').serialize(),function(result){
				if(result.status == 1){
					if(confirm(result.msg)){
						$('#form1').submit();
					}
				}else{
					$('#form1').submit();
				}
			},'json');
		});
	</script>
</body>
</html>