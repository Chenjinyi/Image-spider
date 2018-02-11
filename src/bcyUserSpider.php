<?php
/**
 * Created by PhpStorm.
 * User: jinyichen
 * Date: 2017/9/24
 * Time: 下午1:20
 */

date_default_timezone_set('PRC'); //设置时区
$mode=$argv;
$user=$argv;

if (empty($user[2])){
    echo "用户未指定";
    die();
}
//print_r($mode,$user);

$url=$user[2];

$ch=curl_init();  //初始化一个cURL会话
curl_setopt($ch,CURLOPT_URL,$url);//设置需要获取的 URL 地址
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

if (empty($result=curl_exec($ch))){
    print_r('无法连接半次元');
    die();
};//执行一个cURL会话

preg_match('/title.*\|/',$result,$file_dir_name);
preg_match_all('/detail\/\d{1,9}\/\d{1,9}/',$result,$urls);
$urls=$urls[0];
$file_dir_name=$file_dir_name[0];
$file_dir_name = str_replace('title>','',$file_dir_name);$file_dir_name = str_replace(' ','',$file_dir_name);
$file_dir_name = str_replace('的个人主页','',$file_dir_name);$file_dir_name = str_replace('|','',$file_dir_name);
//print_r($file_dir_name);
if (!file_exists($file_dir_name)){
 mkdir($file_dir_name,0777);
}



function image_save($file_url,$dir_name,$file_name){ //下载
//    var_dump($file_url,$dir_name,$file_name);
    $image_number=1;
    foreach ($file_url as $urls){
        $image_name = $file_name.'-'.$image_number;
        $urls  = str_replace('/w650','',$urls);
        $urls = "http://".$urls;
        $filename = $dir_name.DIRECTORY_SEPARATOR.$image_name.".jpg";
        $image_save = file_get_contents($urls);
        file_put_contents($filename,$image_save);
        $image_number++;
    }
    unset($image_number);
}


$for_num = count($urls)-1;//获取数组总数
for ($i=0;$i<=$for_num;$i++){
    switch ($mode[1]){
        case 1:
            $new_url="https://bcy.net/illust/".$urls[$i];//获取URL
            break;
        case 2:
            $new_url="https://bcy.net/coser/".$urls[$i];//获取URL
            break;
        default:
            echo "Error";
            die();
    }
    curl_setopt($ch,CURLOPT_URL,$new_url);
    $image=curl_exec($ch);//抓取页面

    switch ($mode[1]) {
        case 1:
            preg_match('/\|.*\|/', $image, $title_string);//获取title
            preg_match('/\s.*\s/', $title_string[0], $title);
            $title = $title[0];
            break;
        case 2:
            preg_match('/e>.*\|/',$image,$title_string);
            preg_match('/.*c/', $title_string[0], $title);
            $title = $title[0];
            $title = str_replace("e>","",$title);
            $title = str_replace("c","",$title);
            break;
        default:
            echo "Error";
            die();
    }

    preg_match_all('/img\d\S*w650/',$image,$image_urls);//正则取出图片链接
    $image_urls=$image_urls[0];//优化数组

    $log = fopen($file_dir_name.DIRECTORY_SEPARATOR.'log','ab+');

    foreach($image_urls as $log_url){
        $log_url  = str_replace('/w650','',$log_url);
        fwrite($log,'http://'.$log_url.PHP_EOL);
    }
    fclose($log);

    $title  = str_replace(' ','',$title);
    $title  = str_replace('/','_',$title);

    print_r($title.PHP_EOL);

    image_save($image_urls,$file_dir_name,$title);

}
//
print_r('爬取完毕');
curl_close($ch);
