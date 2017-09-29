<?php
defined('IN_MET') or exit('No permission');//所有文件都是已这句话开头，保证系统单入口。

load::sys_class('admin');//包含后台基类，“.class.php”可以省略。


class userdata extends admin {//继承后台基类。类名称要与文件名一致


    public function __construct() {
		global $_M;
		parent::__construct();//如果重写了初始化方法,一定要调用父类的初始化函数。

		$this->sensOper = load::own_class('SensorOperation', 'new');

		nav::set_nav(1, '组织管理', $_M['url']['own_form'].'&a=doindex');
		nav::set_nav(2, '设备管理', $_M['url']['own_form'].'&a=dodevice');
		nav::set_nav(3, '应用设置', $_M['url']['own_form'].'&a=doappset');
		nav::set_nav(4, '用户设备', $_M['url']['own_form'].'&a=dodeviceuser');

	}
	
    public function doindex(){//定义自己的方法
        global $_M;//引入全局数组
        //自己的代码
        nav::select_nav(1);
		$action = $_M[form]['action'];
		switch($action){
			case 'modify':
				break;
			default:
				break;
		}
		require $this->template('own/group');
	}

	public function dogroupaddmod(){
		global $_M;
		require $this->template('own/group_add_modify');
	}

	public function dodevice(){
		global $_M;
		nav::select_nav(2);

		require $this->template('own/device');
	}

	public function dodeviceaddmod(){
		global $_M;
		$action = $_M[form]['action'];
		switch($action){
			case 'add':
				$query = "INSERT INTO {$_M[table]['userdata_ddwl_device']} SET
						serial_number = '{$_M[form]['device_serial_number']}',
						protocal = '{$_M[form]['protocal']}'";
						
				DB::query($query);
				$deviceId = DB::insert_id();
				break;
			default:
				break;
		}
		require $this->template('own/device_add_modify');
	}


	public function dodeviceuser(){
		global $_M;
		nav::select_nav(4);
		$action = $_M[form]['action'];
		switch($action){
			case 'getGroupDevice':
				$groupId = $_M[form]['id'];
				break;
			default:
				$groupId = 0;
				break;
		}
		require $this->template('own/device_user');
	}

	public function dosensor(){
		global $_M;
		$deviceName = $_M[form]['deviceName'];
		$deviceId = $_M[form]['id'];
		$obj->deviceName =$deviceName;
		$obj->deviceId =$deviceId;
		$json_data = json_encode($obj);

		require $this->template('own/sensor');
	}
	


	public function doappset(){
		global $_M;
		nav::select_nav(3);

		require $this->template('own/appset');
	}

	public function dogetinfo(){
		global $_M;
		$action = $_M[form]['action'];
		switch($action){
			case 'getGroups':
				$groups = DB::get_all("SELECT * FROM {$_M[table]['userdata_group']} LIMIT {$_M[form]['startItem']},{$_M[form]['pageSize']}");
				for($i = 0; $i < count($groups); $i++){
					$deviceCount = count(DB::get_all("SELECT * FROM {$_M[table]['userdata_device']} WHERE group_id = {$groups[$i]['id']}"));
					$user = DB::get_one("SELECT * FROM {$_M[table]['user']} WHERE id = {$groups[$i]['create_man_id']}");
					array_push($groups[$i], $user['username'], $deviceCount);
				}
				$obj->_data = $groups;
				$json_data = json_encode($obj);
				echo($json_data);
				break;
			case 'getDevices':
				$devices = DB::get_all("SELECT * FROM {$_M[table][userdata_ddwl_device]} LIMIT {$_M[form]['startItem']}, {$_M[form]['pageSize']}");
				for($i = 0; $i < count($devices); $i++){
					$sensorCount = count(DB::get_all("SELECT * FROM {$_M[table]['userdata_ddwl_sensor']} WHERE device_id = {$devices[$i]['id']}"));
					$onet = DB::get_one("SELECT * FROM {$_M[table]['userdata_onet']} WHERE ddwl_device_id = {$devices[$i]['id']}");
					array_push($devices[$i], $sensorCount, $onet['onet_device_id'], $onet['onet_data_view'], $onet['device_id']);
				}
				$obj->_data = $devices;
				$json_data = json_encode($obj);
				echo($json_data);
				break;
			case 'getDevicesUser':
				$groupId = $_M[form]['groupId'];
				if($groupId == 0){
					$devices = DB::get_all("SELECT * FROM {$_M[table][userdata_device]} LIMIT {$_M[form]['startItem']}, {$_M[form]['pageSize']}");
				} else {
					$devices = DB::get_all("SELECT * FROM {$_M[table][userdata_device]} WHERE group_id = {$groupId} LIMIT {$_M[form]['startItem']}, {$_M[form]['pageSize']}");
				}
				$obj->_data = $devices;
				$json_data = json_encode($obj);
				echo($json_data);
				break;
			case 'addDevice':
				$deviceId = $this->sensOper->createDevice($_M[form]['deviceName'], $_M[form]['deviceSerialNum'], $_M[form]['protocal']);
				if($deviceId['device_id']){
					// $deviceSerialNum = str_replace("-", "", $_M[form]['deviceSerialNum']);
					$index = 0;
					$affectRows = 0;
				
					$query = "INSERT INTO {$_M[table]['userdata_ddwl_device']} SET
							name = '{$_M[form]['deviceName']}',
							serial_number = '{$_M[form]['deviceSerialNum']}',
							protocal = '{$_M[form]['protocal']}'";
					DB::query($query);
					$index++;
					$affectRows += DB::affected_rows();

					$lastDeviceId = DB::insert_id();

					//将传感器和设备相联系
					for($i = 0; $i < count($_M[form]['sensorTypeIds']); $i++){
						$query = "INSERT INTO {$_M[table]['userdata_ddwl_sensor']} SET
							device_id = {$lastDeviceId},
							type_id = {$_M[form]['sensorTypeIds'][$i]}";
						DB::query($query);
						$index++;
						$affectRows += DB::affected_rows();
					}

					$parseChunk = $_M[form]['parseChunk'];

					$parseSplits = explode('"', $parseChunk);
					$dataView = substr($parseSplits[5], 0, strlen($parseSplits[5])-1);
					$appid = substr($parseSplits[7], 0, strlen($parseSplits[7])-1);
					
					$query ="INSERT INTO {$_M[table]['userdata_onet']} SET
							ddwl_device_id = {$lastDeviceId},
							onet_data_view = '{$dataView}',
							onet_device_id = '{$deviceId['device_id']}',
							onet_app_id = '{$appid}',
							onet_parse_chunk = '{$parseChunk}'";					
					DB::query($query);
					$index++;
					$affectRows += DB::affected_rows();

					if($index == $affectRows){
						echo("success");
					} else{
						echo("insert database error!");
					}
				} else{
					echo("failed to create device!");
				}
				break;
			case 'getSensorsByDeviceId':
				$sensors = DB::get_all("SELECT * FROM {$_M[table]['userdata_ddwl_sensor']} where device_id = {$_M[form]['deviceId']}");
				for($i = 0; $i < count($sensors); $i++){
					$type = DB::get_one("SELECT * FROM {$_M[table]['userdata_type']} WHERE id = {$sensors[$i]['type_id']}");
					array_push($sensors[$i], $type['name']);
				}
				$obj->_data = $sensors;
				$json_data = json_encode($obj);
				echo($json_data);
				break;
		}
	}
}
?>