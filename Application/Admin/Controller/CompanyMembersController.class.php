<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class CompanyMembersController extends BackendController{
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Members');
    }
    /**
     * 企业会员列表
     */
    public function index(){
        $this->_name = 'Members';
        $db_pre = C('DB_PREFIX');
        $table_name = $db_pre.'members';
        $verification=I('get.verification',0,'intval');
        $settr=I('get.settr',0,'intval');
        $key_type = I('request.key_type',0,'intval');
        $key = I('request.key','','trim');
        if ($key && $key_type>0){
            switch($key_type){
                case 1:
                    $where[$table_name.'.username']=array('like',$key.'%');break;
                case 2:
                    $where[$table_name.'.uid']=array('eq',$key);break;
                case 3:
                    $where[$table_name.'.email']=array('like',$key.'%');break;
                case 4:
                    $where[$table_name.'.mobile']=array('like',$key.'%');break;
            }
        }else{
            if ($settr>0){
                $tmpsettr=strtotime("-".$settr." day");
                $where['reg_time']=array('gt',$tmpsettr);
            }
            if ($verification>0){
                switch($verification){
                    case 1:
                        $where['email_audit']=array('eq',1);break;
                    case 2:
                        $where['email_audit']=array('eq',0);break;
                    case 3:
                        $where['mobile_audit']=array('eq',1);break;
                    case 4:
                        $where['mobile_audit']=array('eq',0);break;
                }
            }
        }
        $where['utype'] = 1;
        if('' != $is_bind = I('request.is_bind')){
            if($is_bind){
                $where['b.is_bind'] = intval($is_bind);
                $where['b.openid'] = array('neq','');
            }else{
                $where['b.is_bind'] = array(array('eq',0),array('is',null), 'or');
            }
        }
        $this->field = $table_name.".*,c.companyname,c.contents as c_contents,c.id as company_id,b.is_bind";
        $this->order = $table_name.'.uid '.'desc';
        $joinsql[0] = 'left join '.$db_pre."company_profile as c on ".$table_name.".uid=c.uid";
        $joinsql[1] = 'left join '.$db_pre."members_bind as b on ".$table_name.".uid=b.uid and b.type='weixin'";
        $this->join = $joinsql;
        $this->where = $where;
        parent::index();
    }
    /**
     * 删除会员
     */
    public function delete(){
        $tuid = I('post.tuid','trim','trim')!=''?I('post.tuid'):$this->error('你没有选择会员！');
        $sitegroup_uids = M('Members')->where(array('uid'=>array('in',$tuid)))->getField('sitegroup_uid',true);
        if(false===D('Members')->delete_member($tuid)) $this->error('删除会员失败！');
        $type['_user'] = 1;
        D('CompanyProfile')->admin_delete_company($tuid);
        $type['_company'] = 1;
        D('Jobs')->admin_delete_jobs_for_uid($tuid);
        $type['_jbos'] = 1;
        if(C('qscms_sitegroup_open') && C('qscms_sitegroup_domain') && C('qscms_sitegroup_secret_key') && C('qscms_sitegroup_id')){
            require_once QSCMSLIB_PATH . 'passport/sitegroup.php';
            $name = 'sitegroup_passport';
            $passport = new $name();
            false === $passport->delete($sitegroup_uids,$type) && $this->error($passport->get_error());
        }
        $this->success('删除成功！');
    }
    /**
     * 解绑手机号记录
     */
    public function unbind_mobile() {
        $this->_name = 'UnbindMobile';
        $db_pre = C('DB_PREFIX');
        $table_name = $db_pre.'unbind_mobile';
        $settr = I('get.settr', 0, 'intval');
        $key_type = I('request.key_type', 0, 'intval');
        $key = I('request.key', '', 'trim');
        if ($key && $key_type > 0) {
            switch ($key_type) {
                case 1:
                    $where[$table_name.'.username'] = array('like', $key . '%');
                    break;
                case 2:
                    $where[$table_name.'.uid'] = array('eq', $key);
                    break;
                case 3:
                    $where[$table_name.'.mobile'] = array('like', $key . '%');
                    break;
            }
        } else {
            if ($settr > 0) {
                $tmpsettr = strtotime("-" . $settr . " day");
                $where['add_time'] = array('GT', $tmpsettr);
            }
        }
        $where['utype'] = 1;
        $this->where = $where;
        $this->field = $table_name.".*,c.companyname,c.id as company_id";
        $this->order = $table_name.'.id '.'DESC';
        $this->join = 'left join '.$db_pre."company_profile as c on ".$table_name.".uid=c.uid";
        parent::index();
    }
    /**
     * 删除解绑手机记录
     */
    public function del_unbind_mobile(){
        $id = I('request.id');
        !$id && $this->error('请选择记录');
        if ($n = D('UnbindMobile')->del($id)) {
            $this->success("删除成功！共删除{$n}行");
        } else {
            $this->error("删除失败！");
        }
    }
    /**
     * 给解绑手机记录备注
     */
    public function set_unbind_mobile() {
        $id = I('request.id');
        !$id && $this->error('请选择记录！');
        $remark = I('post.remark','','trim');
        $result = D('UnbindMobile')->set_remark($id,$remark);
        if($result){
            $this->success("备注成功！");
        }else{
            $this->error('备注失败！');
        }
    }
}
?>