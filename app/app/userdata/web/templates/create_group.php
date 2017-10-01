<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
// header("Content-Type:text/html;charset=utf-8"); 
require_once $this->template('own/header');
echo <<<EOT
-->


		<div class="col-md-4">
			<table class="table" id='page-table'>
				<thead>
					<tr>
						<th></th>
						<th>
							组名
						</th>
						<th>
							创建人
						</th>
						<th>
							设备总数
						</th>
						<th>
							操作
						</th>
					</tr>
				</thead>
				<tbody>
				
					
				</tbody>
				
			</table>

			
		</div>
		<div class="col-md-1"></div>
		<div class="col-md-3">
			<div class="row">
				<div class="col-md-12">·
					
					<form role="form" action="{$urlUserdata}a=docreategroup&action=createGroup" method="POST">
						<div class="text-center">
							<label class="my-form-title ">修改设备信息</label>
						</div>
						<div class="form-group">
							 
							<label class="control-label">
								设备组名称
							</label>
							<input type="text" class="form-control" placeholder="请输入你要创建的设备组名称 如：设备组1" name="group-name">
						</div>
						<div class="form-group">
							<label class="control-label">
								用户登录密码
							</label>
							<input type="password" class="form-control" placeholder="请输入登录时的密码 如：123456" name="password">
						</div>

						<button type="submit" class="btn btn-success">
							确认创建
						</button>
					</form>
				</div>
			</div>
			
		</div>
		<div class="col-md-1"></div>
		</div>
	</div>

<script type="text/javascript">
var site = '{$_M['url'][site]}' + 'data/request_page.php?n=userdata&c=userdata&';
var pageSize = 1;
var allItemCount = {$groupCount};

$(document).ready(function(){
	initPage("getGroupInfo");
	getGroupInfo("first");
});
</script>

<!--
EOT;
require_once $this->template('own/footer');
?>