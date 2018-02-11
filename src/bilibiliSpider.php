<?php
/**
 * Created by PhpStorm.
 * User: jinyichen
 * Date: 2017/11/28
 * Time: 下午11:46
 */


date_default_timezone_set('PRC'); //设置时区

$stime=microtime(true);

if (!empty($mode)) {//设置爬取mode URL设置
    switch ($mode) {
        case 8:
            $mode_name = 'week';
            break;
        case 9;
            $mode_name= 'month';
            break;
        case 10;
            $mode_name= 'day';
            break;
        default:
            echo "参数错误" . PHP_EOL;
            die();
    }
    $url = "http://api.vc.bilibili.com/link_draw/v2/Doc/ranklist?biz=1&category=&rank_type=".$mode_name."&date=".date('Y-m-d')."&page_num=0&page_size=50";

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

    $imageIndex=array();
    foreach ($result->data->items as $a){
        array_push($imageIndex,$a->item->pictures->img_src);
    }
    $dirName = "Resource".DIRECTORY_SEPARATOR.date('n-d') . '-' . $mode_name."-"."Bilibili";
    if (!file_exists($dirName)) {
        mkdir($dirName, 0777,true);//创建文件夹
    }

    die();
    function image_save($file_url, $dir_name, $file_name)
    { //下载
        print_r(PHP_EOL.$file_name . PHP_EOL);
        $image_number = 1;
        foreach ($file_url as $url) {
            $image_name = $file_name . '-' . $image_number;
            $url = "http://" . $url;
            $format = explode('.',$url);

            if (file_exists($dir_name . DIRECTORY_SEPARATOR . $image_name . "." . $format[3])) {//检测是否存在
                echo "已存在" . PHP_EOL;
                continue;
            }else {
                if ($image_save = file_get_contents($url)) {
                    file_put_contents($dir_name . DIRECTORY_SEPARATOR . $image_name . "." . $format[3],$image_save);
                    $image_number++;
                } else {
                    print_r("下载错误：" . $url);
                }
            }
        }

        unset($image_number);
    }

    $for_num = count($imageIndex) - 1;//获取数组总数
    for ($i = 0; $i <= $for_num; $i++) {
        curl_setopt($ch, CURLOPT_URL, $imageIndex[$i]);
        $image = curl_exec($ch);//抓取页面

        preg_match_all("/image_url.*\d*/", $image, $image_urls);//正则取出图片链接

        $title = $result->data[$i]->title."-".$result->data[$i]->user->first_name.$result->data[$i]->user->last_name;
        var_dump($title,$image_urls);
        die();
        image_save($image_urls, $dirName, $title);

    }

    curl_close($ch);
    $etime=microtime(true);//获取程序执行结束的时间
    echo "爬取完毕".PHP_EOL."用时";
    print_r($etime-$stime);

}
