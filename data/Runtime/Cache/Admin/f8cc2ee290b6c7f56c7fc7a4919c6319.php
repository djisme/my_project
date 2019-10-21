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
		var URL = '/recruit/index.php/Admin/Mailconfig',
			SELF = '/recruit/index.php?m=Admin&amp;c=Mailconfig&amp;a=index',
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
        <p>您可以通过发送测试邮件来调试配置信息。</p>
        <p>通过连接 SMTP 服务器发送邮件需邮箱账户开通SMTP服务。</p>
        <p>您可以添加多个SMTP账户，系统将随机使用SMTP账户。</p>
        <p class="link_green_line">如需使用阿里云邮件推送服务，请先登录阿里云官方网站申请api权限。<a href="https://help.aliyun.com/product/29412.html" target="_blank">立即申请</a>。</p>
        <p class="link_green_line">阿里云邮件推送控制台地址：<a href="https://dm.console.aliyun.com" target="_blank">https://dm.console.aliyun.com</a></p>
      </div>
<div class="toptit">设置</div>
<form action="<?php echo U('mailconfig/index');?>" method="post"   name="form1" id="form1">
    <div class="form_main width150">
        <div class="fl">接口类型：</div>
        <div class="fr">
          <div class="imgradio">
              <input name="api_type" class="api_type" type="hidden" value="<?php if(in_array($info['method'],array('1','2','3'))): ?>normal<?php else: ?>aliyun<?php endif; ?>">
              <div class="J_api_type radio <?php if(in_array($info['method'],array('1','2','3'))): ?>select<?php endif; ?>" data="normal" title="系统API">系统API</div>
              <div class="J_api_type radio <?php if($info['method'] == 4): ?>select<?php endif; ?>" data="aliyun" title="阿里云DirectMail API">阿里云DirectMail API</div>
              <div class="clear"></div>
          </div>
        </div>
        <div class="clear"></div>
    </div>
    <div id="normal-block">
        <div id="methodsel" class="form_main width150">
            <div class="fl">发送方式：</div>
            <div class="fr">
              <div class="imgradio">
                  <input name="method" type="hidden" value="">
                  <div class="radio <?php if($info['method'] == 1): ?>select<?php endif; ?>" data="1" title="通过连接 SMTP 服务器发送邮件">通过连接 SMTP 服务器发送邮件</div>
                  <div class="radio <?php if($info['method'] == 2): ?>select<?php endif; ?>" data="2" title="通过sendmail 发送邮件">通过sendmail 发送邮件</div>
                  <div class="radio <?php if($info['method'] == 3): ?>select<?php endif; ?>" data="3" title="通过 PHP 函数 SMTP 发送邮件">通过 PHP 函数 SMTP 发送邮件</div>
                  <div class="clear"></div>
              </div>
            </div>
            <div class="clear"></div>
        </div>
        <div style="display:none;"  id="method_sendmail">
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><div class="html_tpl form_main width150">
                    <div class="fl">SMTP服务器地址：</div>
                    <div class="fr">
                        <input name="smtpservers[]" id="smtpservers" type="text" maxlength="30" class="input_text_default middle" value="<?php echo ($list["smtpservers"]); ?>"/>
                        <label class="no-fl-note">如：smtp.qq.com</label>
                        <span style="color:#0066CC; cursor:pointer" class="delsmtp">X 删除此账户</span>
                    </div>
                    <div class="fl">SMTP服务帐户名：</div>
                    <div class="fr">
                        <input name="smtpusername[]" id="smtpusername" type="text" maxlength="100" class="input_text_default middle" value="<?php echo ($list["smtpusername"]); ?>"/>
                    </div>
                    <div class="fl">SMTP服务密码：</div>
                    <div class="fr">
                        <input name="smtppassword[]" id="smtppassword" type="password" maxlength="40" class="input_text_default middle" value="<?php echo ($list["smtppassword"]); ?>"/>
                    </div>
                    <div class="fl">发信人邮件地址：</div>
                    <div class="fr">
                        <input name="smtpfrom[]" id="site_title" type="text" maxlength="60" class="input_text_default middle" value="<?php echo ($list["smtpfrom"]); ?>"/>
                    </div>
                    <div class="fl">端口：</div>
                    <div class="fr">
                        <input name="smtpport[]" id="smtpport" type="text" maxlength="60" class="input_text_default middle" value="<?php echo ($list["smtpport"]); ?>"/>
                        <label class="no-fl-note">默认：25</label>
                    </div>
                    <div class="clear"></div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            <div id="html"></div>
        </div>
        <div class="form_main width150">
            <div class="fl"></div>
            <div class="fr">
                <input name="save" type="submit" class="admin_submit"    value="保存修改"/>
                <input type="button" name="add_form" id="add_form" value="继续添加" class="admin_submit" />
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div id="aliyun-block" class="form_main width150" style="display:none;">
        <div class="fl">Access Key：</div>
        <div class="fr">
            <input name="aliyun_accessKey" id="aliyun_accessKey" type="text" class="input_text_default middle" value="<?php echo ($info["aliyun_accessKey"]); ?>"/>
            <label class="no-fl-note">阿里云Access Key ID</label>
        </div>
        <div class="fl">Access Secret：</div>
        <div class="fr">
            <input name="aliyun_accessSecret" id="aliyun_accessSecret" type="password" class="input_text_default middle" value="<?php echo ($info["aliyun_accessSecret"]); ?>"/>
            <label class="no-fl-note">阿里云Access Key Secret</label>
        </div>
        <div class="fl">发信地址：</div>
        <div class="fr">
            <input name="aliyun_account" id="aliyun_account" type="text" class="input_text_default middle" value="<?php echo ($info["aliyun_account"]); ?>"/>
            <label class="no-fl-note">管理控制台中配置的发信地址</label>
        </div>
        <div class="fl">标签：</div>
        <div class="fr">
            <input name="aliyun_tag" id="aliyun_tag" type="text" class="input_text_default middle" value="<?php echo ($info["aliyun_tag"]); ?>"/>
            <label class="no-fl-note">控制台创建的标签</label>
        </div>
        <div class="fl"></div>
        <div class="fr">
            <input name="save" type="submit" class="admin_submit"    value="保存修改"/>
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
<script id="J_email" type="text/html">
<div class="html_tpl form_main width150">
    <div class="fl">SMTP服务器地址：</div>
    <div class="fr">
        <input name="smtpservers[]" id="smtpservers" type="text" maxlength="30" class="input_text_default middle" value=""/>
        <label class="no-fl-note">如：smtp.qq.com</label>
        <span style="color:#0066CC; cursor:pointer" class="delsmtp">X 删除此账户</span>
    </div>
    <div class="fl">SMTP服务帐户名：</div>
    <div class="fr">
        <input name="smtpusername[]" id="smtpusername" type="text" maxlength="100" class="input_text_default middle" value=""/>
    </div>
    <div class="fl">SMTP服务密码：</div>
    <div class="fr">
        <input name="smtppassword[]" id="smtppassword" type="password" maxlength="40" class="input_text_default middle" value=""/>
    </div>
    <div class="fl">发信人邮件地址：</div>
    <div class="fr">
        <input name="smtpfrom[]" id="site_title" type="text" maxlength="60" class="input_text_default middle" value=""/>
    </div>
    <div class="fl">端口：</div>
    <div class="fr">
        <input name="smtpport[]" id="smtpport" type="text" maxlength="60" class="input_text_default middle" value=""/>
        <label class="no-fl-note">默认：25</label>
    </div>
    <div class="clear"></div>
</div>
</script>
<script type="text/javascript">
$(document).ready(function()
{
    setsendmailshow();
    $("#methodsel .radio").click(function(event) {
        var stlval=$(this).attr('data');
        if (stlval=="1") {
            $("#method_sendmail,#add_form").show();
            $("#add_form").show();
        } else {
            $("#method_sendmail,#add_form").hide();
        }
    })
    function setsendmailshow() {
        var stlval=$("#methodsel .select").attr('data');
        if (stlval=="1") {
            $("#method_sendmail,#add_form").show();
            $("#add_form").show();
        } else {
            $("#method_sendmail,#add_form").hide();
        }
    }
    $("#add_form").click(function() {
        $("#html").append($("#J_email").html());
    })

    // 删除账号
    $('.delsmtp').live('click', function() {
        var htNum = $('.html_tpl').length;

        if (htNum==1) {
            alert('至少留一个SMTP账户！');
        } else {
            $(this).closest('.html_tpl').empty();
        }
    });
    // 初始化接口类型
    if($('.api_type').val()=='normal'){
        $('#normal-block').show();
        $('#aliyun-block').hide();
    } else {
        $('#normal-block').hide();
        $('#aliyun-block').show();
    }
    // 接口类型切换
    $('.J_api_type').click(function(){
        var type = $(this).attr('data');
        if(type=='normal'){
            $('#normal-block').show();
            $('#aliyun-block').hide();
            $('.method_first').attr('checked',true);
            $('#method_sendmail').show();
        } else {
            $('#normal-block').hide();
            $('#aliyun-block').show();
        }
    })
});
</script>
</body>
</html>