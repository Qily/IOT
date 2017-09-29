<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
header("Content-Type:text/html;charset=utf-8"); 
$title = '设备信息';
require_once $this->template('own/header');

echo <<<EOT
-->


		<div class="col-md-4">
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
							设备总数
						</th>
						<th>
							操作
						</th>
					</tr>
				</thead>
				<tbody>
					


<!--
EOT;
$userGroups = DB::get_all("SELECT * FROM {$_M[table]['userdata_group']} WHERE create_man_id = '{$loginId}'");
for($i = 0; $i < count($userGroups); $i++){
	$group = $userGroups[$i];
	//根据组名获得create_id从而从user表中获取用户名
	$username = DB::get_one("SELECT username FROM {$_M[table]['user']} WHERE id = '{$group['create_man_id']}'");
	
	//根据组名在userdata_device表中获得device数量
	$deviceCount = count(DB::get_all("SELECT id FROM {$_M[table]['userdata_device']} where group_id = '{$group['id']}'"));

	$order = $i + 1;
	array_push($group,$order, $username['username'], $deviceCount);
echo <<<EOT
-->
					<tr>
						<td>
							{$group[0]}
						</td>
						
						<td>
							{$group['name']}
						</td>
						<td>
							{$group[1]}
						</td>
						<td>
							{$group[2]}
						</td>
						<td>

							<a class="btn btn-danger" href="javascript:if(confirm('确定删除该组并删除组下的所有设备？！！'))location='{$urlUserdata}a=dogroupopera&action=del&id={$group['id']}'">删除</a>

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

<!--
EOT;
require_once $this->template('own/footer');
?>