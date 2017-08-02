<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=donews&action=save" target="_self">
	<input type="hidden" name="id" value="{$news_data['id']}">
	<div class="v52fmbx">
		<dl>
			<dt>标题</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="title" data-required="1" value="{$news_data['title']}">
				</div>
			</dd>
		</dl>
		<dl>
			<dt>封面图片</dt>
			<dd class="ftype_upload">
				<div class="fbox">
					<input name="img" type="text" data-upload-type="doupimg" data-required="1" value="{$news_data['img']}"/>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>图片在文中显示</dt>
			<dd>
				<div class="fbox">
					<label class="radio-inline">
						<input name="isshow" value="1" type="radio" data-required="1" data-checked="{$news_data['isshow']}">
						显示
					</label>
					<label class="radio-inline">
						<input name="isshow" value="0" type="radio">
						不显示
					</label>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>描述</dt>
			<dd class="ftype_textarea">
				<div class="fbox">
					<textarea name="description" placeholder="">{$news_data['description']}</textarea>
				</div>
			</dd>
		</dl>
		<dl>
        	<dt>内容</dt>
			<dd class="ftype_ckeditor">
				<div class="fbox">
					<textarea name="content">{$news_data['content']}</textarea>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>阅读原文链接</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="url" value="{$news_data['url']}">
				</div>
				<span class="tips">选填，文章底部的原文链接</span>
			</dd>
		</dl>
		<dl>
			<dt>跳转外部链接</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="link" value="{$news_data['link']}">
				</div>
				<span class="tips">选填，填写后，用户点击不再打开内容页面，直接跳转到该链接</span>
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