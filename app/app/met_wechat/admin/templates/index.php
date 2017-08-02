<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=doindex&action=save" target="_self">
	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">公众号配置</h3>
		<dl>
			<dt>微信主目录</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="LW[wechat_dir]" data-required="1" value="{$config['wechat_dir']}">
				</div>
			</dd>
		</dl>
		<dl>
			<dt>APPID</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="LW[wechat_appid]" value="{$config['wechat_appid']}">
				</div>
			</dd>
		</dl>
		<dl>
			<dt>APPSECRET</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="LW[wechat_appsecret]" value="{$config['wechat_appsecret']}">
				</div>
			</dd>
		</dl>
		<dl>
			<dt>微信二维码</dt>
			<dd class="ftype_upload">
				<div class="fbox">
					<input
						name="LW[wechat_qrcode]"
						type="text"
						data-upload-type="doupimg"
						value="{$config['wechat_qrcode']}"
					/>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>接口URL</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="" value="{$token_url}">
				</div>
				<span class="tips">复制到公众平台对应位置</span>
			</dd>
		</dl>
		<dl>
			<dt>TOKEN</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="LW[wechat_token]" data-required="1" value="{$config['wechat_token']}">
				</div>
				<span class="tips">字母数字随便填，和微信对应上就行</span>
			</dd>
		</dl>
		<h3 class="v52fmbx_hr">自带页面链接</h3>
<!--
EOT;
foreach ($link as $key => $val) {
echo <<<EOT
-->
		<dl>
			<dt>{$val['name']}</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" value="{$val['url']}">
				</div>
				<span class="tips"></span>
			</dd>
		</dl>
<!--
EOT;
}
echo <<<EOT
-->
		<dl class="noborder">
			<dd>
				<input type="submit" name="submit" value="保存" class="btn btn-primary">
			</dd>
		</dl>
	</div>
</form>
<!--
EOT;
require_once $this -> template('ui/foot');
?>