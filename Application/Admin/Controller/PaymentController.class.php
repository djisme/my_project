<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class PaymentController extends BackendController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Payment');
    }
	public function index(){
        $this->order = 'listorder desc';
        parent::index();
    }
    protected function get_pay_info($typename){
        $commom_info['p_introduction']="简短描述：";
        $commom_info['notes']="详细描述：";
        $commom_info['fee']="交易手续费：";
        //转账/汇款
        $info['remittance'] = array();
        //网银在线
        $info['chinabank'] = array(
            'partnerid'=>'商户编号：',
            'ytauthkey'=>'MD5 密钥：'
            );
        //财付通
        $info['tenpay'] = array(
            'partnerid'=>'商户编号：',
            'ytauthkey'=>'MD5 密钥：'
            );
        //支付宝
        $info['alipay'] = array(
            'partnerid'=>'合作者身份(Partner ID)：',
            'ytauthkey'=>'安全校验码(Key)：',
            'parameter1'=>'支付宝帐号：'
            );
        //微信支付
        $info['wxpay'] = array(
            'partnerid' => '商户编号：',
            'ytauthkey'=>'商户支付密钥：'
            );
        return array_merge($commom_info,$info[$typename]);
    }
    protected function _after_select(){
        $typename = I('get.typename');
        $pay = $this->get_pay_info($typename);
        $this->assign('pay',$pay);
    }
    /**
     * 卸载
     */
    public function uninstall($typename){
        $this->_mod->where(array('typename'=>$typename))->setField('p_install',1);
        $this->success(L('operation_success'));
    }
}