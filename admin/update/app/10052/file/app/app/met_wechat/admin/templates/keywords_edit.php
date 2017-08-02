<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=dokeywords&action=save" target="_self">
	<input type="hidden" name="keyid" value="{$word_data['id']}">
	<div class="v52fmbx">
		<dl>
			<dt>关键词类型</dt>
			<dd>
				<div class="fbox">
					<label class="radio-inline">
						<input name="type" type="radio" value="1" data-required="1" data-checked="{$word_data['type']}">
						精确匹配关键词
					</label>
					<label class="radio-inline">
						<input name="type" type="radio" value="2">
						模糊匹配关键词
					</label>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>优先级</dt>
			<dd class="ftype_range">
				<div class="fbox">
					<input type="text" name="level" data-rangestep="1" data-rangemin="0" data-rangemax="10" value="{$word_data['level']}">
					<span>数字越大，优先级越高，一般不要设置，0就好</span>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>关键词</dt>
			<dd class="ftype_tags">
				<div class="fbox">
					<input name="word" type="hidden" data-label="|" value="{$word_data['word']}">
				</div>
				<span class="tips">必填，可以填写多个，不超过30个字符</span>
			</dd>
		</dl>
		<dl class="replys">
			<dt>回复内容</dt>
			<dd class="ftype_tags">
				<div class="fbox">
					<input name="replyid" id="reply_list" type="hidden" data-label="|" value="{$word_data['replyid']}">
				</div>
				<span class="tips">点击下方列表选择，可以同时回复多条内容哦！</span>
			</dd>
		</dl>
		<dl>
			<dt></dt>
			<dd class="ftype_input">
				<div class="fbox" style="width:800px;">
					<div class="v52fmbx-table-top">
						<div>
							<input name="search_reply" data-table-search="1" type="text" value="" class="ui-input" placeholder="输入名称进行搜索" style="width:200px;">
						</div>
					</div>
					<table class="display dataTable ui-table" data-table-ajaxurl="{$_M[url][own_form]}a=dokeywords&action=ajax_table_reply" data-table-pagelength="20">
						<thead>
							<tr>
								<th width="50">编号</th>
								<th width="50">类型</th>
								<th width="">名称</th>
								<th width="50">操作</th>
							</tr>
						</thead>
						<tbody>
						<!--这里的数据由控件自动生成-->
						</tbody>
					</table>
				</div>
			</dd>
		</dl>

		<dl class="noborder">
			<dd>
				<input type="submit" name="save" value="保存" class="btn btn-primary">
			</dd>
		</dl>
	</div>
</form>
<!--
EOT;
require_once $this -> template('ui/foot');
?>