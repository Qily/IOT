/*********************************************************
 * 获取每页组别信息
 */
function getGroupPage(action){
    var pageInfo = pageEvent(action);
    var site = $('#site').text();
    var getGroupSite = site + 'a=dogetinfo&action=getGroups';
    //获取相应的组别信息
    $.ajax({
        url:getGroupSite,
        dataType:'json',
        type:'POST',
        async:false,
        data:{startItem:pageInfo[0], pageSize:pageInfo[1]},
        success:function(data){
            // alert(data);
            fillGroupTable(data, pageInfo[0]);
        },
        error:function(){
            alert("error");
        }
    }).responseText;

    //填充表格
    function fillGroupTable(data, startItem){
        $("tbody").children().remove();
        var html = "";
        startItem++;
        for(var i= 0; i < data._data.length; i++){
            startItem += i;
            html += "<tr>";        
            html +="<td>"+ startItem +"</td>";
            html +="<td>"+ data._data[i]['name'] +"</td>";
            html +="<td><a class='btn btn-info' href='"+ site + "a=dodeviceuser&action=getGroupDevice&id="+ data._data[i]['id'] +"'>"+ data._data[i][1]  +"</a></td>";
            html +="<td>"+ data._data[i][0]  +"</td>";
            html +="<td>";
            html +="<a class='btn btn-info' href='"+ site + "a=doindex&action=modify&id="+ data._data[i]['id'] +"'>修改</a>";
            html +="<a class='btn btn-danger' href='"+ site + "a=doindex&action=delete&id="+ data._data[i]['id'] +"'>删除</a>";
            html +="</td>";
            html +="</tr>";
        }
        $("tbody").append(html);
    }
}

function addGroup(){
    var site = $('#site').text();
    var addGroupSite = site + "a=dogroupaddmod";
    location.href = addGroupSite;
}


/***************************************************************************
 * 获取每页device信息
 ***************************************************************************/
function getDevicePage(action){
    var pageInfo = pageEvent(action);
    var site = $('#site').text();
    var getGroupSite = site + 'a=dogetinfo&action=getDevices';
    //获取相应的组别信息
    $.ajax({
        url:getGroupSite,
        dataType:'json',
        type:'POST',
        async:false,
        data:{startItem:pageInfo[0], pageSize:pageInfo[1]},
        success:function(data){
            // alert(data);
            fillDeviceTable(data, pageInfo[0]);
        },
        error:function(){
            alert("error");
        }
    }).responseText;

    //填充表格
    function fillDeviceTable(data, startItem){
        $("tbody").children().remove();
        var html = "";
        startItem++;
        for(var i= 0; i < data._data.length; i++){
            startItem += i;
            html += "<tr>";        
            html +="<td>"+ startItem +"</td>";
            html +="<td>"+ data._data[i]['name'] +"</td>";
            html +="<td>"+ data._data[i]['serial_number']  +"</td>";
            html +="<td>"+ data._data[i]['protocal']  +"</td>";
            html +="<td><a class='btn btn-info' href='"+ site + "a=dosensor&deviceName="+ data._data[i]['name'] +"&id="+ data._data[i]['id'] +"'>"+ data._data[i][0] +"</a></td>";
            html +="<td>"+ data._data[i][1]  +"</td>";
            html +="<td>"+ data._data[i][2]  +"</td>";
            html +="<td>"+ data._data[i][3]  +"</td>";
            html +="<td>";
            html +="<a class='btn btn-info' href='"+ site + "a=doindex&action=modify&id="+ data._data[i]['id'] +"'>修改</a>";
            html +="<a class='btn btn-danger' href='"+ site + "a=doindex&action=delete&id="+ data._data[i]['id'] +"'>删除</a>";
            html +="</td>";
            html +="</tr>";
        }
        $("tbody").append(html);
    }
}


function device_add_modify(){
    var site = $('#site').text();
    var addDeviceSite = site + "a=dodeviceaddmod";
    // alert(addDeviceSite);
    location.href = addDeviceSite;
}


/***************************************************************************
 * 获取每页deviceUser信息
 ***************************************************************************/
function getDeviceUserPage(action, groupId){
    var pageInfo = pageEvent(action);
    var site = $('#site').text();
    var getGroupSite = site + 'a=dogetinfo&action=getDevicesUser';
    //获取相应的组别信息
    $.ajax({
        url:getGroupSite,
        dataType:'json',
        type:'POST',
        async:false,
        data:{startItem:pageInfo[0], pageSize:pageInfo[1], groupId:groupId},
        success:function(data){
            // alert(data);
            fillDeviceTable(data, pageInfo[0]);
        },
        error:function(){
            alert("error");
        }
    }).responseText;

    //填充表格
    function fillDeviceTable(data, startItem){
        $("tbody").children().remove();
        var html = "";
        startItem++;
        for(var i= 0; i < data._data.length; i++){
            startItem += i;
            html += "<tr>";        
            html +="<td>"+ startItem +"</td>";
;
            html +="<td>"+ data._data[i]['name'] +"</td>";
            html +="<td>"+ data._data[i]['location'] +"</td>";
            html +="<td>"+ data._data[i]['serial_number']  +"</td>";
            html +="<td>"+ data._data[i]['description']  +"</td>";
            
            html +="<td>";
            html +="<a class='btn btn-info' href='"+ site + "a=doindex&action=modify&id="+ data._data[i]['id'] +"'>修改</a>";
            html +="<a class='btn btn-danger' href='"+ site + "a=doindex&action=delete&id="+ data._data[i]['id'] +"'>删除</a>";
            html +="</td>";
            html +="</tr>";
        }
        $("tbody").append(html);
    }
}

/*********************************************************************************
    初始化page相关的操作
    current-page 用来记录当前处于第几页
    page-size 记录每页多少条记录
    max-page 记录总的记录页数
    site 记录网站地址，因为不是php文件，不能直接通过$_M[url][own_form]获得网站地址
 *********************************************************************************/
function initPage(pageSize, allCount, site){
    var html = "";
    var maxPage = 0;
    if(allCount%pageSize == 0){
        maxPage = Math.floor(allCount/pageSize) - 1;
    } else{
        maxPage = Math.floor(allCount/pageSize);
    }
	html += "<div id='current-page' hidden='true'>"+ 0 +"</div>";
	html += "<div id='page-size' hidden='true'>"+ pageSize +"</div>";
	html += "<div id='max-page' hidden='true'>"+ maxPage +"</div>";
	html += "<div id='site' hidden='true'>"+ site +"</div>";
    $(".page-info").append(html);
}

function pageEvent(action){
    var currentPage = parseInt($('#current-page').text());
    var pageSize = parseInt($('#page-size').text());
    var maxPage = parseInt($('#max-page').text());
    
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


/*************************************************************************************
 *  device_add_modify
 *************************************************************************************/


 function initDeviceAddMod(){
    addSensorOption();
    addProtocalOption();
 }

 function addSensorOption(){
    $(".sensor-type").children().remove();
    var html = "";
    for(var i = 0; i < sensorType.length; i++){
       html += "<option value='"+ sensorType[i]['id'] +"'>"+ sensorType[i]['name'] +"</option>";
    }
    $(".sensor-type").append(html);
 }

 function addSensor(){
     var html = "";

     html += "<td>";
     html += "<div class='fbox'>";
     html += "<select class='sensor-type'></select>";
     html += "</div>";
     html += "</td>";
     $("#add-type-img").before(html);
     addSensorOption();
 }

 function addProtocalOption(){
    $("#protocal").children().remove();
    var html = "";
    for(i in protocal){
       html += "<option value='"+ protocal[i] +"'>"+ protocal[i] +"</option>";
    }
    $("#protocal").append(html);
 }

function setSerial(){
    x="1234567890poiuytrewqasdfghjklmnbvcxzQWERTYUIPLKJHGFDSAZXCVBNM";
    var serialNum = "";
    for(var i = 0; i < 16; i++){
        serialNum += x.charAt(Math.ceil(Math.random()*1000)%x.length);
    }
    var serialNumWithSpace = "";
    var start = 0;
    for(var j = 0; j < (serialNum.length/4); j++){
        
        
        serialNumWithSpace += serialNum.substr(start, 4) + "-";
        start += 4;
    }
    // alert(serialNum);
    $("#device-serial-number").val(serialNumWithSpace.substr(0, 19));
}

function toOnet(){
    var onetUrl = "https://open.iot.10086.cn/app/list?pid=89967";
    window.open(onetUrl);
}

function addDevice(){
    //将字符串解析成有用信息
    var deviceName = $('#device-name').val();
    var deviceSerialNum = $("#device-serial-number").val();
    var protocal = $('#protocal').val();
    var sensorTypeIds = new Array();
    for(var i = 0; i < $('.sensor-type').length; i++){
        sensorTypeIds[i] = $('.sensor-type')[i].value;
    }
    var parseChunk = $("#parse-chunk").val();

    // var site = $('#site').text();
    var addDeviceSite = site + "a=dogetinfo&action=addDevice";
    // alert(addDeviceSite);

    $.ajax({
        url:addDeviceSite,
        type:'POST',
        // dataType:'json',
        data:{deviceName:deviceName, deviceSerialNum:deviceSerialNum, protocal:protocal, sensorTypeIds:sensorTypeIds, parseChunk:parseChunk},
        success:function(data){
            alert(data);
        },
        error:function(){
            alert("error");
        }
    });
}



/*************************************************************************************
 *  device_add_modify
 *************************************************************************************/
function getSensor(deviceName, deviceId, site){
    var getGroupSite = site + 'a=dogetinfo&action=getSensorsByDeviceId';
    //获取相应的组别信息
    $.ajax({
        url:getGroupSite,
        dataType:'json',
        type:'POST',
        async:false,
        data:{deviceId:deviceId},
        success:function(data){
            // alert(data);
            fillSensorTable(data);
        },
        error:function(){
            alert("error");
        }
    }).responseText;

    //填充表格
    function fillSensorTable(data){
        $("tbody").children().remove();
        var html = "";
        for(var i= 0; i < data._data.length; i++){
            html += "<tr>";        
            html +="<td>"+ i+1 +"</td>";
;
            html +="<td>"+ deviceName +"</td>";
            html +="<td>"+ data._data[i][0] +"</td>";
             
            html +="<td>";
            html +="<a class='btn btn-info' href='"+ site + "a=doindex&action=modify&id="+ data._data[i]['id'] +"'>修改</a>";
            html +="<a class='btn btn-danger' href='"+ site + "a=doindex&action=delete&id="+ data._data[i]['id'] +"'>删除</a>";
            html +="</td>";
            html +="</tr>";
        }
        $("tbody").append(html);
    }
}