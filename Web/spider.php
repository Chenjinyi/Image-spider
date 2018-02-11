<?php
/**
 * Created by PhpStorm.
 * User: franary
 * Date: 8/30/17
 * Time: 1:25 PM
 */
include_once 'config.php';

date_default_timezone_set('PRC'); //设置时区

if (!empty($argv[1])){//设置爬取mode
    $mode=$argv[1];
}

if(!empty($_POST['mode'])){
    $mode=$_POST['mode'];
}

if (!empty($_POST['mode'])){
    if (@!$_POST['key']=='key'){
        echo "Key不正确";
        die();
    }
}

if (empty($mode)){//设置爬取mode
    echo "参数不能为空！".PHP_EOL. "1 = 绘画区 | 2 = COS区 | 3 = 文章区".PHP_EOL;
    die();
}


switch ($mode){//设置爬取mode
    case 1:
        echo "开始爬取绘画区".PHP_EOL;
        $url="https://bcy.net/illust/toppost100";//URL设置
        break;
    case 2:
        echo "开始爬取COS区".PHP_EOL;
        $url="https://bcy.net/coser/toppost100";
        break;
    case 3:
        echo "开始爬取文章区".PHP_EOL;
        include_once 'bcyNovelSpider.php';
        die();
        break;
    default:
        echo "参数错误".PHP_EOL;
        die();
}

$connect = mysqli_connect($DataHost,$DataUser,$DataPass,$DataBase)or die('连接数据库失败！');
mysqli_select_db($connect, $DataName) or die('选择数据库失败！');
mysqli_set_charset($connect,'utf8');

$serve_time=time();

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

$result=curl_exec($ch);//执行一个cURL会话
//获取全部URL
preg_match_all('/detail\/\d{1,9}\/\d{1,9}/',$result,$urls); //urls
$urls = $urls[0];//优化数组

//print_r($urls);

switch ($mode){
    case 1:
        $create_dir="img";
        break;
    case 2:
        $create_dir='cos';
        break;
    default:
        $create_dir='';
}

$dir_name = date('n-d').'-'.$create_dir;
if(!file_exists($dir_name)){
    mkdir($dir_name,0777);//创建文件夹
}

function image_save($file_url,$dir_name,$file_name,$connect){ //下载
//    var_dump($file_url,$dir_name,$file_name);
    print_r($file_name.PHP_EOL);
    $image_number=1;
    foreach ($file_url as $urls){
        $image_name = $file_name.'-'.$image_number;
        $urls  = str_replace('/w650','',$urls);
        $urls = "http://".$urls;

        $filename = $dir_name.DIRECTORY_SEPARATOR.$image_name.".jpg";
        $path = $dir_name."/".$image_name.".jpg";

        if (file_exists($filename)){//检测是否存在
            echo "已存在".PHP_EOL;
            continue;
        }

        @$image_save = file_get_contents($urls);
        @file_put_contents($filename, $image_save);

        $sql="INSERT INTO image(image_path, image_title, url)VALUE ('{$path}','{$file_name}','{$urls}')";
        mysqli_query($connect,$sql);

        $image_number++;

        $log = fopen($dir_name.DIRECTORY_SEPARATOR.'log','ab+');
        fwrite($log,$urls.PHP_EOL);
        fclose($log);
    }
    unset($image_number);
}


$for_num = count($urls)-1;//获取数组总数
for ($i=0;$i<=$for_num;$i+=2){
    switch ($mode){
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
    switch ($mode) {
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

    $title  = str_replace(' ','',$title);
    $title  = str_replace('/','_',$title);

    if (!$argv[2]=0) {
        if (empty($argv[2])) { //超时

        } else {
            if ( time() - $serve_time<= $argv[2]) {
                echo "超过限定爬取时间";
                die();
            }
        }
    }
    image_save($image_urls,$dir_name,$title,$connect);

}
//
curl_close($ch);
echo "爬取完毕".PHP_EOL."用时".time()-$serve_time;