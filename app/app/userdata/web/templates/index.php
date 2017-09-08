<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '设备信息';
require_once $this->template('own/header');

$loginId = get_met_cookie('metinfo_member_id');
$user_groups = DB::get_all("select * from {$_M[table]['userdata_group_user']} where user_id = '{$loginId}'");
$sensors = array();
for($i = 0; $i < count($user_groups); $i++){
      $sensorSingleGroup = DB::get_all("select * from {$_M[table]['userdata_sensor']} where groupId = '{$user_groups[$i]['group_id']}' ORDER BY id ASC");
     if($sensorSingleGroup != null){
      $sensors = array_merge($sensors, $sensorSingleGroup);
}
}
$sensors_count = count($sensors);
$sensors_json = json_encode($sensors);

$data = array();
for($i = 0; $i < $sensors_count; ++$i){
	if($sensors[$i]['tag'] == 'humi'){
		$data[$i] = $imghumi;
	} else if($sensors[$i]['tag']== 'temper'){
		$data[$i] = $imgtemper;
	} 
}
echo <<<EOT
-->

	
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script type="text/javascript">

var sensors_json = {$sensors_json};


function load() {
	function createApp(dom, id, is_model, device_id) {
		var width = dom.clientWidth;
		var height = dom.clientHeight;
		var iframe = document.createElement('iframe');
		iframe.style.width = width + 'px';
		iframe.style.height = height + 'px';
		iframe.style.border = '0 none';
		iframe.setAttribute('frameBorder', '0');
		var src = 'https://open.iot.10086.cn/appview/p/' + id;
		if (Boolean(Number(is_model))) src += '&is_model=1&device_id=' + device_id;
		iframe.setAttribute('src', src);
		dom.appendChild(iframe);
	}

	function createApp2(dom, host, openId, is_model, device_id) {
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
		// createApp(dom, dom.getAttribute('data-view'), dom.getAttribute('data-is-model'), dom.getAttribute('data-device-id'));
		createApp2(dom, dom.getAttribute('data-host'), dom.getAttribute('data-view'), dom.getAttribute('data-is-model'), dom.getAttribute('data-device-id'));
	}
}

//$(document).ready(function(){
//	load();
//})

function changeView(dataView){
	$("#charts").attr("data-view",dataView);
	$("iframe").remove();
	load();
}


window.onload = function IniEvent() {
	var tbl = document.getElementById("tblMain");
	var trs = tbl.getElementsByTagName("tr");

	for (var i = 0; i < trs.length; i++) {
		trs[i].onclick = TrOnClick;
	}
	trs[0].onclick = TrOnClick;
	
	trs[0].style.background = "yellow";
	changeView(sensors_json[0].dataView);
				
}
function TrOnClick() {
	var tbl = document.getElementById("tblMain");
	var trs = tbl.getElementsByTagName("tr");
	
	for (var i = 0; i < trs.length; i++) {
		
		if (trs[i] == this) { //判断是不是当前选择的行
			trs[i].style.background = "yellow";
			for(var j = 0; j < sensors_json.length; j++){
				if(trs[i].cells[1].innerHTML == sensors_json[j].sensorName){						
					changeView(sensors_json[j].dataView);
				}
			}
		}
		else{
			trs[i].style.background = "white";
		}
	}
}




$(document).ready(function(){
	$("#p1").hide();
	//$("#SIM900A_Test").text(100);
	getSensors();
	setInterval("getSensors()",5000);
});


function getSensors(){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getSensorsByLoginId',
		type:'POST',
		dataType:'json',
		//cache:true,
		//data:{sensorName:sensorName, startTime:startTime, endTime:endTime},
		success:function(data){
			//先找出所有的传感器
			//通过名称将传感器和td的Id联系，从而更新数据
			for(var i = 0; i < data._count; i++){
				getLastData(data._data[i].sensorName);
			}

		},
		error:function(){
			alert('获取历史');
		}
	});
}

function getLastData(sensorName){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getLastData',
		type:'POST',
		dataType:'json',
		data:{sensorName:sensorName},
		success:function(data){
			//先找出所有的传感器
			//通过名称将传感器和td的Id联系，从而更新数据

			$("#"+sensorName).text(data.datastreams[0].datapoints[0].value);
			//alert(data.datastreams[0].datapoints[0].value);

		},
		error:function(){
			alert('获取历史数据错误');
		}
	});
}

function showUp(id){
	$("#upId").val(id);
	$("#p1").show();
}


</script>


<div class="col-md-8">
	<div class="col-md-12">
		<div class="col-md-8">
<div id="p1">
<form action="{$_M['url'][site]}data/index.php" action="POST">
<input type="hidden" name="id" id="upId">
<input type="hidden" name="action" value="up">
设备名称：<input type="text" name="sensorName"/>
设备位置：<input type="text" name="sensorLoca"/>
<input type="submit" value="确定">
</form>
</div>

				<table class="table">
					<thead>
						<tr>
							<th></th>
							<th>名称</th>
							<th>当前值</th>
							<th>位置</th>
							<th>操作</th>
					</thead>

					<tbody id="tblMain">
<!--
EOT;
for($i=0; $i<$sensors_count; $i++){
echo <<<EOT
-->
						<tr>
						
							<td><img src={$data[$i]} /></td>
							<td class="value">{$sensors[$i]['sensorName']}</td>

							<td><div id={$sensors[$i]['sensorName']}>1</div></td>
							<td>{$sensors[$i]['sensorLoca']}</td>
						 	<td><a class="btn btn-warning" onclick="showUp({$sensors[$i]['id']})">修改</a>
								 <a class="btn btn-danger"  href="javascript:if(confirm('确定删除？'))location='{$urlUserdata}a=doindex&action=del&id={$sensors[$i]['id']}'">删除</a><td>
						</tr>
						
<!--
EOT;
}
echo <<<EOT
-->
					</tbody>
				</table>
			</div>
			

			<div class="col-md-4">
				<div class="j_10086_iotapp" id="charts" data-host="https://open.iot.10086.cn" data-view="fcf021830dc307d45a55c4e9b2e7876c" data-pid="89967" data-appid="19320" style="height:600px">
				</div>
			</div>

		</div>
	</div>
							
	<div class="col-md-1"></div>

	</div>
</div>

<script src="{$jquery_min_js}"></script>
<script src="{$bootstrap_min_js}"></script>
<script src="{$scripts_js}"></script>
<!--
require_once $this->template('own/footer');
EOT;
?>