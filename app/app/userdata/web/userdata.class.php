<?php
defined('IN_MET') or exit('No permission');//所有文件都是已这句话开头，保证系统单入口。

load::sys_class('web');//包含后台基类，“.class.php” 可以省略。

class userdata extends web {//继承后台基类。类名称要与文件名一致
    public function __construct() {
		global $_M;
        parent::__construct();//如果重写了初始化方法,一定要调用父类的初始化函数。
        $this->sensOper = load::own_class('SensorOperation', 'new');
        $this->sqlOper = load::own_class('SQL', 'new');
        $this->loginId = get_met_cookie('metinfo_member_id');
        $this->check(1);	
    }

    public function doindex(){//定义自己的方法
        global $_M;//引入全局数组
        $action = $_M[form]['action'];
        switch($action){
            case 'del':
				$delSensor = DB::get_one("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE id = {$_M[form][id]}");
                $query = "DELETE from {$_M[table]['userdata_sensor']} WHERE id= {$_M[form][id]}";
                DB::query($query);

				$this->sensOper->delSensor($delSensor[deviceId]);
                $sucToPage = $_M[url][site]."data/request_page.php?n=userdata&c=userdata&a=doindex";
                $text =" 删除成功！";
                require_once $this -> template('own/success');
                break;
            case 'up':
                $sucToPage = $_M[url][site]."data/request_page.php?n=userdata&c=userdata&a=doindex";
                $text = $_M[form]['id'];
                require_once $this -> template('own/success');
                break;
            default:
                break;

        }
	    require_once $this ->template('own/index');
    }

	public function docreategroup(){
        global $_M;
        $action = $_M[form]['action'];
        switch($action){
            case createGroup:
                $query = "INSERT INTO {$_M[table]['userdata_group']} SET
                        name = '{$_M[form]['group-name']}',
                        create_man_id = '{$this->loginId}',
                        status = 1";
                DB::query($query);
                $text="设备组添加成功！";
                $sucToPage = $_M[url][site]."data/request_page.php?n=userdata&c=userdata&a=docreategroup";
                require_once $this -> template('own/success');
                break; 
            default:
                break;
        }
		require_once $this -> template('own/create_group');
    }
    
    public function doadddevice(){
        global $_M;

        $action = $_M[form]['action'];
        switch($action){
            case 'addDevice':
                //添加设备的方法步骤
                //首先确定serial-num号在本身的数据库中有没有
                //有的话就将数据库中的内容取出来分别放到用户的数据库中
                //要复制的数据库有这些
                //1 device的
                $serial = $_M[form]['device-serial-num'];
                $deviceInLib = DB::get_one("SELECT * FROM {$_M[table][userdata_ddwl_device]} WHERE serial_number = '{$serial}'");
                if($deviceInLib){
                    $name = $_M[form]['device-name'];
                    $loca = $_M[form]['device-loca'];
                    $groupId = DB::get_one("SELECT * FROM {$_M[table][userdata_group]} WHERE name = '{$_M[form]['group-name']}'")['id'];
                    $onetId = $deviceInLib['onet_id'];
                    $desc = $_M[form]['device-desc'];

                    $query = "INSERT INTO {$_M[table][userdata_device]} SET
                            group_id = '{$groupId}',
                            onet_id = '{$onetId}',
                            name = '{$name}',
                            location = '{$loca}',
                            serial_number = '{$serial}',
                            description = '{$desc}'";
                    DB::query($query);
                    //2 device对应的sensor的
                    
                    $insertId = DB::insert_id();
                    // print_r($insertId);
                    $sensors = DB::get_all("SELECT * FROM {$_M[table]['userdata_ddwl_sensor']} WHERE device_id = '{$deviceInLib['id']}'");
                    // print_r($sensors[0]['type_id']);
                    for($i = 0; $i < count($sensors); $i++){
                        $query = "INSERT INTO {$_M[table]['userdata_sensor']} SET
                                device_id = '{$insertId}',
                                type_id = '{$sensors[$i]['type_id']}'";
                        DB::query($query);
                    }
                    //3 device对应的onenet配置,由于是用一个onet数据库，当onet_id正确联系后即可使用

                    //成功跳转
                    $text="创建成功！";
                    $sucToPage = $_M[url][site]."data/request_page.php?n=userdata&c=userdata&a=doindex";
                    require_once $this -> template('own/success');


                } else{
                    $text="序列号错误！";
                    $sucToPage = $_M[url][site]."data/request_page.php?n=userdata&c=userdata&a=doadddevice";
                    require_once $this -> template('own/success');
                }
                break;
            default:
                break;
        }
        require_once $this->template('own/add_device');
    }
	
    
    public function doanalysis(){
        global $_M;        
        require_once $this->template('own/data_analysis');
    }
    
    public function doexception(){
        global $_M;
        require_once $this->template('own/data_exception');
    }
    
    public function dosceneset(){
        global $_M;
        require_once $this->template('own/scene_set');
    }

    public function doscenedisplay(){
        global $_M;
        require_once $this->template('own/scene_display');
    }
    
    public function douploadscene(){
        global $_M;
        require_once $this->template('own/upImg');
    }
    
    public function dogetinfo(){
        global $_M;
        $action = $_M['form']['action'];
        $scenename = $_M['form']['name'];
        $loginId = get_met_cookie('metinfo_member_id');
        switch($action){
            //用于scene_set中获取场景号信息，在dosceneset()中的话，网页跳转的语句影响输出结果
            case 'getSceneId':
                $sceneData = DB::get_one("SELECT * FROM {$_M[table]['userdata_scene']} WHERE name = '{$scenename}'");
                echo($sceneData['id']);
                break;
            case 'saveDeviceInfo':
                $deviceId = $_M['form']['deviceId'];
                $query = "INSERT INTO {$_M[table]['userdata_scene_device']} SET
                        device_id = '{$deviceId}',
                        scene_id = '{$_M['form']['sceneId']}',
                        rela_width = '{$_M['form']['relaWidth']}',
                        rela_height = '{$_M['form']['relaHeight']}'";
                DB::query($query);
                echo("success");
                break;
            case 'getSceneByImgPath':
                $sceneDataForDispaly = DB::get_one("SELECT * FROM {$_M[table]['userdata_scene']} WHERE img_path = '{$_M[form]['imgPath']}'");
                echo($sceneDataForDispaly['id']);
                break;
            // case 'getAllSensors':
            //     $sensors = DB::get_all("SELECT * FROM {$_M[table]['userdata_scene_sensor']} WHERE scene_id = '{$_M[form]['sceneId']}'");
            //     $obj->_data = $sensors;
            //     $obj->_count = count($sensors);
            //     $json_data = json_encode($obj);
            //     echo($json_data);
            //     break;
            case 'getDevicesBySceneId':
                $devices = DB::get_all("SELECT * FROM {$_M[table]['userdata_scene_device']} WHERE scene_id = '{$_M[form]['sceneId']}'");
                $obj->_data = $devices;
                $obj->_count = count($devices);
                $json_data = json_encode($obj);
                echo($json_data);
                break;

            case 'getDeviceById':
                $device = DB::get_one("SELECT name,group_id FROM {$_M[table]['userdata_device']} WHERE id = '{$_M[form]['deviceId']}'");
                $obj->name = $device['name'];
                $obj->groupId = $device['group_id'];
                $json_data = json_encode($obj);
                echo($json_data);
                break;
            
            case getSensorByDeviceId:
                $sensors = DB::get_all("SELECT id,type_id FROM {$_M[table]['userdata_sensor']} WHERE device_id = '{$_M[form]['deviceId']}'");
                for($i = 0; $i < count($sensors); $i++){
                    $typeImg = DB::get_one("SELECT * FROM {$_M[table]['userdata_type']} WHERE id = '{$sensors[$i]['type_id']}'");
                    array_push($sensors[$i], $typeImg['img_path']);
                }
                $obj->_data = $sensors;
                $json_data = json_encode($obj);
                echo($json_data);
                break;

            case 'getSensorById':
                $sensor = DB::get_one("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE id = '{$_M[form]['sensorId']}'");
                $obj->name = $sensor['sensorName'];
                $obj->tag = $sensor['tag'];
                $obj->groupId = $sensor['groupId'];
                $json_data = json_encode($obj);
                echo($json_data);
                break;
            case 'saveImg':
                $loginUserId = get_met_cookie('metinfo_member_id');
                $createDate = date("Y/m/d");
                $sceneName = $_M['form']['name'];
                $imgpath = $_M['form']['imgPath'];
                $query = "INSERT INTO {$_M[table]['userdata_scene']} SET
                        name = '{$sceneName}',
                        img_path = '{$imgpath}',
                        create_man_id = '{$loginUserId}',
                        create_date ='{$createDate}'";
                DB::query($query);
                $sceneId = DB::insert_id();
                echo($sceneId);
                break;
            case 'getHistData':
                $deviceData = DB::get_one("SELECT * FROM {$_M[table]['userdata_device']} WHERE name = '{$_M['form']['deviceName']}'");

                $onet = DB::get_one("SELECT * FROM {$_M[table]['userdata_onet']} WHERE id = '{$deviceData['onet_id']}'");
                $start_time = $_M[form]['startTime'];
                $end_time = $_M[form]['endTime'];
                $limit = 100;
                $dataHists = array();
                //根据id获取相应的sensor                
                $sensors = DB::get_all("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE device_id = '{$deviceData['id']}'");
                // 根据相应的sensor获取data_flow
                for($i = 0; $i < count($sensors); $i++){
                    $type = DB::get_one("SELECT * FROM {$_M[table]['userdata_type']} WHERE id = '{$sensors[$i]['type_id']}'");
                    // arr
                    // print_r($onet['onet_device_id']."#".$type['data_flow']."#". $start_time."#".$end_time);
                    $dataHist = $this->sensOper->getHistData($onet['onet_device_id'], $type['data_flow'], $start_time,$end_time, $limit);
                    array_push($dataHists, $dataHist);
                }

                $json_data = json_encode($dataHists);
                echo($json_data);
                break;
            case 'getLastData':

                $dataHist = $this->sensOper->getHistData($_M[form]['onetDeviceId'], $_M[form]['onetDataflow']);

                $json_data = json_encode($dataHist);
                echo($json_data);
                // echo(100);
                break;
            // case 'getDevicesByLoginId':
            //     $loginId = get_met_cookie('metinfo_member_id');
            //     //找登录用户对应的组
            //     $user_groups = DB::get_all("select * from {$_M[table]['userdata_group_user']} where user_id = '{$loginId}'");
            //     $sensors = array();
            //     for($i = 0; $i < count($user_groups); $i++){
            //         //通过组找对应的传感器
            //         $sensorSingleGroup = DB::get_all("select * from {$_M[table]['userdata_sensor']} where groupId = '{$user_groups[$i]['group_id']}' ORDER BY id ASC");
            //         if($sensorSingleGroup != null){
            //         $sensors = array_merge($sensors, $sensorSingleGroup);
            //         }
            //     }
            //     $obj->_count = count($sensors);
            //     $obj->_data = $sensors;
            //     $json_data = json_encode($obj);
            //     echo($json_data);
            //     break;
            case 'getGroup':
                $userGroups = DB::get_all("SELECT * FROM {$_M[table]['userdata_group']} WHERE create_man_id = '{$loginId}' ORDER BY id ASC");
                $obj->_count = count($userGroups);
                $obj->_data = $userGroups;
                $json_data = json_encode($obj);
                echo($json_data);
                break;
            default:
                break;
        }
    }  
}
?>
