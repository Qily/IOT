<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '场景展示';
require_once $this->template('own/header');

$loginId = get_met_cookie('metinfo_member_id');
//TODO:对比输出
$scenes = DB::get_all("select * from {$_M[table]['userdata_scene']} where create_man_id = '{$loginId}' ORDER BY id ASC");
$obj->_data = $scenes;
$scenes_json = json_encode($obj);

$bootstrap_min_js = $_M[url][own]."web/templates/js/bootstrap.min.js";
$jquery_min_js = $_M[url][own]."web/templates/js/jquery.min.js";
$scripts_js = $_M[url][own]."web/templates/js/scripts.js";

$imgScene = $_M[url][own]."img/scene.png";



echo <<<EOT
-->

	
	


<div class="col-md-8">
	<div class="col-md-12">
		<div class="col-md-10" id = "scene">
			<img id="scene-child"/>
		</div>					
		<div class="col-md-2" id = "scenes-list">

		</div>
	</div>
</div>
								
<div class="col-md-1"></div>

</div>
</div>

<script src="{$jquery_min_js}"></script>
<script src="{$bootstrap_min_js}"></script>
<script src="{$scripts_js}"></script>
<script >
$(document).ready(function (){
	//加载场景列表
	var html = "";
	for(var i = 0; i < $scenes_json._data.length; i++){
		html += "<div><a href='javascript:void(0);' onclick='loadScene("+ i +")'><img src={$imgScene}>"+ $scenes_json._data[i].name +"</img></a></div>";
	}
	$("#scenes-list").append(html);
	
	//加载第一个场景
	loadScene(0);
	//加载场景对应的传感器

});

function loadScene(index){
	var imgPath = $scenes_json._data[index].img_path;
	//为了获取原图像的宽度和高度
	var originImg = "<img id='originImg' hidden='true' src=" + imgPath +" title='ccc' alt='ccc'/>";
	$("#scene").append(originImg);
	var img = new Image();
	img.src = $("#originImg").attr("src");
	var imgDivWidth = $(document).width() * 0.45;
	var imgDivHeight = imgDivWidth * (img.height/ img.width);
	$("#scene-child").attr('src', imgPath);
	$("#scene-child").width(imgDivWidth).height(imgDivHeight);

	//加载场景对应的传感器
	//方法是通过图片路径找出对应的场景号
	//然后通过场景号找出对应的传感器id，name,rela_width, rela_height,
	//实现场景的再现
	loadSensors(imgPath);
}

function loadSensors(imgPath){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getSceneByImgPath&imgPath='+imgPath,
		type:'POST',
		success:function(data){
			//data中记录的是sceneId
			//通过sceneId找出所有的传感器，以及对应的信息
			getAllSensors(data);
		},
		error:function(){
			alert("获取场景id出错！");
		}
	});
}

function getAllSensors(sceneId){
	$("#scene").children(".sensors").remove();
	
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getAllSensors&sceneId='+sceneId,
		dataType:'json',
		type:'POST',
		success:function(data){
			//alert(data._data[0].id);
			for(var i = 0; i < data._count; i++){
				getSensorById(data._data[i].sensor_id, i, data._data[i].rela_width, data._data[i].rela_height);
			}
			
		},
		error:function(){
			alert("获取场景对应的传感器出错！");
		}
	});
}

function getSensorById(sensorId, index, relaWidth, relaHeight){
	var html = "";
	var sensorIcon = '{$imgtemper}';
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getSensorById&sensorId='+sensorId,
		type:'json',
		dataType:'json',
		async: false,
		success:function(data){
			//alert(data.tag)
			if(data.tag == 'humi'){
				sensorIcon = '{$imghumi}';
			} else if(data.tag == 'temper'){
				sensorIcon = '{$imgtemper}';
			}
			// var sleft = $("#scene-child").offset().left + $("#scene-child").width() * relaWidth;
			// var stop = $("#scene-child").offset().top + $("#scene-child").height() * relaHeight;
			var sleft = $("#scene-child").width() * relaWidth;
			var stop = $("#scene-child").height() * relaHeight;
			html = "<div class='sensors' id=sensor-in-scene"+ index +"><img src="+ sensorIcon +" />"+ data.name +"</div>";
			//alert(html);
			//计算坐标绝对值
			
			//alert(sleft +"***"+stop)
			//alert($("#scene-child").width() *relaWidth);

			//alert(html);
			$('#scene').append(html);
			//alert($("#sensor-in-scene0").text());
			$("#sensor-in-scene"+index).css({'position':'absolute', 'left':sleft+'px', 'top':stop+'px'});
		},
		error:function(){

		}
	}).responseText;
}

</script>
<!--
require_once $this->template('own/footer');
EOT;
?>
