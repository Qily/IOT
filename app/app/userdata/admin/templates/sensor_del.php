<?php
header("Content-Type: text/html; charset=utf-8");
require 'OneNetApi.php';

$apikey = 'Pj3ho=07dPOUkQuHVunpJoa5QnA=';
$apiurl = 'http://api.heclouds.com';

//创建api对象
$sm = new OneNetApi($apikey, $apiurl);
 
$datastream = $sm->device_delete($device_id);

$error_code = 0;
$error = '';
if (empty($datastream)) {
    //处理错误信息
    $error_code = $sm->error_no();
    $error = $sm->error();
}

//展现设备
var_dump($datastream);