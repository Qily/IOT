<!--<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
echo <<<EOT
-->
<link rel="stylesheet" href="{$_M[url][own]}admin/templates/css/metinfo.css?{$jsrand}" />
<form method="POST" name="myform" class="ui-from" action="" target="_self">
	<div class="v52fmbx">
		<dl>
			<dd class="ftype_description">
				自定义标签相当于曾经的模板配置文件（lang/language_cn.ini），但是如今更为方便和灵活。未来我们会不断优化模板制作助手，因此我们非常希望大家能够提供优化建议。
				<a href="{$_M[url][site_admin]}index.php?n=appstore&c=appstore&a=doappdetail&type=app&no=10012&lang={$_M[lang]}&anyid=65" target="_blank">去提建议</a>
			</dd>
		</dl>
<table class="display dataTable ui-table" data-table-datatype="jsonp" data-table-ajaxurl="{$_M['url']['own_form']}a=dotable_temlist_json">
    <thead>
        <tr>
            <th width="200" data-table-columnclass="met-center">预览图</th>
            <th width="80" data-table-columnclass="met-center">模板编号</th>
			<th width="80">模板类型</th>
			<th>操作</th>

        </tr>
    </thead>
	<tbody>
	</tbody>
			<tfoot>
				<tr>
					<th colspan="4" class="formsubmit">
						<span class="tips" style="font-weight:normal; color:#666; margin-left:10px;">
						此处删除模板并不会删除 网站根目录/templates/ 下的模板文件夹
						</span>
					</th>
				</tr>
			</tfoot>
	<tfoot>
		<tr>
			<th colspan="4" class="formsubmit">
				<a href="{$_M['url']['own_name']}&c=temtool&a=dotemin" class="ui-addlist addtem" style="top:0px;left:0px;"><i class="fa fa-plus-circle"></i>新增模板</a>	
			</th>
		</tr>
	</tfoot>
</table>
	</div>
</form>
<div class="remodal" data-remodal-id="modal"><div class="temset_box"></div></div>
<!--
EOT;
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>