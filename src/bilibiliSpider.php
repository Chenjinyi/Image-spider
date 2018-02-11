<?php
/**
 * Created by PhpStorm.
 * User: jinyichen
 * Date: 2017/11/28
 * Time: 下午11:46
 */


date_default_timezone_set('PRC'); //设置时区

$stime=microtime(true);
$NDate=date('Y-m-d');
if (!empty($mode)) {//设置爬取mode URL设置
    switch ($mode) {
        case 9:
            $url = "http://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=1&category=&rank_type=week&date=".$NDate."&page_num=0&page_size=50";
            break;
        case 10:
            $url = "http://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=1&category=&rank_type=month&date=".$NDate."&page_num=0&page_size=50";
            break;
        case 11:
            $url = "http://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=1&category=&rank_type=day&date=".$NDate."&page_num=0&page_size=50";
            break;
        case 12:
            $url=  "https://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=2&category=cos&rank_type=week&date=".$NDate."&page_num=0&page_size=50";
            break;
        case 13:
            $url=  "https://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=2&category=cos&rank_type=month&date=".$NDate."&page_num=0&page_size=50";
            break;
        case 14:
            $url=  "https://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=2&category=cos&rank_type=day&date=".$NDate."&page_num=0&page_size=50";
            break;
        case 15:
            $url=  "https://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=2&category=sifu&rank_type=week&date=".$NDate."&page_num=0&page_size=50";
            break;
        case 16:
            $url="https://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=2&category=sifu&rank_type=month&date=".$NDate."&page_num=0&page_size=50";
            break;
        case 17:
            $url="https://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=2&category=sifu&rank_type=day&date=".$NDate."&page_num=0&page_size=50";;
            break;
        default:
            echo "参数错误" . PHP_EOL;
            die();
    }

    $ch = curl_init();  //初始化一个cURL会话
    curl_setopt($ch, CURLOPT_URL, $url);//设置需要获取的 URL 地址
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Connection: keep-alive",
        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
        "Upgrade-Insecure-Requests: 1",
        "DNT:1",
        "Accept-Language:zh-CN,zh;q=0.8",
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36"
    )); // 设置浏览器的特定header

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//不返回数据

    if (empty($result = curl_exec($ch))) {
        print_r('无法连接BiliBili   ');
        die();
    };//执行一个cURL会话
    $result=json_decode($result);

    $num=0;
    foreach (@$result->data->items as $a){
        $imageNum=0;
        $user = $a->user->name;$title = $a->item->title;$imageList = $a->item->pictures;
        foreach ($imageList as $b){
            $image[$num] = [$title."-".$user."-"."$imageNum"=>$b->img_src];
            $num++;$imageNum++;
        }
        unset($imageNum);
    }

    $dirName = "Resource".DIRECTORY_SEPARATOR.date('n-d') . '-' ."Bilibili";
    if (!file_exists($dirName)) {
        mkdir($dirName, 0777,true);//创建文件夹
    }

//    print_r($result);
    foreach ($image as $file){
        { //下载
            print_r(PHP_EOL . array_keys($file)[0]);
            $format=explode(".",$file[array_keys($file)[0]]);$format=".".$format[3];
            if (file_exists($dirName . DIRECTORY_SEPARATOR .array_keys($file)[0].$format)) {//检测是否存在
                echo "已存在" . PHP_EOL;
            } else {
                if ($imageSave = file_get_contents($file[array_keys($file)[0]])) {
                    file_put_contents($dirName. DIRECTORY_SEPARATOR . array_keys($file)[0].$format, $imageSave);
                } else {
                    print_r("下载错误：" . $file[array_keys($file)[0]]);
                }
            }
        }
    }

    curl_close($ch);
    $etime=microtime(true);//获取程序执行结束的时间
    echo "爬取完毕".PHP_EOL."用时";
    print_r($etime-$stime);

}
