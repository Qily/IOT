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

$bootstrap_min_js = $_M[url][own]."web/templates/js/bootstrap.min.js";
$jquery_min_js = $_M[url][own]."web/templates/js/jquery.min.js";
$jquery_min_js_1_6 = $_M[url][own]."web/templates/js/jquery-1.6.2.min.js";
$scripts_js = $_M[url][own]."web/templates/js/scripts.js";
$addImg = $_M[url][own]."web/templates/files/addImg.png";
$dragresize = $_M[url][own]."web/templates/js/$dragresize.js";




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
			<input type = "button" class = "btn btn-success col-md-12" value="保存设置" name="save-scene-set">
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
<script src="{$dragresize}"></script>

<script src="{$bootstrap_min_js}"></script>
<script src="{$scripts_js}"></script>
<script type="text/javascript">
$(document).ready(function(){
	$.ajax({
		url:'{$urlUserdata}a=doscenesensor',
		type:'POST',
		dataType:'json',
		//cache: false,
		//contentType: false,		//不可缺参数
		//processData: false,		//不可缺参数
		success:function(data){
			var html= '';
			var tag = '{$imghumi}';
			for(var i = 0; i < data._groupCount; i++) {
				for(var j = 0; j < data._data[i].length; j++){
					if(data._data[i][j].type == "humi"){
						tag = "'{$imghumi}'";
					} else if(data._data[i][j].type == "temper") {
						tag = "'{$imgtemper}'";
					}
					html += "<div id=sensor-list"+ i +"><img src="+ tag +">"+data._data[i][j].name +'</img>';
					
					//alert(data._data[i][j].name);
				}

			}
			$('#sensors-list').html(html);
			//alert(data)
			
			$('#sensor-list1').live("click", function(){
				alert("bb");
				//$(this).easydrag();
			});
			
		},
		error:function(){
			alert('上传出错');
		}
	});
	

	

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
});



</script>
<!--
require_once $this->template('own/footer');
EOT;
?>
