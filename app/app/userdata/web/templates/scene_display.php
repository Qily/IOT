<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
require_once $this->template('own/header');

// //先获得相关的组
// $userGroups = DB::get_all("SELECT id, name FROM {$_M[table]['userdata_group']} WHERE create_man_id = '{$loginId}'");
// //通过组获得相关的device
// $devices = array();
// for($i = 0; $i < count($userGroups); $i++){
// 	$singleGroupDevices = DB::get_all("SELECT * FROM {$_M[table]['userdata_device']} WHERE group_id = '{$userGroups[$i]['id']}' ORDER BY id ASC");
// 	for($j = 0; $j < count($singleGroupDevices); $j++){
// 		//根据设备和onet_id的联系，将onet和device联系起来
// 		$onet = DB::get_one("SELECT * FROM {$_M[table]['userdata_onet']} WHERE device_id = '{$singleGroupDevices[$j]['id']}'");

// 		array_push($singleGroupDevices[$j], $userGroups[$i]['name'], $singleGroupDevices[$j]['id'], $onet['onet_data_view'], $onet['onet_device_id']);
// 	}
// 	if($singleGroupDevices != null){
// 		// array_push($singleGroupDevices, "a");
//     	$devices = array_merge($devices, $singleGroupDevices);
// 	}
	
// }
// //通过device获得相关的sensor
// $sensors = array();
// for($in = 0; $in < count($devices); $in++){
	
// 	$singleDeviceSensors = DB::get_all("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE device_id = '{$devices[$in]['id']}' ORDER BY id ASC");

// 	for($j = 0; $j < count($singleDeviceSensors); $j++){
// 		//通过sensor获得相应的type
// 		$type = DB::get_one("SELECT * FROM {$_M[table]['userdata_type']} WHERE id = '{$singleDeviceSensors[$j]['type_id']}'");
// 		array_push($singleDeviceSensors[$j], $devices[$in][1], $type['name'], $type['data_flow'], $type['img_path'], $devices[$in][3]);
// 	}

// 	if($singleDeviceSensors != null){
//     	$sensors = array_merge($sensors, $singleDeviceSensors);
// 	}
// }
// /*********************************************************************
//  * $devices[$i][0] 设备所在组号
//  * $devices[$i][1] 设备所在组名
//  * $devices[$i][2] 设备的id号
//  * $devices[$i][3] 设备所对应的onet data-view
//  *********************devices*****************************************
//  *********************sensors*****************************************
//  * $sensors[$i][0] 传感器所对应的设备id号 
//  * $sensors[$i][1] 传感器对应的类型名称
//  * $sensors[$i][2] 传感器对应的数据流
//  * $sensors[$i][3] 传感器对应的类型图片路径
//  * $sensors[$i][3] 传感器对应的所在设备onet_device_id
// *********************************************************************/
// $json_devices = json_encode($devices);
// $json_sensors = json_encode($sensors);

// //TODO:对比输出
// $scenes = DB::get_all("select * from {$_M[table]['userdata_scene']} where create_man_id = '{$loginId}' ORDER BY id ASC");

// $obj->_data = $scenes;
// $scenes_json = json_encode($obj);

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
			<p><a class = "btn btn-danger form-control" href="javascript:if(confirm('确定删除？'))location='#'">删除场景</a></p>
		</div>
	</div>
</div>
								
<div class="col-md-1"></div>

<script type="text/javascript">

var site = '{$_M['url'][site]}' + 'data/request_page.php?n=userdata&c=userdata&';
var imgScene = '{$_M[url][own]}' + 'img/scene.png';
var imgSensor = '{$_M[url][own]}' + 'img/';
var urlOwn = '{$_M[url][own]}';
$(document).ready(function (){
	getAllDeviceAndSensor();
	getScene();

	//加载场景列表
	loadSceneList();
	//加载第一个场景
	loadScene(firstSceneId);
	//加载场景对应的传感器
	setInterval("getSensors()",2000);

});
</script>
<!--
EOT;
require_once $this->template('own/footer');
?>
