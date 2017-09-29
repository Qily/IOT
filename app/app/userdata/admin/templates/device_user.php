<!--<?php
require_once $this->template('ui/head');//引用头部UI文件

if($groupId != 0){
$devices = DB::get_all("SELECT * FROM {$_M['table']['userdata_device']} WHERE group_id = {$groupId}");
	$deviceCount = count($devices);
} else{
	$devices = DB::get_all("SELECT * FROM {$_M['table']['userdata_device']}");
	$deviceCount = count($devices);
}



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
					<th>设备号</th>
					<th>设备名称</th>
					<th>位置</th>
					<th>序列号</th>
					<th>描述</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>



			</tbody>
		</table>

		<dl>
			<dd class="text-center noborder">
				<ul>
					<li>
						<a href="javascript:void(0);" onclick="getDeviceUserPage('first')">首页</a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="getDeviceUserPage('prev')">上一页</a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="getDeviceUserPage('next')">下一页</a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="getDeviceUserPage('last')">尾页</a>
					</li>
				</ul>
			</dd>
		</dl>

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
	var currentPage = 0;	
	var pageSize = 2;
	var groupId = {$groupId};
	// alert(groupId);
	var site = "{$_M[url][own_form]}";

	initPage(pageSize, {$deviceCount}, site);

	getDeviceUserPage('first', groupId);
});

</script>

<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>