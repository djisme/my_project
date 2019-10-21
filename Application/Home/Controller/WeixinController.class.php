<?php
namespace Home\Controller;
use Common\Controller\FrontendController;
use Common\qscmslib\wxBizMsgCrypt\WXBizMsgCrypt;
class WeixinController extends FrontendController{
	public function _initialize() {
        parent::_initialize();
    }
    public function index(){
		$echoStr = I('get.echostr','','trim');
		$echoStr && $this->_valid($echoStr);
		$this->_responseMsg();
	}
	/**
	 * [_valid 微信接入认证]
	 */
	protected function _valid($echoStr){
		if($this->checkSignature()) exit($echoStr);
		exit('false');
	}
	/**
	 * [checkSignature 验名认证]
	 */
	protected function checkSignature(){
	    $signature = I('get.signature','','trim');
	    $timestamp = I('get.timestamp','','trim');
	    $nonce = I('get.nonce','','trim');
		$tmpArr = array(C('qscms_weixin_apptoken'), $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);		
		if($tmpStr == $signature){
			return true;
		}else{
			return false;
		}
	}
	//验证php版本
	protected function check_php_version($version) {
		 $php_version = explode('-',phpversion());
		 // strnatcasecmp( $php_version[0], $version ) 0表示等于，1表示大于，-1表示小于
		 $is_pass = strnatcasecmp($php_version[0],$version)>=0?true:false;
		 return $is_pass;
	}
	//检查网站微信接口是否开启
	protected function check_weixin_open($object){
		if(!C('qscms_weixin_apiopen')){
			$this->content="网站微信接口已经关闭";
			$this->transmitText($object,$this->content);
		}
	}
	/**
	 * [_responseMsg 消信类]
	 */
	protected function _responseMsg(){
		if(!$this->checkSignature()) exit('false');
		if($postStr = I('globals.HTTP_RAW_POST_DATA','','trim')){
			$this->timestamp  = I('get.timestamp','','trim');
			$this->nonce = I('get.nonce','','trim');
			$this->msg_signature  = I('get.msg_signature','','trim');
			$this->encrypt_type = 'aes' == I('get.encrypt_type','','trim') ? 'aes' : 'raw';
			//解密
			if ($this->encrypt_type == 'aes'){
				$pc = new WXBizMsgCrypt(C('qscms_weixin_apptoken'),C('qscms_weixin_encoding_aes_key'),C('qscms_weixin_appid'));
		
				$decryptMsg = "";//解密后的明文
		
				$errCode = $pc->decryptMsg($this->msg_signature, $this->timestamp, $this->nonce, $postStr, $decryptMsg);
				// file_put_contents(QSCMS_DATA_PATH."test.txt","123");
				if($errCode != 0){
					echo "";
            		exit;
				}
				$postStr = $decryptMsg;
			}
			if($this->check_php_version("5.2.11")){
				libxml_disable_entity_loader(true);
			}
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $rxType = trim($postObj->MsgType);
		
            //消息类型分离
            switch ($rxType){
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$rxType;
                    break;
            }
            //加密
			if ($this->encrypt_type == 'aes'){
				$encryptMsg = ''; //加密后的密文
				$errCode = $pc->encryptMsg($result, $this->timeStamp, $this->nonce, $encryptMsg);
				if($errCode != 0){
					echo "";
            		exit;
				}
				$result = $encryptMsg;
			}
            echo $result;
        }else{
            echo "";
            exit;
        }
	}
	protected function xmlToArray($simpleXmlElement){  
	    $simpleXmlElement=(array)$simpleXmlElement;  
	    foreach($simpleXmlElement as $k=>$v){  
	        if($v instanceof SimpleXMLElement ||is_array($v)){  
	            $simpleXmlElement[$k]=xmlToArray($v);  
	        }  
	    }  
	    return $simpleXmlElement;  
	} 
	//接收事件消息
    protected function receiveEvent($object){
        switch($object->Event){
            case "subscribe":
                if ($object->EventKey){//用户未关注时，进行关注后的事件推送
                    $this->actionScan($object);
                } else {
                    $this->content = "欢迎关注".C('qscms_site_name')."！
1.绑定您的".C('qscms_site_name')."帐号，求职招聘更加方便，并实时接收提醒通知。<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点此立即绑定</a>
2.您可以回复【j】或【n】获取紧急、最新招聘信息，或者回复职位、公司等关键词如【销售】获取相关职位信息。
3.每日签到，免费获取".C('qscms_points_byname')."，商城好礼兑不停！";
                }
                break;
            case "LOCATION":
                  $map=(array)$object;
                  S('location_'.addslashes($object->FromUserName),$map);
	            break;
            case "SCAN":
                $this->actionScan($object);
                break;
            case "CLICK":
            	$this->check_weixin_open($object);
                switch ($object->EventKey){
                    case "binding"://绑定
                    	$this->clickBinding($object);
						break;
					case "resume_refresh"://刷新简历
						$this->clickResumeRefresh($object);
						break;
					case "nearby_jobs"://周边职位
						$this->clickNearbyJobs($object);
						break;
					case "jobs_refresh"://刷新职位
						$this->clickJobsRefresh($object);
						break;
					case "sign_day"://每日签到
						$this->clickSignDay($object);
						break;
                }
                break;
            case "unsubscribe":
            	$fromUsername = addslashes($object->FromUserName);
            	M('MembersBind')->where(array('type'=>'weixin','openid'=>$fromUsername))->limit('1')->save(array('is_focus'=>0,'focustime'=>0));
                break;
            default:
                $this->content = "欢迎关注".C('qscms_site_name')."！
1.绑定您的".C('qscms_site_name')."帐号，求职招聘更加方便，并实时接收提醒通知。<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点此立即绑定</a>
2.您可以回复【j】或【n】获取紧急、最新招聘信息，或者回复职位、公司等关键词如【销售】获取相关职位信息。
3.每日签到，免费获取".C('qscms_points_byname')."，商城好礼兑不停！";
                break;
        }
        if(is_array($this->content)){
            if (isset($this->content[0])){
                $result = $this->transmitNews($object, $this->content);
            }
        }else{
            $result = $this->transmitText($object, $this->content);
        }
        return $result;
    }
    //接收文本消息
    protected function receiveText($object){
    	$this->check_weixin_open($object);
        $keyword = trim($object->Content);
		$keyword = addslashes($keyword);
        //自动回复模式
        $this->enterSearch($object,$keyword);
        if(is_array($this->content)){
            if (isset($this->content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $this->content);
            }
        }else{
            $result = $this->transmitText($object, $this->content);
        }
        return $result;
    }
    //回复文本消息
    private function transmitText($object, $content){
        $xmlTpl = '<xml>
	        <ToUserName><![CDATA[%s]]></ToUserName>
	        <FromUserName><![CDATA[%s]]></FromUserName>
	        <CreateTime>%s</CreateTime>
	        <MsgType><![CDATA[text]]></MsgType>
	        <Content><![CDATA[%s]]></Content>
	        </xml>';
		$content = $content;
        $result = sprintf($xmlTpl, addslashes($object->FromUserName), $object->ToUserName, time(), $content);
        return $result;
    }
    //回复图文消息
    private function transmitNews($object, $newsArray){
        if(!is_array($newsArray)) return;
        $itemTpl = "<item>
	        <Title><![CDATA[%s]]></Title>
	        <Description><![CDATA[%s]]></Description>
	        <PicUrl><![CDATA[%s]]></PicUrl>
	        <Url><![CDATA[%s]]></Url>
			</item>";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<ArticleCount>%s</ArticleCount>
			<Articles>
			$item_str</Articles>
			</xml>";
        $result = sprintf($xmlTpl, addslashes($object->FromUserName), $object->ToUserName, time(), count($newsArray));
        return $result;
    }
    public function open_gate($event_key,$object){
    	if(stripos($event_key,'gate_') !== false){
    		$usinfo = $this->get_user_info($object->FromUserName);
    		$event_key_arr = explode("_", $event_key);
    		if($event_key_arr[1]=='in'){
				$status = 'opendoorin1';
			}else{
				$status = 'opendoorout1';
			}
    		$url = "http://gate.74cms.com/Pad/Index/index";
    		$data['userinfo'] = json_encode($usinfo);
    		$data['status'] = $status;
    		$data['tag'] = $event_key_arr[2];
    		$data['secret'] = $event_key_arr[3];
	    	https_request($url,$data);
    	}
    }
    //扫描事件
	protected function actionScan($object){
	    //用户未关注时，关注后的推送数据包中，EventKey含有“qrscene_”前缀
        $event_key = stripos($object->EventKey,'qrscene_') === false ? $object->EventKey : trim($object->EventKey,'qrscene_');
        $this->open_gate($event_key,$object);
		if($event_key == 0){
			$usinfo = $this->get_user_info($object->FromUserName);
			if($usinfo){
				$this->content = "欢迎关注".C('qscms_site_name')."！
1.您可以回复【j】或【n】获取紧急、最新招聘信息，或者回复职位、公司等关键词如【销售】获取相关职位信息。
2.每日签到，免费获取".C('qscms_points_byname')."，商城好礼兑不停！
3.<a href='".C('qscms_site_domain')."'>点此进入".C('qscms_site_name')."</a>";
			}else{
				$this->content = "欢迎关注".C('qscms_site_name')."！
1.绑定您的".C('qscms_site_name')."帐号，求职招聘更加方便，并实时接收提醒通知。<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点此立即绑定</a>
2.您可以回复【j】或【n】获取紧急、最新招聘信息，或者回复职位、公司等关键词如【销售】获取相关职位信息。
3.每日签到，免费获取".C('qscms_points_byname')."，商城好礼兑不停！";
			}
		}elseif($event_key<=10000000){
			$usinfo = $this->get_user_info($object->FromUserName);
			if($usinfo){
				$this->content = "<a href='".C('qscms_site_domain').'?m=Home&c=Callback&a=weixin_login&openid='.addslashes($object->FromUserName).'&uid='.$usinfo['uid'].'&event_key='.$event_key."'>点此立即登录".C('qscms_site_name')."网页</a>";
			}else{
				$this->content="您还未绑定".C('qscms_site_name')."帐号，现在开始绑定：<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点击开始注册/绑定帐号</a>";
			}
		}elseif($event_key>10000000 && $event_key<=20000000){
			$this->content="<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点击此处开始注册</a>";
		}elseif($event_key>20000000 && $event_key<=30000000){
			$usinfo = $this->get_user_info($object->FromUserName);
			if($usinfo){
				$this->content="您已绑定过".C('qscms_site_name')."帐号【".unicode_emoji($usinfo['username'])."】。如需解绑，请回复“解绑”";
			}else{
				$fromUsername = addslashes($object->FromUserName);
				F('/weixin/'.($event_key%10).'/'.$event_key,$fromUsername);
				if(F('/weixin/'.($event_key%10).'/'.$event_key)===$fromUsername){
					$this->content="恭喜，您已成功绑定".C('qscms_site_name').",下次可直接扫码登录了哦！如需解绑，请回复“解绑”";
				}else{
					$this->content="绑定失败，请重新绑定！";
				} 
			}
		}elseif($event_key>30000000 && $event_key<=40000000){// 微信扫码获取联系方式
            if($job_id = F('/weixin/'.($event_key%10).'/'.$event_key)){
                $job = D('Jobs')->get_jobs_one(array('id'=>$job_id));
                $this->content="职位名称：".$job['jobs_name'];
                $job['contact']['contact'] && $job['contact']['contact_show'] && $this->content .= "
联系人：".$job['contact']['contact'];
                $job['contact']['telephone'] && $job['contact']['telephone_show'] && $this->content .= "
手机号：".$job['contact']['telephone'];
                trim($job['contact']['landline_tel'],'-') && $job['contact']['landline_tel_show'] && $this->content .= "
座机：".trim($job['contact']['landline_tel'],'-');
 				$this->content.="
联系我时，请说是在".C('qscms_site_name')."上看到的";
				if($jobs['contact']['telephone_show'] == 0 && $job['contact']['landline_tel_show'] == 0){
                   $this->content .= "
联系方式：".'该公司暂未公开联系方式！';
				}
            }else{
                $this->content="获取职位联系电话失败！";
            }
        }elseif($event_key>40000000 && $event_key<=50000000){// 微信扫码获取联系方式
            if($company_id = F('/weixin/'.($event_key%10).'/'.$event_key)){
                $company = D('CompanyProfile')->where(array('id'=>$company_id))->find();
                $this->content="公司名称：".$company['companyname'];
                $company['contact'] && $company['contact_show'] && $this->content .= "
联系人：".$company['contact'];
                $company['telephone'] && $company['telephone_show'] && $this->content .= "
手机号：".$company['telephone'];
                trim($company['landline_tel'],'-') && $company['landline_tel_show'] && $this->content .= "
座机：".trim($company['landline_tel'],'-');
                $this->content.="
联系我时，请说是在".C('qscms_site_name')."上看到的";
				if($company['telephone_show'] == 0 && $company['landline_tel_show'] == 0){
                   $this->content .= "
联系方式：".'该公司暂未公开联系方式！';
				}
            }else{
                $this->content="获取职位联系电话失败！";
            }
        }
	}
	/**
	 * [get_user_info 读取用户绑定信息]
	 */
	protected function get_user_info($fromUsername){
		$fromUsername = addslashes($fromUsername);
		$user = M('MembersBind')->where(array('type'=>'weixin','openid'=>$fromUsername))->find();
		if(!$user){
			$reg = \Common\qscmslib\weixin::get_user_info($fromUsername);
            if($reg['state'] && $reg['data']['unionid']){
            	$reg['data']['info'] = $reg['data']['bind_info'];
            	$user = M('MembersBind')->where(array('type'=>'weixin','unionid'=>$reg['data']['unionid']))->find();
            	if(!$user){
					M('MembersBind')->add($reg['data']);
            	}else{
            		$reg['data']['info'] = serialize(array_merge(unserialize($user['info']),unserialize($reg['data']['info'])));
            		$user['uid'] && $reg['data']['is_bind'] = 1;
            		M('MembersBind')->where(array('type'=>'weixin','unionid'=>$reg['data']['unionid']))->save($reg['data']);
            		$user = array_merge($user,$reg['data']);
            	}
            }
		}elseif(!$user['is_focus'] || !$user['is_bind']){
			if($user['uid'] && !$user['is_bind']) $user['is_bind'] = $data['is_bind'] = 1;
			$user['is_focus'] = $data['is_focus'] = 1;
			M('MembersBind')->where(array('type'=>'weixin','openid'=>$fromUsername))->save($data);
		}
		if($user && $user['uid']){
			$user['info'] && $user['info'] = !is_array($user['info']) ? unserialize($user['info']) : $user['info'];
			$userinfo = M('members')->field('utype,username,email,mobile,email_audit,mobile_audit')->where(array('uid'=>$user['uid']))->find();
			$userinfo && $user = array_merge($user,$userinfo);
			return $user;
		}
		return false;
	}
	/**
	 * [clickBinding 绑定事件]
	 */
	protected function clickBinding($object){
		$usinfo = $this->get_user_info($object->FromUserName);
		if($usinfo){
			$this->content="您已绑定过".C('qscms_site_name')."帐号【".unicode_emoji($usinfo['info']['keyname'])."】,如需解绑,请回复'解绑'";
		}else{
			$this->content="您还未绑定".C('qscms_site_name')."帐号，现在开始绑定：<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点击开始注册/绑定帐号</a>";
		}
	}
	/**
	 * [clickResumeRefresh 刷新简历]
	 */
	protected function clickResumeRefresh($object){
		$usinfo = $this->get_user_info($object->FromUserName);
		if($usinfo){
			if($usinfo['utype'] != 2){
				$this->content = "本操作需要绑定个人帐号！";
			}else{
	            $uid = $usinfo['uid'];
	            $refresh_log = M('RefreshLog');
	            $refrestime = $refresh_log->where(array('uid'=>$uid,'type'=>2001))->order('addtime desc')->getfield('addtime');
	            $duringtime=time()-$refrestime;
	            $space = C('qscms_per_refresh_resume_space')*60;
	            $today = strtotime(date('Y-m-d'));
	            $tomorrow = $today+3600*24;
	            $count = $refresh_log->where(array('uid'=>$uid,'type'=>2001,'addtime'=>array('BETWEEN',array($today,$tomorrow))))->count();
	            if(C('qscms_per_refresh_resume_time')!=0&&($count>=C('qscms_per_refresh_resume_time'))){
	                $this->content = "每天最多只能刷新".C('qscms_per_refresh_resume_time')."次,您今天已超过最大刷新次数限制！";
	            }elseif($duringtime<=$space && $space!=0){
	                $this->content = C('qscms_per_refresh_resume_space')."分钟内不能重复刷新简历！";
	            }else{
	            	$resume = D('Resume');
	            	$pid = $resume->where(array('uid'=>$uid))->getfield('id',true);
	            	$resume->refresh_resume($pid,$usinfo);
	                $this->content = "刷新成功!";
	            }
			}
		}else{
			$this->content="您还未绑定".C('qscms_site_name')."帐号，现在开始绑定：<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点击开始注册/绑定帐号</a>";
		}
	}
	/**
	 * [clickNearbyJobs 周边职位]
	 */
	protected function clickNearbyJobs($object){
		$usinfo = $this->get_user_info($object->FromUserName);
		if($usinfo){
			if($usinfo['utype']!=2){
				$this->content = "本操作需要绑定个人帐号！";
			}else{
				$data = S('location_'.addslashes($object->FromUserName));
				if(empty($data['Latitude']) || empty($data['Longitude'])){
					$this->content = "奇怪了，难道HR们都偷懒放假去了？我什么职位都没捞着，要不您换个地方再发位置给我？如何发送地理位置？";
					return false;
				}
				$lat = $data['Latitude'];
				$lng = $data['Longitude'];
				$where = array(
		            '经度' => $lng,
		            '纬度' => $lat,
		            '搜索范围' => 5,
		            '显示数目' => 5
		        );
		        $jobs_mod = new \Common\qscmstag\jobs_listTag($where);
		        $jobs_list = $jobs_mod->run();
				if(!$jobs_list['list']){
					$this->content="奇怪了，难道HR们都偷懒放假去了？我什么职位都没捞着，要不您换个地方再发位置给我？如何发送地理位置？";
				}else{
					$i=1;
					$first_pic = C('qscms_site_domain').C('qscms_site_dir').attach(C('qscms_weixin_first_pic'),'weixin');
					foreach ($jobs_list['list'] as $key => $value){
					    $title=$value['jobs_name']."--".$value['companyname'];
						$url = build_mobile_url(array('c'=>'Jobs','a'=>'show','params'=>'&id='.$value['id'].'&openid='.addslashes($object->FromUserName)));
					    if($i==1){
		                    $picurl = $first_pic;
						}else{
							$picurl= C('qscms_site_domain').C('qscms_site_dir').$value['logo'];		
						}
						$i++;
						$content[] = array("Title"=>$title, "Description"=>'', "PicUrl"=>$picurl, "Url" =>$url);	
					}
					$this->content = $content;
				}
			}
		}else{
			$this->content="您还未绑定".C('qscms_site_name')."帐号，现在开始绑定：<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点击开始注册/绑定帐号</a>";
		}
	}
	/**
	 * [clickJobsRefresh 刷新职位]
	 */
	protected function clickJobsRefresh($object){
		$usinfo = $this->get_user_info($object->FromUserName);
		if($usinfo){
			if($usinfo['utype']!=1){
				$this->content = "本操作需要绑定企业帐号！";
			}else{
				$jobs_mod = D('Jobs');
				$yid = $jobs_mod ->where(array('uid'=>$usinfo['uid']))->getfield('id',true);
				if($yid){
					$reg = $jobs_mod->jobs_refresh(array('yid'=>$yid,'user'=>$usinfo));
					if($reg['state']){
						$this->content = '刷新成功！';
					}else{
						$this->content = $reg['error'];
					}
				}else{
					$this->content = '请先添加职位！';
				}
			}
		}else{
			$this->content="您还未绑定".C('qscms_site_name')."帐号，现在开始绑定：<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点击开始注册/绑定帐号</a>";
		}
	}
	/**
	 * [clickSignDay 每日签到]
	 */
	private function clickSignDay($object){
		$usinfo = $this->get_user_info($object->FromUserName);
		if($usinfo){
			$reg = D('Members')->sign_in($usinfo);
            if($reg['state']){
                $s = $usinfo['utype'] == 1 ? 18 : 3; 
                D('TaskLog')->do_task($usinfo,$s);
                $this->content = "签到成功，获得 ".$reg['points'].C('qscms_points_byname')." ！回复“签到记录”查看历史签到。
每日签到，免费获取".C('qscms_points_byname')."，商城好礼兑不停，快邀请好友加入吧！";
            }else{
                $this->content = $reg['error'];
            }
		}else{
			$this->content="您还未绑定".C('qscms_site_name')."帐号，现在开始绑定：<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点击开始注册/绑定帐号</a>";
		}
	}
	/**
	 * [enterSearch 输入关键字搜索职位]
	 */
	private function enterSearch($object,$keyword){
		if($keyword=="解绑"){
			$usinfo = $this->get_user_info($object->FromUserName);
			if($usinfo){
				$reg = M('MembersBind')->where(array('type'=>'weixin','uid'=>$usinfo['uid']))->limit(1)->save(array('uid'=>0,'is_bind'=>0,'bindingtime'=>0));
				if($reg !== false){
					$this->visitor->logout();
					$passport = $this->_user_server();
        			$synlogout = $passport->synlogout();
					$this->content="解除绑定成功！";
				}else{
					$this->content="解除绑定失败！";
				}
			}else{
				$this->content="您还没有绑定帐号！";
			}
		}elseif($keyword=="签到记录"){
			$usinfo = $this->get_user_info($object->FromUserName);
			if($usinfo){
				$handsel_mod = M('MembersHandsel');
				$where = array('uid'=>$usinfo['uid'],'htype'=>'task_sign');
				$sign_in = $handsel_mod->field('addtime')->where($where)->order('id desc')->find();
				if($sign_in){
					$all_num = $handsel_mod->where($where)->count();
					$all_points = $handsel_mod->where($where)->sum('points');
					$where['addtime'] = array('egt',strtotime(date('Y-m-1')));
					$month_num = $handsel_mod->where($where)->count();
					$this->content="您累计已签到: ".$all_num." 天
您本月已累计签到:".$month_num." 天
您上次签到时间:".date('Y-m-d H:i:s',$sign_in['addtime'])."
您目前获得的总奖励为:".$all_points.C('qscms_points_byname');
				}else{
					$this->content="您还没有签到！";
				}
			}else{
				$this->content="您还未绑定".C('qscms_site_name')."帐号，现在开始绑定：<a href='".build_mobile_url(array('c'=>'Members','a'=>'apilogin_binding','params'=>'&openid='.addslashes($object->FromUserName)))."'>点击开始注册/绑定帐号</a>";
			}
		}else{
			$tag_map = array('显示数目'=>5);
			$keyword_search = false;
			$tag_map['排序']='rtime.desc';
			if($keyword=="j")
			{
				$tag_map['紧急招聘'] = 1;
			}
			elseif($keyword!="n")
			{
				$keyword_search = true;
			}
            if($keyword_search){
                $tag_map['关键字'] = $keyword;
            }
            
            $jobs_list_search_class = new \Common\qscmstag\jobs_listTag($tag_map);
            $jobs_list = $jobs_list_search_class->run();
            $jobs_list = $jobs_list['list'];
			if($jobs_list){
				$fromUsername = addslashes($object->FromUserName);
				$first_pic = C('qscms_site_domain').C('qscms_site_dir').attach(C('qscms_weixin_first_pic'),'resource');
				if($keyword=="j")
				{
					$first_title = '紧急招聘的职位';
					$first_url = build_mobile_url(array('c'=>'Jobs','a'=>'index','params'=>'&openid='.$fromUsername));
				}
				elseif($keyword=="n")
				{
					$first_title = '最新招聘的职位';
					$first_url = build_mobile_url(array('c'=>'Jobs','a'=>'index','params'=>'&openid='.$fromUsername));
				}
				else
				{
					$first_title = '符合“'.$keyword.'”的职位';
					$first_url = build_mobile_url(array('c'=>'Jobs','a'=>'index','params'=>'&key='.$keyword.'&openid='.$fromUsername));
				}
				$content_arr[] = array("Title"=>$first_title, "Description"=>'', "PicUrl"=>$first_pic, "Url" =>$first_url);
				foreach ($jobs_list as $key => $val) {
					$title = cut_str($val['jobs_name'],11,0,'...')."\r\n".cut_str($val['companyname'],11,0,'...');
					$url = build_mobile_url(array('c'=>'Jobs','a'=>'show','params'=>'&id='.$val['id'].'&openid='.$fromUsername));
	                $picurl = C('qscms_site_domain').C('qscms_site_dir').$val['logo'];
					$content_arr[] = array("Title"=>$title, "Description"=>'', "PicUrl"=>$picurl, "Url" =>$url);
				}
				$content_arr[] = array("Title"=>'查看更多>>', "Description"=>'', "PicUrl"=>'', "Url" =>$first_url);
				$this->content = $content_arr;
			}
			if(empty($this->content)){
				$this->content="没有找到包含关键字 ".$keyword." 的信息，试试其他关键字";
			}
		}
	}
	protected function get_url($url){
		return C('qscms_site_domain').$url;
	}
	protected function get_show_url($url){
		return C('apply.Subsite') ? $url : C('qscms_site_domain').$url;
	}
}
?>