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




echo <<<EOT
-->
<div class="col-md-8">
	<div class="col-md-12">
		<div class="col-md-10">
            <span>
				<img id="btn-add-img" onclick="getElementById('inputfile').click()" title="点击添加图片" alt="点击添加图片" src="{$addImg}">选择一张.jpg图片</img>
				
			</span>
			<input type="file" id="inputfile" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/>
            <div id="feedback">
             
            </div>
        </div>



		<div class="col-md-2">
			<input type = "button" class = "btn btn-success col-md-12" value="保存设置" name="save-scene-set">
			<h3></h3>
			<img src="{$imghumi}">传感器</img>
        </div>
	</div>
</div>
								
<div class="col-md-1"></div>


</div>
</div>

<script src="{$jquery_min_js}"></script>
<script src="{$jquery_min_js_1_6}"></script>

<script src="{$bootstrap_min_js}"></script>
<script src="{$scripts_js}"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#inputfile").change(function(){
	//创建FormData对象
	var data = new FormData();
	//为FormData对象添加数据
	$.each($('#inputfile')[0].files, function(i, file) {
		data.append('upload_file'+i, file);
	});
	//发送数据
	$.ajax({
		url:'{$urlUserdata}a=douploadscene',
		type:'POST',
		data:data,
		cache: false,
		contentType: false,		//不可缺参数
		processData: false,		//不可缺参数
		success:function(data){
			$(".addImgNote").hide();
			$("#btn-add-img").hide();
			//data = $(data).html();
			alert(data);
			//第一个feedback数据直接append，其他的用before第1个（ .eq(0).before() ）放至最前面。
			//data.replace(/&lt;/g,'<').replace(/&gt;/g,'>') 转换html标签，否则图片无法显示。
			if($("#feedback").children('img').length == 0) $("#feedback").append(data);
			//else $("#feedback").children('img').eq(0).before(data);
			else{
				$("#feedback").children('img').remove();
				$("#feedback").append(data);
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
