<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('own/tmp_lib_header');
echo <<<EOT
-->
<style type="text/css">
body{background:#333333;}
.lc_radius{background:#232323;margin:0 auto;padding:10px 20px;color:#fff;text-align:center;border-radius:100px;-moz-border-radius: 100px;-webkit-border-radius: 100px;box-shadow: inset 0 5px 10px -5px #191919,0 1px 0 0 #444;-moz-box-shadow: inset 0 5px 10px -5px #191919,0 1px 0 0 #444;-webkit-box-shadow: inset 0 5px 10px -5px #191919,0 1px 0 0 #444;}
.lc_radius p{margin:0;}
</style>
	<div class="am-g">
		<div class="am-u-sm-10 am-u-md-10 am-u-lg-6 am-u-sm-centered">
			<br>
			<br>
			<img class="am-img-thumbnail" src="{$config['wechat_qrcode']}" width="100%">
			<div class="am-cf"></div>
			<br>
			<div class="lc_radius">
				<p>请长按二维码识别关注</p>
				<p>{$_M['config']['met_webname']}</p>
			</div>
		</div>
	</div>
<!--
EOT;
require_once $this -> template('own/tmp_lib_footer');
?>