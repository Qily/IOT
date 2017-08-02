<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=donews&action=del" target="_self">
	<div class="v52fmbx">
		<div class="v52fmbx-table-top">
			<div class="ui-table-search">
				<i class="fa fa-search"></i>
				<input name="search_title" data-table-search="1" type="text" value="" class="ui-input" placeholder="输入名称进行搜索" style="width:200px;">
			</div>
		</div>
		<table class="display dataTable ui-table" data-table-ajaxurl="{$_M[url][own_form]}a=donews&action=ajax_table" data-table-pagelength="20">
			<thead>
				<tr>
					<th width="30"><input name="id" type="checkbox" data-table-chckall="id" value=""></th>
					<th width="100">图片</th>
					<th width="">标题</th>
					<th width="100">阅读量</th>
					<th width="200">时间</th>
					<th width="150">操作</th>
				</tr>
			</thead>
			<tbody>
			<!--这里的数据由控件自动生成-->
			</tbody>
			<tfoot>
				<tr>
					<th><input name="id" type="checkbox" data-table-chckall="id" value=""></th>
					<th colspan="5">
						<a href="{$_M[url][own_form]}a=donews&action=edit" class="btn btn-success"><i class="fa fa-plus-circle"></i> 添加素材</a>
						<input type="submit" name="del" value="删除" class="btn btn-danger">
						<a href="{$news_url}" target="_blank" class="btn btn-warning">图文列表页面</a>
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