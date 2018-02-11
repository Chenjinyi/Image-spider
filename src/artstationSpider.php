<?php
/**
 * Created by PhpStorm.
 * User: jinyichen
 * Date: 2017/11/26
 * Time: 上午10:16
 */


date_default_timezone_set('PRC'); //设置时区

print_r(PHP_EOL."请输入爬取页数(1页=50个作品):");
if (!empty($posts_num= abs(trim(fgets(STDIN))))){
    if ($posts_num>=51){
        echo "数值过大(1-50)";
        die();
    }
}else{
    echo "不能为空";
    die();
}

$stime=microtime(true);

if (!empty($mode)) {//设置爬取mode URL设置
    switch ($mode) {
        case 5:
            $mode_name = 'trending';
            break;
        case 6;
            $mode_name= 'latest';
            break;
        case 7;
            $mode_name= 'picks';
            break;
        default:
            echo "参数错误" . PHP_EOL;
            die();
    }

    function image_save($url, $dir_name, $file_name)
    { //下载
        print_r(PHP_EOL . $file_name . PHP_EOL);
        if (file_exists($dir_name . DIRECTORY_SEPARATOR . $file_name)) {//检测是否存在
            echo "已存在" . PHP_EOL;
        } else {
            if (@$image_save = file_get_contents($url)) {
                @file_put_contents($dir_name . DIRECTORY_SEPARATOR . $file_name, $image_save);
            } else {
                print_r("下载错误：" . $url);
            }
        }
    }

//    $url = "https://www.artstation.com/";
    for ($start_num=1;$start_num<=$posts_num;$start_num++) {

        $url = "https://www.artstation.com/projects.json?page=" . $start_num . "&sorting=" . $mode_name;

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
            print_r('无法连接Artstation');
            die();
        };//执行一个cURL会话
        $result = json_decode($result);

        $imageIndex = array();
        foreach ($result->data as $a) {
            array_push($imageIndex, $a->cover->medium_image_url);
        }

        $dirName = "Resource" . DIRECTORY_SEPARATOR . date('n-d') . '-' . $mode_name . "-Artstation";
        if (!file_exists($dirName)) {
            mkdir($dirName, 0777, true);//创建文件夹
        }

//    $for_num = count($imageIndex) - 1;//获取数组总数
//    for ($i = 0; $i <= $for_num; $i++) {
//        curl_setopt($ch, CURLOPT_URL, $imageIndex[$i]);
//        $image = curl_exec($ch);//抓取页面
//
//        preg_match_all("/image_url.*\d*/", $image, $image_urls);//正则取出图片链接
//
//        $title = $result->data[$i]->title."-".$result->data[$i]->user->first_name.$result->data[$i]->user->last_name;
//        var_dump($image_urls);
//        die();
//        image_save($image_urls, $dirName, $title);
//
//    }
//    https://cdna.artstation.com/p/assets/images/images/008/352/772/medium/anna-nikonova-aka-newmilky-zoe-12.jpg?1512196050%
        $imageNum = 0;
        foreach ($imageIndex as $i) {
            $i = str_replace("medium", "large", $i);
            $title = $result->data[$imageNum]->title . "-" . $result->data[$imageNum]->user->first_name . $result->data[$imageNum]->user->last_name;

            if (strstr($i, "jpg")) {
                $title .= ".jpg";
            } elseif (strstr($i, "png")) {
                $title .= ".png";
            } else {
                $title .= $i;
            }

            image_save($i, $dirName, $title);
            $imageNum++;
        }
    curl_close($ch);
    }
    $etime=microtime(true);//获取程序执行结束的时间
    echo "爬取完毕".PHP_EOL."用时";
    print_r($etime-$stime);

}
