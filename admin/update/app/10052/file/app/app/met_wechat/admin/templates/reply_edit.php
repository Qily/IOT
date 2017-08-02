<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('ui/head');
$data['text'] = urldecode($data['text']);
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M[url][own_form]}a=doreply&action=save" target="_self">
	<input type="hidden" name="replyid" value="{$data['id']}">
	<div class="v52fmbx">
		<dl>
			<dt>名称</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="name" data-required="1" value="{$data['name']}">
				</div>
				<span class="tips"></span>
			</dd>
		</dl>
		<dl>
			<dt>消息类型</dt>
			<dd>
				<div class="fbox">
					<label class="radio-inline">
						<input name="msgtype" type="radio" value="text" data-required="1" data-checked="{$data['type']}" data-showhide="text_box">
						{$this->L_cn['text']}
					</label>
					<label class="radio-inline">
						<input name="msgtype" type="radio" value="image" data-showhide="image">
						{$this->L_cn['image']}
					</label>
					<label class="radio-inline">
						<input name="msgtype" type="radio" value="voice" data-showhide="voice">
						{$this->L_cn['voice']}
					</label>
					<label class="radio-inline">
						<input name="msgtype" type="radio" value="news" data-showhide="news">
						{$this->L_cn['news']}
					</label>
					<label class="radio-inline">
						<input name="msgtype" type="radio" value="column" data-showhide="column">
						{$this->L_cn['column']}
					</label>
				</div>
			</dd>
		</dl>
		<dl class="text_box none">
			<dt></dt>
			<dd class="ftype_textarea">
				<div class="fbox">
					<textarea name="text" placeholder="" style="height:300px;">{$data['text']}</textarea>
				</div>
			</dd>
		</dl>
		<dl class="image none">
			<dt>
				<input name="image_url_old" type="hidden" value="{$data['url']}"/>
				<input name="image_mediaid" type="hidden" value="{$data['mediaid']}"/>
			</dt>
			<dd class="ftype_upload">
				<div class="fbox">
					<input name="image_url" type="text" data-upload-type="doupimg" value="{$data['url']}"/>
				</div>
				<span class="tips">支持PNG\JPEG\JPG\GIF格式，文件大小建议压缩在500k以内为最佳</span>
			</dd>
		</dl>
		<dl class="voice none">
			<dt>
				<input name="voice_url_old" type="hidden" value="{$data['url']}"/>
				<input name="voice_mediaid" type="hidden" value="{$data['mediaid']}"/>
			</dt>
			<dd class="ftype_upload">
				<div class="fbox">
					<input name="voice_url" type="text" data-upload-type="doupfile" value="{$data['url']}"/>
				</div>
				<span class="tips">支持MP3\WMA\WAV格式，长度不超过60s，文件大小建议压缩到500k内为最佳</span>
			</dd>
		</dl>
		<dl class="voice image none">
			<dt></dt>
			<dd>
				<div class="fbox">
					<label class="radio-inline">
						<input name="material" type="radio" value="1">
						永久素材
					</label>
					<label class="radio-inline">
						<input name="material" type="radio" value="0">
						临时素材(3天)
					</label>
				</div>
			</dd>
		</dl>
		<dl class="news none">
			<dt></dt>
			<dd class="ftype_tags">
				<div class="fbox">
					<input name="msg_list" id="msg_list" type="hidden" data-label="|" value="{$data['msg_list']}">
				</div>
				<span class="tips">点击下方列表选择，最多一次只能发送8篇图文哦！</span>
			</dd>
		</dl>
		<dl class="news none">
			<dt></dt>
			<dd class="ftype_input">
				<div class="fbox">
					<div class="v52fmbx-table-top">
						<div>
							<input name="search_reply" data-table-search="1" type="text" value="" class="ui-input" placeholder="输入标题进行搜索" style="width:200px;">
						</div>
					</div>
					<table class="display dataTable ui-table" data-table-ajaxurl="{$_M[url][own_form]}a=donews&action=ajax_table_reply" data-table-pagelength="20">
						<thead>
							<tr>
								<th width="50">编号</th>
								<th width="100">图片</th>
								<th width="">标题</th>
								<th width="100">阅读量</th>
								<th width="200">时间</th>
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
		<dl class="column none">
			<dt>选择栏目</dt>
			<dd class="ftype_select-linkage">
				<div class="fbox" data-selectdburl="{$_M[url][own_form]}a=doreply&action=ajax_column">
					<select name="column_1" class="prov" data-checked="{$columns[0]}-{$columns[1]}"></select>
					<select name="column_2" class="city" data-checked="{$columns[2]}"></select>
					<select name="column_3" class="dist" data-checked="{$columns[3]}"></select>
				</div>
			</dd>
		</dl>
		<dl class="column none">
			<dt>推送类型</dt>
			<dd>
				<div class="fbox">
					<label class="radio-inline">
						<input name="column_4" value="0" type="radio" data-checked="{$columns[4]}">
						全部内容
					</label>
					<label class="radio-inline">
						<input name="column_4" value="1" type="radio">
						推荐内容
					</label>
				</div>
			</dd>
		</dl>
		<dl class="column none">
			<dt>推送条数</dt>
			<dd class="ftype_range">
				<div class="fbox">
					<input type="text" name="column_5" data-rangestep="1" data-rangemin="0" data-rangemax="8" value="{$columns[5]}">
					<span>最多可一次推送8条</span>
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