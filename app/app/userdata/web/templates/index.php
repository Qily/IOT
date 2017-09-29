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
		$onet = DB::get_one("SELECT * FROM {$_M[table]['userdata_onet']} WHERE device_id = '{$singleGroupDevices[$j]['id']}'");
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
echo <<<EOT
-->


<script type="text/javascript">
var sensors = {$json_sensors};
var devices = {$json_devices};

$(document).ready(function(){
	getSensors();
	setInterval("getSensors()",5000);
});
	
	
function getSensors(){
	// 获取相应的
	for(i in sensors){
		// alert(sensors[0][4]);
		sensorId = sensors[i]['id'];
		onetDeviceId = sensors[i][4];
		onetDataflow = sensors[i][2];
		// alert(sensorId+"@"+onetDeviceId+"@"+onetDataflow);
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
			// alert(data.datastreams[0].datapoints[0].value);
			$("#sensor"+sensorId).text(data.datastreams[0].datapoints[0].value);
			//alert(data.datastreams[0].datapoints[0].value);

		},
		error:function(){
			//alert('获取历史数据错误');
		}
	});
}

function load() {
	function createApp(dom, host, openId, is_model, device_id) {
		var width = dom.clientWidth;
		var height = dom.clientHeight;
		var iframe = document.createElement('iframe');
		iframe.style.width = width + 'px';
		iframe.style.height = height + 'px';
		iframe.style.border = '0 none';
		iframe.setAttribute('frameBorder', '0');
		var src = (host || 'https://open.iot.10086.cn') + '/app/browse2?openid=' + openId;
		if (Boolean(Number(is_model))) src += '&is_model=1&device_id=' + device_id;
		iframe.setAttribute('src', src);
		dom.appendChild(iframe);
	}

	var appDomList = document.querySelectorAll('.j_10086_iotapp');
	for (var i = 0; i < appDomList.length; i++) {
		var dom = appDomList[i];
		createApp(dom, dom.getAttribute('data-host'), dom.getAttribute('data-view'), dom.getAttribute('data-is-model'), dom.getAttribute('data-device-id'));
	}
}

function changeView(dataView){
	$("#charts").attr("data-view",dataView);
	$("iframe").remove();
	load();
}

function exAndMerge(){
	var extendMerges = $("#tblMain tr .extend-merge");
	for(var i = 0; i < extendMerges.length; i++){
		if(extendMerges[i] == this){
			changeIcon(extendMerges[i]);
			changeTable(this.id);
		}
	}
	//改变伸展和关闭的图表
	function changeIcon(extendMerge){
		if(extendMerge.src == '{$imgExtend}'){
			extendMerge.src = '{$imgMerge}';
		} else{
			extendMerge.src = '{$imgExtend}';
		}
		
	}
	//改变是否显示具体传感器数据	
	function changeTable(i){
		$(".index-sensor-tr"+i).toggle();
	}
}

function initIndex(sensors){
	loadSensors(sensors);

	function loadSensors(sensors){
		//1 要知道图表是什么
		//2 要知道数值是什么
		//3 要知道状态是什么
		//4 要知道所属设备是什么
		for(var i = 0; i < sensors.length; i++){
			var runStop = "img/running.png";
			loadOneSensor(sensors[i][0], sensors[i][3], sensors[i]['id'], runStop);
		}
	}
	function loadOneSensor(deviceId, imgPath, sensorId, runStop){
		// alert(deviceId);
		var html = "";
		html +="<tr class=index-sensor-tr"+deviceId+">";
		html +="<td style='border:none'></td>";
		html +="<td class='index-sensor-tr'><img src={$_M[url][own]}"+ imgPath +"></td>";
		html +="<td class='index-sensor-tr' id=sensor"+ sensorId +">100</td>";
		html +="<td class='index-sensor-tr'><img src={$_M[url][own]}"+ runStop +"></td>";
		html +="</tr>";
		$(".trMain"+deviceId).after(html);
	}
}


$(document).ready(function(){
	initIndex(sensors);
	IniEvent();
});


//联合下面的TrOnClick()相应点击行事件
function IniEvent() {
	var trs = $("#tblMain .trMain");
	var extendMerges = $("#tblMain tr .extend-merge");

	for(var i = 0; i < extendMerges.length; i++){
		extendMerges[i].onclick = exAndMerge;
	}
	for (var i = 0; i < trs.length; i++) {
		trs[i].onclick = TrOnClick;
	}
	//trs[0].onclick = TrOnClick;
	
	trs[0].style.background = "yellow";
	changeView(devices[0][2]);
}

function TrOnClick() {
	var trs = $("#tblMain .trMain");
	for (var i = 0; i < trs.length; i++) {
		
		if (trs[i] == this) { //判断是不是当前选择的行
			trs[i].style.background = "yellow";
			for(var j = 0; j < devices.length; j++){
				if(trs[i].cells[1].innerHTML == devices[j]['name']){						
					changeView(devices[j][2]);
				}
			}
		}
		else{
			trs[i].style.background = "white";
		}
	}
}
</script>


<div class="col-md-8">
	<div class="col-md-12">
		<div class="col-md-7">
				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>名称</th>
							<th>位置</th>
							<th>组别</th>
						</tr>
					</thead>

					<tbody id="tblMain">
<!--
EOT;
for($i=0; $i<count($devices); $i++){
		$index = $devices[$i]['id'];
echo <<<EOT
-->
							<tr class="trMain trMain{$index}">
								<td><img src="{$imgMerge}" class="extend-merge" id="{$index}"/></td>
								<td id="name">{$devices[$i]['name']}</td>
								<td>{$devices[$i]['location']}</td>
								<td>{$devices[$i][0]}</td>
							</tr>
						
<!--
EOT;
}
echo <<<EOT
-->
					</tbody>
				</table>
			</div>
			<div class="col-md-5">
				<div class="j_10086_iotapp pinned" id="charts" data-host="https://open.iot.10086.cn" data-view="fcf021830dc307d45a55c4e9b2e7876c" data-pid="89967" 
						data-appid="19320" style="height:600px">
				</div>
			</div>

		</div>
	</div>
							
	<div class="col-md-1"></div>

	</div>
</div>
	
<!--
EOT;
require_once $this->template('own/footer');
?>