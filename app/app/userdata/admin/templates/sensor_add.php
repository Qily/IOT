<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
//PHP代码
require_once $this->template('ui/head');//引用头部UI文件

$action = $_M['form']['action'];
if($action == 'modify'){
	$action_text="修改设备信息";
	$submit_text = "修改";
} else if($action == 'add'){
	$action_text="添加设备";
	$submit_text = "保存";
}


echo <<<EOT
-->
<script type="text/javascript">
		function toOneNet(){
			window.open("https://open.iot.10086.cn/app/list?pid=89967");	
		}
</script>

<form method="POST" class="ui-from" name="sensor_form" action="{$_M[url][own_form]}a=dosensor&action=save&modify_id={$_M['form']['id']}&device_id={$_M['form']['deviceId']}">
	<div class="v52fmbx">
		<h3 class="v52fmbx_hr">{$exedaction_text}</h3>
		<dl>
			<dt>设备名称</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name='sensorName' data-required="1" value={$sensor_array['sensorName']}>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>设备描述</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<textarea name="sensorDescrip" rows='8' cols='47' value="description"}>description</textarea>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>鉴权信息</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="authInfo" data-required="1" value={$sensor_array['authInfo']}>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>类别</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="tag" data-required="1" value={$sensor_array['tag']}>
				</div>
				<span>"humi"表示湿度传感器，"temper"表示温度传感器</span>
			</dd>
		</dl>

		<dl>
			<dt>创建图表</dt>
			<dd>
				<input class="btn btn-success" type="button" name="btn2OneNet" onclick="toOneNet()" value="OneNet">
			</dd>
		</dl>
		<dl>
			<dt>解析串</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<textarea name="parseChunk" rows='8' cols='47'>{$sensor_array['parseChunk']}</textarea>
				</div>
			</dd>
		</dl>
		

		
		<dl>
			<dt>设备位置</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="sensorLoca" value={$sensor_array['sensorLoca']}>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>所属设备组号</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="groupId" data-required="1" value={$sensor_array['groupId']}>
				</div>
			</dd>
		</dl>
		<dl>
			<dt>设备状态</dt>
			<dd class="ftype_input">
				<div class="fbox">
					<input type="text" name="sensorStatus" value={$sensor_array['sensorStatus']}>
				</div>
			</dd>
		</dl>


		<dl class="noborder">
			<dd>
				<input type="submit" name="submit" value={$submit_text} class="btn btn-primary">
			</dd>
		</dl>
	</div>
</form>


<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件
?>