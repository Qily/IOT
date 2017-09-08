<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
header("Content-Type:text/html;charset=utf-8"); 
$title = '设备信息';
require_once $this->template('own/header');

echo <<<EOT
-->


		<div class="col-md-5">
			<table class="table">
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
							操作
						</th>
					</tr>
				</thead>
				<tbody>
					


<!--
EOT;

$loginUserId = get_met_cookie("metinfo_member_id");

$loginUserGroups = DB::get_all("SELECT * From {$_M[table]['userdata_group_user']} where user_id = '{$loginUserId}'");
for($i = 0; $i < count($loginUserGroups); $i++){
      //获取组名称
      $group = DB::get_one("SELECT * From {$_M[table]['userdata_group']} where id = '{$loginUserGroups[$i]['group_id']}'");
      //获取创建人名称
      $createUser = DB::get_one("SELECT * FROM {$_M[table]['user']} where id = '{$group['group_manager_id']}'");
      $order = $i + 1;
echo <<<EOT
-->
					<tr>
						<td>
							{$order}
						</td>
						<td>
							{$group['group_id']}
						</td>
						<td>
							{$createUser['username']}
						</td>
						<td>
<!--
EOT;
if($createUser['username'] != get_met_cookie('metinfo_member_name')){
echo <<<EOT
-->
							<a class="btn btn-primary" disabled="true">删除</a>

<!--
EOT;
} else{
echo <<<EOT
-->
							<a class="btn btn-danger" href="javascript:if(confirm('确定删除该组并删除组下的所有设备？！！'))location='{$urlUserdata}a=dogroupopera&action=del&id={$group['id']}'">删除</a>
<!--
EOT;
}
echo <<<EOT
-->
						</td>
					</tr>
<!--
EOT;
}
echo <<<EOT
-->
					
				</tbody>
			</table>
		</div>

		<div class="col-md-3">
			<div class="row">
				<div class="col-md-12">·
					<h2>
						创建设备组
					</h2>
					<form role="form" action="{$urlUserdata}a=dogroupopera&action=create" method="POST">
						<div class="form-group">
							 
							<label for="exampleInputEmail1">
								设备组名称
							</label>
							<input type="text" class="form-control" name="groupName">
						</div>
						<div class="form-group">
							 
							<label>
								用户登录密码
							</label>
							<input type="password" class="form-control" name="loginPassword">
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
	<script src="{$bootstrip_min_js}"></script>
	<script src="{$jquery_min_js}"></script>
	<script src="{$scripts_js}"></script>

<!--
require_once $this->template('own/footer');
EOT;
?>