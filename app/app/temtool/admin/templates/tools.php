<!--<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
echo <<<EOT
-->
<link rel="stylesheet" href="{$_M[url][own]}admin/templates/css/metinfo.css?{$jsrand}" />
<div class="v52fmbx">
		<h3 class="v52fmbx_hr">常用资料</h3>
		<dl>
			<dd>
				<a href="http://edu.metinfo.cn/mblable/" target="_blank">标签大全</a>
			</dd>
		</dl>
		<dl>
			<dd>
				<a href="http://edu.metinfo.cn/mbfaq/" target="_blank">知识与技巧</a>
			</dd>
		</dl>
</div>
<!--
EOT;
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>