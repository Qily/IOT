<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加
//PHP代码
require_once $this->template('ui/head');//引用头部UI文件
$sensorType_ = DB::get_all("SELECT * FROM {$_M[table]['userdata_type']}");
$protocal_ = array('MQTT', 'HTTP', 'EDP');
$protocal = json_encode($protocal_); 
$sensorType = json_encode($sensorType_);

$css_own = $_M[url][own]."admin/templates/css/own.css";
$js_jquery = $_M[url][own]."admin/templates/js/jquery.js";
$js_own = $_M[url][own]."admin/templates/js/own.js";
$imgAdd = $_M[url][own]."img/add.png";
$imgRandom = $_M[url][own]."img/random.png";




echo <<<EOT
-->

<link href="{$css_own}" rel="stylesheet">
<form method="POST" class="ui-from">
    <div class="v52fmbx">
        <h3 class="v52fmbx_hr">添加设备</h3>
            <div class="v52fmbx">
            <dl>
                <dt>设备名称</dt>
                <dd class="ftype_input">
                    <div class="fbox">
                        <input type="text" id="device-name" data-required="1" value="{$device['name']}">
                    </div>
                </dd>
                <dt>序列号</dt>
                <dd class="ftype_input">
                    <div class="fbox">
                        
                        <table>
                        <tr>
                            <td>
                                <div class="fbox">
                                    <input type="label" disabled='true' id="device-serial-number" name="device-serial-number" data-required="1" value="{$device['serial_number']}">
                                </div>
                            </td>
                            
                            <td><img onclick="setSerial()" src="{$imgRandom}"/></td>
                        <tr>
                    </table>
                    </div>
                </dd>
            </dl>
            
        

            <dl class="column">
                <dt>协议类型</dt>
                <dd class="ftype_select">
                    <div class="fbox">
                        <select name='protocal' id="protocal">{$device['protocal']}</select>
                    </div>
                </dd>
            </dl>


            <dl class="column">
                <dt>传感器类型</dt>
                <dd class="ftype_select">
                    <table>
                        <tr>
                            <td>
                                <div class="fbox">
                                    <select class='sensor-type' id="test"></select>
                                </div>
                            </td>
                            
                            <td id="add-type-img"><img onclick="addSensor()" src="{$imgAdd}"/></td>
                        <tr>
                    </table>
                </dd>
            </dl>

            <dl class="noborder">
                <dt>OneNet</dt>
                <dd>
                    <input type="button" class="btn btn-primary" onclick="toOnet()" value="OneNet"></input>
                </dd>
            </dl>

            <dl>
                <dt>解析串</dt>
                <dd class="ftype_textarea">
                    <div class="fbox">
                        <textarea name="parse-chunk" id="parse-chunk"></textarea>
                    </div>
                </dd>
            </dl>

            <dl class="noborder">
                <dd>
                    <input type="button" class="btn btn-primary" onclick="addDevice()" value="添加"></input>
                </dd>
            </dl>
        </div>
    </div>
</form>


<script src="{$js_jquery}"></script>
<script src="{$js_own}"></script>

<script>
var protocal = $protocal;
var sensorType = $sensorType;
var site = '{$_M[url][own_form]}';
$(document).ready(function(){
    initDeviceAddMod();
    setSerial();
});

</script>
<!--
EOT;
require_once $this->template('ui/foot');//引用底部UI文件