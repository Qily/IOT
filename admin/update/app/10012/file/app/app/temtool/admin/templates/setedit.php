<!--<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M['url']['own_name']}c=setedit&a=doedit&id={$tem['id']}&no={$tem['no']}" target="_self">
	<div class="v52fmbx">
		<div class="v52fmbx-table-top">
			<div class="ui-float-right">
				<a href="{$_M['url']['own_name']}c=temset&a=doset&no={$tem['no']}">返回</a>
			</div>
			<div class="ui-float-left">

			</div>
		</div>
		<h3 class="v52fmbx_hr">选项设置</h3>
		<dl>
			<dt>设置标题</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="valueinfo" data-required="1" value="{$tem['valueinfo']}">
				</div>
			</dd>
		</dl>
		<dl>
			<dt>位置联动</dt>
			<dd class="ftype_select-linkage">
				<div class="fbox" data-selectdburl="{$_M[url][own_name]}&c=setedit&a=dopos&no={$tem['no']}">
					<select name="pos" data-required="1" class="prov" data-checked="{$tem['pos']}"></select>  
					<select name="no_order" data-required="1" class="city" data-checked="{$tem['preid']}"></select>
				</div>
				<span class="tips">此设置将放到选中项的后一项</span>
			</dd>
		</dl>
		<dl>
			<dt>类型</dt>
			<dd class="ftype_select-linkage">
				<div class="fbox" data-selectdburl="{$_M[url][own_name]}&c=setedit&a=dotype">
					<select name="type" data-required="1" class="prov" data-checked="{$tem['type']}"></select>  
					<select name="style" data-required="1" class="city" data-checked="{$tem['style']}"></select>
				</div>
				<span class="tips">设置选项类型</span>
			</dd>
		</dl>
		<dl>
			<dt>选项设置</dt>
			<dd class="ftype_tags">
				<div class="fbox">
					<input name="selectd" type="hidden" data-label="\$M\$" value="{$tem['selectd']}">
				</div>
				<span class="tips">按“选项内容\$T\$选项值”填入选项</span>
			</dd>
		</dl>
		<dl>
			<dt>变量名称</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="name" value="{$tem['name']}">
				</div>
			</dd>
		</dl>
		<dl>
			<dt>默认值</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="value" value="{$tem['value']}">
				</div>
			</dd>
		</dl>
		<dl>
			<dt>注释</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="tips" value="{$tem['tips']}">
				</div>
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
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>