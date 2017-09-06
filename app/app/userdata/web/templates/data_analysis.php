<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '数据分析';
require_once $this->template('own/header');

$jquery_min_js = $_M[url][own]."web/templates/js/jquery.min.js";
echo <<<EOT
-->

<div class="col-md-8">
	  <div id="container" style="height: 400px"></div>
</div>
<div class="col-md-1">
</div>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts-all-3.js"></script>

<script src="{$jquery_min_js}"></script>
<script type="text/javascript">
window.onload = function(){
	getHistData();
	
	
	
}

function getHistData(){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getHistData',
		type:'POST',
		dataType:'json',
		async:false,
		success:function(data){
			//alert(data.datastreams[0].datapoints[0].at);
			plot_static(data);
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

function plot_static(datastream){
	//获取了相应的数据datastream
	//alert(datastream.count);
	var dom = document.getElementById("container");
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
			feature: {
				saveAsImage: {}
			}
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
</script>

<!--
require_once $this->template('own/footer');
EOT;
?>
