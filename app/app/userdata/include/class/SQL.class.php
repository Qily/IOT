<?php
header("Content-Type: text/html; charset=utf-8");

Class SQL{
	public function __construct() {
        global $_M;
        // $this->loginId = get_met_cookie('metinfo_member_id');
    }

    public function getGroupInfo($id, $start, $size){
        global $_M;
        $userGroups = DB::get_all("SELECT * FROM {$_M[table]['userdata_group']} WHERE create_man_id = {$id} LIMIT {$start}, {$size}");
        $_userGroups = array();
        for($i = 0; $i < count($userGroups); $i++){
            // $group = $userGroups[$i];
            //根据组名获得create_id从而从user表中获取用户名
            $username = DB::get_one("SELECT username FROM {$_M[table]['user']} WHERE id = '{$userGroups[$i]['create_man_id']}'");
            
            //根据组名在userdata_device表中获得device数量
            $deviceCount = count(DB::get_all("SELECT id FROM {$_M[table]['userdata_device']} where group_id = '{$userGroups[$i]['id']}'"));
        
            $order = $i + 1;
            array_push($userGroups[$i],$order, $username['username'], $deviceCount);
        }
        return $this->encode2Json($userGroups);
        
    }


    public function getDeviceAndSensor($loginId, $start=null, $size=null){
        global $_M;
        $query = "SELECT A.id, A.name, A.location, A.serial_number, A.description,
                B.onet_data_view,
                B.onet_device_id,
                C.id as group_id,
                C.name as group_name
                FROM {$_M[table][userdata_device]} as A right join {$_M[table][userdata_onet]} as B
                ON B.device_id = A.id
                RIGHT JOIN {$_M[table]['userdata_group']} as C ON A.group_id = C.id
                WHERE A.id IS NOT NULL AND
                C.create_man_id = '{$loginId}'";
        if($start != null){
            $query = $query." LIMIT $start, $size";
        }
        $devices = DB::get_all($query);
        //通过device获得相关的sensor
        $sensors = array();
        for($in = 0; $in < count($devices); $in++){
            
            $singleDeviceSensors = DB::get_all("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE device_id = '{$devices[$in]['id']}' ORDER BY id ASC");

            for($j = 0; $j < count($singleDeviceSensors); $j++){
                //通过sensor获得相应的type
                $type = DB::get_one("SELECT * FROM {$_M[table]['userdata_type']} WHERE id = '{$singleDeviceSensors[$j]['type_id']}'");
                array_push($singleDeviceSensors[$j], $devices[$in]['id'], $type['name'], $type['data_flow'], $type['img_path'], $devices[$in]['onet_device_id']);
            }

            if($singleDeviceSensors != null){
                $sensors = array_merge($sensors, $singleDeviceSensors);
            }
        }
        /*********************************************************************
        * $devices[$i][group_name] 设备所在组号
        * $devices[$i][group_id] 设备所在组名
        * $devices[$i][onet_device_id] 设备的id号
        * $devices[$i][onet_data_view] 设备所对应的onet_data-view
        *********************devices*****************************************
        *********************sensors*****************************************
        * $sensors[$i][0] 传感器所对应的设备id号 
        * $sensors[$i][1] 传感器对应的类型名称
        * $sensors[$i][2] 传感器对应的数据流
        * $sensors[$i][3] 传感器对应的类型图片路径
        * $sensors[$i][3] 传感器对应的所在设备onet_device_id
        *********************************************************************/
        return $this->encode2Json($devices, $sensors);
        
    }


    public function getScene($loginId){
        global $_M;
        $scenes = DB::get_all("SELECT * FROM {$_M[table]['userdata_scene']} WHERE create_man_id = '{$loginId}' ORDER BY id ASC");
        if($scenes != null){
            $firstSceneId = $scenes[0]['id'];
            return $this->encode2Json($scenes, $firstSceneId);
        } else{
            return $this->encode2Json($scenes);
        }
    }

    public function encode2Json($data1, $data2=null, $data3=null){
        $obj->_data1 = $data1;
        if($data2 != null){
            $obj->_data2 = $data2;
        }
        if($data3 != null){
            $obj->_data3 = $data3;
        }
        $json_data = json_encode($obj);
        return $json_data;
    }

}
