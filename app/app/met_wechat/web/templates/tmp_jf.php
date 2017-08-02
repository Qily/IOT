<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('own/tmp_lib_header');
$user['nickname'] = urldecode($user['nickname']);
echo <<<EOT
-->
	<br>
			<table class="am-table am-table-bordered am-table-centered">
			    <tr>
			        <td rowspan="2" class="am-text-middle" width="40%"><img src="{$user['headimgurl']}" alt="{$user['nickname']}" class="am-img-thumbnail"></td>
			        <td class="am-text-middle am-success">{$user['nickname']}</td>
			    </tr>
			    <tr>
			    	<td class="am-text-middle am-warning">共{$points}积分</td>
			    </tr>
			</table>
			<table class="am-table am-table-bordered am-table-centered">
				<tr class="am-active">
					<th width="25%">加减</th>
					<th width="25%">积分</th>
					<th>日期</th>
				</tr>
			</table>
			<div class="am-panel-group" id="accordion">
<!--
EOT;
$i=0;
foreach ($list['list'] as $key => $val) {
$val['points'] = $val['points']>0?$val['points']:'-';
$i++;
$date = date("Y-m-d", $val['date']);
switch ($val['type']) {
	case 'add':
		$color = 'am-text-success';
		$type = '增加';
		break;
	case 'del':
		$color = 'am-text-danger';
		$type = '减少';
		break;
	case 'new':
		$color = 'am-text-secondary';
		$type = '设置';
		break;
}
echo <<<EOT
-->
				<div class="am-panel am-panel-default">
				    <div class="am-panel-hd">
				        <h4 class="am-panel-title am-text-center" data-am-collapse="{parent: '#accordion', target: '#do-not-say-{$i}'}">
				        	<div class="am-g">
				        		<div class="am-u-sm-3 {$color}">{$type}</div>
								<div class="am-u-sm-3">{$val['points']}</div>
								<div class="am-u-sm-6">{$date}</div>
							</div>
						</h4>
				    </div>
				    <div id="do-not-say-{$i}" class="am-panel-collapse am-collapse">
				        <div class="am-panel-bd">
				            {$val['text']}
				        </div>
				    </div>
				</div>
<!--
EOT;
}
$prev = $list['page_prev']?'':'am-disabled';
$next = $list['page_next']?'':'am-disabled';
$prevurl = $list['page_prev']?$news_data['url'].'&page='.$list['page_prev']:'javascript:;';
$nexturl = $list['page_next']?$news_data['url'].'&page='.$list['page_next']:'javascript:;';
echo <<<EOT
-->
			</div>
			<ul class="am-pagination">
				<li class="am-pagination-prev {$prev}"><a href="{$prevurl}">&laquo; 上一页</a></li>
				<li class="am-pagination-next {$next}"><a href="{$nexturl}">下一页 &raquo;</a></li>
			</ul>

<!--
EOT;
require_once $this -> template('own/tmp_lib_footer');
?>