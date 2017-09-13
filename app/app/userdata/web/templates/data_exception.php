<!--<?php
defined('IN_MET') or exit('No permission');

$title = '数据异常';
require_once $this->template('own/header');
echo <<<EOT
-->
<script>
$(document).ready(function(){
	init();
});


     <h1>异常数据模块</h1>
</div>
<script src="{$bootstrip_min_js}"></script>
<script src="{$jquery_min_js}"></script>
<script src="{$scripts_js}"></script>
<script type="text/javascript">
$(document).ready(function(){
    init();
});

function init(){
    var height = $('#top-img').height()/2;
    var myHeight = height-24;
    logoHeight = height/2;
    $("#login-user").css({'position':'absolute', 'top':myHeight+'px'});
    $("#logo").css({'position':'absolute', 'top':logoHeight+'px'});
}
</script>
<!--
require_once $this->template('own/footer');
EOT;
?>