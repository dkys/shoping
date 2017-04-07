<?php
namespace Home\Controller;
use Think\Controller;
//空控制的使用
class EmptyController extends Controller {
    
    public function _empty(){
        echo "<div style='width:800px;margin:0 auto;'><img src='".IMG_PATH."server_err.jpg"."'></div>";
        }
   
}