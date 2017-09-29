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

		array_push($singleGroupDevices[$j], $userGroups[$i]['id'], $userGroups[$i]['name'], $singleGroupDevices[$j]['id'], $onet['onet_data_view']);
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
	<div class="col-md-12">
		<div class="col-md-10">
            <span>
				<img id="btn-add-img" onclick="getElementById('inputfile').click()" title="点击添加图片" alt="点击添加图片" src="{$addImg}"><span id="add-img-note">选择一张现场图片(.jpg图片)</span></img>
				
			</span>
			<input type="file" id="inputfile"/>
            <div id="feedback">
            </div>
        </div>



		<div class="col-md-2" >
			<input type = "button" class = "btn btn-success col-md-12" value="保存设置" name="save-scene-set" onclick='saveScene()'/>
			<div id="device-list"></div>
			
        </div>
	</div>
</div>
								
<div class="col-md-1"></div>



<script type="text/javascript">
//获取列表数据
var devices = {$json_devices};
$(document).ready(function(){
	//显示列表数据并可移动
	deviceList(devices);
	//上传图片相关设置
	uploadImg();
});


function uploadImg(){
	$("#inputfile").change(function(){
		//创建FormData对象
		var data = new FormData();
		//为FormData对象添加数据
		$.each($('#inputfile')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});

		var divImgWidth = $(document).width() * 0.45;
		//发送数据
		$.ajax({
			url:'{$urlUserdata}a=douploadscene&divImgWidth='+ divImgWidth,
			type:'POST',
			data:data,
			cache: false,
			contentType: false,		//不可缺参数
			processData: false,		//不可缺参数

			success:function(data){
				$("#add-img-note").hide();
				$("#btn-add-img").hide();
				if($("#feedback").children('img').length == 0) $("#feedback").html(data);
				else{
					$("#feedback").children('img').remove();
					$("#feedback").html(data);
				}
			},
			error:function(){
				alert('上传出错');
			}
		});
	});
}

function deviceList(devices){
	var html ="";
	for(var i = 0; i < devices.length; i++) {
		deviceId = devices[i]['id'];
		groupId = devices[i][0] % 4 + 1;
		html += "<div class='device-list-scene' id='device"+ deviceId +"'><img class='left-img' src='"+ '{$img}' + groupId +".png'/>"+ devices[i]['name'] +"</div>";
	}
	html += "<div id='device-count' hidden='true'>"+ devices.length +"</div>";
	// alert(html);
	$('#device-list').append(html);
	$("#device-list>div").easydrag();
}

function saveScene(){
	//首先对异常情况进行处理
	//没有导入图片等等
	var imgPath = $("#scene-img-path").val();
	if(imgPath == null || imgPath==""){
		alert("请添加相应的场景(图片)");
		return;
	}
	//对数据进行存储
	//创建一个场景对象，弹出一个输入框输入场景的名称
	var name = prompt("请输入场景名称");
	// 注意这里应该有个查重处理
	if(name!=null && name!=""){
		//保存进数据库
		saveImg(name, imgPath);
	}

	//保存场景
	function saveImg(name, imgPath){
		$.ajax({
			url:'{$urlUserdata}a=dogetinfo&action=saveImg',
			type:'POST',
			data:{name:name, imgPath:imgPath},
			success:function(data){
				saveDevices(data);
			},
			error:function(){
				alert('保存图片场景出错！请重新尝试...');
			}
		});
	}

	//获取scene_device的信息
	function saveDevices(sceneId){
		var divLeft = $("#feedback").offset().left;
		var divTop = $("#feedback").offset().top;
		var divRight = divLeft + $("#feedback").width();
		var divBottom = divTop + $("#feedback").height();
		// alert("saveDevice");

		for(index in devices){
			var deviceId = devices[index]['id'];

			var deviceLeft = $('#device' + deviceId).offset().left;
			
			var deviceTop = $('#device' + deviceId).offset().top;
			
			if(deviceLeft > divLeft && deviceLeft < divRight
					&& deviceTop > divTop && deviceTop < divBottom) {
				//计算出传感器相对于img的比例系数
				//将数据记录到数据库中
				//同时，也要记录id，方便对创景进行修改
				//方式和savaImg()基本相同，都是ajax请求后端存储
				var relaWidth = (deviceLeft - divLeft)/(divRight - divLeft);
				var relaHeight = (deviceTop - divTop)/(divBottom - divTop);
				//数据库字段有id（auto_increamse）,device_id, scene_id, rela_width, rela_height
				//device_id = devices[index]['id']
				saveToDB(deviceId, sceneId, relaWidth, relaHeight);
			}
		}
	}


	//将信息保存到数据库中
	function saveToDB(deviceId, sceneId, relaWidth, relaHeight){
		$.ajax({
			url:'{$urlUserdata}a=dogetinfo&action=saveDeviceInfo',
			type:'POST',
			data:{deviceId:deviceId, sceneId:sceneId, relaWidth:relaWidth, relaHeight:relaHeight},
			async:false,
			success:function(data){
				//alert("保存成功！");
			},
			error:function(){
				alert("保存信息失败，请重新尝试...");
			}
		}).responseText;
	}
}
</script>
<!--
EOT;
require_once $this->template('own/footer');
?>
