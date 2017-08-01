<!--<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

require $this->template('ui/head');
echo <<<EOT
-->
<link rel="stylesheet" href="{$_M[url][own]}admin/templates/css/metinfo.css?{$jsrand}" />
<div class="v52fmbx">
		<dl>
			<dd class="ftype_description">
				学习模板制作必须具备 HTML 以及 CSS 基础，甚至需要逐步了解 JS 和 PHP 基础。
			</dd>
		</dl>
		<h3 class="v52fmbx_hr">图文教程</h3>
		<dl>
			<dd>
				<a href="http://doc.metinfo.cn/muban/" target="_blank">模板制作从入门到精通（图文教程）</a>
			</dd>
		</dl>
		<h3 class="v52fmbx_hr">视频教程</h3>
		<dl>
			<dd>
				<a href="http://ke.qq.com/cgi-bin/courseDetail?course_id=35536" target="_blank">MetInfo模板制作视频教程（共四课时）</a>
			</dd>
		</dl>
		<h3 class="v52fmbx_hr">模板制作培训</h3>
		<dl>
			<dd>
				MetInfo官方教你做模板！
				<a href="http://ke.qq.com/cgi-bin/courseDetail?course_id=32691" target="_blank" class="ui-addlist">点此报名</a>
			</dd>
		</dl>
</div>
<!--
EOT;
require $this->template('ui/foot');

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>