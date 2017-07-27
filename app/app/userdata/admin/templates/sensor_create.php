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

$datastream = $sm->device_add($device);

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