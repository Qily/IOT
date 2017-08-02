<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=dokeywords&action=save_table" target="_self">
	<div class="v52fmbx">
		<div class="v52fmbx-table-top">
			<div class="ui-table-search">
				<i class="fa fa-search"></i>
				<input name="search_word" data-table-search="1" type="text" value="" class="ui-input" placeholder="输入名称进行搜索" style="width:200px;">
			</div>
		</div>
		<table class="display dataTable ui-table" data-table-ajaxurl="{$_M[url][own_form]}a=dokeywords&action=ajax_table" data-table-pagelength="20">
			<thead>
				<tr>
					<th width="30"><input name="id" type="checkbox" data-table-chckall="id" value=""></th>
					<th width="150">关键词类型</th>
					<th width="200">回复内容</th>
					<th>关键词</th>
					<th width="150">操作</th>
				</tr>
			</thead>
			<tbody>
			<!--这里的数据由控件自动生成-->
			</tbody>
			<tfoot>
				<tr>
					<th><input name="id" type="checkbox" data-table-chckall="id" value=""></th>
					<th colspan="4">
						<a href="{$_M[url][own_form]}a=dokeywords&action=edit" class="btn btn-success"><i class="fa fa-plus-circle"></i> 添加关键词</a>
						<input type="submit" name="del" value="删除" class="btn btn-danger" data-confirm="确认删除？">
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