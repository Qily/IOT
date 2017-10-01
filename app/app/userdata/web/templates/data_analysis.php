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
		
// 		array_push($singleGroupDevices[$j], $userGroups[$i]['id'], $userGroups[$i]['name'], $singleGroupDevices[$j]['id'], $onet['onet_data_view'], $onet['onet_device_id']);
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
// 		array_push($singleDeviceSensors[$j], $devices[$in][1], $type['name'], $type['data_flow'], $type['img_path']);
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
// *********************************************************************/
// $json_devices = json_encode($devices);
// $json_sensors = json_encode($sensors);

echo <<<EOT
-->

<div class="col-md-8">
	<div class="row">
		
		<div class="col-md-12">
			<div class="row">
				<form>

					<div class="col-md-4">
						<div class="row">
							<div class="form-group" style="background-color:#CBA">
								
								<label>设备名称</label>
								<div class="controls">
									<select class="form-control" id="first-device-name"></select>
								</div>
								
								<label>开始日期</label>
								<input type="text" id="first-start-time" class="form-control calender"/>

								<label>结束日期</label>
								<input type="text" id="first-end-time" class="form-control calender"/>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="row">
							<div class="form-group" style="background-color:#ABC">
							
								<label>设备名称</label>
								<div class="controls">
									<select class="form-control" id="second-device-name"></select>
								</div>

								<label>开始日期</label>
								<input type="text" id="second-start-time" class="form-control calender"/>
								<label>
									结束日期
								</label>
								<input type="text" id="second-end-time" class="form-control calender"/>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="row">
							<div class="form-group" style="background-color:#999999">
							
								<label>设备名称</label>
								<div class="controls">
									<select class="form-control" id="third-device-name"></select>
								</div>		

								<label>开始日期</label>
								<input type="text" id="third-start-time" class="form-control calender"/>
								<label>结束日期</label>
								<input type="text" id="third-end-time" class="form-control calender"/>
							</div>
						</div>
					</div>

					<input type="button" class="btn btn-primary form-control" onclick="plotAllCharts()" value="开始分析" />
				</form>
				<div class="col-md-7">
					<div class="row">
						<div id="charts">
							<div style="height: 20px"></div>							
						</div>
					</div>
				</div>
				<div class="col-md-5">
					<div class="row">
						<div class='sensor-charts'>
						</div>
					</div>
				</div>

			</div>
		</div><!-- col-md-12 -->	
	</div>
</div><!-- col-md-8 1+2+8(9+3)+1 -->

<div class="col-md-1"></div>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>



<script type="text/javascript">
var site = '{$_M['url'][site]}' + 'data/request_page.php?n=userdata&c=userdata&';
$(document).ready(function(){
	getAllDeviceAndSensor();
	loadSelectList();
	$.datetimepicker.setLocale('ch');
	$(".calender").datetimepicker({
		//value:'2017-09-08 10:54',
		format:'Y-m-d H:i:s',
		minDate:'2017/08/01',
		step:20
	});
});




</script>

<!--
EOT;
require_once $this->template('own/footer');
?>
