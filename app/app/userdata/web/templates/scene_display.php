<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '设备信息';
require_once $this->template('own/header');

//先获得相关的组
$userGroups = DB::get_all("SELECT id, name FROM {$_M[table]['userdata_group']} WHERE create_man_id = '{$loginId}'");
//通过组获得相关的device
$devices = array();
for($i = 0; $i < count($userGroups); $i++){
	$singleGroupDevices = DB::get_all("SELECT * FROM {$_M[table]['userdata_device']} WHERE group_id = '{$userGroups[$i]['id']}' ORDER BY id ASC");
	for($j = 0; $j < count($singleGroupDevices); $j++){
		//根据设备和onet_id的联系，将onet和device联系起来
		$onet = DB::get_one("SELECT * FROM {$_M[table]['userdata_onet']} WHERE id = '{$singleGroupDevices[$j]['onet_id']}'");
		array_push($singleGroupDevices[$j], $userGroups[$i]['name'], $singleGroupDevices[$j]['id'], $onet['onet_data_view'], $onet['onet_device_id']);
	}
	if($singleGroupDevices != null){
		// array_push($singleGroupDevices, "a");
    	$devices = array_merge($devices, $singleGroupDevices);
	}
	
}
//通过device获得相关的sensor
$sensors = array();
for($in = 0; $in < count($devices); $in++){
	
	$singleDeviceSensors = DB::get_all("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE device_id = '{$devices[$in]['id']}' ORDER BY id ASC");

	for($j = 0; $j < count($singleDeviceSensors); $j++){
		//通过sensor获得相应的type
		$type = DB::get_one("SELECT * FROM {$_M[table]['userdata_type']} WHERE id = '{$singleDeviceSensors[$j]['type_id']}'");
		array_push($singleDeviceSensors[$j], $devices[$in][1], $type['name'], $type['data_flow'], $type['img_path'], $devices[$in][3]);
	}

	if($singleDeviceSensors != null){
    	$sensors = array_merge($sensors, $singleDeviceSensors);
	}
}
/*********************************************************************
 * $devices[$i][0] 设备所在组号
 * $devices[$i][1] 设备所在组名
 * $devices[$i][2] 设备的id号
 * $devices[$i][3] 设备所对应的onet data-view
 *********************devices*****************************************
 *********************sensors*****************************************
 * $sensors[$i][0] 传感器所对应的设备id号 
 * $sensors[$i][1] 传感器对应的类型名称
 * $sensors[$i][2] 传感器对应的数据流
 * $sensors[$i][3] 传感器对应的类型图片路径
 * $sensors[$i][3] 传感器对应的所在设备onet_device_id
*********************************************************************/
$json_devices = json_encode($devices);
$json_sensors = json_encode($sensors);

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
var mydevices = {$json_devices};
var mysensors = {$json_sensors};

var scenes = $scenes_json._data;
$(document).ready(function (){
	//加载场景列表
	loadSceneList();
	//加载第一个场景
	loadScene(40);
	//加载场景对应的传感器
	setInterval("getSensors()",2000);

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
				// $('#sensor-in-scene'+ data._data[0]['id'] + ' span').text(100);
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




	
function getSensors(){
	for(i in mysensors){
		// alert(mysensors[0][4]);
		sensorId = mysensors[i]['id'];
		onetDeviceId = mysensors[i][4];
		onetDataflow = mysensors[i][2];
		getLastData(sensorId, onetDeviceId, onetDataflow);
	}
	
}
	
function getLastData(sensorId, onetDeviceId, onetDataflow){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getLastData',
		type:'POST',
		dataType:'json',
		data:{onetDeviceId:onetDeviceId, onetDataflow:onetDataflow},
		success:function(data){
			//先找出所有的传感器
			//通过名称将传感器和td的Id联系，从而更新数据
			if($('#sensor-in-scene'+ sensorId + ' span')){
				$('#sensor-in-scene'+ sensorId + ' span').text(data.datastreams[0].datapoints[0].value);
			}
			//alert(data.datastreams[0].datapoints[0].value);

		},
		error:function(){
			//alert('获取历史数据错误');
		}
	});
}

</script>
<!--
EOT;
require_once $this->template('own/footer');
?>
