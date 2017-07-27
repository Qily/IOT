<!--<?php
require_once $this->template('ui/head');//引用头部UI文件

$group_users = DB::get_all("select * from {$_M['table']['userdata_group_user']} ORDER BY user_id");

echo <<<EOT
-->
	<script type="text/javascript">
		function group_user_add_modify(){
			window.location.href="{$_M[url][own_form]}a=dogroupuseradd&action=add";
		}
	</script>

	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">组织列表</h3>
		<table class="display dataTable">
		<thead>
			<tr>
				<th>用户名</th>
				<th>设备组</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>

<!--
EOT;
for($i=0; $i<count($group_users); $i++){
	$user_array = DB::get_one("SELECT * FROM {$_M[table]['user']} WHERE id = {$group_users[$i]['user_id']}");
	$group_array = DB::get_one("SELECT group_id FROM {$_M[table]['userdata_group']} where id = {$group_users[$i]['group_id']}");


	if($group_users[$i]['group_user_status'] == 0){
		$group_user_status_text = '停用中';
	} else {
		$group_user_status_text = '使用中';
	}
echo <<<EOT
-->		
			<tr>
				<td>{$user_array['username']}</td>
				<td>{$group_array[group_id]}</td>
				<td>{$group_user_status_text}</td>
				<td>
					<a class="btn btn-success" href="{$_M[url][own_form]}a=dogroupuser&action=changeStatus&id={$group_users[$i]['id']}&group_user_status={$group_users[$i]['group_user_status']}">改变状态</a>
					<a class="btn btn-info" href="{$_M[url][own_form]}a=dogroupuseradd&action=modify&id={$group_users[$i]['id']}">修改</a>
					<a class="btn btn-danger" href="{$_M[url][own_form]}a=dogroupuser&action=delete&id={$group_users[$i]['id']}">删除</a>
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
				<input type="button" name="add" value="添加用户" onclick="group_user_add_modify()" class="btn btn-primary">				
			</dd>
		</dl>
	</div>


<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>