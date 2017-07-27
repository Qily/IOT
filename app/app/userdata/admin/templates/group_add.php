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


$id = $_M['form']['id'];
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=doindex&action=save&modify_id={$id}">
	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">{$action_text}</h3>

		<dl>
			<dt>设备组ID</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name='groupId' data-required="1" value={$group_array['group_id']}>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>状态</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="groupStatus" value={$group_array['group_status']}>
				</div>
				<span class="tips">0表示停用中，1表示使用中</span>
			</dd>
		</dl>
		<dl>
			<dt>组长ID</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="groupManagerId" value={$group_array['group_manager_id']}>
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