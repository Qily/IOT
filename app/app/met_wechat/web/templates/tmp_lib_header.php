<!--<?php
defined('IN_MET') or exit('No permission');
$year = date("Y", time());
echo <<<EOT
-->
<!doctype>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title>{$news_data['page_title']}</title>
    <meta name="description" content="{$news_data['description']}">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="//cdn.bootcss.com/amazeui/2.7.2/css/amazeui.min.css">
</head>
<body>
<!--
EOT;
if ($action != 'detail') {
echo <<<EOT
-->
    <header class="am-header am-header-default">
        <h1 class="am-header-title" style="line-height:50px;margin:0">
            {$news_data['web_title']}
        </h1>
    </header>
<!--
EOT;
}
echo <<<EOT
-->
    <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6 am-u-lg-4 am-u-sm-centered">

<!--
EOT;
?>