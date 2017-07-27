<!--<?php
require_once $this->template('ui/head');//引用头部UI文件

$table_sensor = $_M['table']['userdata_sensor'];
$sensor_data = DB::get_all("SELECT * FROM {$table_sensor} ORDER BY id ASC");

echo <<<EOT
-->
	<script type="text/javascript">
		function seneor_add_modify(){
			window.location.href="{$_M[url][own_form]}a=dosensoradd&action=add";	
		}
	</script>


<form method="POST" class="ui-from">
	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">组织列表</h3>
		<table class="display dataTable">
		<thead>
			<tr>
				<th>设备号</th>
				<th>设备名称</th>
				<th>设备位置</th>
				
				<th>Device ID</th>
				<th>App ID</th>
				<th>图表id</td>
				<th>设备类别</th>
				
				<th>所属设备组</th>
				<th>设备状态</th>

				<th>操作</th>
			</tr>
		</thead>
		<tbody>

<!--
EOT;
for($i=0; $i<count($sensor_data); $i++){
	if($sensor_data[$i]['sensorStatus'] == 0){
		$sensor_state_text = '停用中';
	} else {
		$sensor_state_text = '使用中';
	}
	$groupData = DB::get_one("select * from {$_M['table']['userdata_group']} where id = {$sensor_data[$i]['groupId']}");
	$groupId = $groupData['group_id'];
echo <<<EOT
-->		
			<tr>
				<td>{$sensor_data[$i]['id']}</td>
				<td>{$sensor_data[$i]['sensorName']}</td>
				<td>{$sensor_data[$i]['sensorLoca']}</td>

				<td>{$sensor_data[$i]['deviceId']}</td>
				<td>{$sensor_data[$i]['appId']}</td>
				<td>{$sensor_data[$i]['dataView']}</td>
				<td>{$sensor_data[$i]['tag']}</td>
				
				<td>{$groupId}</td>
				<td>{$sensor_state_text}</td>
				<td>
					<a class="btn btn-success" href="{$_M[url][own_form]}a=dosensor&action=changeStatus&sensor_id={$sensor_data[$i]['id']}&sensorStatus={$sensor_data[$i]['sensorStatus']}">改变状态</a>
					<a class="btn btn-info" href="{$_M[url][own_form]}a=dosensoradd&action=modify&id={$sensor_data[$i]['id']}&deviceId={$sensor_data[$i]['deviceId']}">修改</a>
					<a class="btn btn-danger" data-confirm="您确定要删除该信息吗？删除之后无法再恢复" href="{$_M[url][own_form]}a=dosensor&action=delete&deviceId={$sensor_data[$i]['deviceId']}&id={$sensor_data[$i]['id']}">删除</a>
				</td>
			</tr>
<!--
EOT;
}
echo <<<EOT
-->

		</tbody>
		</table>

		<dl class="noborder">
			<dd>
				<input type="button" name="add" value="添加设备" onclick="seneor_add_modify()" class="btn btn-primary">				
			</dd>
		</dl>
	</div>
</form>

<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>