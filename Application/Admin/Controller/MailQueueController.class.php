<?php
namespace Admin\Controller;
use Common\Controller\BackendController;
class MailQueueController extends BackendController{
	public function _initialize() {
        parent::_initialize();
        $this->_mod = D('Mailqueue');
        $this->_name = 'Mailqueue';
    }
    public function index(){
        $map = array();
        $m_type=I('get.m_type',0,'intval');
        $order_by = 'm_id desc';
        if ($key && $key_type>0)
        {
            switch($key_type){
                case 1:
                    $map['m_subject']=array('like','%'.$key.'%');break;
                case 2:
                    $map['m_mail']=array('eq',$key);break;
            }
        }
        else
        {
            $m_type>0 && $map['m_type'] = array('eq',$m_type);
        }
        parent::_list($this->_mod,$map,$order_by,"*",'','',10,'_format_list');
        $this->display();
    }
    /**
     * 格式化列表
     */
    public function _format_list($list){
        $arr = array();
        foreach ($list as $key => $value) {
            $value['m_subject']=$value['m_subject'];
            $value['m_subject_']=cut_str(strip_tags($value['m_subject']),18,0,"...");
            $value['m_body']=strip_tags($value['m_body']);
            $value['m_body_']=cut_str(strip_tags($value['m_body']),18,0,"...");
            $arr[] = $value;
        }
        return $arr;
    }
    /**
     * 添加发送任务
     */
    public function mailqueue_add(){
        if(IS_POST){
            $_POST['m_mail']=I('post.m_mail','','trim')?I('post.m_mail','','trim'):$this->error('邮件地址必须填写！');
            if (!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$_POST['m_mail'])) 
            {
                $this->error('邮箱格式错误！');
            }
            $_POST['m_subject']=I('post.m_subject','','trim')?$this->_mod->replace_label(I('post.m_subject','','trim')):$this->error('邮件标题必须填写！');
            $_POST['m_body']=I('post.m_body','','trim')?$this->_mod->replace_label(I('post.m_body','','trim')):$this->error('邮件内容必须填写！');
        }else{
            $label[]=array('{sitename}','网站名称');
            $label[]=array('{sitedomain}','网站域名');
            $label[]=array('{address}','联系地址');
            $label[]=array('{tel}','联系电话');
            $this->assign('label',$label);
        }
        parent::add();
    }
    /**
     * 修改发送任务
     */
    public function mailqueue_edit(){
        if(IS_POST){
            $_POST['m_mail']=I('post.m_mail','','trim')?I('post.m_mail','','trim'):$this->error('邮件地址必须填写！');
            if (!preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$_POST['m_mail'])) 
            {
                $this->error('邮箱格式错误！');
            }
            $_POST['m_subject']=I('post.m_subject','','trim')?$this->_mod->replace_label(I('post.m_subject','','trim')):$this->error('邮件标题必须填写！');
            $_POST['m_body']=I('post.m_body','','trim')?$this->_mod->replace_label(I('post.m_body','','trim')):$this->error('邮件内容必须填写！');
        }else{
            $label[]=array('{sitename}','网站名称');
            $label[]=array('{sitedomain}','网站域名');
            $label[]=array('{address}','联系地址');
            $label[]=array('{tel}','联系电话');
            $this->assign('label',$label);
        }
        parent::edit();
    }
    /**
     * 批量添加发送任务
     */
    public function mailqueue_batchadd(){
        if(IS_POST){
            $selutype=I('post.selutype',0,'intval');
            $selsettr=I('post.selsettr',0,'intval');
            if ($selutype>0)
            {
                $map['utype'] = array('eq',$selutype);
            }   
            if ($selsettr>0)
            {
                $data=strtotime("-{$selsettr} day");
                $map['last_login_time'] = array('lt',$data);
            }
            $map['email'] = array('neq','');
            $m_subject=I('post.m_subject','','trim')?$this->_mod->replace_label(I('post.m_subject','','trim')):$this->error('邮件标题必须填写！');
            $m_body=I('post.m_body','','trim')?$this->_mod->replace_label(I('post.m_body','','trim')):$this->error('邮件内容必须填写！');
            if($map){
                $result = D('Members')->where($map)->select();
            }else{
                $result = D('Members')->select();
            }
            
            $n=0;
            foreach ($result as $key => $user) {
                $setsqlarr['m_type'] = 1;
                $setsqlarr['m_uid']=$user['uid'];
                $setsqlarr['m_mail']=$user['email'];
                $setsqlarr['m_subject']=$this->_mod->replace_label($m_subject,$user);    
                $setsqlarr['m_body']=$this->_mod->replace_label($m_body,$user);
                $setsqlarr['m_addtime']=time();
                $r = $this->_mod->add($setsqlarr);
                if($r){
                    $n++;
                }else{
                    $this->error('添加失败！');
                }
            }
            $this->success("添加成功，共添加 {$n} 行 ");
            exit;
        }else{
            $label[]=array('{sitename}','网站名称');
            $label[]=array('{sitedomain}','网站域名');
            $label[]=array('{username}','会员用户名');
            $label[]=array('{lastlogintime}','最后登录时间');
            $label2[]=array('{address}','联系地址');
            $label2[]=array('{tel}','联系电话');
            $this->assign('label',$label);
            $this->assign('label2',array_merge($label,$label2));
            $this->display();
        }
    }
    /**
     * 批量发送
     */
    public function totalsend(){
        $sendtype=I('post.sendtype',1,'intval');
        $intervaltime=I('post.intervaltime',3,'intval');
        $sendmax=I('post.sendmax',0,'intval');
        $senderr=I('post.senderr',0,'intval');
        if ($sendmax>0)
        {
            $limit = $sendmax;
        }else{
            $limit = false;
        }
        if ($sendtype==1)
        {
            $id=I('post.id');
            if (empty($id))
            {
                $this->error("请选择项目！",1);
            }
            if(!is_array($id)) $id=array($id);
            $sqlin=implode(",",$id);
            if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
            {
                $select = $this->_mod->where(array('m_id'=>array('in',$sqlin)));
                if($limit){
                    $select = $select->limit($limit);
                }
                $result = $select->select();
                $idarr = array();
                foreach ($result as $key => $value) {
                    $idarr[] = $value['m_id'];
                }
                if (empty($idarr))
                {
                    $this->error("没有可发送的邮件");
                }
                file_put_contents(RUNTIME_PATH."Temp/send.txt", serialize($idarr));
                $this->redirect(U('send',array('senderr'=>$senderr,'intervaltime'=>$intervaltime)));
            }
            
        }
        elseif ($sendtype==2)
        {
            $select = $this->_mod->where(array('m_type'=>1));
            if($limit){
                $select = $select->limit($limit);
            }
            $result = $select->select();
            $idarr = array();
            foreach ($result as $key => $value) {
                $idarr[] = $value['m_id'];
            }
            if (empty($idarr))
            {
                $this->error("没有可发送的邮件");
            }
            @file_put_contents(RUNTIME_PATH."Temp/send.txt", serialize($idarr));
            $this->redirect(U('send',array('senderr'=>$senderr,'intervaltime'=>$intervaltime)));
        }
        elseif ($sendtype==3)
        {
            $select = $this->_mod->where(array('m_type'=>3));
            if($limit){
                $select = $select->limit($limit);
            }
            $result = $select->select();
            $idarr = array();
            foreach ($result as $key => $value) {
                $idarr[] = $value['m_id'];
            }
            if (empty($idarr))
            {
                $this->error("没有可发送的邮件");
            }
            @file_put_contents(RUNTIME_PATH."Temp/send.txt", serialize($idarr));
            $this->redirect(U('send',array('senderr'=>$senderr,'intervaltime'=>$intervaltime)));
        }
    }
    /**
     * 执行发送
     */
    public function send(){
        $sendtype=I('get.sendtype',1,'intval');
        $intervaltime=I('get.intervaltime',3,'intval');
        $senderr = I('get.senderr',0,'intval');
        $tempdir=RUNTIME_PATH."Temp/send.txt";
        $content = file_get_contents($tempdir);
        $idarr = unserialize($content);
        $totalid=count($idarr);
        if (empty($idarr))
        {
            $this->success('任务执行完毕！',U('index'));
            exit;
        }
        else
        {
            $m_id=array_shift($idarr);
            @file_put_contents($tempdir,serialize($idarr));
            $mail = $this->_mod->where(array('m_id'=>array('eq',intval($m_id))))->find();
            $r = D('Mailconfig')->send_mail(array('sendto_email'=>$mail['m_mail'],'subject'=>$mail['m_subject'],'body'=>$mail['m_body']));
            if(true !== $r){
                $this->_mod->where(array('m_id'=>array('eq',intval($m_id))))->setField('m_type',3);
                if ($senderr=="2")
                {
                    $this->error('邮件发送发生错误！',U('index'));
                }
                else
                {
                    $this->error('发生错误，准备发送下一封，剩余任务总数：'.($totalid-1),U('send',array('senderr'=>$senderr,'intervaltime'=>$intervaltime)));
                }       
            }
            else
            {
                $this->_mod->where(array('m_id'=>array('eq',intval($m_id))))->save(array('m_type'=>2,'m_sendtime'=>time()));
                $this->success('发送成功，准备发送下一封，剩余任务总数：'.($totalid-1),U('send',array('senderr'=>$senderr,'intervaltime'=>$intervaltime)));
            }
        }   
    }
    /**
     * 删除邮件队列任务
     */
    public function del(){
        $n=0;
        $deltype=I('post.deltype',0,'intval');
        $map = false;
        if ($deltype==1)
        {
            $id=I('post.id');
            if (empty($id))
            {
                $this->error("请选择项目！");
            }
            if(!is_array($id)) $id=array($id);
            $sqlin=implode(",",$id);
            if (preg_match("/^(\d{1,10},)*(\d{1,10})$/",$sqlin))
            {
                $map['m_id'] = array('in',$sqlin); 
            }
        }
        elseif ($deltype==2)
        {
            $map['m_type'] = array('eq',1); 
        }
        elseif ($deltype==3)
        {
            $map['m_type'] = array('eq',2); 
        }
        elseif ($deltype==4)
        {
            $map['m_type'] = array('eq',3); 
        }
        elseif ($deltype==5)
        {
            $map['m_id'] = array('gt',0);
        }
        $this->_del($map);
        $this->success('删除成功！');
        exit;
    }
    /**
     * 删除公用方法
     */
    protected function _del($map=false){
        $model = $this->_mod;
        if($map){
            $model = $model->where($map);
        }
        $model->delete();
    }
}
?>