<?php
/**
 * 阿里云DirectMail API
 *
 * @author andery
 */
namespace Common\ORG;
use DefaultProfile;
use DefaultAcsClient;
use Dm\Request\V20151123 as Dm; 
use ClientException;
use ServerException; 
require_once QSCMSLIB_PATH.'/../ORG/AliyunEmail/aliyun-php-sdk-core/Config.php'; 
   
class AliyunSendEmail 
{  
    /** 
     * @var string 区域标志 
     */  
    public $region = 'cn-hangzhou';  
    /** 
     * @var string accessKey 
     */  
    public $accessKey;  
    /** 
     * @var string accessSecret 
     */  
    public $accessSecret;  
    /** 
     * @var string 控制台创建的发信地址 
     */  
    public $accountName;  
    /** 
     * @var string 发信人昵称 
     */  
    public $fromAlias;  
    /** 
     * @var string 控制台创建的标签 
     */  
    public $tagName;  

    public function __construct($accessKey,$accessSecret,$accountName,$fromAlias,$tagName){
        $this->accessKey = $accessKey;
        $this->accessSecret = $accessSecret;
        $this->accountName = $accountName;
        $this->fromAlias = $fromAlias;
        $this->tagName = $tagName;
    }
   
    public function send($email, $title, $content)  
    {  
        //获取配置信息  
        $region = $this->region;  
        $accessKey = $this->accessKey;  
        $accessSecret = $this->accessSecret;  
        $accountName = $this->accountName;  
        $fromAlias = $this->fromAlias;  
        $tagName = $this->tagName; 
        $iClientProfile = DefaultProfile::getProfile($region, $accessKey,  
                $accessSecret);  
        //发送单个邮件示例  
        $client = new DefaultAcsClient($iClientProfile); 

        $request = new Dm\SingleSendMailRequest(); 
        $request->setAccountName($accountName);  
        $request->setFromAlias($fromAlias);  
        $request->setAddressType(1);  
        $request->setTagName($tagName);  
        $request->setReplyToAddress("false");  
        //收件人  
        $request->setToAddress($email);   
        //发信标题       
        $request->setSubject($title);  
           
        //发信内容  
        $request->setHtmlBody($content);       
         
        try {  
            $response = $client->getAcsResponse($request);  
            if ($response) {  
                return $response;  
            }  
        } catch (ClientException  $e) {  
            return $e->getErrorMessage();
        } catch (ServerException  $e) {   
            return $e->getErrorMessage();
        }  
    }
}  