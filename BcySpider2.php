<?php
/**
 * Created by PhpStorm.
 * User: jinyichen
 * Date: 2018/5/30
 * Time: 下午4:43
 */

//$init_curl=curl_init();

date_default_timezone_set('PRC'); //设置时区

include_once "SpiderCore.php";


$Core= new SpiderCore();
//登陆操作
if (!file_exists('cookie')) {
    //获取用户的账户密码
    echo "请输入半次元用户名：";
    $username = fgets(STDIN);
    echo "请输入半次元密码：";
    $password = fgets(STDIN);
//    获取csrf_token
    $Core->CurlBcyToken("https://bcy.net/","token");
    $tokenFile=$Core->getFile('token',false);
    $token = strstr($tokenFile,"_csrf_token");
    $token = substr($token,12,33);
//    print_r($token);
    $form_data =
        [
            'email' => $username,
            'password' => $password,
            'remember' => '1',
            '_csrf_token' =>$token
      ];
    print_r($form_data);
    $Core->login_post("https://bcy.net/public/dologin", "cookie", $form_data,"token");
}
//$result = get_content("https://bcy.net/",'cookie');
//print_r($result);
