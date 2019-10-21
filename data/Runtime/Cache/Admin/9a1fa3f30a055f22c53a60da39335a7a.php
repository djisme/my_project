<?php if (!defined('THINK_PATH')) exit();?><!-- public:header 以下内容 -->
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>网站后台管理中心- Powered by 74cms.com</title>
	<link rel="shortcut icon" href="<?php echo C('qscms_site_dir');?>favicon.ico"/>
	<meta name="author" content="骑士CMS" />
	<meta name="copyright" content="74cms.com" />
	<link href="__ADMINPUBLIC__/css/common.css" rel="stylesheet" type="text/css">
	<script src="__ADMINPUBLIC__/js/jquery.min.js"></script>
	<script src="__ADMINPUBLIC__/js/jquery.highlight-3.js"></script>
	<script src="__ADMINPUBLIC__/js/jquery.vtip-min.js"></script>
	<script src="__ADMINPUBLIC__/js/jquery.modal.dialog.js"></script>
	<!--[if lt IE 9]>
	<script type="text/javascript" src="__ADMINPUBLIC__/js/PIE.js"></script>
	<script type="text/javascript">
		(function ($) {
			$.pie = function (name, v) {
				// 如果没有加载 PIE 则直接终止
				if (!PIE) return false;
				// 是否 jQuery 对象或者选择器名称
				var obj = typeof name == 'object' ? name : $(name);
				// 指定运行插件的 IE 浏览器版本
				var version = 9;
				// 未指定则默认使用 ie10 以下全兼容模式
				if (typeof v != 'number' && v < 9) {
					version = v;
				}
				// 可对指定的多个 jQuery 对象进行样式兼容
				if ($.browser.msie && obj.size() > 0) {
					if ($.browser.version * 1 <= version * 1) {
						obj.each(function () {
							PIE.attach(this);
						});
					}
				}
			}
		})(jQuery);
		if ($.browser.msie) {
			$.pie('.pie_about');
		};
	</script>
	<![endif]-->
	<script src="__ADMINPUBLIC__/js/jquery.disappear.tooltip.js"></script>
	<script src="__ADMINPUBLIC__/js/common.js"></script>
	<script>
		var URL = '/recruit/index.php/Admin/Sms',
			SELF = '/recruit/index.php?m=Admin&amp;c=Sms&amp;a=config_edit',
			ROOT_PATH = '/recruit/index.php/Admin',
			APP	 =	 '/recruit/index.php';

		var qscms = {
			is_subsite : <?php if($apply['Subsite'] and C('SUBSITE_VAL.s_id') > 0): ?>1<?php else: ?>0<?php endif; ?>,
			subsite_level : "<?php if($apply['Subsite'] and C('SUBSITE_VAL.s_id') > 0): echo C('SUBSITE_VAL.s_level'); else: echo C('qscms_category_district_level'); endif; ?>",
			default_district : "<?php if($apply['Subsite'] and C('SUBSITE_VAL.s_id') > 0): echo C('qscms_default_district'); else: echo C('SUBSITE_VAL.s_default_district'); endif; ?>"
		};
	</script>
	<?php echo ($synsitegroupunbindmobile); ?>
	<?php echo ($synsitegroupregister); ?>
	<?php echo ($synsitegroupedit); ?>
	<?php echo ($synsitegroup); ?>
</head>
<body>

<!-- public:header 以上内容 -->
<?php if(empty($menu_title)): ?><div class="allpagetop">后台管理中心<strong>/</strong>首页</div>
	<?php else: ?>
	<div class="allpagetop"><?php echo ($menu_title); ?><strong>/</strong><?php echo ($sub_menu_title); ?></div><?php endif; ?>
<div class="mains">
	<div class="h1tit">
		<div class="h1">
            <?php if($sub_menu['pageheader']): echo ($sub_menu["pageheader"]); ?>
                <?php else: ?>
                <!--欢迎登录 <?php echo C('qscms_site_name');?> 管理中心！--><?php endif; ?>
        </div>
        <?php if(!empty($sub_menu['menu'])): ?><div class="topnav">
                <?php if(is_array($sub_menu['menu'])): $i = 0; $__LIST__ = $sub_menu['menu'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><a href="<?php echo U($val['module_name'].'/'.$val['controller_name'].'/'.$val['action_name']); echo ($val["data"]); if($isget and $_GET): echo get_first(); endif; if($_GET['_k_v']): ?>&_k_v=<?php echo (_I($_GET['_k_v'])); endif; ?>" class="<?php echo ($val["class"]); ?>"><?php echo ($val["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                <div class="clear"></div>
            </div><?php endif; ?>
		<div class="clear"></div>
	</div>
<div class="toptip">
    <div class="toptit">提示：</div>
    <p>短信模块属收费模块，需申请开通后才能使用，请联系我司客服申请开通。</p>
    <p class="link_green_line">资费标准请联系骑士销售获取，更多介绍请进入 <a href="http://www.74cms.com" target="_blank">骑士人才系统官方网站</a></p>
    <p><font color="red">开启短信发送服务前，请确认短信服务商已正确配置！</font></p>
    <p><font color="red">阿里大于的短信暂时不能正常发送招聘类信息，使用前请先向阿里大于官方核实，谨慎使用！</font></p>
</div>
	<form action="<?php echo U('sms/config_edit');?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
		<div class="toptit">设置</div>
		<div class="form_main width200">
			<div class="fl">开启短信发送：</div>
            <div class="fr">
				<div class="imgradio">
					<input name="sms_open" type="hidden" value="<?php echo C('qscms_sms_open');?>">
					<div class="radio <?php if(C('qscms_sms_open') == 1): ?>select<?php endif; ?> J_sms_open" id="J_sms_open_1" data="1" title="开启">开启</div>
					<div class="radio <?php if(C('qscms_sms_open') == 0): ?>select<?php endif; ?> J_sms_open" data="0" title="关闭">关闭</div>
					<div class="clear"></div>
				</div>
            </div>
            <div id="j_show" <?php if(C('qscms_sms_open') == 0): ?>style="display:none"<?php endif; ?>>
            	<div class="fl">默认短信服务商：</div>
	            <div class="fr">
	            	<div class="select_input_new w400 flo J_hoverinput J_dropdown J_listitme_parent">
		                <span class="J_listitme_text">请选择短信服务商</span>
		                <div class="dropdowbox_sn J_dropdown_menu">
		                    <div class="dropdow_inner_sn">
		                        <ul class="nav_box">
		                            <?php if(is_array($sms_list)): $i = 0; $__LIST__ = $sms_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sms): $mod = ($i % 2 );++$i;?><li><a class="J_listitme <?php if($key == C('qscms_sms_default_service')): ?>list_sel<?php endif; ?>" href="javascript:;" data-code="<?php echo ($key); ?>"><?php echo ($sms["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		                        </ul>
		                    </div>
		                </div>
		                <input class="J_listitme_code" name="sms_default_service" id="sms_default_service" type="hidden" value="" />
		            </div>
					<!-- <select name="sms_default_service" style="width:200px">
						<option value="" <?php if(C('qscms_sms_default_service') == ''): ?>selected="selected"><?php endif; ?>>请选择短信服务商</option>
						<?php if(is_array($sms_list)): $i = 0; $__LIST__ = $sms_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sms): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($key == C('qscms_sms_default_service')): ?>selected="selected"<?php endif; ?>><?php echo ($sms["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select> -->
					<div class="sin_text">（请在“服务商接入”栏目下安装短信接口）</div>
					<div class="clear"></div>
	            </div>
	            <div class="fl">验证码类短信服务商：</div>
	            <div class="fr">
	            	<div class="select_input_new w400 flo J_hoverinput J_dropdown J_listitme_parent">
		                <span class="J_listitme_text">请选择短信服务商</span>
		                <div class="dropdowbox_sn J_dropdown_menu">
		                    <div class="dropdow_inner_sn">
		                        <ul class="nav_box">
		                            <?php if(is_array($sms_list)): $i = 0; $__LIST__ = $sms_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sms): $mod = ($i % 2 );++$i;?><li><a class="J_listitme <?php if($key == C('qscms_sms_captcha_service')): ?>list_sel<?php endif; ?>" href="javascript:;" data-code="<?php echo ($key); ?>"><?php echo ($sms["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		                        </ul>
		                    </div>
		                </div>
		                <input class="J_listitme_code" name="sms_captcha_service" id="sms_captcha_service" type="hidden" value="" />
		            </div>
					<!-- <select name="sms_captcha_service" style="width:200px">
						<option value="" <?php if(C('qscms_sms_captcha_service') == ''): ?>selected="selected"><?php endif; ?>>请选择短信服务商</option>
						<?php if(is_array($sms_list)): $i = 0; $__LIST__ = $sms_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sms): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($key == C('qscms_sms_captcha_service')): ?>selected="selected"<?php endif; ?>><?php echo ($sms["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select> -->
					<div class="sin_text">（请在“服务商接入”栏目下安装短信接口）</div>
					<div class="clear"></div>
	            </div>
	            <div class="fl">通知类短信服务商：</div>
	            <div class="fr">
	            	<div class="select_input_new w400 flo J_hoverinput J_dropdown J_listitme_parent">
		                <span class="J_listitme_text">请选择短信服务商</span>
		                <div class="dropdowbox_sn J_dropdown_menu">
		                    <div class="dropdow_inner_sn">
		                        <ul class="nav_box">
		                            <?php if(is_array($sms_list)): $i = 0; $__LIST__ = $sms_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sms): $mod = ($i % 2 );++$i;?><li><a class="J_listitme <?php if($key == C('qscms_sms_notice_service')): ?>list_sel<?php endif; ?>" href="javascript:;" data-code="<?php echo ($key); ?>"><?php echo ($sms["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		                        </ul>
		                    </div>
		                </div>
		                <input class="J_listitme_code" name="sms_notice_service" id="sms_notice_service" type="hidden" value="" />
		            </div>
					<!-- <select name="sms_notice_service" style="width:200px">
						<option value="" <?php if(C('qscms_sms_notice_service') == ''): ?>selected="selected"><?php endif; ?>>请选择短信服务商</option>
						<?php if(is_array($sms_list)): $i = 0; $__LIST__ = $sms_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sms): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($key == C('qscms_sms_notice_service')): ?>selected="selected"<?php endif; ?>><?php echo ($sms["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select> -->
					<div class="sin_text">（请在“服务商接入”栏目下安装短信接口）</div>
					<div class="clear"></div>
	            </div>
	            <div class="fl">其它类短信服务商：</div>
	            <div class="fr">
	            	<div class="select_input_new w400 flo J_hoverinput J_dropdown J_listitme_parent">
		                <span class="J_listitme_text">请选择短信服务商</span>
		                <div class="dropdowbox_sn J_dropdown_menu">
		                    <div class="dropdow_inner_sn">
		                        <ul class="nav_box">
		                            <?php if(is_array($sms_list)): $i = 0; $__LIST__ = $sms_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sms): $mod = ($i % 2 );++$i;?><li><a class="J_listitme <?php if($key == C('qscms_sms_other_service')): ?>list_sel<?php endif; ?>" href="javascript:;" data-code="<?php echo ($key); ?>"><?php echo ($sms["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
		                        </ul>
		                    </div>
		                </div>
		                <input class="J_listitme_code" name="sms_other_service" id="sms_other_service" type="hidden" value="" />
		            </div>
					<!-- <select name="sms_other_service" style="width:200px">
						<option value="" <?php if(C('qscms_sms_other_service') == ''): ?>selected="selected"<?php endif; ?>>请选择短信服务商</option>
						<?php if(is_array($sms_list)): $i = 0; $__LIST__ = $sms_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sms): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($key == C('qscms_sms_other_service')): ?>selected="selected"<?php endif; ?>><?php echo ($sms["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select> -->
					<div class="sin_text">（请在“服务商接入”栏目下安装短信接口）</div>
					<div class="clear"></div>
	            </div>
            </div>
            <div class="fl"></div>
	        <div class="fr">
	            <input type="submit" class="admin_submit" value="保存修改"/>
	        </div>
			<div class="clear"></div>
		</div>
	</form>
</div>
<!-- public:footer 以下内容 -->
<div class="footer link_blue">
    Powered by <a href="http://www.74cms.com" target="_blank"><span style="color:#009900">74</span><span
        style="color: #FF3300">cms</span></a> v<?php echo C('QSCMS_VERSION');?>
</div>
<!-- public:footer 以上内容 -->
<script type="text/javascript" src="__ADMINPUBLIC__/js/jquery.listitem.js"></script>
<script type="text/javascript" src="__ADMINPUBLIC__/js/jquery.dropdown.js"></script>
<script type="text/javascript">
  $(".J_sms_open").click(function(){
  	if (eval($(this).attr('data'))) {
  		$("#j_show").show();
  	} else {
  		$("#j_show").hide();
  	}
  });
	if ($('.J_listitme.list_sel').length) {
		$('.J_listitme.list_sel').each(function(index, el) {
			var listSelCn = $.trim($(this).text());
            var listSelCode = $(this).data('code');
			console.log(listSelCn);
            $(this).closest('.J_listitme_parent').find('.J_listitme_text').text(listSelCn);
            $(this).closest('.J_listitme_parent').find('.J_listitme_code').val(listSelCode);
		})
	}
</script>
</body>
</html>