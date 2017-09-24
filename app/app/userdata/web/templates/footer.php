<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

echo <<<EOT
-->
<div class="col-md-12" id="copyright">
	<div class="text-center"><h5>大地物联 版权所有 2017-2020 陕ICP备8888888</h5></div>
	<p class="text-center">&copy; All Rights Reserved</p>
</div>
<img id='bottom-img' src="{$imgBottom}" style="width:100%"/>
</div>
<script src="{$jquery_min_js}"></script>
<script src="{$bootstrap_min_js}"></script>
<script src="{$easydrag}"></script>
<script src="{$jquery_datetimepicker_full_min_js}"></script>
<script src="{$js_share}"></script>
<script src="{$scripts_js}"></script>
<script src="{$js_pin}"></script>


<script type="text/javascript">
$(document).ready(function(){
	$(".pinned").pin();
	$(".nav").pin();

});
</script>
	</body>
</html>
<!--
EOT;
?>