<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=domenu&action=table" target="_self">
	<div class="v52fmbx">
		<table class="display dataTable ui-table" data-table-ajaxurl="{$_M[url][own_form]}a=domenu&action=ajax_table" data-table-pagelength="20">
			<thead>
				<tr>
					<th width="30"><input name="id" type="checkbox" data-table-chckall="id" value=""></th>
					<th width="150">排序</th>
					<th width="200">标题</th>
					<th width="100">类型</th>
					<th>值</th>
					<th width="200">操作</th>
				</tr>
			</thead>
			<tbody>
			<!--这里的数据由控件自动生成-->
			</tbody>
			<tfoot>
				<tr>
					<th><input name="id" type="checkbox" data-table-chckall="id" value=""></th>
					<th colspan="5">
						<a href="javascript:;" class="btn btn-success" data-table-addlist="{$_M[url][own_form]}a=domenu&action=ajax_add_tr"><i class="fa fa-plus-circle"></i> 添加一级菜单</a>
						<input type="submit" name="save" value="保存菜单" class="btn btn-primary">
						<input type="submit" name="del" value="批量删除" class="btn btn-danger" class="delet" data-confirm="确认删除此菜单？">
						<a href="{$_M[url][own_form]}a=domenu&action=getmenu" class="btn btn-info" data-confirm="确定获取已发布的微信菜单？获取后现有菜单将被删除！">获取菜单</a>
						<a href="{$_M[url][own_form]}a=domenu&action=fabu" class="btn btn-warning">发布菜单</a>
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