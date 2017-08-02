<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
$openid = $_M['form']['openid']?"&openid=".$_M['form']['openid']:"";
echo <<<EOT
-->
	<div class="v52fmbx">
		<div class="v52fmbx-table-top">
			<div class="ui-table-search">
				<i class="fa fa-search"></i>
				<input name="openid" data-table-search="1" type="text" value="" class="ui-input" placeholder="用户openid" style="width:200px;">
			</div>
		</div>
		<table class="display dataTable ui-table" data-table-ajaxurl="{$_M[url][own_form]}a=dopoints&action=ajax_table{$openid}" data-table-pagelength="20">
			<thead>
				<tr>
					<th width="300">用户OPENID</th>
					<th width="60">
						<select name="type" data-table-search="1">
							<option value="">加减</option>
							<option value="add">增加</option>
							<option value="del">减少</option>
							<option value="new">设置</option>
						</select>
					</th>
					<th width="50">数值</th>
					<th width="">说明</th>
					<th width="160">时间</th>
				</tr>
			</thead>
			<tbody>
			<!--这里的数据由控件自动生成-->
			</tbody>
		</table>
	</div>
</form>
<!--
EOT;
require_once $this -> template('ui/foot');
?>