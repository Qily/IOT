<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

header("Content-Type: text/html;charset=utf-8"); 

$imgdetail = $_M[url][own]."img/detail.png";
$imgtemper = $_M[url][own]."img/temper.png";
$imghumi = $_M[url][own]."img/humi.png";
$imgexception = $_M[url][own]."img/exception.png";
$imgdevice = $_M[url][own]."img/device.png";
$imgdataanalysis = $_M[url][own]."img/dataAnalysis.png";
$imghome = $_M[url][own]."img/home.png";
$imgTop = $_M[url][own]."img/top.png";
$imgGroup = $_M[url][own]."img/group.png";
$imgGroupUsers = $_M[url][own]."img/groupUsers.png";
$imgAddSensor = $_M[url][own]."img/addSensor.png";
$imgSceneSet = $_M[url][own]."img/sceneset.png";
$imgSceneDisplay = $_M[url][own]."img/scenedisplay.png";
$imgAdd = $_M[url][own]."img/add.png";

$data_analysis_page = $_M[url][own]."web/templates/data_analysis.php";
$group_opera = "http://ttggcc.get.vip/data/group_opera.php";
$devices_page= "http://ttggcc.get.vip/data/index.php";
$bootstrap_min_css = $_M[url][own]."web/templates/css/bootstrap.min.css";
$style_css = $_M[url][own]."web/templates/css/style.css";

$bootstrap_min_js = $_M[url][own]."web/templates/css/bootstrap.min.js";
$jquery_min_js = $_M[url][own]."web/templates/css/jquery.min.js";
$scripts_js = $_M[url][own]."web/templates/css/scripts.js";



$urlUserdata = $_M['url'][site]."data/request_page.php?n=userdata&c=userdata&";

echo <<<EOT
-->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="设备相关">
    <!--<meta http-equiv=refresh content="10">-->

    <title>{$title}</title>
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="{$bootstrap_min_css}" rel="stylesheet"> 
    <link href="{$style_css}" rel="stylesheet">
</head>
<body>

	
	<div class="container-fluid">
		<div class="row">
		<img alt="Top Image" src="{$imgTop}" style="width:100%">
		<div class="col-md-12" style="height:50px"></div>
		</div>
		<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-2">
				<ul class="nav nav-stacked">
					<li><a href="{$_M[url][site]}"><img src="{$imghome}"/>&nbsp&nbsp返回首页</a></li>
					
					<li><a href="{$urlUserdata}a=doindex"><img src="{$imgdevice}"/>&nbsp&nbsp设备列表</a></li>

                    <li><a href="{$urlUserdata}a=dogroupopera"><img src="{$imgGroup}"/>&nbsp&nbsp设备组操作</a></li>

					
                    <li><a href="{$urlUserdata}a=doaddsensor"><img src="{$imgAddSensor}"/>&nbsp;&nbsp创建设备</a></li>
                    <li><a href="{$urlUserdata}a=doaddgroupuser"><img src="{$imgGroupUsers}"/>&nbsp;&nbsp添加组成员</a></li>


                    <li><a href="{$urlUserdata}a=doexception"><img src="{$imgexception}"/>&nbsp;&nbsp异常数据</a></li>
					<li><a href="{$urlUserdata}a=doanalysis"><img src="{$imgdataanalysis}"/>&nbsp;&nbsp数据分析</a></li>

                    <li><a href="{$urlUserdata}a=dosceneset"><img src="{$imgSceneSet}"/>&nbsp;&nbsp场景设置</a><li>

                    <li><a href="{$urlUserdata}a=doscenedisplay"><img src="{$imgSceneDisplay}"/>&nbsp;&nbsp场景展示</a><li>
				</ul>
			</div>
<!--
EOT;
?>
