<!--<?php
require_once $this->template('ui/head');//引用头部UI文件

$applyData = DB::get_all("SELECT * from {$_M[table]['userdata_post_sensor']} ORDER BY id ASC");

echo <<<EOT
-->
	<script type="text/javascript">
		function seneor_add_modify(){
			window.location.href="{$_M[url][own_form]}a=dosensoradd&action=add";	
		}
	</script>

	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">组织列表</h3>
		<table class="display dataTable">
		<thead>
			<tr>
				<th>设备名称</th>
				<th>设备位置</th>
				<th>创建人</th>
				<th>图表类型</td>
				<th>设备类别</th>
				
				<th>所属设备组</th>

				<th>操作</th>
			</tr>
		</thead>
		<tbody>

<!--
EOT;
for($i=0; $i<count($applyData); $i++){
echo <<<EOT
-->		
			<tr>
				<td>{$applyData[$i]['sensor_name']}</td>
				<td>{$applyData[$i]['sensor_loca']}</td>
				<td>test06</td>
				<td>{$applyData[$i]['table_type']}</td>
				<td>{$applyData[$i]['sensor_type']}</td>
				
				<td>{$applyData[$i]['sensor_group_id']}</td>
				<td>
					<a class="btn btn-success" href="{$_M[url][own_form]}a=dosensor&action=changeStatus&sensor_id={$sensor_data[$i]['id']}&sensorStatus={$sensor_data[$i]['sensorStatus']}">获取信息</a>
					<a class="btn btn-info" href="{$_M[url][own_form]}a=dosensoradd&action=modify&id={$sensor_data[$i]['id']}&deviceId={$sensor_data[$i]['deviceId']}">改变状态</a>
					<a class="btn btn-danger" href="{$_M[url][own_form]}a=dosensor&action=delete&deviceId={$sensor_data[$i]['deviceId']}&id={$sensor_data[$i]['id']}">删除</a>
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


<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>