<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
//PHP代码
require_once $this->template('ui/head');//引用头部UI文件
$groups = DB::get_all("SELECT * FROM {$_M[table]['userdata_group']}");
$groupCount = count($groups);


$css_own = $_M[url][own]."admin/templates/css/own.css";
$js_jquery = $_M[url][own]."admin/templates/js/jquery.js";
$js_own = $_M[url][own]."admin/templates/js/own.js";


$site = $_M[url][own_form]."a=dogetinfo&action=getGroups";
echo <<<EOT
-->

<link href="{$css_own}" rel="stylesheet">
<div class="v52fmbx">
	<h3 class="v52fmbx_hr">设备组列表</h3>
	<div class='page-info'></div>
	<table class="display dataTable">
	<thead>
		<tr>
			<th>组号</th>
			<th>组名</th>
			<th>组内设备</th>
			<th>创建人</th>
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
					<a href="javascript:void(0);" onclick="getGroupPage('first')">首页</a>
				</li>
				<li>
					<a href="javascript:void(0);" onclick="getGroupPage('prev')">上一页</a>
				</li>
				<li>
					<a href="javascript:void(0);" onclick="getGroupPage('next')">下一页</a>
				</li>
				<li>
					<a href="javascript:void(0);" onclick="getGroupPage('last')">尾页</a>
				</li>
			</ul>
		</dd>
	</dl>

	<dl class="noborder">
		<dd>
			<input type="button" value="添加设备组" onclick="addGroup()" class="btn btn-primary">				
		</dd>
	</dl>
</div>

<script src="{$js_jquery}"></script>
<script src="{$js_own}"></script>

<script>
$(document).ready(function(){
	var currentPage = 0;	
	var pageSize = 2;
	var site = "{$_M[url][own_form]}";
	
	initPage(pageSize, {$groupCount}, site);

	getGroupPage('first');
});

</script>
<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>