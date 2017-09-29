<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '数据分析';
require_once $this->template('own/header');


//先获得相关的组
$userGroups = DB::get_all("SELECT id, name FROM {$_M[table]['userdata_group']} WHERE create_man_id = '{$loginId}'");
//通过组获得相关的device
$devices = array();
for($i = 0; $i < count($userGroups); $i++){
	$singleGroupDevices = DB::get_all("SELECT * FROM {$_M[table]['userdata_device']} WHERE group_id = '{$userGroups[$i]['id']}' ORDER BY id ASC");
	for($j = 0; $j < count($singleGroupDevices); $j++){
		//根据设备和onet_id的联系，将onet和device联系起来
		$onet = DB::get_one("SELECT * FROM {$_M[table]['userdata_onet']} WHERE device_id = '{$singleGroupDevices[$j]['id']}'");
		
		array_push($singleGroupDevices[$j], $userGroups[$i]['id'], $userGroups[$i]['name'], $singleGroupDevices[$j]['id'], $onet['onet_data_view'], $onet['onet_device_id']);
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
		array_push($singleDeviceSensors[$j], $devices[$in][1], $type['name'], $type['data_flow'], $type['img_path']);
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
*********************************************************************/
$json_devices = json_encode($devices);
$json_sensors = json_encode($sensors);

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
var devices = $json_devices;
var sensors = $json_sensors;
$(document).ready(function(){
	loadSelectList();
	$.datetimepicker.setLocale('ch');
	$(".calender").datetimepicker({
		//value:'2017-09-08 10:54',
		format:'Y-m-d H:i:s',
		minDate:'2017/08/01',
		step:20
	});
});

function loadSelectList(){
	var html = "<option>不启用</option>";
	for(i in devices){
		html += "<option>"+ devices[i].name +"</option>";
	}
	$("select").append(html);
}

//******************************************/
//*          响应开始分析事件
//******************************************/
function plotAllCharts(){
	getParam($("#first-device-name"), $('#first-start-time'), $('#first-end-time'), 1);
	getParam($("#second-device-name"), $('#second-start-time'), $('#second-end-time'), 2);
	getParam($("#third-device-name"), $('#third-start-time'), $('#third-end-time'), 3);
	function getParam(device, start, end, index){
		var container = $('#container'+index);
		if(device.val() != '不启用'){
			if(container){
				container.remove();
			}
			$("#charts").append("<div id='container" + index + "'></div>");
			var containerId = "#container" + index;
			var containerId = "container"+index;
			var deviceName = device.val();
			var startTime = start.val();
			var endTime = end.val();
			
			if(startTime == "" || startTime == null) startTime = null;
			if(endTime == "" || endTime == null) endTime = null;
			// alert($('#container'+index)+ "@" +containerId+ "@" +deviceName+ "@" + startTime+ "@" + endTime);
			getHistData(containerId, deviceName, startTime, endTime);
		}
	}
	//获得历史数据
	function getHistData(domId, deviceName, startTime, endTime){
		var sensorContainer = "";
		var deviceOnetId = "";
		var deviceId = 0;
		for(i in devices){
			if(devices[i]['name'] == deviceName){
				deviceOnetId = devices[i][4];
				deviceId = devices[i]['id'];
				// alert(deviceOnetId+"#"+deviceId);
			}
		}
		$.ajax({
			url:'{$urlUserdata}a=dogetinfo&action=getHistData',
			type:'POST',
			dataType:'json',
			async:false,
			data:{deviceOnetId:deviceOnetId, startTime:startTime, endTime:endTime, deviceId:deviceId},
			success:function(data){
				// alert(data);
				for(i in data){
					if(data[i].count != 0){
						$("#"+domId).append("<div id='sensor-" + domId +"-"+ i + "' style='height:300px'></div>");
						sensorContainerId = "sensor-" + domId + "-" + i;
						plot_static(data[i], sensorContainerId, deviceName);
					}
				}
			},
			error:function(){
				alert('获取'+deviceName+'历史数据错误');
			}
		}).responseText;
	}
}

function plot_static(datastream, domId, deviceName){
	//获取了相应的数据datastream
	//alert(datastream.count);
	var dom = document.getElementById(domId);
	var myChart = echarts.init(dom);
	var app = {};
	option = null;
	app.title = '多 X 轴示例';
	type="湿度";
	
	var dataX = new Array();
	var data1 = new Array();
	for(var i = 0 ; i < datastream.count; i++){
		dataX[i] = datastream.datastreams[0].datapoints[i].at;
		data1[i] = datastream.datastreams[0].datapoints[i].value;
	}
	// dealData(chartContainerId, data1, datastream.count);
	
	option = {
		title: {
			text: deviceName
		},
		tooltip: {
			trigger: 'axis'
		},
		legend: {
			data:[type]
		},
		grid: {
			left: '3%',
			right: '4%',
			bottom: '3%',
			containLabel: true
		},
		toolbox: {
			// feature: {
			// 	saveAsImage: {}
			// }
		},
		xAxis: {
			type: 'category',
			boundaryGap: false,
			data: dataX
		},
		yAxis: {
			type: 'value'
		},
		series: [
			{
				name:'温度传感器',
				type:'line',
				stack: '总量',
				data: data1
			}
		]
	};
	
	myChart.setOption(option);
	
}


function dealData(domId, data, dataLength){
	var step = 5;//阈值
	var count = 0; //表示异常数据的个数
	var eData = new Array();
	var normalData = new Array();
	var normalCount = 0;
	//判断第一二个数
	for(var i = 0; i < 2; i++)
	{
		var times = 0; //判断是否是异常数据的次数依据
		if(Math.abs(data[i]- data[i+1]) > step) times++;
		if(Math.abs(data[i]- data[i+2]) > step) times++;
		if(Math.abs(data[i]- data[i+3]) > step) times++;
		if(Math.abs(data[i]- data[i+4]) > step) times++;
		if(times>=1){
			eData[count] = data[i];
			count++;
		} else{
			normalData[normalCount] = data[i];
			normalCount++;
		}
	}
	for(var j = 2; j < dataLength-2; j++)
	{
		var times = 0; //判断是否是异常数据的次数依据
		if(Math.abs(data[j]- data[j-2]) > step) times++;
		if(Math.abs(data[j]- data[j-1]) > step) times++;
		if(Math.abs(data[j]- data[j+1]) > step) times++;
		if(Math.abs(data[j]- data[j+2]) > step) times++;
		if(times>=2){
			eData[count] = data[j];
			count++;
		} else {
			normalData[normalCount] = data[j];
			normalCount++;
		}
	}
	//判断第length-2个数和length-1个数
	for(var m = dataLength-2; m < dataLength; m++)
	{
		var times = 0; //判断是否是异常数据的次数依据
		if(Math.abs(data[i]- data[m-4]) > step) times++;
		if(Math.abs(data[i]- data[m-3]) > step) times++;
		if(Math.abs(data[i]- data[m-2]) > step) times++;
		if(Math.abs(data[i]- data[m-1]) > step) times++;
		if(times>=1){
			eData[count] = data[m];
			count++;
		} else {
			normalData[normalCount] = data[m];
			normalCount++;
		}
	}
	var avg = calcAvg(normalData, normalCount);
	var max = Math.max.apply(null, normalData);
	var min = Math.min.apply(null, normalData);
	// calcMost();
	var center = calcCenter(normalData, normalCount);

	
	plotChart(domId, avg, max, min, center, eData, count);
}


function calcCenter(normalData, normalCount){
	normalData.sort(numAscSort);
	var i = Math.floor(normalCount/2) + 1;
	return normalData[i];
}
function numAscSort(a, b){
	return a - b;
}

function calcAvg(normalData, normalCount){
	var sum = 0;
	for(var i = 0; i < normalCount; i++){
		sum += normalData[i];
	}
	return sum/normalCount;
}

function plotChart(analysisTable, avg, max, min, center, eData, count){
	alert(analysisTable);
	
	if($("#"+analysisTable)) {
		$("#"+analysisTable).remove();
	}
	var html = "";

	html += "<table class='table'> <tbody>";
	// html += "<tr><td>平均数</td>";
	// html += "<td>"+avg+"</td></tr>";

	// html += "<tr><td>最大值</td>";
	// html += "<td>"+max+"</td></tr>";

	// html += "<tr><td>最小值</td>";
	// html += "<td>"+min+"</td></tr>";

	// html += "<tr><td>中位数</td>";
	// html += "<td>"+center+"</td></tr>";

	html += "<tr><td>可能异常值</td>";
	html += "<td>";
	for(var i = 0; i < count; i++){
		html += eData[i]+" -";
	}
	html +="</td>";
	html += "</tr>";
	
	html += "</tbody> </table>";
	alert(html);
	$("#"+analysisTable).append(html);
}
</script>

<!--
EOT;
require_once $this->template('own/footer');
?>
