<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=doconfig&action=save" target="_self">
	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">基本回复设置</h3>
		<dl>
			<dd class="ftype_description" style="background:#337ab7;">
				特别注意：<br>
				1、这里填的是回复内容的ID，ID可以到回复管理中查询<br>
				2、可以同时回复多条消息
			</dd>
		</dl>
		<dl>
			<dt>关注自动回复</dt>
			<dd class="ftype_tags">
				<div class="fbox">
					<input name="LW[auto_reply_gz]" type="hidden" data-label="|" value="{$config['auto_reply_gz']}">
				</div>
				<span class="tips"></span>
			</dd>
		</dl>
		<dl>
			<dt>自定义回复</dt>
			<dd class="ftype_tags">
				<div class="fbox">
					<input name="LW[auto_reply_no]" type="hidden" data-label="|" value="{$config['auto_reply_no']}">
				</div>
				<span class="tips"></span>
			</dd>
		</dl>
		<h3 class="v52fmbx_hr">数据回调</h3>
		<dl>
			<dt>关注回调</dt>
			<dd class="ftype_tags">
				<div class="fbox">
					<input name="LW[backurl_gz]" type="hidden" data-label="|" value="{$config['backurl_gz']}">
				</div>
				<span class="tips">请不要随便输入内容！如果安装其它应用有提示将某个URL填入这里，请按照提示操作即可！</span>
			</dd>
		</dl>
		<h3 class="v52fmbx_hr">图文素材 畅言评论功能</h3>
		<dl>
			<dt>client_id</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input name="LW[cy_client_id]" value="{$config['cy_client_id']}">
				</div>
				<span class="tips">填入数值即开启</span>
			</dd>
		</dl>
		<dl>
			<dt>conf</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input name="LW[cy_conf]" value="{$config['cy_conf']}">
				</div>
				<span class="tips">填入数值即开启</span>
			</dd>
		</dl>
		<h3 class="v52fmbx_hr">图文消息模板选择</h3>
		<dl>
			<dt></dt>
			<dd>
				<div class="fbox">
<!--
EOT;
foreach ($msg_tmp as $key => $val) {
echo <<<EOT
-->
					<label class="radio-inline">
						<input name="LW[wechat_msg_tmp]" value="{$val['id']}" type="radio" data-checked="{$config['wechat_msg_tmp']}">
						<img src="{$_M['url']['app']}{$val['m_name']}/web/templates/images/{$val['m_action']}.png" width="100px" height="100px" style="border:1px #ccc solid;"><br>
						{$val['name']}
					</label>
<!--
EOT;
}
echo <<<EOT
-->
				</div>
			</dd>
		</dl>

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