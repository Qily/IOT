<!--<?php
defined('IN_MET') or exit('No permission');//保持入口文件，每个应用模板都要添加

$title = '设备信息';
require_once $this->template('own/header');

$loginId = get_met_cookie('metinfo_member_id');
$user_groups = DB::get_all("select * from {$_M[table]['userdata_group_user']} where user_id = '{$loginId}'");
$sensors = array();
for($i = 0; $i < count($user_groups); $i++){
      $sensorSingleGroup = DB::get_all("select * from {$_M[table]['userdata_sensor']} where groupId = '{$user_groups[$i]['group_id']}' ORDER BY id ASC");
     if($sensorSingleGroup != null){
      $sensors = array_merge($sensors, $sensorSingleGroup);
}
}
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

$bootstrap_min_js = $_M[url][own]."web/templates/js/bootstrap.min.js";
$jquery_min_js = $_M[url][own]."web/templates/js/jquery.min.js";
$scripts_js = $_M[url][own]."web/templates/js/scripts.js";



echo <<<EOT
-->
<script>
var xmlHttp = null;

function setImg(){      

    if(window.XMLHttpRequest){
        xmlHttp = new XMLHttpRequest();
    } else {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    

    document.getElementById('scene').innerHTML='<h1>hehe</h1>';

}
</script>
	
	

<div class="col-md-8">
	<div class="col-md-12">
		<div class="col-md-8">
            <form action="{$urlUserdata}a=douploadscene" method="post">
            <table>
                <tr>
                    <th>
                        <input type="file" name="file" id="file" /> 
                    </th>
                    <th>
                        <input type="button" class="btn btn-success" name="setimg" onclick="setImg()" value="确定" />
                    </th>
                </tr>
            </table>
            </form>
            <div id="scene">
             
            </div>
        </div>
		<div class="col-md-4">
        </div>
	</div>
</div>
								
<div class="col-md-1"></div>


</div>
</div>

<script src="{$jquery_min_js}"></script>
<script src="{$bootstrap_min_js}"></script>
<script src="{$scripts_js}"></script>
<!--
require_once $this->template('own/footer');
EOT;
?>
