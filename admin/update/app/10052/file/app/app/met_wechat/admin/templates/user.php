<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=douser&action=edit" target="_self">
	<div class="v52fmbx">
		<div class="v52fmbx-table-top">
			<div class="ui-table-search">
				<i class="fa fa-search"></i>
				<input name="openid" data-table-search="1" type="text" value="" class="ui-input" placeholder="用户昵称，openid，用户id" style="width:200px;">
			</div>
		</div>
		<table class="display dataTable ui-table" data-table-ajaxurl="{$_M[url][own_form]}a=douser&action=ajax_table" data-table-pagelength="10">
			<thead>
				<tr>
					<th width="30"><input name="id" type="checkbox" data-table-chckall="id" value=""></th>
					<th width="50">用户id</th>
					<th width="60">头像</th>
					<th>昵称</th>
					<th width="80">积分</th>
					<th width="260">OPENID</th>
					<th width="40">性别</th>
					<th width="40">关注</th>
					<th width="150">地址</th>
					<th width="100">关注时间</th>
					<th width="200">操作</th>
				</tr>
			</thead>
			<tbody>
			<!--这里的数据由控件自动生成-->
			</tbody>
			<tfoot>
				<tr>
					<th><input name="id" type="checkbox" data-table-chckall="id" value=""></th>
					<th colspan="3">
						<input type="submit" name="del" value="删除用户" class="btn btn-danger" class="delet" data-confirm="确认删除？">
						<input type="submit" name="points" value="保存积分" class="btn btn-primary">
					</th>
					<th colspan="7">
						<a class="btn btn-success lc_get_all_user_btn" data-alt="导入公众平台所有关注用户OPENID">导入公众平台所有关注用户OPENID</a>
						<span style="padding-left:10px;color:red;">建议：只在第一次使用本应用时导入</span>
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!--
EOT;
require_once $this -> template('ui/foot');
?>