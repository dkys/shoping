<?php
namespace Home\Controller;
use Think\Controller;
class GoodsController extends Controller {
    public function index(){
          //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if ($rel = $check->checklog()) {
            $this->assign('loginstatus',$rel);
        }
        $this->display('category');
        
    }
//*********************************************************商品详情页**************************************************//     
    public function detail(){
         //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if ($rel = $check->checklog()) {
            $this->assign('loginstatus',$rel);
        }
        if(!empty($_GET)){
            $mod = D();
            $detail = $mod->table('sw_goods')->join('sw_brand')
                    ->where("goods_id={$_GET['goodsid']} and goods_brand_id=brand_id")
                    ->find();
                    $url = U('home/user/index/login');
            $this->assign('url',$url);
            $this->assign('detail',$detail);
//*********************************************************商品评价展示**************************************************//
            $num = $mod->table('sw_comment')->count();//获取所有评论的总数
            $page = new \Think\Page($num, 2);
            $page->setConfig("prev", '<<上一页');
            $page->setConfig("next", '下一页>>');
            $commentinfo = $mod->table('sw_comment')->where("comment_goods_id={$_GET['goodsid']}")
                    ->order("comment_id desc")
                    ->limit($page->firstRow,$page->listRows)
                    ->select();//获取所有评论信息
            $pagestr = $page->show();
            $userinfo = $mod->table('sw_user')->field('user_name,user_id')->select();//获取用户信息
            $this->assign('num',$num);
            $this->assign('userinfo',$userinfo);
            $this->assign('commentinfo',$commentinfo);
            $this->assign('page',$pagestr);

        }
        $this->display('detail');
    }

    
    
    
    
    
    
    
    //空方法的使用
    public function _empty($name){
        echo "<div style='width:800px;margin:0 auto;'><img src='".IMG_PATH."server_err.jpg"."'></div>";
    }
    
    
    
    
}