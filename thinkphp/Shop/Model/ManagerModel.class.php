<?php
namespace Model;
use Think\Model;
class ManagerModel extends Model {
    public function checklogin($name,$pwd){
        if(empty($name)){
            return '用户名不得为空！';
        }elseif(empty($pwd)){
            return '密码不得为空！';
        } else {
            
        //getBy字段名（） 表示以所写的字段名查找数据库
        $info = $this->getByadmin_user($name);
        if($info != NULL){
            if($info['admin_psd'] != $pwd){
                return '用户名或者密码错误!';
            }else{
                return $info;
            }
        }else{
            return '用户名或者密码错误';
        }
        }
    }
}
