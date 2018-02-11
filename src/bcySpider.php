<?php
/**
 * Created by PhpStorm.
 * User: franary
 * Date: 8/30/17
 * Time: 1:25 PM
 */

date_default_timezone_set('PRC'); //设置时区


if (!empty($mode)) {//设置爬取mode
    switch ($mode) {
        case 1:
            $url = "https://bcy.net/illust/toppost100";//URL设置
            break;
        case 2;
            $url = "https://bcy.net/coser/toppost100";
            break;
        default:
            echo "参数错误" . PHP_EOL;
            die();
    }

    $stime=microtime(true);

    $ch = curl_init();  //初始化一个cURL会话
    curl_setopt($ch, CURLOPT_URL, $url);//设置需要获取的 URL 地址
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Host:bcy.net",
        "Connection: keep-alive",
        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
        "Upgrade-Insecure-Requests: 1",
        "DNT:1",
        "Accept-Language:zh-CN,zh;q=0.8",
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36"
    )); // 设置浏览器的特定header

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//不返回数据

    if (empty($result = curl_exec($ch))) {
        print_r('无法连接半次元');
        die();
    };//执行一个cURL会话

//获取全部URL
    preg_match_all('/detail\/\d{1,9}\/\d{1,9}/', $result, $urls); $urls = $urls[0];

    switch ($mode) {
        case 1:
            $create_dir = "illust";
            break;
        case 2:
            $create_dir = 'cos';
            break;
        default:
            $create_dir = '';
    }

    $dirName = "Resource".DIRECTORY_SEPARATOR.date('n-d') . '-' . $create_dir.'-'."Bcy";
    if (!file_exists($dirName)) {
        mkdir($dirName, 0777,true);//创建文件夹
    }


    function image_save($file_url, $dir_name, $file_name)
    { //下载
        print_r(PHP_EOL.$file_name . PHP_EOL);
        $image_number = 1;
        foreach ($file_url as $url) {
            $file_name = str_replace(array('绘画作品'), 'FNL' . date('s-i'), $file_name);//特殊去重
            $image_name = $file_name . '-' . $image_number;
            $url = "http://" . $url;
            $format = explode('.',$url);

            if (file_exists($dir_name . DIRECTORY_SEPARATOR . $image_name . "." . $format[3])) {//检测是否存在
                echo "已存在" . PHP_EOL;
                continue;
            }else {
                if ($image_save = file_get_contents($url)) {
                    @file_put_contents($dir_name . DIRECTORY_SEPARATOR . $image_name . "." . $format[3],$image_save);
                    $image_number++;
                } else {
                    print_r("下载错误：" . $url);
                }
            }
        }

        unset($image_number);
    }

    $for_num = count($urls) - 1;//获取数组总数
    for ($i = 0; $i <= $for_num; $i += 2) {
        switch ($mode) {
            case 1:
                $new_url = "https://bcy.net/illust/" . $urls[$i];//获取URL
                break;
            case 2:
                $new_url = "https://bcy.net/coser/" . $urls[$i];//获取URL
                break;
            default:
                echo "Error";
                die();
        }

        curl_setopt($ch, CURLOPT_URL, $new_url);
        $image = curl_exec($ch);//抓取页面
        switch ($mode) {
            case 1:
                preg_match('/\|.*\|/', $image, $title_string);//获取title
                preg_match('/\s.*\s/', $title_string[0], $title);
                $title = $title[0];
                break;
            case 2:
                preg_match('/e>.*\|/', $image, $title_string);
                preg_match('/.*c/', $title_string[0], $title);
                $title = str_replace(array('e>','c'), "", $title[0]);
                break;
            default:
                echo "Error";
                die();
        }

        preg_match_all('/img\d\S*w650/', $image, $image_urls);//正则取出图片链接
        $image_urls = str_replace('/w650', '', $image_urls[0]);

        $title = str_replace(' ', '', $title);
        $title = str_replace('/', '_', $title);


        image_save($image_urls, $dirName, $title);

    }


    curl_close($ch);
    $etime=microtime(true);//获取程序执行结束的时间
    echo "爬取完毕".PHP_EOL."用时";
    print_r($etime-$stime);

}
