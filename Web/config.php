<?php
/**
 * Created by PhpStorm.
 * User: jinyichen
 * Date: 2017/9/25
 * Time: 下午4:30
 */

$DataHost='localhost'; //Host
$DataBase='image';  //Name
$DataUser='root'; //User
$DataPass='root'; //Password
$DataName='image'; //数据表名

$connect = mysqli_connect($DataHost,$DataUser,$DataPass,$DataBase)or die('连接数据库失败！');
mysqli_select_db($connect, $DataName) or die('选择数据库失败！');

