<?php

namespace Home\Controller;

use Think\Controller;

class UserController extends Controller {

//*********************************************************用户登录**************************************************//
    public function index() {
        $this->login();
        $this->display('login');
    }

    public function login() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if ($check->checklog()) {
            $this->success('亲爱的用户,您已经登录过了!', U('index/index'), 3);
            exit();
        }
        if (!empty($_POST)) {
            $mod = new \Model\LoginModel('user');
            $info = $mod->checklogin($_POST['user_name'], $_POST['pwd']);
            if (!$info) {
                $this->error('用户名或密码错误,请重试!', __SELF__, 2);
            } else {
                session('user_id', $info['user_id']);
                session('user_name', $info['user_name']);
                session('user_pwd', $info['pwd']);
                $this->success('登录成功!', U('user/ucenter'), 2);
                exit();
            }
        }
    }
    public function logout() {
        session(NULL);
        
        $this->redirect('user/index','',0,'退出成功!');
    }
//*********************************************************用户注册**************************************************//
    public function register() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if ($check->checklog()) {
            $this->success('亲爱的用户,您已经登录过了!', U('index/index'), 3);
            exit();
        }
        if (!empty($_POST)) {
            $mod = new \Model\UserModel();
            if (!$mod->create()) {
                echo $mod->getError();
            } else {
                $mod->user_hobby = implode(',', $_POST['user_hobby']);
                if ($mod->add()) {
                    $this->success('注册成功', '');
                } else {
                    $this->error('注册失败！', '');
                }
            }
        }
        $this->display('register');
    }

//*********************************************************购物车列表**************************************************//
    public function cart() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if (!$rel = $check->checklog()) {
            $this->success('亲,您还没有登录哦!', U('user/index'), 3);
            exit();
        } else {
            $this->assign('loginstatus', $rel);
        }

        $mod = D();
        $sql = "select * from sw_goods,sw_cart where goods_id=c_goods_id and c_member_id={$_SESSION['user_id']};";
        $cartinfo = $mod->query($sql);

        //获取商品的总价格
        foreach ($cartinfo as $value) {
            $all_price[] = $value['c_num'] * $value['goods_price'];
        }
        //array_sum()计算数组中所有元素的和  算出所有的商品总和
        $price_sum = array_sum($all_price);

        //获取购物车商品总数
        $num = $mod->table('sw_cart')->where("c_member_id={$_SESSION['user_id']}")->count();
        $this->assign('price_sum', $price_sum);
        $this->assign('num', $num);
        $this->assign('cart_info', $cartinfo);
        $this->display('cart');
    }

//*********************************************************添加购物车处理**************************************************//
    public function addcart() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if (!$check->checklog()) {
            $this->ajaxReturn('亲,您还没有登录哦!');
            exit();
        }
        if (!empty($_POST)) {

            //获取与商品对应的品牌id
            $mod = D();
            $sql = "select * from sw_brand where brand_id=(select goods_brand_id from sw_goods where goods_id={$_POST['c_goods_id']})";
            $brand_id = $mod->query($sql);
            //将数据插入购物车数据表
            $mod = D('cart');
            $mod->create();
            $mod->c_member_id = $_SESSION['user_id'];

            //add()返回最后插入数据库数据的id
            $rel = $mod->add();
            if ($rel) {
                $this->ajaxReturn('添加购物车成功!');
            } else {
                $this->ajaxReturn('添加购物车失败');
            }
        }
    }

//*********************************************************删除购物车商品**************************************************//    
    public function cartdele() {
        if (isset($_GET['cid']) && !empty($_GET['cid'])) {
            $mod = D('cart');
            $rel = $mod->where("c_id={$_GET['cid']}")->delete();
            if ($rel) {
                $this->success('删除成功!', U('user/cart'));
            }
        }
    }

//*********************************************************订单提交**************************************************//
    public function auction() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if (!$rel = $check->checklog()) {
            $this->ajaxReturn('亲,您还没有登录哦!');
            exit();
        } else {
            $this->assign('loginstatus', $rel);
        }
        if (isset($_GET['goodsid']) && !empty($_GET['goodsid'])) {

            $mod = D();
            //获取购物车的商品信息
            $sql = "select * from sw_goods,sw_cart where goods_id={$_GET['goodsid']} and c_goods_id={$_GET['goodsid']} and c_member_id={$_SESSION['user_id']};";
            $cartinfo = $mod->query($sql);
            foreach ($cartinfo as $value) {
                $all_price[] = $value['c_num'] * $value['goods_price'];
            }
            //array_sum()计算数组中所有元素的和  算出所有的商品总和
            $price_sum = array_sum($all_price);

            //获取收货人信息
            $receiving = $mod->table('sw_receiving')->where("member_id={$_SESSION['user_id']}")->select();
            $this->assign('price_sum', $price_sum); //商品总数
            $this->assign('cartinfo', $cartinfo); //购物车商品信息
            $this->assign('rece', $receiving); //收货信息
            $this->display();
        }
    }

//*********************************************************支付并生成订单处理**************************************************//    
    public function payment() {
        if (!empty($_POST)) {
            $order_code = date('YmdHis') . rand(1000, 9999);
            $mod = D();
            $mod->table('sw_order');
            $mod->create();
            $mod->order_sn = $order_code; //订单号
            $mod->order_u_id = $_SESSION['user_id']; //用户id
            $mod->order_goods_id = $_POST['order_goods_id']; //用户id
            if ($id = $mod->add()) {
//           echo $id;
                $this->success('支付成功!', U("user/order?id={$id}"), 3);
            }
        }
    }

//*********************************************************订单完成后返回订单号**************************************************//    
    public function order() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if (!$rel = $check->checklog()) {
            $this->ajaxReturn('亲,您还没有登录哦!');
            exit();
        } else {
            $this->assign('loginstatus', $rel);
        }
        if (isset($_GET['id'])) {
            $mod = D('order');
            $orderifo = $mod->join("sw_receiving on order_id={$_GET['id']} and order_consignee_id=receiving_id")->find();
            $this->assign('orderifo', $orderifo);
            $this->display();
        } else {
            $this->success('亲,您访问的页面不存在哦!', U('index/index'), 3);
        }
    }

//*********************************************************订单列表**************************************************//        
    public function orderlist() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if (!$rel = $check->checklog()) {
            $this->ajaxReturn('亲,您还没有登录哦!');
            exit();
        } else {
            $this->assign('loginstatus', $rel);
        }
        $mod = D('order');
        $orderinfo = $mod->where("order_u_id={$_SESSION['user_id']}")->select();
        $this->assign('orderinfo', $orderinfo);
        $this->display();
    }

//*********************************************************订单取消**************************************************//     
    public function orderdel() {
        if (isset($_GET['orderid']) && !empty($_GET['orderid'])) {
            $mod = D('order');
            $rel = $mod->where("order_id={$_GET['orderid']}")->delete();
            if ($rel) {
                $this->success('取消订单成功!', U('user/orderlist'));
            }
        }
    }

//*********************************************************修改订单状态为3:待评价**************************************************//     
    public function conf() {
        if (isset($_POST['orderid']) && !empty($_POST['orderid'])) {
            $mod = D('order');
            $rows = $mod->where("order_id={$_POST['orderid']}")->setField('order_status', 3);
            if($rows){
                echo '交易成功,请给本商品给予评价!';
            }
        }
    }

//*********************************************************用户中心首页**************************************************//   
    public function ucenter() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if (!$rel = $check->checklog()) {
            $this->success('亲,您还没有登录哦!', U('user/index'), 3);
            exit();
        } else {
            $this->assign('loginstatus', $rel);
        }
        $this->display();
    }

//*********************************************************添加收货地址**************************************************//     
    public function receadd() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if (!$rel = $check->checklog()) {
            $this->success('亲,您还没有登录哦!', U('user/index'), 3);
            exit();
        } else {
            $this->assign('loginstatus', $rel);
        }
        $this->display();
    }

//*********************************************************商品评价处理**************************************************//     
    public function comment() {
        $mod = D('comment');
        $mod->create();
        if ($mod->add()) {
            $this->success('评论成功!', U("home/goods/detail/goodsid/{$_POST['comment_goods_id']}"));
        }
    }

//*********************************************************商品评价展示**************************************************//         
}
