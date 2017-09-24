<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '设备信息';
require_once $this->template('own/header');


echo <<<EOT
-->
<div class="col-md-8">
	<setion>
		<div class="col-md-5 my-form">
			
			<form role="form" action="{$urlUserdata}a=doadddevice&action=addDevice" method="POST" >
				<div class="text-center">
					<label class="my-form-title ">添加设备</label>
				</div>
				
				<div class="form-group">
					<label class="control-label">设备名称</label><label style="color:red">&nbsp;*必填</label>
					<div class="controls">
						<input type="text" placeholder="给你的设备命名，如：设备一" class="form-control" name="device-name">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">鉴权码</label><label style="color:red">&nbsp;*必填</label>
					<div class="controls">
						<input type="text" placeholder="在购买的设备上寻找授权码，如：xxxx-xxxx-xxxx-xxxx" class="form-control" name="device-serial-num">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">组别</label><label style="color:red">&nbsp;*必填</label>
					<div class="controls form-contorls">
						<select class="col-md-6 controls" id="group-option" name="group-name">
						</select>
						<input type="button" class="btn btn-info col-md-offset-1 col-md-5" value="创建组别"/>
					</div>
				</div>
				</br>
				</br>

				<div class="form-group">
					<label class="control-label">设备位置</label>
					<div class="controls">
						<input type="text" placeholder="设备所在位置，如：大棚一东1号位置" class="form-control" name="device-loca"/>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">设备描述</label>
					<div class="controls">
						<textarea placeholder="对设备的描述，如：本设备是一个测试大棚一东部的温度的装置" class="form-control" name="device-desc"></textarea>
					</div>
				</div>

				<input type="submit" class="btn btn-success col-md-6"/>
			</form>
		</div>
	</setion>


	<div class="col-md-1"></div>



	<setion>
		<div class="col-md-5 my-form">
			<form>
				<div class="text-center">
					<label class="my-form-title ">修改设备信息</label>
				</div>
				<div class="form-group">
					<label class="control-label">设备名称</label><label style="color:red">&nbsp;*必填</label>
					<div class="controls form-contorls">
						<select class="form-control" id="group-option" name="group-name">
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">鉴权码</label><label style="color:red">&nbsp;*必填</label>
					<div class="controls">
						<input type="text" placeholder="在购买的设备上寻找授权码，如：xxxx-xxxx-xxxx-xxxx" class="form-control" name="device-serial-num">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">组别</label><label style="color:red">&nbsp;*必填</label>
					<div class="controls form-contorls">
						<select class="col-md-6 controls" id="group-option" name="group-name">
						</select>
						<input type="button" class="btn btn-info col-md-offset-1 col-md-5" value="确认修改"/>
					</div>
				</div>
			</form>
		</div>	
	</setion>


	<div class="col-md-1"></div>


</div><!-- main end -->
<div class="col-md-1"></div>


<script type="text/javascript">
$(document).ready(function(){
	setGroupOption();
});

function setGroupOption(){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getGroup',
		dataType:'json',
		type:'POST',

		success:function(data){
			var html="";
			for(var i = 0; i < data._count; i++){
				html += "<option>"+ data._data[i]['name']+"</option>";
			}
			$("#group-option").append(html);
		},

		error:function(){
			// alert("error");
		}
	});
}
</script>

<!--
EOT;
require_once $this->template('own/footer');
?>