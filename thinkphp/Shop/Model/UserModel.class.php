<?php
namespace Model;
use Think\Model;


class UserModel extends Model {
    
    //$patchValidate 批量输出验证信息
    protected $patchValidate = true;
    
    //$_validate 表单验证关键字
    protected $_validate = array(
        array('user_name','require','用户名不得为空！'),
        array('pwd','require','密码不得为空！'),
//        array('repwd','require','确认密码不得为空'),
        //参数4: 0.有参数就验证  1.必须验证 2.值不为空的时候验证
        array('pwd','repwd','两次密码输入不一致！',0,'confirm'),
        array('user_email','email','邮箱格式不正确，请返回重新填写！'),//邮箱验证
        array('user_qq','/^[1-9]\d{4,10}$/','qq号码格式不正确，请返回重新填写！'),//qq号码验证
        array('user_tel','/^1[3,5,8][0-9]\d{8}$/','手机号码格式不正确，请返回重新填写！'),//手机号码验证
        array('user_education','2,3,4,5','请选择学历',0,'in'),//学历信息验证
        array('user_hobby','check_hobby','请至少选择两项爱好！',0,'callback'),//个人爱好信息验证
    );
    public function check_hobby($hobby){
        if(count($hobby)<2){
            return FALSE;
        }else{
            return true;
        }
    }
}
