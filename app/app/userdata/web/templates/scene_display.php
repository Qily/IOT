<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '场景展示';
require_once $this->template('own/header');

$loginId = get_met_cookie('metinfo_member_id');
//TODO:对比输出
$scenes = DB::get_all("select * from {$_M[table]['userdata_scene']} where create_man_id = '{$loginId}' ORDER BY id ASC");
$obj->_data = $scenes;
$scenes_json = json_encode($obj);
echo <<<EOT
-->

	
	


<div class="col-md-8">
	<div class="col-md-12">
		<div class="col-md-10" id = "scene">
			<img id="scene-child"/>
		</div>					
		<div class="col-md-2" id = "scenes-list">
			<input type = "button" class = "btn btn-success col-md-12" value="新建场景" name="save-scene-set" onclick='createScene()'/>
			<input type = "button" class = "btn btn-warning col-md-6" value="修改场景" name="save-scene-set" onclick='createScene()'/>
			<a class = "btn btn-danger col-md-6" href="javascript:if(confirm('确定删除？'))location='{$urlUserdata}a=dosceneset'">删除场景</a>
		</div>
	</div>
</div>
								
<div class="col-md-1"></div>


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
	setInterval("getSensors()",2000);

});

function createScene(){
	location.href="{$urlUserdata}a=dosceneset";
}

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
	$("#scene").children(".sensor-id").remove();
	if($('#sensors-count')){
		$('#sensors-count').remove();
	}
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getAllSensors&sceneId='+sceneId,
		dataType:'json',
		type:'POST',
		async:false,
		success:function(data){
			//alert(data._data[0].id);
			var i = 0
			for(i = 0; i < data._count; i++){
				getSensorById(data._data[i].sensor_id, i, data._data[i].rela_width, data._data[i].rela_height);
			}
			var htmlCount = "<div hidden='true' id='sensors-count'>"+i+"</div>";
			$("#scenes-list").append(htmlCount);
			
		},
		error:function(){
			alert("获取场景对应的传感器出错！");
		}
	}).responseText;
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

			html += "<div class='sensors' id=sensor-in-scene"+ index +"><img src="+ sensorIcon +" />"+ data.name +"</div>";
			var html1 = "<div class = 'sensor-id' id=sensor-in-scene-val"+index+">val:0</div>";
			$('#scene').append(html);
			$("#sensor-in-scene"+index).append(html1);
			//$('#scene').append(html1);
			$("#sensor-in-scene"+index).css({'position':'absolute', 'left':sleft+'px', 'top':stop+'px'});
			//$("#sensor-in-scene-val"+index).css({'position':'absolute', 'left':sleft+'px', 'top':stop+'px'});
		},
		error:function(){
			alert("获取单个设备信息出错！");
		}
	}).responseText;
}

function getSensors(){
	var sensorInSceneId="";
	var sensorName = "";
	//alert($("#sensors-count").text());
	for(var i = 0; i < $("#sensors-count").text(); i++){
		sensorName = $("#sensor-in-scene"+i).text().split("val:",1);
		// alert(sensorName);
		sensorInSceneId = "sensor-in-scene-val"+i;
		getLastData(sensorName, sensorInSceneId);
		// alert("#"+sensorInSceneId);
		
	}
}

function getLastData(sensorName, sensorInSceneId){
	// alert(sensorName);
	// sensorName = "SIM900A_Test1";

	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getLastData&sensorName='+sensorName,
		type:'POST',
		dataType:'json',
		// data:{sensorName:sensorName},
		// async:false,
		success:function(data){
			//先找出所有的传感器
			//通过名称将传感器和td的Id联系，从而更新数据

			$("#"+sensorInSceneId).text("val:"+data.datastreams[0].datapoints[0].value);
		},
		error:function(){
			//alert('获取历史数据错误');
			//return;
		}
	});
}

</script>
<!--
EOT;
require_once $this->template('own/footer');
?>
