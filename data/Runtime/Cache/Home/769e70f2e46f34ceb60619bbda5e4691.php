<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo ($page_seo["title"]); ?></title>
<meta name="keywords" content="<?php echo ($page_seo["keywords"]); ?>"/>
<meta name="description" content="<?php echo ($page_seo["description"]); ?>"/>
<meta name="author" content="骑士CMS"/>
<meta name="copyright" content="74cms.com"/>
<?php if($apply['Subsite']): ?><base href="<?php echo C('SUBSITE_DOMAIN');?>"/><?php endif; ?>
<link rel="shortcut icon" href="<?php echo C('qscms_site_dir');?>favicon.ico"/>
<script src="<?php echo C('TPL_HOME_PUBLIC_DIR');?>/js/jquery.min.js"></script>
<script src="<?php echo C('TPL_HOME_PUBLIC_DIR');?>/js/htmlspecialchars.js"></script>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<script type="text/javascript">
	var app_spell = "<?php echo APP_SPELL;?>";
	var qscms = {
		base : "<?php echo C('SUBSITE_DOMAIN');?>",
		keyUrlencode:"<?php echo C('qscms_key_urlencode');?>",
		domain : "http://<?php echo ($_SERVER['HTTP_HOST']); ?>",
		root : "/recruit/index.php",
		companyRepeat:"<?php echo C('qscms_company_repeat');?>",
		regularMobile: /^13[0-9]{9}$|14[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$|17[0-9]{9}$|16[0-9]{9}$|19[0-9]{9}$/,
		is_subsite : <?php if($apply['Subsite'] and C('SUBSITE_VAL.s_id') > 0): ?>1<?php else: ?>0<?php endif; ?>,
		subsite_level : "<?php if($apply['Subsite'] and C('SUBSITE_VAL.s_id') > 0): echo C('SUBSITE_VAL.s_level'); else: echo C('qscms_category_district_level'); endif; ?>",
		smsTatus: "<?php echo C('qscms_sms_open');?>",
		captcha_open:"<?php echo C('qscms_captcha_open');?>",
		varify_mobile:"<?php echo C('qscms_captcha_config.varify_mobile');?>",
		varify_suggest:"<?php echo C('qscms_captcha_config.varify_suggest');?>",
        varify_user_login:"<?php echo ($verify_userlogin); ?>",
		is_login:"<?php if($visitor): ?>1<?php else: ?>0<?php endif; ?>",
		default_district : "<?php if($apply['Subsite'] and C('SUBSITE_VAL.s_id') > 0): echo C('SUBSITE_VAL.s_default_district'); else: echo C('qscms_default_district'); endif; ?>",
		default_district_spell : "<?php if($apply['Subsite'] and C('SUBSITE_VAL.s_id') > 0): echo C('SUBSITE_VAL.s_default_district_spell'); else: echo C('qscms_default_district_spell'); endif; ?>"
	};
	$(function(){
		$.getJSON("<?php echo U('Home/AjaxCommon/get_header_min');?>",function(result){
			if(result.status == 1){
				$('#J_header').html(result.data.html);
			}
		});
	})
</script>
<?php echo ($synlogin); ?>
<?php echo ($synsitegroupregister); ?>
<?php echo ($synsitegroupunbindmobile); ?>
<?php echo ($synsitegroupedit); ?>
<?php echo ($synsitegroup); ?>
	<link href="<?php echo C('TPL_PUBLIC_DIR');?>/css/common.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo C('TPL_PUBLIC_DIR');?>/css/index.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo C('TPL_PUBLIC_DIR');?>/css/common_ajax_dialog.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo C('TPL_PUBLIC_DIR');?>/css/slider/themes/default/default.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo C('TPL_PUBLIC_DIR');?>/css/slider/nivo-slider.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo C('TPL_PUBLIC_DIR');?>/js/jquery.common.js" type="text/javascript" language="javascript"></script>
</head>
<body>
    <div class="header_min" id="header">
	<div class="header_min_top">
		<div id="J_header" class="itopl font_gray6 link_gray6">
			<span class="link_yellow">欢迎登录<?php echo C('qscms_site_name');?>！请 <a id="J_site_login" href="javascript:;">登录</a> 或 <a id="J_site_reg" href="javascript:;">免费注册</a></span>
		</div>
		<div class="itopr font_gray9 link_gray6 substring"> <a href="/recruit/" class="home">网站首页</a>|<a href="<?php echo url_rewrite('QS_m');?>" class="m">手机访问</a>|<a href="<?php echo url_rewrite('QS_help');?>" class="help">帮助中心</a>|<?php if(!empty($apply['Mall'])): ?><a href="<?php echo url_rewrite('QS_mall_index');?>" class="shop"><?php echo C("qscms_points_byname");?>商城</a>|<?php endif; ?><a href="<?php echo U('Home/Index/shortcut');?>" class="last">保存到桌面</a> </div>
	    <div class="clear"></div>
	</div>
</div>
<div class="other_top_nav">
    <div class="ot_nav_box">
        <div class="ot_nav_logo"><a href="/recruit/"><img src="<?php if(C('qscms_logo_home')): echo attach(C('qscms_logo_home'),'resource'); else: echo C('TPL_HOME_PUBLIC_DIR');?>/images/logo.gif<?php endif; ?>" border="0"/></a></div>
        <div class="ot_nav_sub">
            <?php if(empty($sitegroup)): if($apply['Subsite']): ?><div class="ot_sub_group" id="J-choose-subcity">
                        <div class="ot_sub_icon"></div>
                        <div class="ot_sub_txt"><?php echo C('SUBSITE_VAL.s_sitename');?></div>
                        <div class="clear"></div>
                    </div><?php endif; ?>
                <?php else: ?>
                <div class="ot_sub_group" id="J-choose-subcity">
                    <div class="ot_sub_icon"></div>
                    <div class="ot_sub_txt"><?php echo ($sitegroup_org["name"]); ?></div>
                    <div class="clear"></div>
                </div><?php endif; ?>
        </div>
        <div class="ot_nav_link <?php if($apply['Subsite'] || $sitegroup): ?>has_sub<?php endif; ?>">
        <ul class="link_gray6 nowrap">
            <?php $tag_nav_class = new \Common\qscmstag\navTag(array('列表名'=>'nav','调用名称'=>'QS_top','显示数目'=>'8','cache'=>'0','type'=>'run',));$nav = $tag_nav_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$nav);?>
            <?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?><li class="on_li J_hoverbut <?php if(MODULE_NAME == C('DEFAULT_MODULE')): if($nav['tag'] == strtolower(CONTROLLER_NAME)): ?>select<?php endif; else: if($nav['tag'] == strtolower(MODULE_NAME)): ?>select<?php endif; endif; ?>"><a href="<?php echo ($nav['url']); ?>" target="<?php echo ($nav["target"]); ?>"><?php echo ($nav["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
</div>
<script type="text/javascript" src="<?php echo C('TPL_HOME_PUBLIC_DIR');?>/js/jquery.modal.dialog.js"></script>
<?php if(!$sitegroup && $apply['Subsite']): ?><script id="J-sub-dialog-content" type="text/html">
        <div class="sub-dialog-group">
            <div class="sdg-title">亲爱的用户您好：</div>
            <div class="sdg-split-20"></div>
            <div class="sdg-h-tips">请您切换到对应的地区分站，让我们为您提供更准确的职位信息。</div>
            <div class="sdg-split-30"></div>
            <div class="sdg-h-line"></div>
            <div class="sdg-split-20"></div>
            <div class="sdg-master-group">
                <?php if($subsite_org): ?><div class="sdg-txt-left">点击进入</div>
                    <a href="<?php echo U('Home/Subsite/set',array('sid'=>$subsite_org['s_id']));?>" class="sdg-go"><?php echo ($subsite_org["s_sitename"]); ?></a>
                    <div class="sdg-txt-right">或者切换到以下城市</div>
                    <?php else: ?>
                    <div class="sdg-txt-right">切换到以下城市</div><?php endif; ?>
                <div class="clear"></div>
            </div>
            <div class="sdg-split-20"></div>
            <div class="sdg-sub-city-group">
                <?php if(is_array($district)): $i = 0; $__LIST__ = array_slice($district,0,10,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dis): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Home/Subsite/set',array('sid'=>$dis['s_id']));?>" class="sdg-sub-city"><?php echo ($dis["s_sitename"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                <?php if(count($district) > 11): ?><a href="<?php echo U('Home/Subsite/index');?>" class="sdg-sub-city more">更多地区</a><?php endif; ?>
                <div class="clear"></div>
            </div>
            <div class="sdg-split-16"></div>
            <div class="sdg-bottom-tips">如果您在使用中遇到任何问题，请随时联系 <?php if(C('qscms_top_tel')): echo C('qscms_top_tel'); else: echo C('qscms_bootom_tel'); endif; ?> 寻求帮助</div>
            <div class="sdg-split-11"></div>
        </div>
    </script>
    <script type="text/javascript">
        <?php if(!empty($subsite_org)): ?>showSubDialog();<?php endif; ?>
        $('#J-choose-subcity').click(function () {
            showSubDialog();
        });
        function showSubDialog() {
            var qsDialog = $(this).dialog({
                title: '切换地区',
                showFooter: false,
                border: false
            });
            qsDialog.setContent($('#J-sub-dialog-content').html());
            $('.sdg-sub-city').each(function (index, value) {
                if ((index + 1) % 4 == 0) {
                    $(this).addClass('no-mr');
                }
            });
        }
    </script><?php endif; ?>
<?php if(!empty($sitegroup)): ?><script id="J-sub-dialog-content" type="text/html">
        <div class="sub-dialog-group">
            <div class="sdg-title">亲爱的用户您好：</div>
            <div class="sdg-split-20"></div>
            <div class="sdg-h-tips">请您切换到对应的分站，让我们为您提供更准确的职位信息。</div>
            <div class="sdg-split-30"></div>
            <div class="sdg-h-line"></div>
            <div class="sdg-split-20"></div>
            <div class="sdg-master-group">
                <div class="sdg-txt-right">切换到以下城市</div>
                <div class="clear"></div>
            </div>
            <div class="sdg-split-20"></div>
            <div class="sdg-sub-city-group">
                <?php if(is_array($sitegroup)): $i = 0; $__LIST__ = array_slice($sitegroup,0,10,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$dis): $mod = ($i % 2 );++$i;?><a href="<?php echo ($dis["domain"]); ?>" class="sdg-sub-city"><?php echo ($dis["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                <?php if(count($sitegroup) > 11): ?><a href="<?php echo U('Home/Subsite/index');?>" class="sdg-sub-city more">更多分站</a><?php endif; ?>
                <div class="clear"></div>
            </div>
            <div class="sdg-split-16"></div>
            <div class="sdg-bottom-tips">如果您在使用中遇到任何问题，请随时联系 <?php if(C('qscms_top_tel')): echo C('qscms_top_tel'); else: echo C('qscms_bootom_tel'); endif; ?> 寻求帮助</div>
            <div class="sdg-split-11"></div>
        </div>
    </script>
    <script type="text/javascript">
        $('#J-choose-subcity').click(function () {
            showSubDialog();
        });
        function showSubDialog() {
            var qsDialog = $(this).dialog({
                title: '切换地区',
                showFooter: false,
                border: false
            });
            qsDialog.setContent($('#J-sub-dialog-content').html());
            $('.sdg-sub-city').each(function (index, value) {
                if ((index + 1) % 4 == 0) {
                    $(this).addClass('no-mr');
                }
            });
        }
    </script><?php endif; ?>
    <div class="ni-top-group">
        <div class="ni-top-container">
            <div class="ni-top-left">
                <div class="main-sty">
                    <div class="sty-cell J_sli select" data-type="QS_jobslist">找工作<div class="sty-aow"></div></div>
                    <div class="sty-cell J_sli" data-type="QS_resumelist">招人才<div class="sty-aow"></div></div>
                    <div class="sty-cell J_sli" data-type="QS_companylist">搜企业<div class="sty-aow"></div></div>
                    <?php if(!empty($apply['Store'])): ?><div class="sty-cell J_sli" data-type="QS_store">门店招聘<div class="sty-aow"></div></div><?php endif; ?>
                    <?php if(!empty($apply['Parttime'])): ?><div class="sty-cell J_sli" data-type="QS_parttime">搜兼职<div class="sty-aow"></div></div><?php endif; ?>
                    <?php if(!empty($apply['House'])): ?><div class="sty-cell J_sli" data-type="QS_house_rent">租房<div class="sty-aow"></div></div><?php endif; ?>
                    <?php if(!empty($apply['Gworker'])): ?><div class="sty-cell J_sli" data-type="QS_gworker">普工<div class="sty-aow"></div></div><?php endif; ?>
                    <div class="clear"></div>
                </div>
                <div class="main-sip">
                    <div class="ip-group">
                        <form id="ajax_search_location">
                            <div class="ip-box"><input type="text" name="key" id="top_search_input" value="" placeholder="请输入关键字" /></div>
                            <div class="ip-btn"><input id="top_search_btn" type="submit" class="sobut J_hoverbut" value="搜  索" /></div>
                            <input type="hidden" name="act" id="top_search_type" value="QS_jobslist" />
                        </form>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="sip-sco">
                    <div class="sco-box" id="J_msgbox">
                        <ul id="J_ajax_scroll"></ul>
                    </div>
                    <script type="text/javascript">
                        var ajaxScrollHtml = '';
                        $.getJSON('<?php echo U("Index/ajax_scroll");?>',function(result){
                            if (result.status) {
                                var dataArr = result.data;
                                for (var i = 0; i < dataArr.length; i++) {
                                    if (dataArr[i]['utype'] == '1') {
                                        if (dataArr['type'] = 'add') {
                                            ajaxScrollHtml += '<li><div class="log-s-line"><div class="lsl-left"><a target="_blank" href="'+dataArr[i]['company_url']+'">'+dataArr[i]['companyname']+'</a> 发布了 <a target="_blank" href="'+dataArr[i]['job_url']+'">'+dataArr[i]['jobs_name']+'</a></div><div class="lsl-right">'+dataArr[i]['time_cn']+'</div><div class="clear"></div></div></li>';
                                        } else {
                                            ajaxScrollHtml += '<li><div class="log-s-line"><div class="lsl-left"><a target="_blank" href="'+dataArr[i]['company_url']+'">'+dataArr[i]['companyname']+'</a> 刷新了 <a target="_blank" href="'+dataArr[i]['job_url']+'">'+dataArr[i]['jobs_name']+'</a></div><div class="lsl-right">'+dataArr[i]['time_cn']+'</div><div class="clear"></div></div></li>';
                                        }
                                    } else {
                                        if (dataArr[i]['type'] = 'add') {
                                            ajaxScrollHtml += '<li><div class="log-s-line"><div class="lsl-left"><a target="_blank" href="'+dataArr[i]['url']+'">'+dataArr[i]['fullname']+'</a> 发布了新简历</div><div class="lsl-right">'+dataArr[i]['time_cn']+'</div><div class="clear"></div></div></li>';
                                        } else {
                                            ajaxScrollHtml += '<li><div class="log-s-line"><div class="lsl-left"><a target="_blank" href="'+dataArr[i]['url']+'">'+dataArr[i]['fullname']+'</a> 刷新了简历</div><div class="lsl-right">'+dataArr[i]['time_cn']+'</div><div class="clear"></div></div></li>';
                                        }
                                    }
                                }
                                $('#J_ajax_scroll').html(ajaxScrollHtml);
                            }
                        })
                    </script>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="index-plug-group">
                <div class="plug-box">
                    <?php if(!empty($apply['Store'])): ?><a target="_blank" href="<?php echo url_rewrite('QS_store');?>" class="plug-cell"><div class="plug-img store"></div><div class="plug-name">门店招聘</div><div class="h-line"></div></a><?php endif; ?>
                    <?php if(!empty($apply['Parttime'])): ?><a target="_blank" href="<?php echo url_rewrite('QS_parttime');?>" class="plug-cell"><div class="plug-img parttime"></div><div class="plug-name">兼职招聘</div><div class="h-line"></div></a><?php endif; ?>
                    <?php if(!empty($apply['House'])): ?><a target="_blank" href="<?php echo url_rewrite('QS_house_rent');?>" class="plug-cell"><div class="plug-img house"></div><div class="plug-name">附近租房</div><div class="h-line"></div></a><?php endif; ?>
                    <?php if(!empty($apply['Allowance'])): ?><a target="_blank" href="<?php echo url_rewrite('QS_jobslist',array('search_cont'=>'allowance'));?>" class="plug-cell"><div class="plug-img allowance"></div><div class="plug-name">红包职位</div><div class="h-line"></div></a><?php endif; ?>
                    <?php if(!empty($apply['Gworker'])): ?><a target="_blank" href="<?php echo url_rewrite('QS_gworker');?>" class="plug-cell"><div class="plug-img gworker"></div><div class="plug-name">普工招聘</div><div class="h-line"></div></a><?php endif; ?>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <!-- 格子广告 -->
    <?php $tag_ad_class = new \Common\qscmstag\adTag(array('列表名'=>'ad','广告位名称'=>'QS_indexcenterrecommend','职位数量'=>'12','广告数量'=>'15','cache'=>'0','type'=>'run',));$ad = $tag_ad_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$ad);?>
    <?php if(!empty($ad['list'])): ?><div class="im-a-group">
            <?php if(is_array($ad['list'])): $k = 0; $__LIST__ = $ad['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ad_info): $mod = ($k % 2 );++$k;?><div class="im-a-cell c<?php echo ($k); ?> J_hoverbut">
                    <div class="imgbox">
                        <?php if(!empty($ad_info['company']['companyname'])): ?><div class="showinfo pie_about">
                                <div class="comname substring link_yellow"><a target="_blank" href="<?php echo ($ad_info["href"]); ?>"><?php echo ($ad_info['company']['companyname']); ?></a></div>
                                <?php if(!empty($ad_info['company']['jobs'])): ?><div class="jobslist link_gray6">
                                        <?php if(is_array($ad_info[company]['jobs'])): $i = 0; $__LIST__ = $ad_info[company]['jobs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$jobs): $mod = ($i % 2 );++$i;?><div class="jli substring"><a target="_blank" href="<?php echo ($jobs["jobs_url"]); ?>"><?php echo ($jobs["jobs_name"]); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </div><?php endif; ?>
                                <?php if(!empty($ad_info['company']['jobs_count'])): ?><div class="count">
                                        <div class="v">共有<?php echo ($ad_info['company']['jobs_count']); ?>个职位</div>
                                        <div class="more link_blue"><a target="_blank" href="<?php echo url_rewrite('QS_companyjobs',array('id'=>$ad_info['company']['id']));?>">查看全部</a></div>
                                        <div class="clear"></div>
                                    </div><?php endif; ?>
                            </div><?php endif; ?>
                        <a href="<?php echo ($ad_info["href"]); ?>" target="_blank" title="<?php echo ($ad_info["title"]); ?>">
                            <img src="<?php echo attach($ad_info['content'],'ads');?>" border="0"/>
                        </a>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="clear"></div>
        </div><?php endif; ?>
    <!-- 格子广告 end -->
    <!-- 头部横幅广告 -->
    <?php $tag_ad_class = new \Common\qscmstag\adTag(array('列表名'=>'ad','广告位名称'=>'QS_indextopimg','广告数量'=>'2','cache'=>'0','type'=>'run',));$ad = $tag_ad_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$ad);?>
    <?php if(!empty($ad['list'])): if(is_array($ad['list'])): $i = 0; $__LIST__ = $ad['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ad_info): $mod = ($i % 2 );++$i;?><div class="im12_80">
                <a href="<?php echo ($ad_info["href"]); ?>" target="_blank" title="<?php echo ($ad_info["title"]); ?>">
                    <img src="<?php echo attach($ad_info['content'],'ads');?>" border="0" />
                </a>
            </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    <!-- 头部横幅广告 end -->
    <!-- 最新招聘 急聘专区 公告 招聘会-->
    <div class="bic-split-18"></div>
    <div class="ni-m-container-hj">
        <div class="hiring-job">
            <div class="hj-top">
                <div class="hj-top-left">最新招聘</div>
                <a class="hj-top-right" target="_blank" href="<?php echo url_rewrite('QS_jobslist');?>">更多</a>
                <div class="clear"></div>
            </div>
            <div class="hj-jobs">
                <?php $tag_company_jobs_list_class = new \Common\qscmstag\company_jobs_listTag(array('列表名'=>'new_jobs','分页显示'=>'1','职位数量'=>'1','排序'=>'rtime','显示数目'=>'15','cache'=>'0','type'=>'run',));$new_jobs = $tag_company_jobs_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$new_jobs);?>
                <?php if(is_array($new_jobs['list'])): $k = 0; $__LIST__ = $new_jobs['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$company): $mod = ($k % 2 );++$k;?><div class="hj-jli j<?php echo ($k); ?>">
                        <?php if(is_array($company['jobs'])): $i = 0; $__LIST__ = $company['jobs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$jobs): $mod = ($i % 2 );++$i;?><div class="hj-spl-18"></div>
                            <div class="hj-main-line">
                                <div class="hj-line-jn"><a target="_blank" href="<?php echo ($jobs["jobs_url"]); ?>" title="<?php echo ($jobs["jobs_name"]); ?>"><?php echo ($jobs["jobs_name"]); ?></a></div>
                                <div class="hj-line-wa"><?php echo ($jobs["wage_cn"]); ?></div>
                                <div class="clear"></div>
                            </div>
                            <div class="hj-spl"></div>
                            <div class="hj-main-line">
                                <div class="hj-line-jn link_gray9"><a target="_blank" href="<?php echo ($company["company_url"]); ?>" title="<?php echo ($company["companyname"]); ?>"><?php echo ($company["companyname"]); ?></a></div>
                                <div class="hj-line-time"><?php echo ($jobs["refreshtime_cn"]); ?></div>
                                <div class="clear"></div>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <div class="hot-job">
            <div class="ht-top">
                <div class="ht-top-left">急聘专区</div>
                <a class="ht-top-right" target="_blank" href="<?php echo url_rewrite('QS_jobslist',array('emergency'=>1));?>">更多</a>
                <div class="clear"></div>
            </div>
            <div class="ht-eme">
                <div class="slide">
                    <?php $tag_jobs_list_class = new \Common\qscmstag\jobs_listTag(array('列表名'=>'emergency_jobs','紧急招聘'=>'1','显示数目'=>'6','cache'=>'0','type'=>'run',));$emergency_jobs = $tag_jobs_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$emergency_jobs);?>
                    <?php if(is_array($emergency_jobs['list'])): $i = 0; $__LIST__ = $emergency_jobs['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$jobs): $mod = ($i % 2 );++$i;?><div class="sli">
                            <div class="tit substring link_gray6"><a target="_blank" href="<?php echo ($jobs["jobs_url"]); ?>"><?php echo ($jobs["jobs_name"]); ?></a></div>
                            <div class="salary substring"><?php echo ($jobs["wage_cn"]); ?></div>
                            <div class="clear"></div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="ht-not">
                <div class="noticestab">
                    <div class="tli select">公告</div>
                    <?php if(!empty($apply['Jobfair'])): ?><div class="tli last">招聘会</div><?php endif; ?>
                    <?php if(!empty($apply['Seniorjobfair'])): ?><div class="tli last">招聘会</div><?php endif; ?>
                    <div class="clear"></div>
                </div>
                <div class="notice_showtabs first">
                    <ul class="link_gray6">
                        <?php $tag_notice_list_class = new \Common\qscmstag\notice_listTag(array('列表名'=>'notice_list','显示数目'=>'4','分类'=>'1','cache'=>'0','type'=>'run',));$notice_list = $tag_notice_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$notice_list);?>
                        <?php if(is_array($notice_list['list'])): $i = 0; $__LIST__ = $notice_list['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$notice): $mod = ($i % 2 );++$i;?><li class="substring new"><a href="<?php echo ($notice["url"]); ?>" target="_blank"><?php echo ($notice["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
                <?php if(!empty($apply['Jobfair'])): ?><div class="notice_showtabs">
                        <!--招聘会 -->
                        <ul class="link_gray6">
                            <?php $tag_jobfair_list_class = new \Common\qscmstag\jobfair_listTag(array('列表名'=>'jobfair_list','显示数目'=>'4','排序'=>'ordid:desc','cache'=>'0','type'=>'run',));$jobfair_list = $tag_jobfair_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$jobfair_list);?>
                            <?php if(is_array($jobfair_list['list'])): $i = 0; $__LIST__ = $jobfair_list['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$jobfair): $mod = ($i % 2 );++$i;?><li class="substring new"><a href="<?php echo ($jobfair['url']); ?>" target="_blank"><?php echo ($jobfair["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div><?php endif; ?>
                <?php if(!empty($apply['Seniorjobfair'])): ?><div class="notice_showtabs">
                        <!--招聘会 -->
                        <ul class="link_gray6">
                            <?php $tag_senior_jobfair_list_class = new \Common\qscmstag\senior_jobfair_listTag(array('列表名'=>'jobfair_list','显示数目'=>'4','排序'=>'ordid:desc','cache'=>'0','type'=>'run',));$jobfair_list = $tag_senior_jobfair_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$jobfair_list);?>
                            <?php if(is_array($jobfair_list['list'])): $i = 0; $__LIST__ = $jobfair_list['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$jobfair): $mod = ($i % 2 );++$i;?><li class="substring new"><a href="<?php echo ($jobfair['url']); ?>" target="_blank"><?php echo ($jobfair["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div><?php endif; ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <!-- 急聘专区 热门职位 end -->
    <!-- 明星雇主 -->
    <?php $tag_ad_class = new \Common\qscmstag\adTag(array('列表名'=>'ad','广告位名称'=>'QS_star_employer','广告数量'=>'20','cache'=>'0','type'=>'run',));$ad = $tag_ad_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$ad);?>
    <?php if(!empty($ad['list'])): ?><div class="bic-split-18"></div>
        <div class="star_employer_box" stats="0">
            <i class="star_tips"></i>
            <i class="star_refresh" id="js_star_refresh"></i>
            <?php if(count($ad['list']) > 10): ?><i class="star_more" id="js_star_more"></i><?php endif; ?>
            <ul id="star_company_logos_ul">
                <?php if(is_array($ad['list'])): $i = 0; $__LIST__ = $ad['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ad_info): $mod = ($i % 2 );++$i;?><li>
                        <a target="_blank" href="<?php echo ($ad_info["href"]); ?>" title="<?php echo ($ad_info["title"]); ?>" <?php if(!$ad_info.company): ?>class="no_com"<?php endif; ?>><img src="<?php echo attach($ad_info['content'],'ads');?>"><?php if($ad_info.company): ?><span><?php echo ($ad_info["company"]["companyname"]); ?></span><?php endif; ?></a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <script type="text/javascript">
                $("#js_star_refresh").on("click", function(){
                    var starBox = $(".star_employer_box"),
                        page = Number(starBox.attr('page')) || 2;
                    $.ajax({
                        type : "get",
                        url :"<?php echo U('home/ajaxCommon/star_employer');?>",
                        data :{stats:starBox.attr('stats') || 0,page:page},
                        success : function(r){
                            if(r.status){
                                if(r.data.isfull){
                                    starBox.attr('page',1);
                                }else{
                                    starBox.attr('page',page+=1);
                                }
                                var html = "";
                                $.each(r.data.list,function(i,n){
                                    html += "<li>";
                                    if (n.company) {
                                        html += '<a target="_blank" href="'+n.href+'"><img src="'+n.content+'" /><span>'+n.company.companyname+'</span></a>';
                                    } else {
                                        html += '<a target="_blank" href="'+n.href+'" class="no_com"><img src="'+n.content+'" /></a>';
                                    }
                                    html += "</li>";
                                });
                                $("#star_company_logos_ul").html(html);
                            }
                        }
                    });
                });
                //查看更多
                $("#js_star_more").on("click",function(){
                    var starBox = $(".star_employer_box");
                    if(starBox.hasClass("star_employer_more")) {
                        starBox.removeClass("star_employer_more").attr('stats',0);
                    }else{
                        starBox.addClass("star_employer_more").attr('stats',1);
                    }
                    return false;
                });
            </script>
        </div><?php endif; ?>
    <!-- 明星雇主 end -->
    <!--红包职位-->
    <?php if(C('apply.Allowance')): ?><div class="bic-split-18"></div>
        <div class="some-job-group alo">
            <a target="_blank" href="<?php echo url_rewrite('QS_jobslist',array('search_cont'=>'allowance'));?>" class="some-more">查看更多</a>
            <div class="some-job-icon"></div>
            <div class="some-job-jg">
                <?php $tag_jobs_list_class = new \Common\qscmstag\jobs_listTag(array('列表名'=>'jobslist','搜索内容'=>'allowance','显示数目'=>'8','排序'=>'rtime','cache'=>'0','type'=>'run',));$jobslist = $tag_jobs_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$jobslist);?>
                <?php if(is_array($jobslist['list'])): $k = 0; $__LIST__ = $jobslist['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="sjj-cell a<?php echo ($k); ?>">
                        <div class="sjj-cell-right">
                            <div class="alo-num substring"><?php echo ($vo['allowance_info']['per_amount']); ?>元</div>
                        </div>
                        <div class="sjj-cell-left">
                            <div class="sj-cn"><a target="_blank" href="<?php echo ($vo['jobs_url']); ?>"><?php echo ($vo['jobs_name']); ?></a></div>
                            <div class="c-sp-9"></div>
                            <div class="sj-line substring">学历：<?php echo ($vo['education_cn']); ?>&nbsp;&nbsp;|&nbsp;&nbsp;经验：<?php echo ($vo['experience_cn']); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="font_yellow"><?php echo ($vo['wage_cn']); ?></span></div>
                            <div class="c-sp-14"></div>
                            <div class="sj-line font_gray9 substring">更新时间：<?php echo date("Y-m-d",$vo['refreshtime']);?></div>
                        </div>
                        <div class="clear"></div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div><?php endif; ?>
    <!--红包职位 end-->
    <!-- 中部横幅广告 -->
    <?php $tag_ad_class = new \Common\qscmstag\adTag(array('列表名'=>'ad','广告位名称'=>'QS_indexcenter','广告数量'=>'1','cache'=>'0','type'=>'run',));$ad = $tag_ad_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$ad);?>
    <?php if(!empty($ad['list'])): ?><div class="im12_80">
            <?php if(is_array($ad['list'])): $i = 0; $__LIST__ = $ad['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ad_info): $mod = ($i % 2 );++$i;?><a href="<?php echo ($ad_info["href"]); ?>" title="<?php echo ($ad_info["title"]); ?>" target="_blank">
                    <img src="<?php echo attach($ad_info['content'],'ads');?>" border="0"/>
                </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div><?php endif; ?>
    <!-- 中部横幅广告 end -->
    <!-- 推荐简历 -->
    <div class="bic-split-18"></div>
    <div class="some-job-group new-resume J_change_parent">
        <a href="javascript:;" class="out-flag f3 J_change_batch" data-url="<?php echo U('AjaxCommon/resume_list');?>">换<br/>一<br/>批</a>
        <a target="_blank" href="<?php echo url_rewrite('QS_resumelist');?>" class="some-more">查看更多</a>
        <div class="some-job-icon"></div>
        <div class="some-job-jg">
            <div class="ajax_loading"><div class="ajaxloadtxt"></div></div>
            <?php $tag_resume_list_class = new \Common\qscmstag\resume_listTag(array('列表名'=>'recommend_resume','照片'=>'1','显示数目'=>'8','排序'=>'rtime','cache'=>'0','type'=>'run',));$recommend_resume = $tag_resume_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$recommend_resume);?>
            <div class="J_change_result">
                <?php if(is_array($recommend_resume['list'])): $i = 0; $__LIST__ = $recommend_resume['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$resume): $mod = ($i % 2 );++$i;?><div class="sjj-cell a<?php echo ($i); ?>">
                        <div class="sjj-cell-left">
                            <div class="sj-cn"><a target="_blank" href="<?php echo ($resume["resume_url"]); ?>"><?php echo ($resume["fullname"]); ?></a></div>
                            <div class="c-sp-9"></div>
                            <div class="sj-line substring">学历：<?php echo ($resume["education_cn"]); ?>&nbsp;&nbsp;|&nbsp;&nbsp;经验：<?php echo ($resume["experience_cn"]); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<span class="font_yellow"><?php echo date("Y-m-d",$resume['refreshtime']);?></span></div>
                            <div class="c-sp-14"></div>
                            <div class="sj-line font_gray9 substring"><?php echo ($resume["intention_jobs"]); ?></div>
                        </div>
                        <div class="sjj-cell-right"><a target="_blank" href="<?php echo ($resume["resume_url"]); ?>"><img src="<?php echo ($resume["photosrc"]); ?>"></a></div>
                        <div class="clear"></div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <!-- 推荐简历 end -->
    <!-- 职场资讯 -->
    <div class="bic-split-18"></div>
    <div class="some-job-group news">
        <a target="_blank" href="<?php echo url_rewrite('QS_news');?>" class="some-more">查看更多</a>
        <div class="some-job-icon"></div>
        <div class="new-cate-group">
            <div class="ncg-top">
                <?php $tag_classify_class = new \Common\qscmstag\classifyTag(array('列表名'=>'article_category','类型'=>'QS_article','id'=>'1','显示数目'=>'7','cache'=>'0','type'=>'run',));$article_category = $tag_classify_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$article_category);?>
                <?php if(is_array($article_category)): $i = 0; $__LIST__ = $article_category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$category): $mod = ($i % 2 );++$i;?><div class="J_news_list_title ncg-top-cell <?php if($i == 1): ?>select<?php endif; ?>" cid="<?php echo ($key); ?>"><?php echo ($category); ?></div><?php endforeach; endif; else: echo "" ;endif; ?>
                <div class="clear"></div>
            </div>
            <div class="ncg-con-group J_news_content">
                <div class="ajax_loading"><div class="ajaxloadtxt"></div></div>
                <div class="J_news_txt">
                    <?php if(is_array($article_category)): $i = 0; $__LIST__ = array_slice($article_category,0,1,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$category): $mod = ($i % 2 );++$i; $tag_news_list_class = new \Common\qscmstag\news_listTag(array('列表名'=>'article_list','显示数目'=>'15','资讯小类'=>_I($key),'cache'=>'0','type'=>'run',));$article_list = $tag_news_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$article_list); endforeach; endif; else: echo "" ;endif; ?>
                    <?php if(!empty($article_list)): ?><div class="ncg-con-left">
                            <div class="ncg-img-cell">
                                <div class="img-b"><a target="_blank" href="<?php echo ($article_list['list'][0]['url']); ?>"><img src="<?php echo ($article_list['list'][0]['img']); ?>"></a></div>
                                <div class="img-t"><a target="_blank" href="<?php echo ($article_list['list'][0]['url']); ?>" title="<?php echo ($article_list['list'][0]['title']); ?>"><?php echo ($article_list['list'][0]['title']); ?></a></div>
                            </div>
                            <div class="ncg-img-cell">
                                <div class="img-b"><a target="_blank" href="<?php echo ($article_list['list'][1]['url']); ?>"><img src="<?php echo ($article_list['list'][1]['img']); ?>"></a></div>
                                <div class="img-t"><a target="_blank" href="<?php echo ($article_list['list'][1]['url']); ?>" title="<?php echo ($article_list['list'][1]['title']); ?>"><?php echo ($article_list['list'][1]['title']); ?></a></div>
                            </div>
                        </div>
                        <div class="ncg-con-right">
                            <?php if(is_array($article_list['list'])): $i = 0; $__LIST__ = array_slice($article_list['list'],2,10,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$article): $mod = ($i % 2 );++$i;?><div class="ncr-line">
                                    <div class="ncr-line-left"><a target="_blank" href="<?php echo ($article["url"]); ?>" style="<?php if($article['tit_color']): ?>color:<?php echo ($article["tit_color"]); ?>;<?php endif; if($article['tit_b'] > 0): ?>font-weight:bold<?php endif; ?>" title="<?php echo ($article["title"]); ?>"><?php echo ($article["title"]); ?></a></div>
                                    <div class="ncr-line-right"><?php echo date("Y-m-d",$article['addtime']);?></div>
                                    <div class="clear"></div>
                                </div><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div><?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="new-hot-group">
            <div class="new-hot-top">热点资讯</div>
            <div class="nhg-line-con">
                <?php $tag_news_list_class = new \Common\qscmstag\news_listTag(array('列表名'=>'hot_news','显示数目'=>'10','属性'=>'2','cache'=>'0','type'=>'run',));$hot_news = $tag_news_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$hot_news);?>
                <?php if(is_array($hot_news['list'])): $i = 0; $__LIST__ = $hot_news['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$news): $mod = ($i % 2 );++$i;?><div class="nhg-line">
                        <div class="nhg-line-left n<?php echo ($i); ?>"><?php echo ($i); ?></div>
                        <div class="nhg-line-right"><a target="_blank" href="<?php echo ($news["url"]); ?>" style="<?php if($news['tit_color']): ?>color:<?php echo ($news["tit_color"]); ?>;<?php endif; if($news['tit_b'] > 0): ?>font-weight:bold<?php endif; ?>"><?php echo ($news["title"]); ?></a></div>
                        <div class="clear"></div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <!-- 职场资讯 end -->
    <div class="new-footer">
    <div class="footer-act-group">
        <div class="fag-main">
            <?php $tag_link_class = new \Common\qscmstag\linkTag(array('列表名'=>'links','类型'=>'1','cache'=>'0','type'=>'run',));$links = $tag_link_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$links);?>
            <a href="javascript:;" target="_blank" class="fag-link-cell fir">友情链接</a>
            <?php if(is_array($links)): $i = 0; $__LIST__ = $links;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$link): $mod = ($i % 2 );++$i;?><a href="<?php echo ($link["link_url"]); ?>" target="_blank" class="fag-link-cell"><?php echo ($link["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="clear"></div>
        </div>
    </div>
    <div class="footer-txt-group">
        <div class="ftg-main">
            <div class="ftg-left">
                <div class="ftg-a-group">
                    <?php $tag_explain_list_class = new \Common\qscmstag\explain_listTag(array('列表名'=>'list','显示数目'=>'4','cache'=>'0','type'=>'run',));$list = $tag_explain_list_class->run();$frontend = new \Common\Controller\FrontendController;$page_seo = $frontend->_config_seo(array("pname"=>"","title"=>"骑士PHP高端人才系统(www.74cms.com)","keywords"=>"骑士人才系统，74cms，骑士cms，人才网站源码，php人才网程序","description"=>"骑士CMS是基于PHP+MYSQL的免费网站管理系统，提供完善的人才招聘网站建设方案","header_title"=>""),$list);?>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo['url']); ?>" target="_blank" class="fag-link-cell"><?php echo ($vo['title']); ?></a><span class="hl">|</span><?php endforeach; endif; else: echo "" ;endif; ?>
                    <span class="tel">联系电话：<?php echo C('qscms_bootom_tel');?></span>
                </div>
                <p class="copyright">联系地址：<?php echo C('qscms_address');?> &nbsp;&nbsp;网站备案：<?php echo C('qscms_icp');?></p>
                <p class="copyright"><?php echo C('qscms_bottom_other');?> &nbsp;&nbsp;Powered by <a href="http://www.74cms.com">74cms</a> v<?php echo C('QSCMS_VERSION');?> <?php echo htmlspecialchars_decode(C('qscms_statistics'));?></p>
            </div>
            <div class="ftg-right">
                <div class="qr-box">
                    <div class="img"><img src="<?php echo attach(C('qscms_weixin_img'),'resource');?>"></div>
                    <div class="qr-txt">公众号</div>
                </div>
                <?php if(!empty($apply['Mobile'])): ?><div class="qr-box">
                        <div class="img"><img src="<?php echo C('qscms_site_dir');?>index.php?m=Home&c=Qrcode&a=index&url=<?php echo urlencode(build_mobile_url());?>"></div>
                        <div class="qr-txt">触屏端</div>
                    </div><?php endif; ?>
                <?php if(C('qscms_weixinapp_qrcode') && $apply['Weixinapp']): ?><div class="qr-box">
                    <div class="img"><img src="<?php echo attach(C('qscms_weixinapp_qrcode'),'images');?>"></div>
                    <div class="qr-txt">微信小程序</div>
                </div><?php endif; ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div class="floatmenu">
    <?php if(($show_backtop) == "1"): ?><div class="item mobile">
            <a class="blk"></a>
            <?php if(($show_backtop_app) == "1"): ?><div class="popover <?php if( $show_backtop_weixin == 1): ?>popover1<?php endif; ?>">
                    <div class="popover-bd">
                        <label>手机APP</label>
                        <span class="img-qrcode img-qrcode-mobile"><img src="<?php echo C('qscms_site_dir');?>index.php?m=Home&c=Qrcode&a=index&url=<?php echo urlencode(C('qscms_site_domain').U('Mobile/Index/app_download'));?>" alt=""></span>
                    </div>
                </div><?php endif; ?>
            <?php if(($show_backtop_weixin) == "1"): ?><div class="popover">
                    <div class="popover-bd">
                        <label class="wx">企业微信</label>
                        <span class="img-qrcode img-qrcode-wechat"><img src="<?php echo attach(C('qscms_weixin_img'),'resource');?>" alt=""></span>
                    </div>
                    <div class="popover-arr"></div>
                </div><?php endif; ?>
        </div><?php endif; ?>
    <div class="item ask"><a class="blk" target="_blank" href="<?php echo url_rewrite('QS_suggest');?>"></a></div>
    <div id="backtop" class="item backtop" style="display: none;"><a class="blk"></a></div>
</div>

<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo C('TPL_HOME_PUBLIC_DIR');?>/js/PIE.js"></script>
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
    }
</script>
<![endif]-->
<script type="text/javascript" src="<?php echo C('TPL_HOME_PUBLIC_DIR');?>/js/jquery.disappear.tooltip.js"></script>
<script type="text/javascript">
  var global = {
    h: $(window).height(),
    st: $(window).scrollTop(),
    backTop: function () {
      global.st > (global.h * 0.5) ? $("#backtop").show() : $("#backtop").hide();
    }
  }
  $('.footer-txt-group .hl').eq($('.footer-txt-group .hl').length-1).addClass('last');
  $('#backtop').on('click', function () {
    $("html,body").animate({"scrollTop": 0}, 500);
  });
  global.backTop();
  $(window).scroll(function () {
    global.h = $(window).height();
    global.st = $(window).scrollTop();
    global.backTop();
  });
  $(window).resize(function () {
    global.h = $(window).height();
    global.st = $(window).scrollTop();
    global.backTop();
  });
  // 客服QQ
  var app_qq = "<?php echo C('apply.Qqfloat');?>";
  var qq_open = "<?php echo C('qscms_qq_float_open');?>";
  if(app_qq != '' && qq_open == 1){
    var QQFloatUrl = "<?php echo U('Qqfloat/Index/index');?>";
    $.getJSON(QQFloatUrl, function (result) {
      if (result.status == 1) {
        //$(".qq-float").html(result.data);
        $("body").append(result.data);
      }
    });
  }
</script>
    <div class="bt_guider J_bt_guider">
        <p class="shadow"></p>
        <div class="bt_guider_wrap">
            <div class="guider_icon"></div>
            <a href="javascript:;" class="guider_close J_bt_gui_close"></a>
            <div class="gm_qr_code">
                <div class="qr_code_box">
                    <img src="<?php if(C('qscms_index_bottom_wx')): echo attach(C('qscms_index_bottom_wx'),'resource'); else: echo C('qscms_site_dir');?>index.php?m=Home&c=Qrcode&a=index&url=<?php echo urlencode(build_mobile_url()); endif; ?>" alt="触屏端">
                </div>
                <div class="hs_6"></div>
                <div class="qr_other">扫一扫手机也能找工作</div>
            </div>
            <div class="gm_gr_sha"></div>
            <div class="guider_main">
                <div class="gm_left">
                    <div class="hs_16"></div>
                    <div class="gm_site_name"><?php echo C('qscms_index_bottom_title');?></div>
                    <div class="hs_12"></div>
                    <div class="gm_other"><?php echo C('qscms_index_bottom_info');?></div>
                </div>
                <div class="gm_right">
                    <div class="hs_20"></div>
                    <a href="javascript:;" id="J_reg" class="gm_btn">立即加入</a>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <script type="text/javascript">
            $('.J_bt_gui_close').click(function () {
                $('.J_bt_guider').hide();
            })
        </script>
    </div>
    <script type="text/javascript" src="<?php echo C('TPL_PUBLIC_DIR');?>/js/jquery.modal.dialog.js"></script>
    <script type="text/javascript" src="<?php echo C('TPL_PUBLIC_DIR');?>/js/jquery.index.js"></script>
    <script src="http://static.geetest.com/static/tools/gt.js"></script>
    <?php if($apply['Recommend'] and $isRecommend): ?><script type="text/javascript" src="/recruit/<?php echo (APP_NAME); ?>/Recommend/View/default/public/Recommend.js"></script>
        <link href="/recruit/<?php echo (APP_NAME); ?>/Recommend/View/default/public/plugin-recomment.css" rel="stylesheet" type="text/css" /><?php endif; ?>
    <script type="text/javascript">
        $('.h-line:last').remove();
    </script>
</body>
</html>