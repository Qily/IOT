<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '数据分析';
require_once $this->template('own/header');

echo <<<EOT
-->

<div class="col-md-8">
	<div class="row">
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<form>

							<div class="col-md-4">
								<div class="row">
									<div class="form-group" style="background-color:#CBA">
										
										<label>设备名称</label>
										<div class="controls">
											<select class="form-control" id="first-sensor-name"></select>
										</div>
										
										<label>开始日期</label>
										<input type="text" id="first-start-time" class="form-control calender"/>

										<label>结束日期</label>
										<input type="text" id="first-end-time" class="form-control calender"/>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<div class="form-group" style="background-color:#ABC">
									
										<label>设备名称</label>
										<div class="controls">
											<select class="form-control" id="second-sensor-name"></select>
										</div>

										<label>开始日期</label>
										<input type="text" id="second-start-time" class="form-control calender"/>
										<label>
											结束日期
										</label>
										<input type="text" id="second-end-time" class="form-control calender"/>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<div class="form-group" style="background-color:#999999">
									
										<label>设备名称</label>
										<div class="controls">
											<select class="form-control" id="third-sensor-name"></select>
										</div>		

										<label>开始日期</label>
										<input type="text" id="third-start-time" class="form-control calender"/>
										<label>结束日期</label>
										<input type="text" id="third-end-time" class="form-control calender"/>
									</div>
								</div>
							</div>

							<input type="button" class="btn btn-primary form-control" onclick="plotAllCharts()" value="开始分析" />
						</form>
						<div id="charts">
							<div style="height: 20px"></div>
							<!--<div id="container" style="height:400px"></div>
							<div id="container1" style="height:400px"></div>
							<div id="container2" style="height:400px"></div>-->
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<div class="col-md-4">
			<div style="height: 290px"></div>
			<div id="analysis-data"></div><!-- analysis-data -->
		</div><!-- col-md-3 -->
	</div>
</div><!-- col-md-8 1+2+8(9+3)+1 -->

<div class="col-md-1">
</div>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>



<script type="text/javascript">
window.onload = function(){
	loadSelectList();
	$.datetimepicker.setLocale('ch');
	$(".calender").datetimepicker({
		//value:'2017-09-08 10:54',
		format:'Y-m-d H:i:s',
		minDate:'2017/08/01',
		step:20
	});
}

function plotAllCharts(){
	if($("#first-sensor-name").val() != '不启用'){
		if($("#container1")){
			$("#container1").remove();
		}
		$("#charts").append("<div id='container1' style='height:400px'></div>")
		var container = "container1";
		var sensorName = $("#first-sensor-name").val();
		var startTime = $("#first-start-time").val();
		var endTime = $("#first-end-time").val();
		
		if(startTime == "" || startTime == null) startTime = null;
		if(endTime == "" || endTime == null) endTime = null;
		getHistData(container, sensorName, startTime, endTime);
	}
	if($("#second-sensor-name").val() != '不启用'){
		if($("#container2")){
			$("#container2").remove();
		}
		$("#charts").append("<div id='container2' style='height:400px'></div>")
		var container = "container2";
		var sensorName = $("#second-sensor-name").val();
		var startTime = $("#second-start-time").val();
		var endTime = $("#second-end-time").val();
		
		if(startTime == "" || startTime == null) startTime = null;
		if(endTime == "" || endTime == null) endTime = null;
		getHistData(container, sensorName, startTime, endTime);
	}
	if($("#third-sensor-name").val() != '不启用'){
		if($("#container3")){
			$("#container3").remove();
		}
		$("#charts").append("<div id='container3' style='height:400px'></div>")
		var container = "container3";
		var sensorName = $("#third-sensor-name").val();
		var startTime = $("#third-start-time").val();
		var endTime = $("#third-end-time").val();
		
		if(startTime == "" || startTime == null) startTime = null;
		if(endTime == "" || endTime == null) endTime = null;
	 	
		getHistData(container, sensorName, startTime, endTime);
	}
}


function loadSelectList(){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getSensorsByLoginId',
		type:'POST',
		dataType:'json',

		success:function(data){
			//alert(data);
			//通过传回来的传感器参数获得对应的sensorName
			//将sensorName放到select中
			var html="";
			html += "<option>不启用</option>"
			for(var i = 0; i < data._count; i++){
				
				html += "<option>"+ data._data[i].sensorName +"</option>"
			}
			$("select").append(html);
			
		},
		error:function(){

		}
	});
}

function getHistData(domId, sensorName, startTime, endTime){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getHistData',
		type:'POST',
		dataType:'json',
		async:false,
		data:{sensorName:sensorName, startTime:startTime, endTime:endTime},
		success:function(data){
			//alert(sensorName+"+"+startTime+"+"+endTime);
			//alert(data);
			plot_static(data, domId);
		},
		error:function(){
			alert('获取历史数据错误');
		}
	}).responseText;
}

function plot_dynamic(){
	var dom = document.getElementById("container");
	var myChart = echarts.init(dom);
	var app = {};
	option = null;
	function randomData() {
		now = new Date(+now + oneDay);
		value = value + Math.random() * 21 - 10;
		return {
			name: now.toString(),
			value: [
				[now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'),
				Math.round(value)
			]
		}
	}

	var data = [];
	var now = +new Date(1997, 9, 3);
	var oneDay = 24 * 3600 * 1000;
	var value = Math.random() * 1000;
	for (var i = 0; i < 1000; i++) {
		data.push(randomData());
	}

	option = {
		title: {
			text: '动态数据 + 时间坐标轴'
		},
		tooltip: {
			trigger: 'axis',
			formatter: function (params) {
				params = params[0];
				var date = new Date(params.name);
				return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' : ' + params.value[1];
			},
			axisPointer: {
				animation: false
			}
		},
		xAxis: {
			type: 'time',
			splitLine: {
				show: false
			}
		},
		yAxis: {
			type: 'value',
			boundaryGap: [0, '100%'],
			splitLine: {
				show: false
			}
		},
		series: [{
			name: '模拟数据',
			type: 'line',
			showSymbol: false,
			hoverAnimation: false,
			data: data
		}]
	};

	setInterval(function () {

		for (var i = 0; i < 5; i++) {
			data.shift();
			data.push(randomData());
		}

		myChart.setOption({
			series: [{
				data: data
			}]
		});
	}, 1000);;
	if (option && typeof option === "object") {
		myChart.setOption(option, true);
	}
}

function plot_static(datastream, domId){
	//获取了相应的数据datastream
	//alert(datastream.count);
	var dom = document.getElementById(domId);
	var myChart = echarts.init(dom);
	var app = {};
	option = null;
	app.title = '多 X 轴示例';
	
	var dataX = new Array();
	var data1 = new Array();
	for(var i = 0 ; i < datastream.count; i++){
		dataX[i] = datastream.datastreams[0].datapoints[i].at;
		data1[i] = datastream.datastreams[0].datapoints[i].value;
	}
	
	dealData(domId, data1, datastream.count);

	option = {
		title: {
			text: '数据浏览'
		},
		tooltip: {
			trigger: 'axis'
		},
		legend: {
			data:['温度传感器','湿度传感器']
		},
		grid: {
			left: '3%',
			right: '4%',
			bottom: '3%',
			containLabel: true
		},
		toolbox: {
			// feature: {
			// 	saveAsImage: {}
			// }
		},
		xAxis: {
			type: 'category',
			boundaryGap: false,
			data: dataX
		},
		yAxis: {
			type: 'value'
		},
		series: [
			{
				name:'温度传感器',
				type:'line',
				stack: '总量',
				data: data1
			}
			// {
			// 	name:'联盟广告',
			// 	type:'line',
			// 	stack: '总量',
			// 	data:[220, 182, 191, 234, 290, 330]
			// },
		]
	};
	
	myChart.setOption(option);
}


function dealData(domId, data, dataLength){
	var step = 3;//阈值
	var count = 0; //表示异常数据的个数
	var eData = new Array();
	var normalData = new Array();
	var normalCount = 0;
	//判断第一二个数
	for(var i = 0; i < 2; i++)
	{
		var times = 0; //判断是否是异常数据的次数依据
		if(Math.abs(data[i]- data[i+1]) > step) times++;
		if(Math.abs(data[i]- data[i+2]) > step) times++;
		if(Math.abs(data[i]- data[i+3]) > step) times++;
		if(Math.abs(data[i]- data[i+4]) > step) times++;
		if(times>=1){
			eData[count] = data[i];
			count++;
		} else{
			normalData[normalCount] = data[i];
			normalCount++;
		}
	}
	for(var j = 2; j < dataLength-2; j++)
	{
		var times = 0; //判断是否是异常数据的次数依据
		if(Math.abs(data[j]- data[j-2]) > step) times++;
		if(Math.abs(data[j]- data[j-1]) > step) times++;
		if(Math.abs(data[j]- data[j+1]) > step) times++;
		if(Math.abs(data[j]- data[j+2]) > step) times++;
		if(times>=2){
			eData[count] = data[j];
			count++;
		} else {
			normalData[normalCount] = data[j];
			normalCount++;
		}
	}
	//判断第length-2个数和length-1个数
	for(var m = dataLength-2; m < dataLength; m++)
	{
		var times = 0; //判断是否是异常数据的次数依据
		if(Math.abs(data[i]- data[m-4]) > step) times++;
		if(Math.abs(data[i]- data[m-3]) > step) times++;
		if(Math.abs(data[i]- data[m-2]) > step) times++;
		if(Math.abs(data[i]- data[m-1]) > step) times++;
		if(times>=1){
			eData[count] = data[m];
			count++;
		} else {
			normalData[normalCount] = data[m];
			normalCount++;
		}
	}
	var avg = calcAvg(normalData, normalCount);
	var max = Math.max.apply(null, normalData);
	var min = Math.min.apply(null, normalData);
	// calcMost();
	var center = calcCenter(normalData, normalCount);

	var analysisTable = "";
	if(domId == "container1") {
		analysisTable = 'analysis-table1';
	} else if(domId == "container2"){
		analysisTable = 'analysis-table2';
	} else{
		analysisTable = 'analysis-table3'
	}
	plotChart(analysisTable, avg, max, min, center, eData, count);
}


function calcCenter(normalData, normalCount){
	normalData.sort(numAscSort);
	var i = Math.floor(normalCount/2) + 1;
	return normalData[i];
}
function numAscSort(a, b){
	return a - b;
}

function calcAvg(normalData, normalCount){
	var sum = 0;
	for(var i = 0; i < normalCount; i++){
		sum += normalData[i];
	}
	return sum/normalCount;
}

function plotChart(analysisTable, avg, max, min, center, eData, count){
	if($("#"+analysisTable)) {
		$("#"+analysisTable).remove();
	}
	var html = "";
	html += "<div id=" + analysisTable + ">";
	html += "<div id=" + analysisTable + " style='height: 80px'></div>";
	html += "<div style='height: 320px'> <table class='table'> <tbody>";
	html += "<tr><td>平均数</td>";
	html += "<td>"+avg+"</td></tr>";

	html += "<tr><td>最大值</td>";
	html += "<td>"+max+"</td></tr>";

	html += "<tr><td>最小值</td>";
	html += "<td>"+min+"</td></tr>";

	html += "<tr><td>中位数</td>";
	html += "<td>"+center+"</td></tr>";

	html += "<tr><td>可能异常值</td>";
	html += "<td>";
	for(var i = 0; i < count; i++){
		html += eData[i]+" -";
	}
	html +="</td>";
	html += "</tr>";
	
	html += "</tbody> </table> </div></div>";
	$('#analysis-data').append(html);
}
</script>
<script src="{$jquery_min_js}"></script>
<script src="{$jquery_datetimepicker_full_min_js}"></script>
<!--
require_once $this->template('own/footer');
EOT;
?>
