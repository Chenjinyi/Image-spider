<?php
/**
 * Created by PhpStorm.
 * User: jinyichen
 * Date: 2017/9/24
 * Time: 下午3:03
 */

date_default_timezone_set('PRC'); //设置时区

$url="https://bcy.net/novel/toppost100";
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

//获取全部URL
preg_match_all('/detail\/\d{1,9}\/\d{1,9}/',$result,$urls); //urls
$urls = $urls[0];//优化数组
array_unique($urls);

$dir_name = "Resource".DIRECTORY_SEPARATOR.date('n-d') . '-' ."Novel";
if(!file_exists($dir_name)){
    mkdir($dir_name,0777);
}//创建文件夹


$for_num = count($urls)-1;//获取数组总数
for ($i=0;$i<=$for_num;$i+=2){
    $new_url="https://bcy.net/novel/".$urls[$i];
    curl_setopt($ch,CURLOPT_URL,$new_url);
    $font=curl_exec($ch);//抓取页面

    preg_match('/name.*\/p/',$font,$novel);
    if (empty($novel)){
        echo "Error无法获取内容".PHP_EOL.'文章链接'.$new_url.PHP_EOL;
        continue;
    }

    $novel = $novel[0];
    $novel = str_replace("<p>",'',$novel);
    $novel = str_replace("</p>",PHP_EOL,$novel);
    $novel = str_replace("<br>",PHP_EOL,$novel);
    $novel = str_replace(">",'',$novel);
    $novel = str_replace("<p name=",'',$novel);
    $novel = str_replace("name=",'',$novel);
    $novel = preg_replace('/"\S{6}"/','',$novel);
    $novel = preg_replace('/"\S{5}"/','',$novel);

    preg_match('/e>.*\|/',$font,$title);
    $title=$title[0];
    $title = str_replace("e>",'',$title);
    $title = str_replace('|','',$title);
    $title = str_replace(' ','',$title);

    print_r($title.PHP_EOL);

    $file_name = $dir_name.DIRECTORY_SEPARATOR.$title.'.txt';
    if (file_exists($file_name)){//检测是否存在
        echo "已存在".PHP_EOL;
        continue;
    }
    @$save = fopen($file_name,'wb+');
    @fwrite($save,$novel);
    @fclose($save);
}
//
print_r('爬取完毕');
curl_close($ch);
