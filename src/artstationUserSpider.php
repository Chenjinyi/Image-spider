<?php
/**
 * Created by PhpStorm.
 * User: minec
 * Date: 2017/12/20
 * Time: 18:56
 */

date_default_timezone_set('PRC'); //设置时区

print_r(PHP_EOL."请输入用户:");
if (!empty($user_name= trim(fgets(STDIN)))){
    if (empty($user_name)){
        echo "用户未指定";
        die();
    }
}
https://www.artstation.com/projects/kkrPz/comments.json
$stime=microtime(true);

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
    for ($start_num=1;$start_num<=20;$start_num++) {


        $url = "https://www.artstation.com/users/" . $user_name . "/projects.json?page=" . $start_num;

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
            print_r('Error:无法连接Artstation/找不到URL');
            die();
        };//执行一个cURL会话
        $result = json_decode($result);

        if ($result->date = []) {
            die();
        }

        $imageIndex = array();
        foreach ($result->data as $a) {
            array_push($imageIndex, $a->cover->medium_image_url);
        }

        $dirName = "Resource" . DIRECTORY_SEPARATOR . date('n-d') . '-' . $user_name . "-Artstation";
        if (!file_exists($dirName)) {
            mkdir($dirName, 0777, true);//创建文件夹
        }
        $imageNum = 0;
        foreach ($imageIndex as $i) {
            $i = str_replace("medium", "large", $i);
            $title = $result->data[$imageNum]->title ."-".$user_name;

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

