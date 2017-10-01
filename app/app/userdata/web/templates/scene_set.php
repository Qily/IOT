<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

require_once $this->template('own/header');
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
var site = '{$_M['url'][site]}' + 'data/request_page.php?n=userdata&c=userdata&';
var deviceImg = "{$_M[url][own]}"+"img/";
$(document).ready(function(){
	getAllDeviceAndSensor();
	//显示列表数据并可移动
	deviceList(devices);
	//上传图片相关设置
	uploadImg();
});
</script>
<!--
EOT;
require_once $this->template('own/footer');
?>
