<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '场景展示';
require_once $this->template('own/header');

$loginId = get_met_cookie('metinfo_member_id');
//TODO:对比输出
$scenes = DB::get_all("select * from {$_M[table]['userdata_scene']} where create_man_id = '{$loginId}' ORDER BY id ASC");
$obj->_data = $scenes;
$scenes_json = json_encode($obj);

$bootstrap_min_js = $_M[url][own]."web/templates/js/bootstrap.min.js";
$jquery_min_js = $_M[url][own]."web/templates/js/jquery.min.js";
$scripts_js = $_M[url][own]."web/templates/js/scripts.js";

$imgScene = $_M[url][own]."img/scene.png";



echo <<<EOT
-->

	
	


<div class="col-md-8">
	<div class="col-md-12">
		<div class="col-md-10" id = "scene">
			<img id="scene-child"/>
		</div>					
		<div class="col-md-2" id = "scenes-list">

		</div>
	</div>
</div>
								
<div class="col-md-1"></div>

</div>
</div>

<script src="{$jquery_min_js}"></script>
<script src="{$bootstrap_min_js}"></script>
<script src="{$scripts_js}"></script>
<script >
$(document).ready(function (){
	//alert($scenes_json);

	//加载场景列表
	var html = "";
	for(var i = 0; i < $scenes_json._data.length; i++){
		html += "<div><a href='javascript:void(0);' onclick='loadScene("+ i +")'><img src={$imgScene}>"+ $scenes_json._data[i].name +"</img></a></div>";
	}
	$("#scenes-list").append(html);
	
	//加载第一个场景
	loadScene(0);
	//加载场景对应的传感器
});

function loadScene(index){
	var imgPath = $scenes_json._data[index].img_path;
	//为了获取原图像的宽度和高度
	var originImg = "<img id='originImg' hidden='true' src=" + imgPath +" title='ccc' alt='ccc'/>";
	$("#scene").append(originImg);
	var img = new Image();
	img.src = $("#originImg").attr("src");
	var imgDivWidth = $(document).width() * 0.45;
	var imgDivHeight = imgDivWidth * (img.height/ img.width);
	//var html = "<img src=" + imgPath +" style='width:'+ imgDivWidth +'px;height:'+ imgDivHeight +'px' title='ccc' alt='ccc'/>";
	//$("#scene").append(html);
	$("#scene-child").attr('src', imgPath);
	$("#scene-child").width(imgDivWidth).height(imgDivHeight);

	//alert($("#scene-child").offset().left);
	//alert($("#scene-child").offset().top);
}

</script>
<!--
require_once $this->template('own/footer');
EOT;
?>
