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
		<div class="col-md-2" id ="scenes-list">
			<p><input type = "button" class = "btn btn-success form-control" value="新建场景" name="save-scene-set" onclick='createScene()'/></p>
			<p><input type = "button" class = "btn btn-warning form-control" value="修改场景" name="save-scene-set" onclick='createScene()'/></p>
			<p><a class = "btn btn-danger form-control" href="javascript:if(confirm('确定删除？'))location='{$urlUserdata}a=dosceneset'">删除场景</a></p>
		</div>
	</div>
</div>
								
<div class="col-md-1"></div>

<script type="text/javascript">
var scenes = $scenes_json._data;
$(document).ready(function (){
	//加载场景列表
	loadSceneList();
	//加载第一个场景
	loadScene(40);
	//加载场景对应的传感器
	// setInterval("getSensors()",2000);

});



//sceneId表示场景的id
function loadScene(sceneId){
	loadImg(sceneId);
	//加载场景对应的传感器
	//方法是通过图片路径找出对应的场景号
	//然后通过场景号找出对应的传感器id，name,rela_width, rela_height,
	//实现场景的再现
	loadDevices();

	function loadImg(sceneId){
		for(i in scenes){
			if(scenes[i]['id'] == sceneId){
				var imgPath = $scenes_json._data[i].img_path;
			}
		}
		//为了获取原图像的宽度和高度
		var originImg = "<img id='originImg' hidden='true' src=" + imgPath +"/>";
		$("#scene").append(originImg);
		var img = new Image();
		img.src = $("#originImg").attr("src");
		var imgDivWidth = $(document).width() * 0.45;
		var imgDivHeight = imgDivWidth * (img.height/ img.width);

		$("#scene-child").width(imgDivWidth).height(imgDivHeight);
		$("#scene-child").attr('src', imgPath);
	}

	function loadDevices(){
		//根据sceneId获取相应的device
		$.ajax({
			url:'{$urlUserdata}a=dogetinfo&action=getDevicesBySceneId&sceneId='+sceneId,
			dataType:'json',
			type:'POST',
			success:function(data){
				getAllDeviceOnSingle(data._data);
			},
			error:function(){
				alert("获取场景对应的传感器出错！");
			}
		});
	}

	function getAllDeviceOnSingle(devices){
		$("#scene").children(".device").remove();
		for(i in devices){
			$.ajax({
				url:'{$urlUserdata}a=dogetinfo&action=getDeviceById&deviceId='+devices[i]['device_id'],
				dataType:'json',
				type:'POST',
				async:false,
				success:function(data){
					deviceOnScene(data, devices[i]['device_id'], devices[i]['rela_width'], devices[i]['rela_height']);
				},
				error:function(){
					alert("获取场景对应的传感器出错！");
				}
			}).responseText;
		}
	}

	function deviceOnScene(deviceInfo, deviceId, relaWidth, relaHeight){
		//这里要获得设备的信息（名称）
		var sleft = $("#scene-child").width() * relaWidth;
		var stop = $("#scene-child").height() * relaHeight;
		var groupImgId = deviceInfo['groupId'] % 4 + 1;
		var html = "<div class='device' id='device-in-scene"+ deviceId +"'><img class='left-img' src='"+ '{$img}' + groupImgId +".png'/>"+ deviceInfo['name'] +"</div>";
		$('#scene').append(html);
		$("#device-in-scene"+deviceId).css({'position':'absolute', 'left':sleft+'px', 'top':stop+'px'});
		$("#device-in-scene"+deviceId).children(".sensor").remove();
		getSensorsUnderDevice(deviceId);
	}

	function getSensorsUnderDevice(deviceId){
		var html = "";
		var typeImgPath = "";
		$.ajax({
			url:'{$urlUserdata}a=dogetinfo&action=getSensorByDeviceId&deviceId='+deviceId,
			dataType:'json',
			type:'POST',
			async:false,
			success:function(data){
				// alert(data);
				// var typeImgPath = data._data[0][0];
				var sensors = data._data;
				for(i in sensors){
					var typeImgPath = data._data[i][0];
					html += "<div class='sensor' id='sensor-in-scene"+ data._data[i]['id'] +"'><img class='left-img left-padding-10' src='"+ '{$_M[url][own]}' + typeImgPath +"'/>"+ "<span></span>" +"</div>";
				}
				$("#device-in-scene"+deviceId).append(html);
				// alert($('#sensor-in-scene'+ data._data[0]['id'] + ' span'));

				//TODO
				$('#sensor-in-scene'+ data._data[0]['id'] + ' span').text(100);
			},
			error:function(){
				alert("获取场景对应的传感器出错！");
			}
		}).responseText;
	}
}

function loadSceneList(){
	//加载场景列表
	var html = "";
	for(var i = 0; i < scenes.length; i++){
		html += "<div class='scenes-list'><a href='javascript:void(0);' onclick='loadScene("+ scenes[i]['id'] +")'><img class='left-img' src={$imgScene}>"+ scenes[i].name +"</img></a></div>";
	}
	$("#scenes-list").append(html);
}
function createScene(){
	location.href="{$urlUserdata}a=dosceneset";
}




// function getSensors(){
// 	var sensorInSceneId="";
// 	var sensorName = "";
// 	//alert($("#sensors-count").text());
// 	for(var i = 0; i < $("#sensors-count").text(); i++){
// 		sensorName = $("#sensor-in-scene"+i).text().split("val:",1);
// 		// alert(sensorName);
// 		sensorInSceneId = "sensor-in-scene-val"+i;
// 		getLastData(sensorName, sensorInSceneId);
// 		// alert("#"+sensorInSceneId);
		
// 	}
// }

// function getLastData(sensorName, sensorInSceneId){
// 	// alert(sensorName);
// 	// sensorName = "SIM900A_Test1";

// 	$.ajax({
// 		url:'{$urlUserdata}a=dogetinfo&action=getLastData&sensorName='+sensorName,
// 		type:'POST',
// 		dataType:'json',
// 		// data:{sensorName:sensorName},
// 		// async:false,
// 		success:function(data){
// 			//先找出所有的传感器
// 			//通过名称将传感器和td的Id联系，从而更新数据

// 			$("#"+sensorInSceneId).text("val:"+data.datastreams[0].datapoints[0].value);
// 		},
// 		error:function(){
// 			//alert('获取历史数据错误');
// 			//return;
// 		}
// 	});
// }

</script>
<!--
EOT;
require_once $this->template('own/footer');
?>
