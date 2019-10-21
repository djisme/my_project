<?php
/**
 * 文章模板标签解析
 *
 * @author andery
 */
namespace Common\qscmslib;
use Common\ORG\aliyunSendEmail;
class email
{
    private $_error = 0;
    public $mailconfig;
    public function __construct($name) {
        if(false === $this->mailconfig = F('mailconfig')) $this->mailconfig = D('Mailconfig')->get_cache();
    }
    public function smtp_mail($sendto_email,$subject,$body,$From='',$FromName=''){
        if(($this->mailconfig['method'] == 1 && !$this->mailconfig['smtpservers']) || ($this->mailconfig['method'] == 4 && !$this->mailconfig['aliyun_accessSecret'])){
            $this->_error = '请配置邮箱参数！';
            return false;
        }
        $FromName=$FromName?$FromName:C('qscms_site_name');
        //method=4为aliyun DirectMail API接口
        if($this->mailconfig['method'] == 4){
            $aliyunObj = new aliyunSendEmail($this->mailconfig['aliyun_accessKey'],$this->mailconfig['aliyun_accessSecret'],$this->mailconfig['aliyun_account'],$FromName,$this->mailconfig['aliyun_tag']);
            $reg = $aliyunObj->send($sendto_email,$subject,htmlspecialchars_decode($body));
            if($reg->RequestId){
                if($reg->Code){
                    $send_ok = 0;
                    $errorInfo = $reg->Message;
                }else{
                    $send_ok = 1;
                }
            }else{
                $send_ok = 0;
                $errorInfo = $reg;
            }
            $send_from = $this->mailconfig['aliyun_account'];
        }else{
            Vendor('PHPMailer.class#phpmailer');
            $mail = new \PHPMailer();
            $this->mailconfig['smtpservers']=explode('|-_-|',$this->mailconfig['smtpservers']);
            $this->mailconfig['smtpusername']=explode('|-_-|',$this->mailconfig['smtpusername']);
            $this->mailconfig['smtppassword']=explode('|-_-|',$this->mailconfig['smtppassword']);
            $this->mailconfig['smtpfrom']=explode('|-_-|',$this->mailconfig['smtpfrom']);
            $this->mailconfig['smtpport']=explode('|-_-|',$this->mailconfig['smtpport']);
            for ($i=0; $i<count($this->mailconfig['smtpservers']); $i++)
            {
            $mailconfigarray[]=array('smtpservers'=>$this->mailconfig['smtpservers'][$i],'smtpusername'=>$this->mailconfig['smtpusername'][$i],'smtppassword'=>$this->mailconfig['smtppassword'][$i],'smtpfrom'=>$this->mailconfig['smtpfrom'][$i],'smtpport'=>$this->mailconfig['smtpport'][$i]);
            }
            $mc=array_rand($mailconfigarray,1);
            $mc=$mailconfigarray[$mc];
            $this->mailconfig['smtpservers']=$mc['smtpservers'];
            $this->mailconfig['smtpusername']=$mc['smtpusername'];
            $this->mailconfig['smtppassword']=$mc['smtppassword'];
            $this->mailconfig['smtpfrom']=$mc['smtpfrom'];
            $this->mailconfig['smtpport']=$mc['smtpport'];
            $From=$From?$From:$this->mailconfig['smtpfrom'];
            
            if ($this->mailconfig['method']=="1")
            {
                if (empty($this->mailconfig['smtpservers']) || empty($this->mailconfig['smtpusername']) || empty($this->mailconfig['smtppassword']) || empty($this->mailconfig['smtpfrom']))
                {
                    setLog(array('_t'=>'syslog','l_type'=>2,'l_type_name'=>'MAIL','l_str'=>'邮件配置信息不完整'));
                    return false;
                }
            $mail->IsSMTP();
            $mail->Host = $this->mailconfig['smtpservers'];
            $mail->SMTPDebug= 0; 
            $mail->SMTPAuth = true;
            $mail->Username = $this->mailconfig['smtpusername']; 
            $mail->Password = $this->mailconfig['smtppassword']; 
            $mail->Port =$this->mailconfig['smtpport'];
            $mail->From =$this->mailconfig['smtpfrom']; 
            $mail->FromName =$FromName;
            }
            elseif($this->mailconfig['method']=="2")
            {
            $mail->IsSendmail();
            }
            elseif($this->mailconfig['method']=="3")
            {
            $mail->IsMail();
            }
            $mail->CharSet ='utf-8';
            $mail->Encoding = "base64";
            $mail->AddReplyTo($From,$FromName);
            $mail->AddAddress($sendto_email,"");
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body = htmlspecialchars_decode($body);
            $mail->AltBody ="text/html";
            if($mail->Send()){
                $send_ok = 1;
            }else{
                $send_ok = 0;
                $errorInfo = $mail->ErrorInfo;
            }
            $send_from = $this->mailconfig['smtpusername'];
        }
        if($send_ok)
        {
            $s = setLog(array('_t'=>'SysEmailLog','send_from'=>$send_from,'send_to'=>$sendto_email,'subject'=>$subject,'body'=>addslashes($body),'state'=>1));
            return true;
        }
        else
        {
            setLog(array('_t'=>'Syslog','l_type'=>2,'l_type_name'=>'MAIL','l_str'=>$errorInfo));
            setLog(array('_t'=>'SysEmailLog','send_from'=>$send_from,'send_to'=>$sendto_email,'subject'=>$subject,'body'=>addslashes($body),'state'=>2));
            // $this->_error = $errorInfo;
            $this->_error = '邮件无法正常发送！';
            return false;
        }
    }
    /*
        邮件模板替换
    */
    public function label_replace($templates,$replac)
    {
        $replac['sitename']=C('qscms_site_name');
        $replac['sitedomain']=rtrim(C('qscms_site_domain').C('qscms_site_dir'),'/');
        $replac['logo']=$replac['sitedomain'].(C('qscms_logo_home')?attach(C('qscms_logo_home'),'resource'):'/Application/Home/View/'.C('qscms_template_dir').'/public/images/logo_home.png');
        $replac['bootom_tel']=C('qscms_bootom_tel')?C('qscms_bootom_tel'):C('qscms_top_tel');
        $replac['contact_email']=C('qscms_contact_email');
        $replac['qrcode']=$replac['sitedomain'].attach(C('qscms_weixin_img'),'resource');
        $replac['url_membercenter']=$replac['sitedomain'].U('Home/Members/login');
        $replac['url_help']=$replac['sitedomain'].U('Home/Help/index');
        if(!empty($replac)){
            foreach ($replac as $key => $val) {
                $replac['{'.$key.'}'] = $val;
            } 
        }
        return $templates = strtr($templates,$replac);
    }
    public function getError(){
        return $this->_error;
    }
}