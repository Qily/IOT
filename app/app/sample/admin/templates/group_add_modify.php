<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
//PHP代码
require_once $this->template('ui/head');//引用头部UI文件
$groups = DB::get_all("SELECT * FROM {$_M[table]['userdata_group']}");
$groupCount = count($groups);


$css_own = $_M[url][own]."admin/templates/css/own.css";
$js_jquery = $_M[url][own]."admin/templates/js/jquery.js";
$js_own = $_M[url][own]."admin/templates/js/own.js";
$site = $_M[url][own_form]."a=dogetinfo&action=getGroups";

echo <<<EOT
-->

<link href="{$css_own}" rel="stylesheet">
<div>
group_add
</div>


<script src="{$js_jquery}"></script>
<script src="{$js_own}"></script>

<script>
$(document).ready(function(){
    alert("group_add");
});

</script>
<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>