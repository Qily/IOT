<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '设备信息';
require_once $this->template('own/header');

//在右侧显示可以添加的传感器
//在数据库中查找当前登陆的用户所在的组对应的传感器
//在传感器添加完成之后，不能重复添加，因为一个传感器一次只能在一个地方出现
//DB::get_one("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE id = {$_M[form][id]}")
//当前登陆用户id
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
$obj -> _data = $sensors;
$obj -> _sensorsCount = $sensors_count;
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
	var sensorType = '{$imghumi}';
	var sensorsCount = sensorsListData._sensorsCount;
	for(var i = 0; i < sensorsCount; i++) {
		if(sensorsListData._data[i]['tag'] == "humi"){
			sensorType = "'{$imghumi}'";
		} else if(sensorsListData._data[i]['tag'] == "temper"){
			sensorType = "'{$imgtemper}'";
		}
		html += "<div id=sensor-list"+ i +"><img src="+sensorType+">"+sensorsListData._data[i]['sensorName']+'</img></div>';
	}
	html += "<div id='sensors-count' hidden='true'>"+ sensorsCount +"</div>";
	
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
	// 注意这里应该有个查重处理
	if(name!=null && name!=""){
		//保存进数据库
		saveImg(name, imgPath);
	}
}
function getSceneIdByName(name){
	//var sceneName = name;
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getSceneId',
		type:'POST',
		//dataType:'json',
		data:{name:name},
		//async:false,
		success:function(data){
			saveSensors(data);
			alert("保存场景成功！")

		},
		error:function(){
			alert("保存失败，请重新尝试...");
		}
	});
	
}
function saveImg(name, imgPath){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=saveImg',
		type:'POST',
		//dataType:'json',
		data:{name:name, imgPath:imgPath},
		
		success:function(data){
			getSceneIdByName(name);
		},
		error:function(){
			alert('保存图片场景出错！请重新尝试...');
		}
	});
}

function saveSensors(sceneId){
	//在图片范围之内的保存，在图片范围之外的不保存，首先就要确定图片（场景）的边界
	var divLeft = $("#feedback").offset().left;
	var divTop = $("#feedback").offset().top;
	var divRight = divLeft + $("#feedback").width();
	var divBottom = divTop + $("#feedback").height();
	var sensorsCount = $("#sensors-count").text();
	for(var i = 0; i < sensorsCount; i++){
		var sensorLeft = $('#sensor-list'+i).offset().left;
		var sensorTop = $('#sensor-list'+i).offset().top;
		if(sensorLeft > divLeft && sensorLeft < divRight
				&& sensorTop > divTop && sensorTop < divBottom){
			//计算出传感器相对于img的比例系数
			//将数据记录到数据库中
			//同时，也要记录id，方便对创景进行修改
			//方式和savaImg()基本相同，都是ajax请求后端存储
			var relaWidth = (sensorLeft - divLeft)/(divRight - divLeft);
			var relaHeight = (sensorTop - divTop)/(divBottom - divTop);
			//数据库字段有id（auto_increamse）,sensor_id, scene_id, rela_width, rela_height
			//只有sensor_id还没有，所以通过ajax获取到
			
			var sensorName = $('#sensor-list'+i).text();
			$.ajax({
				url:'{$urlUserdata}a=dogetinfo&action=getSensorId&sensorname='+sensorName,
				type:'POST',
				async:false,
				success:function(data){
					saveToDB(data, sceneId, relaWidth, relaHeight);
				},
				error:function(){
					alert('获取传感器信息失败！请重新尝试...');
				}
			}).responseText;
		}
	}
}


function saveToDB(data, sceneId, relaWidth, relaHeight){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=saveSensorinfo',
		type:'POST',
		data:{sensorId:data, sceneId:sceneId, relaWidth:relaWidth, relaHeight:relaHeight},
		async:false,
		success:function(data){
			//alert("保存成功！");
		},
		error:function(){
			alert("保存信息失败，请重新尝试...");
		}
	}).responseText;
}
</script>
<!--
require_once $this->template('own/footer');
EOT;
?>
