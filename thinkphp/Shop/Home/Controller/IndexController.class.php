<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {

    public function index() {
        //验证是否登录
        $check = new \Model\CheckloginModel('user');
        if ($rel = $check->checklog()) {
            $this->assign('loginstatus', $rel);
        }
        $mod = D('goods');
//        print_const();
        $goodsinfo = $mod->limit(9)
                ->order('goods_id asc')
                ->select();
        $this->assign('goodsinfo', $goodsinfo);
        $this->display();
    }
    
    public function test() {
        $url = U('index/word');
        echo "<a href= '{$url}'>点击生成word文档</a>";
    }
    public function getWordDocument() {
        $mht = new \Org\Util\MhtFileMaker();
        if ($isEraseLink)
            $content = preg_replace('/<a\s*.*?\s*>(\s*.*?\s*)<\/a>/i', '$1', $content);   //去掉链接

        $images = array();
        $files = array();
        $matches = array();
        //这个算法要求src后的属性值必须使用引号括起来
        if (preg_match_all('/src=[\'\"](.*?(?:\.png|\.jpg))[\'\"].*?[\/]?>/i', $content, $matches)) {
            $arrPath = $matches[1];
            for ($i = 0; $i < count($arrPath); $i++) {
                $path = $arrPath[$i];
                $imgPath = trim($path);
                if ($imgPath != "") {
                    $files[] = $imgPath;
                    if (substr($imgPath, 0, 7) == 'http://') {
                        //绝对链接，不加前缀
                    } else {
                        $imgPath = $absolutePath . $imgPath;
                    }
                    $images[] = $imgPath;
                }
            }
        }
        $mht->AddContents("tmp.html", $mht->GetMimeType("tmp.html"), $content);

        for ($i = 0; $i < count($images); $i++) {
            $image = $images[$i];
            if (@fopen($image, 'r')) {
                $imgcontent = @file_get_contents($image);
                if ($content)
                    $mht->AddContents($files[$i], $mht->GetMimeType($image), $imgcontent);
            }
            else {
                echo "file:" . $image . " not exist!<br />";
            }
        }
    }

    public function word() {
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        $id = rand(1, 527);
        $url = 'http://io.cc'.U('index/index');
        $wordStr = file_get_contents($url);
        //获取parse_url()解析url到一个数组
        $uri_info = parse_url($url);
        //将域名部分拼接出来
        $uri = $uri_info['scheme'].'://'.$uri_info['host'];
        $fileContent = $this->getWordDocument($wordStr,$uri);
        $fileName = iconv("utf-8", "GBK", 'test' . '_' . $id . '_' . rand(100, 999));
        header("Content-Type: application/doc");
        header("Content-Disposition: attachment; filename=" . $fileName . ".doc");
        echo $fileContent;
//        http://io.cc/thinkphp/index.php/home/index/word
//header("Content-Type: application/msword"); 
//header("Content-Disposition: attachment; filename=doc.doc"); //指定文件名称 
//header("Pragma: no-cache");
//header("Expires: 0");
//$contnet = file_get_contents($url);
////$contnet .= file_get_contents('http:')
//echo $url;
    }

}
