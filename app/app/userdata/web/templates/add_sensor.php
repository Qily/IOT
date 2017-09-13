<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '设备信息';
require_once $this->template('own/header');

$sensor_table = $_M['table']['userdata_sensor'];
$sensors = DB::get_all("SELECT * FROM {$sensor_table} ORDER BY id ASC");

$sensors_count = count($sensors);

$sensors_json = json_encode($sensors);



$data = array();
for($i = 0; $i < $sensors_count; ++$i){
	if($sensors[$i]['tag'] == 'humi'){
		$data[$i] = $imghumi;
	} else if($sensors[$i]['tag']== 'temper'){
		$data[$i] = $imgtemper;
	} 
}
echo <<<EOT
-->



<div class="col-md-8">
	<div class="col-md-4">
		<form role="form" action="{$urlUserdata}a=doaddsensor&action=add" method="POST">
			<div class="form-group">
				<h1>{$test}</h1>

				<label>设备名称	</label>
				<input type="text" class="form-control" name="sensorName" />
			</div>
						

			<div class="form-group">

				<label class="control-label">设备位置</label>
				<div class="controls">
					<input type="text" placeholder="" class="form-control" name="sensorLoca">
					<p class="help-block"></p>
				</div>
			</div>
						
						
			<div class="form-group">
				<label class="control-label">设备组号</label>
				<div class="controls">
					<input type="text" placeholder="" class="form-control" name="groupName">
					<p class="help-block"></p>
				</div>
			</div>

			<div class="form-group">

				<label class="control-label">设备类别</label>
				<div class="controls">
					<select class="form-control" name="sensorType">
						<option>温度传感器</option>
						<option>湿度传感器</option>
					</select>
				</div>
			</div>
						
			<div class="form-group">

				<!-- Textarea -->
				<label class="control-label">设备描述</label>
				<div class="controls">
					<div class="textarea">
						<textarea type="" class="form-control" name="sensorDesc"> </textarea>
					</div>
				</div>
			</div>
						
						
			<label class="control-label">图表类别</label>
			<div class="checkbox">
								
				<label>
					<input type="checkbox" name="dashboard"/> 仪表盘
				</label>
			</div>
			<div class="checkbox">
								
				<label>
					<input type="checkbox" name="lineChart"/> 折线图
				</label>
			</div> 
			<div class="checkbox">
								
				<label>
					<input type="checkbox" name="barGraph"/> 柱状图
				</label>
			</div> 
						

						
						
						
			<button type="submit" class="btn btn-success">
				提交申请
			</button>
		</form>
	</div>
			
	<div class="col-md-1"></div>
		
	<div class="col-md-7">
		<h2>
			添加设备指南
		</h2>
					
		<p>
		<h4>
			说明：添加设备请填写左边申请表单，表单将会在一个工作日内得到处理。
		</h4>
		</p>
		</br>
		<p>
			<h3>设备名称：</h3>请问所要创建的设备取一个名称（必填）
		</p>
		<p>
			<h3>设备位置：</h3>当前设备所处位置（可选）
		</p>
		<p>
			<h3>设备组号：</h3>请为设备指定一个设备组，若组别不存在请<a href="{$urlUserdata}a=dogroupopera">创建组别</a>（必填）
		</p>
		<p>
			<h3>设备类别：</h3>设备是属于哪一个类别，当前的类别有温度、湿度（必填）
		</p>
		<p>
			<h3>设备描述：</h3>有关设备的一些描述（选填）
		</p>
				
	</div>
</div>
		


<div class="col-md-1"></div>
</div>

<script src="{$jquery_min_js}"></script>
<script src="{$bootstrap_min_js}"></script>
<script src="{$scripts_js}"></script>
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