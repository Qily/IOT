<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('own/tmp_lib_header');
echo <<<EOT
-->

<div data-am-widget="list_news" class="am-list-news am-list-news-default">
    <!--列表标题-->
    <div class="am-list-news-bd">
        <ul class="am-list">
<!--
EOT;
foreach ($list['list'] as $key => $val) {
$val['title']=utf8substr($val['title'],0,10);
$val['description']=utf8substr($val['description'],0,30);
$url = $val['link']?$val['link']:$news_data['url']."&action=detail&id=".$val[id];
echo <<<EOT
-->
            <li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
                <div class="am-u-sm-4 am-list-thumb">
                    <a href="{$url}" class="">
                        <img src="../include/thumb.php?dir={$val['img']}&x=200&y=200" alt="{$val['title']}" />
                    </a>
                </div>
                <div class=" am-u-sm-8 am-list-main">
                    <h3 class="am-list-item-hd"><a href="{$url}" class="">{$val['title']}</a></h3>
                    <div class="am-list-item-text">{$val['description']}</div>
                </div>
            </li>
<!--
EOT;
}
$prev = $list['page_prev']?'':'am-disabled';
$next = $list['page_next']?'':'am-disabled';
$prevurl = $list['page_prev']?$news_data['url'].'&page='.$list['page_prev']:'javascript:;';
$nexturl = $list['page_next']?$news_data['url'].'&page='.$list['page_next']:'javascript:;';
echo <<<EOT
-->
        </ul>
    </div>
</div>
<ul class="am-pagination">
	<li class="am-pagination-prev {$prev}"><a href="{$prevurl}">&laquo; 上一页</a></li>
	<li class="am-pagination-next {$next}"><a href="{$nexturl}">下一页 &raquo;</a></li>
</ul>

<!--
EOT;
require_once $this -> template('own/tmp_lib_footer');
?>