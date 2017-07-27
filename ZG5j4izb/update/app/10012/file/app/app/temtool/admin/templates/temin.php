<!--<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M['url']['own_name']}c=temtool&a=doin" target="_self">
	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">新增模板</h3>
		<dl>
			<dt>模板文件夹</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="temname" data-required="1" value="" style="width:100px;">
				</div>
				<span class="tips">需要手动建立模板文件夹</span>
			</dd>
		</dl>
		<dl>
			<dt>模板类型</dt>
			<dd class="ftype_radio">
				<div class="fbox">
					<label><input name="devices" type="radio" value="0" data-checked="0">电脑模板</label>
					<label><input name="devices" type="radio" value="1">手机模板</label>
				</div>
			</dd>
		</dl>
		<dl>
			<dd class="ftype_description">
				如果是修改模板，请先将原模板放到 网站根目录/templates/ 里，这样保存时才能载入模板设置。
			</dd>
		</dl>
		<dl class="noborder">
			<dt> </dt>
			<dd>
				<input type="submit" name="submit" value="保存" class="submit">
			</dd>
		</dl>
	</div>
</form>

<!--
EOT;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>