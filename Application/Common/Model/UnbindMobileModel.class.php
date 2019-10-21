<?php
namespace Common\Model;

use Think\Model;

class UnbindMobileModel extends Model {

    protected $_validate = array(
        array('uid', 'require', '会员id不能为空！', self::EXISTS_VALIDATE, 'regex'),
        array('utype', 'require', '会员类型不能为空！', self::EXISTS_VALIDATE, 'regex'),
        array('username', 'require', '用户名不能为空！', self::EXISTS_VALIDATE, 'regex'),
        array('mobile', 'require', '解绑手机号不能为空！', self::EXISTS_VALIDATE, 'regex'),
        array('mobile', 'mobile', '手机号格式不正确！', self::EXISTS_VALIDATE),
    );

    protected $_auto = array(
        array('add_time', NOW_TIME, self::MODEL_INSERT)
    );

    /**
     * 删除解绑记录
     * @param array $ids 要删除的记录id
     * @return bool
     */
    public function del($ids) {
        if (!is_array($ids)) $ids = array($ids);
        $rst = $this->where(array('id' => array('in', $ids)))->delete();
        if (false === $rst) return false;
        return $rst;
    }

    /**
     * 更新备注
     * @param array $ids 记录id
     * @param string $remark 备注
     * @return bool
     */
    public function set_remark($ids, $remark) {
        if (!is_array($ids)) $ids = array($ids);
        $rst = $this->where(array('id' => array('in', $ids)))->setField(array('remark' => $remark));
        if (false === $rst) return false;
        return true;
    }
}

?>