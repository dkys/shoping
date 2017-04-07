<?php

namespace Admin\Controller;

use Think\Controller;

class ManagerController extends Controller {

    public function login() {
        //display()如果没有参数的时候 调用的视图模板名称 就与当前方法同名
        $this->display();
    }
    public function checkcode() {
         //验证管理员登录信息
        if (!empty($_POST)) {
            $verify = new \Think\Verify();
            if (!$verify->check($_POST['code'])) {
                $this->ajaxReturn('验证码错误！') ;

            } else {
                $login = new \Model\ManagerModel('admin'); //实例化验证表达对象
                $rel = $login->checklogin($_POST['admin_user'], $_POST['admin_pwd']); //调用验证方法
                    if(is_string($rel)){
                        $this->ajaxReturn($rel);
                    }else{
                    //将管理员信息存储到session中
                    session('manager_name',$rel['admin_user']);
                    session('manager_pwd',$rel['admin_psd']);
                    //redirect()直接跳转 中间没有跳转时间限制
                    $this->ajaxReturn('登录成功!');
//                        echo 1;
                    }
                
            }
        }
    }
    public function logout() {
        session(NULL);
        $this->redirect('manager/login', '', 1, '退出成功！');
    }
    public function verifycode() {
        //验证码配置信息设置
        $config = array(
            'imageH' => 25, // 验证码图片高度
            'imageW' => 80, // 验证码图片宽度
            'fontSize' => 12, // 验证码字体大小(px)
            'useCurve'  =>  FALSE,            // 是否画混淆曲线
            'length' => 4, // 验证码位数
            'fontttf' => '1.ttf', // 验证码字体，不设置随机获取
        );
        //实例化验证码类
        $verify = new \Think\Verify($config);
        //调用验证码显示方法
        $verify->entry();
    }

    //空方法的使用
    public function _empty() {
        echo "<div style='width:800px;margin:0 auto;'><img src='" . IMG_PATH . "server_err.jpg" . "'></div>";
    }

}
