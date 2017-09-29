<!--<?php
require_once $this->template('ui/head');//引用头部UI文件


$css_own = $_M[url][own]."admin/templates/css/own.css";
$js_jquery = $_M[url][own]."admin/templates/js/jquery.js";
$js_own = $_M[url][own]."admin/templates/js/own.js";

echo <<<EOT
-->

<link href="{$css_own}" rel="stylesheet">



<form method="POST" class="ui-from">
	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">库存设备</h3>
		<div class="page-info"></div>
		<table class="display dataTable">
			<thead>
				<tr>
					<th>序号</th>
					<th>设备名称</th>
					<th>类型</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>



			</tbody>
		</table>

		<dl class="noborder">
			<dd>
				<input type="button" value="添加设备" onclick="device_add_modify()" class="btn btn-primary"></input>			
			</dd>
		</dl>
	</div>
</form>


<script src="{$js_jquery}"></script>
<script src="{$js_own}"></script>
<script>
$(document).ready(function(){
	var site = "{$_M[url][own_form]}";
	var deviceName = $json_data.deviceName;
	var deviceId = $json_data.deviceId;
	// alert(site+"#"+deviceName + "#" + deviceId);

	getSensor(deviceName, deviceId, site);
});

</script>

<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>