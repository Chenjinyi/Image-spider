<?php
/**
 * Created by PhpStorm.
 * User: jinyichen
 * Date: 2018/5/30
 * Time: 下午5:55
 */

class SpiderCore
{
//Curl操作
    /**
     * @var array 设计Header头
     */
    public $httpHeader=array(
        "Host:bcy.net",
        "Connection: keep-alive",
        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
        "Upgrade-Insecure-Requests: 1",
        "DNT:1",
        "Accept-Language:zh-CN,zh;q=0.8",
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36"
    );

    /**
     * @param $url URL
     * @param $cookie 设置cookie
     * @return mixed 返回html
     */
    public function get_content($url, $cookie)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//不返回数据
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookie
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->httpHeader
        ); // 设置浏览器的特定header
        $rs = curl_exec($ch); //执行cURL抓取页面内容
        curl_close($ch);
        return $rs;
    }

    /**
     * 登陆操作
     * @param $url
     * @param $cookie
     * @param $post
     */
    public function login_post($url, $cookie, $post,$oldCookie)
    {
        $curl = curl_init();//初始化curl模块
        curl_setopt($curl, CURLOPT_URL, $url);//登录提交的地址
        curl_setopt($curl, CURLOPT_HEADER, 0);//是否显示头信息
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//不返回数据
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中
        curl_setopt($curl, CURLOPT_POST, 1);//post方式提交
//        curl_setopt($curl, CURLOPT_COOKIEFILE, $oldCookie); //读取cookie
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));//要提交的信息
        $result = curl_exec($curl);//执行cURL
        print_r($result);
        curl_close($curl);//关闭cURL资源，并且释放系统资源

    }

    /**
     * 获取Cookie get方式
     * @param $url URL
     * @param $cookie 保存名称
     */
    public function CurlBcyToken($url, $cookie)
    {
        $ch = curl_init();  //初始化一个cURL会话
        curl_setopt($ch, CURLOPT_URL, $url);//设置需要获取的 URL 地址
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->httpHeader); // 设置浏览器的特定header
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); //设置Cookie信息保存在指定的文件中
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//不返回数据

        if (empty($result = curl_exec($ch))) {
            print_r('无法连接半次元');
            die();
        };//执行一个cURL会话
        curl_close($ch);
    }

//文件操作

    /**
     * @param $filename 文件名
     * @param $serialize 是否反序列号
     * @return bool|mixed|string 返回信息，文件不存在返回false
     */
    public function getFile($filename, $serialize)
    {
        if (!file_exists($filename)) {
            return false;
        }
        if ($serialize) {
            $file = file_get_contents($filename);
            $result = unserialize($file);
            return $result;
        } else {
            $result = file_get_contents($filename);
            return $result;
        }
    }

    /**
     * 序列化写入信息
     * @param $filename 文件名
     * @param $content 写入信息
     * @param $end 追加文件
     */
    public function serializeFile($filename, $content, $end)
    {
        $text = serialize($content);
        newFile($filename, $text, $end);
    }

    /**
     * 写入信息
     * @param $filename 文件名
     * @param $content 写入信息
     * @param $end 追加文件
     */
    public function newFile($filename, $content, $end)
    {
        if (!file_exists($filename)) { //判断文件是否存在
            touch($filename);
        }
        if ($end) {
            file_put_contents($filename, $content, FILE_APPEND); //不覆盖写入
        } else {
            file_put_contents($filename, $content); //覆盖写入
        }
    }
}