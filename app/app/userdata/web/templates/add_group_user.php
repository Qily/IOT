<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '设备信息';
require_once $this->template('own/header');
echo <<<EOT
-->


<div class="col-md-8">
	<div class="col-md-7">
                        <table class="table">
				<thead>
					<tr>
						<th></th>
						<th>
							组名
						</th>
						<th>
							成员名
						</th>
						<th>
							操作
						</th>
					</tr>
				</thead>
				<tbody>
					


<!--
EOT;
//获取登录用户创建的组下都有哪些成员，userdata_group_user
$order = 0;
for($i = 0; $i < count($canAddGroup); $i++){

       //从GroupName->Group id->userdata_group_user->user id -> user->id
      $singleGroupUsers = DB::get_all("SELECT * FROM {$_M[table]['userdata_group_user']} WHERE group_id = '{$canAddGroup[$i]['id']}'");
      
      for($j = 0; $j < count($singleGroupUsers); $j++){
            $groupUser = DB::get_one("SELECT * FROM {$_M[table]['user']} WHERE id = '{$singleGroupUsers[$j]['user_id']}'");
            $order++; 
      
echo <<<EOT
-->
                                         <tr>
						<td>
							{$order}
						</td>
						<td>
							{$canAddGroup[$i]['group_id']}
						</td>
						<td>
							{$groupUser['username']}
						</td>
						<td>
<!--
EOT;
if($groupUser['username'] == get_met_cookie('metinfo_member_name')){
echo <<<EOT
-->
                                                         <a class="btn btn-primary" disabled="true">删除</a>

<!--
EOT;
}else{
echo <<<EOT
-->

							<a  href="javascript:if(confirm('确定删除？'))location='{$urlUserdata}a=doaddgroupuser&action=del&id={$singleGroupUsers[$j]['id']}'" class="btn btn-danger">删除</a>

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
}
echo <<<EOT
-->
					
				</tbody>
			</table>
		
</div>
							
<div class="col-md-1"></div>
						
<div class="col-md-4">
	<form action="{$urlUserdata}a=doaddgroupuser&action=add" method="POST">
                        <label>当前可以操作的组名有：</label>                              
<!--
EOT;
for($i = 0; $i < count($canAddGroup); $i++){
echo <<<EOT
-->
                                 {$canAddGroup[$i]['group_id']}&nbsp;&nbsp
<!--
EOT;
}
echo <<<EOT
-->

                        <p></p>
			<div class="form-group">
				<label class="control-label">组名</label>
				<div class="controls">
					<input type="text" placeholder="" data-required="1" class="form-control" name="groupName">
					<p class="help-block"></p>
				</div>
			</div>
										
										
			<div class="form-group">
				<label class="control-label">成员名</label>
				<div class="controls">
					<input type="text" placeholder="" class="form-control" name="username">
					<p class="help-block"></p>
				</div>
			</div>
		
			<input type="submit" class="btn btn-success" value="添加"/>
	</form>
         <p></p>
         <h2>组成员添加指南</h2>
									
	<p>
		<h4>说明：添加组成员后该成员能看到该组内所有的设备</h4>
	</p>
	</br>
	<p>
		<h3>组名：</h3>组名称，改组必须为本人创建的组别（必填）
	</p>
	<p>
		<h3>成员名：</h3>所要添加人的用户名（必填）
	</p>
</div>
						
<div class="col-md-1"></div>
</div>

<script src="{$jquery_min_js}"></script>
<script src="{$bootstrap_min_js}"></script>
<script src="{$scripts_js}"></script>
<!--
require_once $this->template('own/footer');
EOT;
?>