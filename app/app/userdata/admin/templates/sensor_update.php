<?php
header("Content-Type: text/html; charset=utf-8");
require 'OneNetApi.php';

$apikey = 'Pj3ho=07dPOUkQuHVunpJoa5QnA=';
$apiurl = 'http://api.heclouds.com';

//创建api对象
$sm = new OneNetApi($apikey, $apiurl);


$device = "{
\"title\":\"$sensor_title\",
\"desc\":\"$sensor_desc\",
\"tags\":[
\"$sensor_tag\"
],
\"auth_info\":\"$auth_info\";
\"private\":true,
\"protocol\":\"MQTT\"
}";
//****************************************TODO*********************************************************
//这里的API有一个bug--不改变鉴权信息 无法改变设备信息，而在OneNet上不改变鉴权信息可以改变设备信息
//*****************************************************************************************************
$datastream = $sm->device_edit($device_id, $device);

$error_code = 0;
$error = '';
if (empty($datastream)) {
    //处理错误信息
    $error_code = $sm->error_no();
    $error = $sm->error();
}

//展现设备
var_dump($datastream);
return $datastream;