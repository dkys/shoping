<?php

namespace Admin\Controller;

use Think\Controller;

class GoodsController extends Controller {
//*********************************************************商品列表处理***************************************************
    public function goodslist() {

        $mod = D('goods');
        //获取商品的总记录数
        $pageall = $mod->count();
        //实例化分页类
        $page = new \Think\Page($pageall, 1);
        $page->setConfig("prev", '<<上一页');
        $page->setConfig("next", '下一页>>');
        //获取商品列表信息
        $goods_info = $mod->join('sw_brand')
                ->where("goods_brand_id=brand_id")
                ->limit($page->firstRow, $page->listRows)
                ->select();
        $pagestr = $page->show();
        //将分页函数返回值赋给模板
        $this->assign('goods_info', $goods_info);
        $this->assign('pagestr', $pagestr);
        $this->display();
    }

    public function add() {
        //获取品牌表的数据
        $mod = M('brand');
        $brand = $mod->select();
        $this->assign('brand', $brand);

        //获取商品分类表的数据
        $mod = M('category');
        $category = $mod->select();
        $this->assign('category', $category);

        //插入数据
        if (!empty($_POST)) {
            if (!empty($_FILES)) {
                //***********************************************图片上传处理***************************************************
                $config = array(
                    'rootPath' => './shop/public/', //设置文件根目录地址 这里指index.php所在文件夹
                    'savePath' => 'uploads/', //设置图片要保存的文件
                );

                $upload = new \Think\Upload($config);
                //调用文件上传处理方法
                $res = $upload->uploadOne($_FILES['goods_img']);
                if (!$res) {
                    //返回错误信息
                    echo $upload->getError();
                } else {
                    $bigimg = $res['savepath'] . $res['savename'];
                    //图片的缩略图制作
                    $img = new \Think\Image();//实例化img对象
                    $img->open('./shop/public/'.$bigimg);//打开文件资源 这里指index.php所在文件夹（相对路径）
                    $img->thumb(50, 50);//参数1：设置图片的最大宽度   参数2：设置图片的最大高度
                    $smallimg = $res['savepath'] .'small'.$res['savename'];
                    $img->save('./shop/public/'.$smallimg); //保存图片资源 这里指index.php所在文件夹（相对路径）
                }
                //*********************************************** 插入数据库处理 ***************************************************
                $mod = M('goods');
                //自动搜集POST数据
                $mod->create();
                //修改成员变量（数据库图片字段）的值
                $mod->goods_big_img = $bigimg;
                $mod->goods_small_img = $smallimg;
                if ($mod->add()) {
                $this->success('添加商品成功', U('goods/goodslist'), 3);
                } else {
                    $this->error('添加失败！', __SELF__, 3);
                }
            }

        }
            $this->display();
    }
//********************************************************* 商品修改处理 ***************************************************
    public function update($goodsid) {
        //获取GET参数的商品id 查询商品信息
        $mod = M('goods');
        $goodsinfo = $mod->find($goodsid);
        $this->assign('goodsinfo', $goodsinfo);

        //获取商品分类信息
        $mod = M('brand');
        $brand = $mod->select();
        $this->assign('brand', $brand);

        //获取商品分类信息
        $mod = M('category');
        $category = $mod->select();

        $this->assign('category', $category);

        //操作修改商品数据
//        var_dump($_POST);
        $mod = M('goods');
        $mod->create();
        if (!empty($_POST)) {
            if ($mod->save()) {
                $this->success('修改商品成功', U("Goods/goodslist"), 3);
            } else {
                $this->error('修改商品失败', U('Goods/update'), 3);
            }
        }

        $this->display();
    }

    public function delete($goodsid) {
        if (!empty($_GET)) {
            $mod = M('goods');
            if ($mod->delete($goodsid)) {
                $this->success('删除成功', U('Goods/goodslist'), 3);
            } else {
                $this->error('删除失败！', U('Goods/goodslist'), 3);
            }
        }
    }

    public function register() {

        $this->display('register');
    }

}
