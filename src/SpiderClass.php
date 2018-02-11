<?php

namespace Spider;

class SpiderClass
{
    public function argvUrl($argv,$argvNumber,array $urls)
    {
        if (empty($argv[$argvNumber])){
            return $this->errorBack('Mode Null');
        }
        if (empty($urls[$argv[$argvNumber]])){
            return $this->errorBack('Mode Error');
            }
        return $urls[$argv[$argvNumber]];
    }//Url

    public function argvName($argv,$argvNumber,array $name)
    {
        return $name[$argv[$argvNumber]];
    }
    public function errorBack($message)
    {
        print_r($message);
        return false;
    }//错误返回

    public function save($fileUrl, $dirName,$fileName)
    {
        if ((bool)$save = file_get_contents($fileUrl)){
           if((bool)file_put_contents($save,$dirName.DIRECTORY_SEPARATOR.$fileName)){
               print_r($fileName);
           }else {
               print_r("Error");
           }
        }else{
            print_r("Error");
        }
    }//下载

    public function dir($name)
    {
        $dirName = date('n-d') . $name;
        if (!file_exists($dirName)) {
            mkdir($dirName, 0777);
        }
        return $dirName;
    } //创建文件夹

    protected $header = [
        "Host:bcy.net",
        "Connection: keep-alive",
        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
        "Upgrade-Insecure-Requests: 1",
        "DNT:1",
        "Accept-Language:zh-CN,zh;q=0.8",
        "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36"
    ];//Header

    public function curlWebsite($url)
    {
        if (empty($url)) {
            print_r("Url Null");
            return false;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        if (empty($result = curl_exec($curl))){
            print_r("Curl Website Error");
            return false;
        }
        curl_close($curl);
        return $result;
    }//Curl

 public function strReplaceAll(array $strReplaces,$string)
 {
     foreach ($strReplaces as $strReplace){
         $array_key=array_keys($strReplaces,$strReplace);
         $array_key=$array_key[0];
         $string= str_replace($strReplace,$array_key,$string);
     }
     return $string;
 }

 public function pregMatch(array $pregReplaces,$string)
 {
     foreach ($pregReplaces as $pregReplace){
         preg_match($pregReplace,$string,$result);
         $result=$result[0];
     }
     return $string;
 }
}

?>
