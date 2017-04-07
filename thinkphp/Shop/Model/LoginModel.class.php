<?php
namespace Model;
use Think\Model;


class LoginModel extends Model {
   
    public function checklogin($name,$pwd) {
        if(empty($name)){
            echo '用户名不得为空!';
        }
        if(empty($pwd)){
            echo '密码不得为空!';
        }
        
        $rel = $this->getByuser_name($name);
        if($rel){
            if($rel['pwd'] == $pwd){
                return $rel;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    
}
