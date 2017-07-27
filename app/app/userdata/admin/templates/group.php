<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
//PHP代码
require_once $this->template('ui/head');//引用头部UI文件


$table_group = $_M['table']['userdata_group'];
$group_data = DB::get_all("SELECT * FROM {$table_group} ORDER BY id ASC");
$group_state_text = '';


echo <<<EOT
-->
	<script type="text/javascript">
		function add_modify(){
			window.location.href="{$_M[url][own_form]}a=dogroupadd&action=add";	
		}
	</script>


	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">设备组列表</h3>
		<table class="display dataTable">
		<thead>
			<tr>
				<th>组号</th>
				<th>状态</th>
				<th>组长</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>

<!--
EOT;
for($i=0; $i<count($group_data); $i++){
	if($group_data[$i]['group_status'] == 0){
		$group_state_text = '停用中';
	} else {
		$group_state_text = '使用中';
	}
	
	$user_data = DB::get_one("select * from {$_M['table']['user']} where id = {$group_data[$i]['group_manager_id']}");
	$groupManager = $user_data['username'];
echo <<<EOT
-->		
			<tr>
				<td>{$group_data[$i]['group_id']}</td>
				<td>{$group_state_text}</td>
				<td>{$groupManager}</td>
				<td>
					<a class="btn btn-success" href="{$_M[url][own_form]}a=doindex&action=changeStatus&id={$group_data[$i]['id']}&group_status={$group_data[$i]['group_status']}">改变状态</a>
					<a class="btn btn-info" href="{$_M[url][own_form]}a=dogroupadd&action=modify&id={$group_data[$i]['id']}">修改</a>
					<a class="btn btn-danger" href="{$_M[url][own_form]}a=doindex&action=delete&id={$group_data[$i]['id']}">删除</a>
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
				<input type="button" name="add" value="添加设备组" onclick="add_modify()" class="btn btn-primary">				
			</dd>
		</dl>
	</div>


<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>