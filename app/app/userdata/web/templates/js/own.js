function getGroupInfo(pageAction){
    var pageInfo = pageEvent(pageAction);
    $.ajax({
        url:site + 'a=dogetinfo&action=getGroupInfo',
        type:'POST',
        dataType:'json',
        data:{startItem:pageInfo[0], pageSize:pageInfo[1]},
        
        success:function(data){
            // alert(data._data1[0][0]);
            fillGroupTab(data._data1);
        },
        error:function(){
            alert("error");
        }
    });

}

function fillGroupTab(data){
    var html = "";
    $("tbody").children().remove();
    for(i in data){
        html += "<tr>";
        html += "<td>"+ data[i][0] +"</td>";
        html += "<td>"+ data[i]['name'] +"</td>";
        html += "<td>"+ data[i][1] +"</td>";
        html += "<td>"+ data[i][2] +"</td>";
        html += "<td>";
        html += "<a class = 'btn btn-danger form-control' href=\"javascript:if(confirm("+"'确定删除？'"+"))location="+"'#'"+"\">删除</a>";
        html += "</td>";
        html += "</tr>";
    }
    $('#page-table').append(html);
}

/*********************************************************************************
    初始化page相关的操作
    current-page 用来记录当前处于第几页
 *********************************************************************************/
function initPage(funcName){
    var html = "";
    html += "<dl>";
    html += "<dd class=\"text-center\">";
    html += "<ul class=\"page-ul\">";
    html += "<li class='page-li'>";
    html += "<a href=\"javascript:void(0);\" onclick=\""+ funcName +"('first')\">首页</a>";
    html += "</li>&nbsp;";

    html += "<li class='page-li'>";
    html += "<a href=\"javascript:void(0);\" onclick=\""+ funcName +"('prev')\">上一页</a>";
    html += "</li>&nbsp;";

    html += "<li class='page-li'>";
    html += "<a href=\"javascript:void(0);\" onclick=\""+ funcName +"('next')\">下一页</a>";
    html += "</li>&nbsp;";

    html += "<li class='page-li'>";
    html += "<a href=\"javascript:void(0);\" onclick=\""+ funcName +"('last')\">尾页</a>";
    html += "</li>";
    html += "</ul>";
    html += "</dd>";
    html += "</dl>";

    html += "<div id='current-page' hidden='true'>"+ 0 +"</div>";
    $('#page-table').after(html)
}


function pageEvent(action){
    var currentPage = parseInt($('#current-page').text());
    var maxPage = 0;
    if(allItemCount % pageSize == 0){
        maxPage = Math.floor(allItemCount/pageSize) - 1;
    } else{
        maxPage = Math.floor(allItemCount/pageSize);
    }
    
    //响应首页尾页上一页下一页事件
    switch(action){
        case 'first':
            currentPage = 0;
            break;
        case 'next':
            if(currentPage == maxPage){
                currentPage = maxPage;
            } else{
                currentPage++;
            }
            break;
            
        case 'prev':
            if(currentPage == 0){
                currentPage = 0;
            } else{
                currentPage--;
            }
            break;
        case 'last':
            currentPage = maxPage;
            break;

    }
    $('#current-page').text(currentPage);      
    var startItem  = currentPage * pageSize;

    var pageInfo = Array();
    pageInfo[0] = startItem;
    pageInfo[1] = pageSize;
    return pageInfo;
}

/********************************************************************************
 *  设备相关操作 index.php
 ********************************************************************************/
var devices = null;
var sensors = null;

function getDeviceAndSensor(pageAction){
    var pageInfo = pageEvent(pageAction);
    // alert(pageInfo[0] + " #"+ pageInfo[1]);
    $.ajax({
        url:site + 'a=dogetinfo&action=getDeviceAndSensor',
        type:'POST',
        dataType:'json',
        data:{startItem:pageInfo[0], pageSize:pageInfo[1]},
        async:false,
        
        success:function(data){
            // alert(data);
            devices = data._data1;
            sensors = data._data2;
            fillDeviceTab();
        },
        error:function(){
            alert("error");
        }
    }).responseText;
}

function fillDeviceTab(){
    var html = "";
    $("tbody").children().remove();
    for(i in devices){
        var index = devices[i]['id'];
        html += "<tr class=\"trMain trMain"+ index +"\">";
        html += "<td><img src="+ imgExtend +" class=\"extend-merge\" id=\""+ index +"\"/></td>";
        html += "<td id=\"name\">"+ devices[i]['name']+ "</td>";
        html += "<td>"+ devices[i]['location'] +"</td>";
        html += "<td>" + devices[i]['group_name'] +"</td>";
        html += "</tr>";   
    }                     
    $('#page-table').append(html);
    initIndex();
    IniEvent();
}

function initIndex(){
    loadSensors(sensors);

    function loadSensors(sensors){
        //1 要知道图表是什么
        //2 要知道数值是什么
        //3 要知道状态是什么
        //4 要知道所属设备是什么
        for(var i = 0; i < sensors.length; i++){
            var runStop = "img/running.png";
            loadOneSensor(sensors[i][0], sensors[i][3], sensors[i]['id'], runStop);
        }
    }
    function loadOneSensor(deviceId, imgPath, sensorId, runStop){
        // alert(deviceId);
        var html = "";
        html +="<tr class=index-sensor-tr"+deviceId+">";
        html +="<td style='border:none'></td>";
        html +="<td class='index-sensor-tr'><img src="+ urlOwn + imgPath +"></td>";
        html +="<td class='index-sensor-tr' id=sensor"+ sensorId +">100</td>";
        html +="<td class='index-sensor-tr'><img src="+ urlOwn + runStop +"></td>";
        html +="</tr>";
        $(".trMain"+deviceId).after(html);
    }
}

//联合下面的TrOnClick()相应点击行事件
function IniEvent() {
	var trs = $("#tblMain .trMain");
	var extendMerges = $("#tblMain tr .extend-merge");

	for(var i = 0; i < extendMerges.length; i++){
		extendMerges[i].onclick = exAndMerge;
	}
	for (var i = 0; i < trs.length; i++) {
		trs[i].addEventListener('click', function() {
            var trs = $("#tblMain .trMain");
            for (var i = 0; i < trs.length; i++) {
                
                if (trs[i] == this) { //判断是不是当前选择的行
                    trs[i].style.background = "yellow";
                    for(var j = 0; j < devices.length; j++){
                        if(trs[i].cells[1].innerHTML == devices[j]['name']){						
                            changeView(devices[j]['onet_data_view']);
                        }
                    }
                }
                else{
                    trs[i].style.background = "white";
                }
            }
        }, false);
	}
	trs[0].style.background = "yellow";
	changeView(devices[0]['onet_data_view']);
}

function load() {
	function createApp(dom, host, openId, is_model, device_id) {
		var width = dom.clientWidth;
		var height = dom.clientHeight;
		var iframe = document.createElement('iframe');
		iframe.style.width = width + 'px';
		iframe.style.height = height + 'px';
		iframe.style.border = '0 none';
		iframe.setAttribute('frameBorder', '0');
		var src = (host || 'https://open.iot.10086.cn') + '/app/browse2?openid=' + openId;
		if (Boolean(Number(is_model))) src += '&is_model=1&device_id=' + device_id;
		iframe.setAttribute('src', src);
		dom.appendChild(iframe);
	}

	var appDomList = document.querySelectorAll('.j_10086_iotapp');
	for (var i = 0; i < appDomList.length; i++) {
		var dom = appDomList[i];
		createApp(dom, dom.getAttribute('data-host'), dom.getAttribute('data-view'), dom.getAttribute('data-is-model'), dom.getAttribute('data-device-id'));
	}
}

function changeView(dataView){
	$("#charts").attr("data-view",dataView);
	$("iframe").remove();
	load();
}

function exAndMerge(){
	var extendMerges = $("#tblMain tr .extend-merge");
	for(var i = 0; i < extendMerges.length; i++){
		if(extendMerges[i] == this){
			changeIcon(extendMerges[i]);
			changeTable(this.id);
		}
	}
	//改变伸展和关闭的图表
	function changeIcon(extendMerge){
		if(extendMerge.src == imgExtend){
			extendMerge.src = imgMerge;
		} else{
			extendMerge.src = imgExtend;
		}
		
	}
	//改变是否显示具体传感器数据	
	function changeTable(i){
		$(".index-sensor-tr"+i).toggle();
	}
}

function getSensors(){
	// 获取相应的
	for(i in sensors){
		// alert(sensors[0][4]);
		sensorId = sensors[i]['id'];
		onetDeviceId = sensors[i][4];
		onetDataflow = sensors[i][2];
		// alert(sensorId+"@"+onetDeviceId+"@"+onetDataflow);
		getLastData(sensorId, onetDeviceId, onetDataflow);
	}
}
	
function getLastData(sensorId, onetDeviceId, onetDataflow){
	$.ajax({
		url:'{$urlUserdata}a=dogetinfo&action=getLastData',
		type:'POST',
		dataType:'json',
		data:{onetDeviceId:onetDeviceId, onetDataflow:onetDataflow},
		success:function(data){
			//先找出所有的传感器
			//通过名称将传感器和td的Id联系，从而更新数据
			$("#sensor"+sensorId).text(data.datastreams[0].datapoints[0].value);
		},
		error:function(){
			//alert('获取历史数据错误');
		}
	});
}


/***********************************************************************************************
 * 数据分析有关 data_analysis
 ***********************************************************************************************/
function getAllDeviceAndSensor(){
    // var pageInfo = pageEvent(pageAction);
    // alert(pageInfo[0] + " #"+ pageInfo[1]);
    $.ajax({
        url:site + 'a=dogetinfo&action=getDeviceAndSensor',
        type:'POST',
        dataType:'json',
        // data:{startItem:pageInfo[0], pageSize:pageInfo[1]},
        async:false,
        
        success:function(data){
            // alert(data);
            devices = data._data1;
            sensors = data._data2;
        },
        error:function(){
            alert("error");
        }
    }).responseText;
}

function loadSelectList(){
	var html = "<option>不启用</option>";
	for(i in devices){
		html += "<option>"+ devices[i].name +"</option>";
	}
	$("select").append(html);
}



// 响应开始分析事件
function plotAllCharts(){
	getParam($("#first-device-name"), $('#first-start-time'), $('#first-end-time'), 1);
	getParam($("#second-device-name"), $('#second-start-time'), $('#second-end-time'), 2);
	getParam($("#third-device-name"), $('#third-start-time'), $('#third-end-time'), 3);
	function getParam(device, start, end, index){
		var container = $('#container'+index);
		if(device.val() != '不启用'){
			if(container){
				container.remove();
			}
			$("#charts").append("<div id='container" + index + "'></div>");
			var containerId = "#container" + index;
			var containerId = "container"+index;
			var deviceName = device.val();
			var startTime = start.val();
			var endTime = end.val();
			
			if(startTime == "" || startTime == null) startTime = null;
			if(endTime == "" || endTime == null) endTime = null;
			// alert($('#container'+index)+ "@" +containerId+ "@" +deviceName+ "@" + startTime+ "@" + endTime);
			getHistData(containerId, deviceName, startTime, endTime);
		}
	}
	//获得历史数据
	function getHistData(domId, deviceName, startTime, endTime){
		var sensorContainer = "";
		var deviceOnetId = "";
		var deviceId = 0;
		for(i in devices){
			if(devices[i]['name'] == deviceName){
				deviceOnetId = devices[i][4];
				deviceId = devices[i]['id'];
				// alert(deviceOnetId+"#"+deviceId);
			}
		}
		$.ajax({
			url:site + 'a=dogetinfo&action=getHistData',
			type:'POST',
			dataType:'json',
			async:false,
			data:{deviceOnetId:deviceOnetId, startTime:startTime, endTime:endTime, deviceId:deviceId},
			success:function(data){
				// alert(data);
				for(i in data){
					if(data[i].count != 0){
						$("#"+domId).append("<div id='sensor-" + domId +"-"+ i + "' style='height:300px'></div>");
						sensorContainerId = "sensor-" + domId + "-" + i;
						plot_static(data[i], sensorContainerId, deviceName);
					}
				}
			},
			error:function(){
				alert('获取'+deviceName+'历史数据错误');
			}
		}).responseText;
	}
}

function plot_static(datastream, domId, deviceName){
	//获取了相应的数据datastream
	//alert(datastream.count);
	var dom = document.getElementById(domId);
	var myChart = echarts.init(dom);
	var app = {};
	option = null;
	app.title = '多 X 轴示例';
	type="湿度";
	
	var dataX = new Array();
	var data1 = new Array();
	for(var i = 0 ; i < datastream.count; i++){
		dataX[i] = datastream.datastreams[0].datapoints[i].at;
		data1[i] = datastream.datastreams[0].datapoints[i].value;
	}
	// dealData(chartContainerId, data1, datastream.count);
	
	option = {
		title: {
			text: deviceName
		},
		tooltip: {
			trigger: 'axis'
		},
		legend: {
			data:[type]
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
		]
	};
	
	myChart.setOption(option);
	
}

/***********************************************************************
 *  add_device.php
 **********************************************************************/

function setGroupOption(){
	$.ajax({
		url:site + 'a=dogetinfo&action=getGroup',
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




/***********************************************************************
 *  scene_display.php
 **********************************************************************/

var scenes = null;
var firstSceneId = null;
function getScene(){
	$.ajax({
		url:site + 'a=dogetinfo&action=getScene',
		dataType: 'json',
		type:'POST',
		async:false,
		success:function(data){
			// alert(data._data1.length);
			scenes = data._data1;
			firstSceneId = data._data2;
		},
		error:function(){
			alert("error");
		}
	}).responseText;
}


//sceneId表示场景的id
function loadScene(sceneId){
	loadImg(sceneId);
	//加载场景对应的传感器
	//方法是通过图片路径找出对应的场景号
	//然后通过场景号找出对应的传感器id，name,rela_width, rela_height,
	//实现场景的再现
	loadDevices();

	function loadImg(sceneId){
		for(i in scenes){
			if(scenes[i]['id'] == sceneId){
				var imgPath = scenes[i].img_path;
			}
		}
		//为了获取原图像的宽度和高度
		var originImg = "<img id='originImg' hidden='true' src=" + imgPath +"/>";
		$("#scene").append(originImg);
		var img = new Image();
		img.src = $("#originImg").attr("src");
		var imgDivWidth = $(document).width() * 0.45;
		var imgDivHeight = imgDivWidth * (img.height/ img.width);

		$("#scene-child").width(imgDivWidth).height(imgDivHeight);
		$("#scene-child").attr('src', imgPath);
	}

	function loadDevices(){
		//根据sceneId获取相应的device
		$.ajax({
			url:site + 'a=dogetinfo&action=getDevicesBySceneId&sceneId='+sceneId,
			dataType:'json',
			type:'POST',
			success:function(data){
				getAllDeviceOnSingle(data._data);
			},
			error:function(){
				alert("获取场景对应的传感器出错！");
			}
		});
	}

	function getAllDeviceOnSingle(devices){
		$("#scene").children(".device").remove();
		for(i in devices){
			$.ajax({
				url:site + 'a=dogetinfo&action=getDeviceById&deviceId='+devices[i]['device_id'],
				dataType:'json',
				type:'POST',
				async:false,
				success:function(data){
					deviceOnScene(data, devices[i]['device_id'], devices[i]['rela_width'], devices[i]['rela_height']);
				},
				error:function(){
					alert("获取场景对应的传感器出错！");
				}
			}).responseText;
		}
	}

	function deviceOnScene(deviceInfo, deviceId, relaWidth, relaHeight){
		//这里要获得设备的信息（名称）
		var sleft = $("#scene-child").width() * relaWidth;
		var stop = $("#scene-child").height() * relaHeight;
		var groupImgId = deviceInfo['groupId'] % 4 + 1;
		var html = "<div class='device' id='device-in-scene"+ deviceId +"'><img class='left-img' src='"+ imgSensor + groupImgId +".png'/>"+ deviceInfo['name'] +"</div>";
		$('#scene').append(html);
		$("#device-in-scene"+deviceId).css({'position':'absolute', 'left':sleft+'px', 'top':stop+'px'});
		$("#device-in-scene"+deviceId).children(".sensor").remove();
		getSensorsUnderDevice(deviceId);
	}

	function getSensorsUnderDevice(deviceId){
		var html = "";
		var typeImgPath = "";
		$.ajax({
			url:site + 'a=dogetinfo&action=getSensorByDeviceId&deviceId='+deviceId,
			dataType:'json',
			type:'POST',
			async:false,
			success:function(data){
				// alert(data);
				// var typeImgPath = data._data[0][0];
				var sensors = data._data;
				for(i in sensors){
					var typeImgPath = data._data[i][0];
					html += "<div class='sensor' id='sensor-in-scene"+ data._data[i]['id'] +"'><img class='left-img left-padding-10' src='"+ urlOwn + typeImgPath +"'/>"+ "<span></span>" +"</div>";
				}
				$("#device-in-scene"+deviceId).append(html);
				// alert($('#sensor-in-scene'+ data._data[0]['id'] + ' span'));

				//TODO
				// $('#sensor-in-scene'+ data._data[0]['id'] + ' span').text(100);
			},
			error:function(){
				alert("获取场景对应的传感器出错！");
			}
		}).responseText;
	}
}

function loadSceneList(){
	alert(scenes.length);
	//加载场景列表
	var html = "";
	for(var i = 0; i < scenes.length; i++){
		html += "<div class='scenes-list'><a href='javascript:void(0);' onclick='loadScene("+ scenes[i]['id'] +")'><img class='left-img' src="+imgScene+">"+ scenes[i].name +"</img></a></div>";
	}
	$("#scenes-list").append(html);
}
function createScene(){
	location.href=site + "a=dosceneset";
}
	
function getSensors(){
	for(i in sensors){
		sensorId = sensors[i]['id'];
		onetDeviceId = sensors[i][4];
		onetDataflow = sensors[i][2];
		getLastData(sensorId, onetDeviceId, onetDataflow);
	}
	
}
	
function getLastData(sensorId, onetDeviceId, onetDataflow){
	$.ajax({
		url:site + 'a=dogetinfo&action=getLastData',
		type:'POST',
		dataType:'json',
		data:{onetDeviceId:onetDeviceId, onetDataflow:onetDataflow},
		success:function(data){
			//先找出所有的传感器
			//通过名称将传感器和td的Id联系，从而更新数据
			if($('#sensor-in-scene'+ sensorId + ' span')){
				$('#sensor-in-scene'+ sensorId + ' span').text(data.datastreams[0].datapoints[0].value);
			}
			//alert(data.datastreams[0].datapoints[0].value);

		},
		error:function(){
			//alert('获取历史数据错误');
		}
	});
}



/*************************************************************************
 *  scene_set.php
 ************************************************************************/
function uploadImg(){
	$("#inputfile").change(function(){
		//创建FormData对象
		var data = new FormData();
		//为FormData对象添加数据
		$.each($('#inputfile')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});

		var divImgWidth = $(document).width() * 0.45;
		//发送数据
		$.ajax({
			url:site + 'a=douploadscene&divImgWidth='+ divImgWidth,
			type:'POST',
			data:data,
			cache: false,
			contentType: false,		//不可缺参数
			processData: false,		//不可缺参数

			success:function(data){
				$("#add-img-note").hide();
				$("#btn-add-img").hide();
				if($("#feedback").children('img').length == 0) $("#feedback").html(data);
				else{
					$("#feedback").children('img').remove();
					$("#feedback").html(data);
				}
			},
			error:function(){
				alert('上传出错');
			}
		});
	});
}

function deviceList(devices){
	// alert(devices);
	var html ="";
	for(var i = 0; i < devices.length; i++) {
		deviceId = devices[i]['id'];
		groupId = devices[i]['group_id'] % 4 + 1;
		html += "<div class='device-list-scene' id='device"+ deviceId +"'><img class='left-img' src='"+ deviceImg + groupId +".png'/>"+ devices[i]['name'] +"</div>";
	}
	html += "<div id='device-count' hidden='true'>"+ devices.length +"</div>";
	// alert(html);
	$('#device-list').append(html);
	$("#device-list>div").easydrag();
}

function saveScene(){
	//首先对异常情况进行处理
	//没有导入图片等等
	var imgPath = $("#scene-img-path").val();
	if(imgPath == null || imgPath==""){
		alert("请添加相应的场景(图片)");
		return;
	}
	//对数据进行存储
	//创建一个场景对象，弹出一个输入框输入场景的名称
	var name = prompt("请输入场景名称");
	// 注意这里应该有个查重处理
	if(name!=null && name!=""){
		//保存进数据库
		saveImg(name, imgPath);
	}

	//保存场景
	function saveImg(name, imgPath){
		$.ajax({
			url:site + 'a=dogetinfo&action=saveImg',
			type:'POST',
			data:{name:name, imgPath:imgPath},
			success:function(data){
				saveDevices(data);
			},
			error:function(){
				alert('保存图片场景出错！请重新尝试...');
			}
		});
	}

	//获取scene_device的信息
	function saveDevices(sceneId){
		var divLeft = $("#feedback").offset().left;
		var divTop = $("#feedback").offset().top;
		var divRight = divLeft + $("#feedback").width();
		var divBottom = divTop + $("#feedback").height();
		// alert("saveDevice");

		for(index in devices){
			var deviceId = devices[index]['id'];

			var deviceLeft = $('#device' + deviceId).offset().left;
			
			var deviceTop = $('#device' + deviceId).offset().top;
			
			if(deviceLeft > divLeft && deviceLeft < divRight
					&& deviceTop > divTop && deviceTop < divBottom) {
				//计算出传感器相对于img的比例系数
				//将数据记录到数据库中
				//同时，也要记录id，方便对创景进行修改
				//方式和savaImg()基本相同，都是ajax请求后端存储
				var relaWidth = (deviceLeft - divLeft)/(divRight - divLeft);
				var relaHeight = (deviceTop - divTop)/(divBottom - divTop);
				//数据库字段有id（auto_increamse）,device_id, scene_id, rela_width, rela_height
				//device_id = devices[index]['id']
			
				saveToDB(deviceId, sceneId, relaWidth, relaHeight);
			}
		}
	}


	//将信息保存到数据库中
	function saveToDB(deviceId, sceneId, relaWidth, relaHeight){
		$.ajax({
			url:site + 'a=dogetinfo&action=saveDeviceInfo',
			type:'POST',
			data:{deviceId:deviceId, sceneId:sceneId, relaWidth:relaWidth, relaHeight:relaHeight},
			async:false,
			success:function(data){
				//alert("保存成功！");
			},
			error:function(){
				alert("保存信息失败，请重新尝试...");
			}
		}).responseText;
	}
}