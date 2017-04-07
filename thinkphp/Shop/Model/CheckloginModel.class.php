<?php
namespace Model;
use Think\Model;

class CheckloginModel extends Model {
    
     public function checklog() {
        if(isset($_SESSION['user_name']) && isset($_SESSION['user_pwd'])){
            $sql = "select * from sw_user where user_name='{$_SESSION['user_name']}' and pwd='{$_SESSION['user_pwd']}'";
            if($this->query($sql)){
                return TRUE;
            }else{
                return FALSE;
            }
           
        }
    }
}
