<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

header("Content-Type: text/html;charset=utf-8");

$loginId = get_met_cookie('metinfo_member_id');

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
$addImg = $_M[url][own]."web/templates/files/addImg.png";
$imgScene = $_M[url][own]."img/scene.png";
$imgUser = $_M[url][own]."img/user_1.png";
$imgLogo = $_M[url][own]."img/logo.png";
$imgBottom = $_M[url][own]."img/bottom.png";
$imgExtend = $_M[url][own]."img/extend.png";
$imgMerge = $_M[url][own]."img/merge.png";
$img = $_M[url][own]."img/";


$data_analysis_page = $_M[url][own]."web/templates/data_analysis.php";
$devices_page= $_M['url'][site]."/data/index.php";

$bootstrap_min_css = $_M[url][own]."web/templates/css/bootstrap.min.css";
$style_css = $_M[url][own]."web/templates/css/style.css";
$jquery_datetimepicker_css = $_M[url][own]."web/templates/css/jquery.datetimepicker.css";

$bootstrap_min_js = $_M[url][own]."web/templates/js/bootstrap.min.js";
$jquery_min_js = $_M[url][own]."web/templates/js/jquery.min.js";
$jquery_datetimepicker_full_min_js = $_M[url][own]."web/templates/js/jquery.datetimepicker.full.min.js";
$easydrag = $_M[url][own]."web/templates/js/jquery.easyDrag.js";
$js_own = $_M[url][own]."web/templates/js/own.js";
$js_pin = $_M[url][own]."web/templates/js/jquery.pin.js";

$urlUserdata = $_M['url'][site]."data/request_page.php?n=userdata&c=userdata&";
$urlUser = $_M['url'][site]."member";

$username = get_met_cookie(metinfo_member_name);


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
	<link href="{$jquery_datetimepicker_css}" rel="stylesheet">
	<script src="{$jquery_min_js}"></script>

	
</head>
<body>

<script type="text/javascript">
	function navEvent(url){
		location.href = url;
	}
		

	function toPersonalInfo(){
		location.href = '{$urlUser}';
	}
	$(document).ready(function(){
		$("#username").text('{$username}');
	});
</script>

<div class="container-fluid clearfix">
	<div class="row">
	<img id='top-img' alt="Top Image" src="{$imgTop}" style="width:100%">

	<div class="col-md-offset-10 superlink" id="login-user" onclick="toPersonalInfo()">

		<div><img src="{$imgUser}"/></div>&nbsp;&nbsp;
		<div><h4 id="username"></h4></div>

	</div>
	<a class="superlink" href="{$_M[url][site]}"><img class="col-md-offset-1" id="logo" src="{$imgLogo}"/>
	</img></a>
	
	</div>
	<div class="row">
		<div class="col-md-1 mainbody"></div>
		<div class="col-md-2">
			<ul class="nav">
				<li class="nav-first-layer">
					数据监控
				</li>
				<li class="nav-second-layer" id="nav1"><a href='javascript:void(0);' onclick="navEvent('{$urlUserdata}a=doindex')"><img src="{$imgdevice}"/>&nbsp&nbsp设备列表</a></li>

				<li class="nav-second-layer" id="nav2"><a href='javascript:void(0);' onclick="navEvent('{$urlUserdata}a=doscenedisplay')"><img src="{$imgSceneDisplay}"/>&nbsp;&nbsp场景展示</a></li>

				<li class="nav-second-layer" id="nav3"><a href='javascript:void(0);' onclick="navEvent('{$urlUserdata}a=doexception')"><img src="{$imgexception}"/>&nbsp;&nbsp异常数据</a></li>

				<li class="nav-first-layer">
					设备管理
				</li>
					<li class="nav-second-layer" id="nav4"><a href='javascript:void(0);' onclick="navEvent('{$urlUserdata}a=docreategroup')"><img src="{$imgGroup}"/>&nbsp&nbsp创建组别</a></li>
				
					<li class="nav-second-layer" id="nav5"><a href='javascript:void(0);' onclick="navEvent('{$urlUserdata}a=doadddevice')"><img src="{$imgAddSensor}"/>&nbsp;&nbsp创建设备</a></li>

				<li class="nav-first-layer">
					数据管理
				</li>
					<li class="nav-second-layer" id="nav6"><a href='javascript:void(0);' onclick="navEvent('{$urlUserdata}a=doanalysis')"><img src="{$imgdataanalysis}"/>&nbsp;&nbsp数据分析</a></li>

					<!--<li class="nav-second-layer"><a href="{$_M[url][site]}"><img src="{$imghome}"/>&nbsp&nbsp返回首页</a></li>-->

			</ul>
		</div>
<!--
EOT;
?>
