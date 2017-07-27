<!--<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
$s='$';
echo <<<EOT
-->
<link rel="stylesheet" href="{$_M[url][own]}admin/templates/css/metinfo.css?{$jsrand}" />
<style>

</style>
<div class="nowtem">
	<span>当前模板：{$_M['form']['no']}</span>
</div>
<form method="POST" name="myform" class="ui-from" action="{$_M['url']['own_form']}a=dosetsave&no={$_M['form']['no']}&pos={$_M['form']['pos']}" target="_self">
	<div class="v52fmbx temset">
		<dl>
			<dd class="ftype_description">
				配置的自定义标签能够直接在后台-外观中显示，便于用户进行设置。本页面表格列表支持拖曳排序。
			</dd>
		</dl>
		<table class="display dataTable ui-table" data-table-ajaxurl="{$_M['url']['own_form']}a=dotable_temset_json&no={$_M['form']['no']}&pos={$_M['form']['pos']}">
			<thead>
				<tr>
					<th width="25" data-table-columnclass="met-center"><input name="id" data-table-chckall="id" type="checkbox" value="" /></th>
					<th width="20" data-table-columnclass="met-center"><i class="fa fa-caret-right"></i></th>
					<th width="120" data-table-columnclass="met-center">类型</th>
					<th width="120">变量名</th>
					<th width="80">默认值</th>
					<th width="130">标题</th>
					<th>说明</th>
					<th width="80" data-table-columnclass="met-center">位置</th>
					<th width="130">编辑</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="9" class="formsubmit">
						<span class="tips" style="font-weight:normal; color:#666; margin-left:10px;">变量使用方法：{{$s}lang_变量名称}&nbsp;&nbsp;&nbsp;如：{{$s}lang_str}</span>
					</th>
				</tr>
			</tfoot>	
			<tfoot>
				<tr>
					<th class="met-center"><input name="id" data-table-chckall="id" type="checkbox" value="" /></th>
					<th width="20" class="met-center"><i class="fa fa-caret-right"></i></th>
					<th class="met-center">类型</th>
					<th>变量名</th>
					<th>默认值</th>
					<th>标题</th>
					<th>说明</th>
					<th class="met-center">位置</th>
					<th>编辑</th>
				</tr>
			</tfoot>
			<tfoot>
				<tr>
					<th colspan="9" class="formsubmit">
						<a href="#" class="ui-addlist" data-table-addlist="{$_M['url']['own_form']}a=dotable_add&pos={$_M['form']['pos']}" style="margin-right:15px;"><i class="fa fa-plus-circle"></i>增加自定义标签</a>
						<input type="submit" name="save" value="保存" class="submit" />
						<input type="submit" name="del" value="删除" data-confirm='您确定要删除选中的自定义标签吗？' class="submit" />
					</th>
				</tr>
			</tfoot>
		</table>
		<div class="remodal" data-remodal-id="modal"><div class="temset_box"></div></div>
	</div>
</form>

<!--
EOT;
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>