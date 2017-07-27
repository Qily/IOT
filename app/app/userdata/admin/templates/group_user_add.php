<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
//PHP代码
require_once $this->template('ui/head');//引用头部UI文件

$action = $_M['form']['action'];
$exedaction_text = "";
$exeaction_text = "";
$submit_text = "";
if($action == 'modify'){
	$action_text="修改设备组";
	$submit_text = "修改";
} else if($action == 'add'){
	$action_text="添加设备组";
	$submit_text = "保存";
}


$group_data = DB::get_one("select * from {$_M[table]['userdata_group']} where id = {$group_user_array['group_id']}");
$user_data = DB::get_one("select * from {$_M[table]['user']} where id = {$group_user_array['user_id']}");



$id = $_M['form']['id'];
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=dogroupuser&action=save&modify_id={$id}">
	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">{$action_text}</h3>

		<dl>
			<dt>用户名</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name='username' data-required="1" value={$user_data['username']}>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>设备组唯一id</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="groupId" value={$group_data['group_id']}>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>状态</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="groupUserStatus" value={$group_user_array['group_user_status']}>
				</div>
			</dd>
		</dl>


		<dl class="noborder">
			<dd>
				<input type="submit" name="submit" value={$submit_text} class="btn btn-primary">
			</dd>
		</dl>
	</div>
</form>


<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>