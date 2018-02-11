<?php
/**
 * Created by PhpStorm.
 * User: franary
 * Date: 8/30/17
 * Time: 1:25 PM
 */

date_default_timezone_set('PRC'); //设置时区

/**
 * Mode
 * 1 bcy illust
 * 2 bcy cos
 * 3 bcy novel
 * 4 bing image
 * 5 artstation  trending
 * 6 artstation  latest
 * 7 artstation  picks
 */
print_r("
-------------------------------------------------
欢迎使用Iamge—Spider／Bcy-Spider

请选择要爬取的内容

1：半次元 绘画区 本周 TOP100

2：半次元 Cos区 本周 TOP100

3：半次元 文章区 本周 TOP100 (Error 半次元更新机制 无法爬取)

4: Bing 今日壁纸

5: Artstation  trending 趋势

6: Artstation  latest 最新

7: Artstation  picks 最佳

8: Artstation User 用户图片

9:BiliBili相簿 画友 周榜

10:BiliBIli相簿 画友 月榜

11:BIliBIli相簿 画友 日榜

12:BIliBIli相簿 COS 周榜

13:BIliBIli相簿 COS 月榜

14:BIliBIli相簿 COS 日榜

15:BIliBIli相簿 私服 周榜

16:BIliBIli相簿 私服 月榜

17:BIliBIli相簿 私服 日榜

By:Franary

Github:https://github.com/Chenjinyi
-------------------------------------------------

");

print_r("请输入参数:");

$mode = trim(fgets(STDIN));

if (!file_exists("Resource")) {
    mkdir("Resource", 0777,true);//创建文件夹
}

if (!empty($mode)) {//设置爬取mode
    switch ($mode) {
        case 1:
            echo PHP_EOL . "开始爬取绘画区" . PHP_EOL;
            include_once 'src/bcySpider.php';
            die();
        case 2:
            echo PHP_EOL . "开始爬取COS区" . PHP_EOL;
            include_once 'src/bcySpider.php';
            die();
        case 3:
            echo PHP_EOL . "开始爬取文章区" . PHP_EOL;
            include_once 'src/bcyNovelSpider.php';
            die();
        case 4:
            echo PHP_EOL . "开始爬取今日Bing壁纸" . PHP_EOL;
            include_once 'src/BingSpider.php';
            die();
        case 5:
            echo PHP_EOL . "开始爬取Artstation  Trending" . PHP_EOL;
            include_once 'src/artstationSpider.php';
            die();
        case 6:
            echo PHP_EOL . "开始爬取Artstation  latest" . PHP_EOL;
            include_once 'src/artstationSpider.php';
            die();
        case 7:
            echo PHP_EOL . "开始爬取Artstation  picks" . PHP_EOL;
            include_once 'src/artstationSpider.php';
            die();
        case 8:
            echo PHP_EOL . "开始爬取Artstation User" . PHP_EOL;
            include_once 'src/artstationUserSpider.php';
            die();
        case 9:
            echo PHP_EOL . "开始爬取BiliBili相簿 画友 周榜" . PHP_EOL;
            include_once 'src/bilibiliSpider.php';
            die();
        case 10:
            echo PHP_EOL . "开始爬取BiliBili相簿 画友 月榜" . PHP_EOL;
            include_once 'src/bilibiliSpider.php';;
            die();
        case 11:
            echo PHP_EOL . "开始爬取BiliBili相簿 画友 日榜" . PHP_EOL;
            include_once 'src/bilibiliSpider.php';;
            die();
        case 12:
            echo PHP_EOL . "开始爬取BiliBili相簿 Cos 周榜" . PHP_EOL;
            include_once 'src/bilibiliSpider.php';;
            die();
        case 13:
            echo PHP_EOL . "开始爬取BiliBili相簿 Cos 月榜" . PHP_EOL;
            include_once 'src/bilibiliSpider.php';;
            die();
        case 14:
            echo PHP_EOL . "开始爬取BiliBili相簿 Cos 日榜" . PHP_EOL;
            include_once 'src/bilibiliSpider.php';;
            die();
        case 15:
            echo PHP_EOL . "开始爬取BiliBili相簿 私服  周榜" . PHP_EOL;
            include_once 'src/bilibiliSpider.php';;
            die();
        case 16:
            echo PHP_EOL . "开始爬取BiliBili相簿 私服 月榜" . PHP_EOL;
            include_once 'src/bilibiliSpider.php';;
            die();
        case 17:
            echo PHP_EOL . "开始爬取BiliBili相簿 私服 日榜" . PHP_EOL;
            include_once 'src/bilibiliSpider.php';;
            die();
        default:
            echo "参数错误" . PHP_EOL;
            die();
    }

//    $stime=microtime(true);
//    $etime=microtime(true);//获取程序执行结束的时间
//    echo "爬取完毕".PHP_EOL."用时";
//    print_r($etime-$stime);
}
