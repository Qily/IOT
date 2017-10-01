<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

require_once $this->template('own/header');
echo <<<EOT
-->


<script type="text/javascript">
var site = '{$_M['url'][site]}' + 'data/request_page.php?n=userdata&c=userdata&';
var pageSize = 1;
var allItemCount = {$deviceCount};
var imgExtend = '{$imgExtend}';
var urlOwn = '{$_M[url][own]}';
var imgMerge = '{$imgMerge}';

$(document).ready(function(){
	initPage("getDeviceAndSensor");
	getDeviceAndSensor("first");
	getSensors();
	setInterval("getSensors()",5000);
});
</script>


<div class="col-md-8">
	<div class="col-md-12">
		<div class="col-md-7">
				<table class="table" id="page-table">
					<thead>
						<tr>
							<th></th>
							<th>名称</th>
							<th>位置</th>
							<th>组别</th>
						</tr>
					</thead>

					<tbody id="tblMain">

					</tbody>
				</table>
			</div>
			<div class="col-md-5">
				<div class="j_10086_iotapp pinned" id="charts" data-host="https://open.iot.10086.cn" data-view="fcf021830dc307d45a55c4e9b2e7876c" data-pid="89967" 
						data-appid="19320" style="height:600px">
				</div>
			</div>

		</div>
	</div>
							
	<div class="col-md-1"></div>

	</div>
</div>
	
<!--
EOT;
require_once $this->template('own/footer');
?>