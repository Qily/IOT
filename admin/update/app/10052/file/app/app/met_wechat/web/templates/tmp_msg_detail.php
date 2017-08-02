<!--<?php
defined('IN_MET') or exit('No permission');
require_once $this -> template('own/tmp_lib_header');
$date = date("Y-m-d", $news_data['addtime']);
$qrcodeurl = $this->LW_BASE->self_url('met_wechat', 'met_wechat', 'domsg_tmp')."&action=qrcode";
echo <<<EOT
-->
<style type="text/css">
.am-article-bd {
	overflow: hidden;
}
.am-article-bd img{
	height: auto !important;
	max-width: 100% !important;
}
</style>
<br>
<article class="am-article">
    <div class="am-article-hd">
        <h1 class="am-article-title">{$news_data['title']}</h1>
        <p class="am-article-meta" style="font-size:1.5rem;">{$date} <a href="{$qrcodeurl}">{$_M['config']['met_webname']}</a></p>
    </div>
    <hr>
    <div class="am-article-bd" style="overflow: hidden;">
<!--
EOT;
if ($news_data['isshow'] == '1') {
echo <<<EOT
-->
        <img src="{$news_data['img']}" class="img-responsive img-rounded" style="width:100%;margin: 0;">
<!--
EOT;
}
echo <<<EOT
-->
        {$news_data['content']}
        <div class="am-cf"></div>
        <br>
        <div class="am-article-footer">
            <small class="am-fl">
<!--
EOT;
if ($news_data['url']) {
echo <<<EOT
-->
                <a href="{$news_data['url']}" title="阅读原文">阅读原文</a>
<!--
EOT;
}
echo <<<EOT
-->
            </small>
            <small class="am-fr">阅读:{$news_data['all_read']}</small>
            <div class="am-cf"></div>
        </div>
    </div>
</article>
<hr>
<!--
EOT;
if ($config[cy_client_id] && $config[cy_conf]) {
echo <<<EOT
-->
<style>
#SOHUCS .cy-logo{display:none;}
</style>
<!--WAP版-->
<div id="SOHUCS" sid="news_{$news_data['id']}" ></div>
<script id="changyan_mobile_js" charset="utf-8" type="text/javascript" 
src="https://changyan.sohu.com/upload/mobile/wap-js/changyan_mobile.js?client_id={$config[cy_client_id]}&conf={$config[cy_conf]}">
</script>
<hr>
<!--
EOT;
}
echo <<<EOT
-->
<!--
EOT;
require_once $this -> template('own/tmp_lib_footer');
?>