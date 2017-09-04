<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '设备信息';
require_once $this->template('own/header');

//在右侧显示可以添加的传感器
//在数据库中查找当前登陆的用户所在的组对应的传感器
//在传感器添加完成之后，不能重复添加，因为一个传感器一次只能在一个地方出现
//DB::get_one("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE id = {$_M[form][id]}")
//当前登陆用户id
$loginUserId = get_met_cookie('metinfo_member_id');
//当前id所对应的组
$sensorGroups = DB::get_all("SELECT * FROM {$_M[table]['userdata_group_user']} WHERE user_id = '{$loginUserId}'");
$totalSensorGroups = count($sensorGroups);

$groupSensors = array();
$index = 0;
foreach($sensorGroups as $sensorGroup) {
	$temps = DB::get_all("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE groupId = '{$sensorGroup['group_id']}'");
	$j = 0;
	foreach($temps as $temp){
		$groupSensors[$index][$j]->type = $temp['tag'];
		$groupSensors[$index][$j]->name = $temp['sensorName'];
		$j++;
	}
	$index++;
}

$obj -> _data = $groupSensors;
$obj -> _groupCount = $totalSensorGroups;
$json_data = json_encode($obj);

$bootstrap_min_js = $_M[url][own]."web/templates/js/bootstrap.min.js";
$jquery_min_js = $_M[url][own]."web/templates/js/jquery.min.js";
$jquery_min_js_1_6 = $_M[url][own]."web/templates/js/jquery-1.6.2.min.js";
$scripts_js = $_M[url][own]."web/templates/js/scripts.js";
$addImg = $_M[url][own]."web/templates/files/addImg.png";
$easydrag = $_M[url][own]."web/templates/js/jquery.easyDrag.js";




echo <<<EOT
-->
<div class="col-md-8">
	<div class="col-md-12">
		<div class="col-md-10">
            <span>
				<img id="btn-add-img" onclick="getElementById('inputfile').click()" title="点击添加图片" alt="点击添加图片" src="{$addImg}"><span id="add-img-note">选择一张.jpg图片</span></img>
				
			</span>
			<input type="file" id="inputfile" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/>
            <div id="feedback">
             
            </div>
        </div>



		<div class="col-md-2">
			<input type = "button" class = "btn btn-success col-md-12" value="保存设置" name="save-scene-set" onclick='saveScene()'/>
			<h3></h3>
			<div id="sensors-list">
				<!--<div id ="div1"><img src="{$imghumi}">传感器</img></div>-->
			</div>
			
        </div>
	</div>
</div>
								
<div class="col-md-1"></div>


</div>
</div>

<script src="{$jquery_min_js}"></script>
<script src="{$jquery_min_js_1_6}"></script>
<script src="{$easydrag}"></script>
<script src="{$bootstrap_min_js}"></script>
<script src="{$scripts_js}"></script>
<script type="text/javascript">
$(document).ready(function(){
	//获取列表数据
	var sensorsListData = {$json_data};
	//显示列表数据并可移动
	sensorList(sensorsListData);
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
				//alert(data);
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

function sensorList(sensorsListData){
	var html= '';
	var tag = '{$imghumi}';
	for(var i = 0; i < sensorsListData._groupCount; i++) {
		for(var j = 0; j < sensorsListData._data[i].length; j++){
			if(sensorsListData._data[i][j].type == "humi"){
				tag = "'{$imghumi}'";
			} else if(sensorsListData._data[i][j].type == "temper") {
				tag = "'{$imgtemper}'";
			}
			html += "<div id=sensor-list"+ i +"><img src="+ tag +">"+sensorsListData._data[i][j].name +'</img></div>';
		}
	}
	$('#sensors-list').append(html);
	$("#sensors-list>div").easydrag();
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
	if(name!=null && name!=""){
		//保存进数据库
		saveImg(name, imgPath);
		saveSensors();
	}
}

function saveImg(name, imgPath){
	$.ajax({
		url:'{$urlUserdata}a=dosceneset&action=save',
		type:'POST',
		//dataType:'json',
		data:{name:name, imgpath:imgPath},
		
		success:function(data){
			alert("保存成功");
		},
		error:function(){
			alert('保存出错');
		}
	});
}

function saveSensors(){
	var p;
}
</script>
<!--
require_once $this->template('own/footer');
EOT;
?>
