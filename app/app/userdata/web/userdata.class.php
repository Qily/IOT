<?php
defined('IN_MET') or exit('No permission');//所有文件都是已这句话开头，保证系统单入口。

load::sys_class('web');//包含后台基类，“.class.php” 可以省略。

class userdata extends web {//继承后台基类。类名称要与文件名一致
    public function __construct() {
		global $_M;
        parent::__construct();//如果重写了初始化方法,一定要调用父类的初始化函数。
		$this->sensOper = load::own_class('SensorOperation', 'new');
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
	    require_once $this -> template('own/index');
    }

	
    public function dogroupopera(){
		global $_M;

                $action = $_M[form]['action'];
                $loginUserId = get_met_cookie('metinfo_member_id');
                switch($action){
                    case 'create':
                         //将数据插入到数据库中
                         $query = "INSERT INTO {$_M[table]['userdata_group']} SET
                                     group_id = '{$_M[form]['groupName']}',
                                     group_manager_id = '{$loginUserId}',
                                     group_status = 1";
                         DB::query($query);
                         //获取刚插入的group id
                         $insertedGroup = DB::get_one("SELECT * FROM {$_M[table]['userdata_group']} WHERE group_id = '{$_M[form]['groupName']}'");
                         //数据插入后，同时在表userdata_group_user中增加联系
                         $query = "INSERT INTO {$_M[table]['userdata_group_user']} SET
                                   group_id = '{$insertedGroup['id']}',
                                   user_id = '{$loginUserId}',
                                   group_user_status = 1";
                         DB::query($query);
                         $sucToPage = $_M[url][site]."data/request_page.php?n=userdata&c=userdata&a=dogroupopera";
                         $text =" 操作成功！";
                         require_once $this -> template('own/success');
                         break;
                    case 'del':
                         $query = "DELETE from {$_M[table]['userdata_sensor']} WHERE groupId = {$_M[form][id]}";
                         DB::query($query);

                         $query = "DELETE FROM {$_M[table]['userdata_group']} WHERE id = {$_M[form]['id']}";
                         DB::query($query);

                         $query = "DELETE FROM {$_M[table]['userdata_group_user']} WHERE group_id = {$_M[form]['id']}";
                         DB::query($query);

                         $sucToPage = $_M[url][site]."data/request_page.php?n=userdata&c=userdata&a=dogroupopera";
                         $text =" 操作成功！";
                         require_once $this -> template('own/success');
                    default:
                         break;
                }
		
		require_once $this -> template('own/group_opera');
	}

	public function doaddgroup(){
		global $_M;
		
		require_once $this -> template('own/add_group');
    }
    
    public function doaddsensor(){
        global $_M;


        $action = $_M[form]['action'];
        switch($action){
            case 'add':
                $tableType="";
                //自己的代码
                if($_M[form]['dashboard']=="on"){
                    $tableType=$tableType."db,";
                }
                if($_M[form]['lineChart']=="on"){
                $tableType=$tableType."lc,";
                }
                if($_M[form]['barGraph']=="on"){
                $tableType=$tableType."bg,";
                }
                $tableGroup = $_M[table]['userdata_group'];
                $addUsername = get_met_cookie('metinfo_member_name');

                $getGroup = DB::get_one("select * from {$_M[table]['userdata_group']} where group_id = '{$_M[form]['groupName']}'");        

                $query = "INSERT INTO {$_M[table]['userdata_post_sensor']} SET
                    sensor_name = '{$_M[form]['sensorName']}',
                    sensor_loca = '{$_M[form]['sensorLoca']}',
                    sensor_group_id = '{$getGroup['id']}',
                    sensor_type = '{$_M[form]['sensorType']}',
                    sensor_desc = '{$_M[form]['sensorDesc']}',
                    table_type = '{$tableType}',
                    add_username = '{$addUsername}'";

                DB::query($query);
                $sucToPage = $_M[url][site]."data/";
                $text = "操作成功！";
                require_once $this -> template('own/success');
                break;
            default:
                break;
        }
        require_once $this->template('own/add_sensor');
    }
	

    public function doaddgroupuser(){
        global $_M;

        //判断创建人是不是当前的组别有没有添加权限
        $loginUserId = get_met_cookie('metinfo_member_id');
        $canAddGroup = DB::get_all("SELECT * FROM {$_M[table]['userdata_group']} WHERE group_manager_id = '{$loginUserId}'");
        $action = $_M[form]['action'];

        switch($action){

            case 'del':
                    $query = "DELETE FROM {$_M[table]['userdata_group_user']} WHERE id = {$_M[form]['id']}";
                    DB::query($query);
                    $text="删除成功！";
                    $sucToPage = $_M[url][site]."data/request_page.php?n=userdata&c=userdata&a=doaddgroupuser";
                    require_once $this -> template('own/success');
                    break;


            case 'add':
                    $groupOK = 0;
                    for($i = 0; $i < count($canAddGroup); $i++){
                        if($canAddGroup[$i]['group_id'] == $_M[form]['groupName']){
                                    $insertGroupId = $canAddGroup[$i]['id'];
                                    $groupOK = 1;
                                    break;
                            }
                        }
                    $userOK = 0;

                    $canAddUser = DB::get_all("SELECT * FROM {$_M[table]['user']}");
                    for($i = 0; $i < count($canAddUser); $i++){
                        if($canAddUser[$i]['username'] == $_M[form]['username']){
                                    $insertUserId = $canAddUser[$i]['id'];
                                    $userOK = 1;
                                    break;
                            }
                        }
                    if($groupOK == 0){
                            $text = "操作失败，组名错误！";
                    } else if($userOK == 0){
                            $text = "操作失败，成员名错误！";
                    }else{
                            $query = "INSERT INTO {$_M[table]['userdata_group_user']} SET
                                            group_id = '{$insertGroupId}',
                                            user_id = '{$insertUserId}',
                                            group_user_status = 1";
                            DB::query($query);
                            $text = "操作成功！";
                    }
                    $sucToPage = $_M[url][site]."data/request_page.php?n=userdata&c=userdata&a=doaddgroupuser";
                    require_once $this -> template('own/success');
                    break;

            
            default:
                    break;
        }

        require_once $this->template('own/add_group_user');
    }
    
    public function doanalysis(){
        global $_M;
        $device_id = "10830731";
        $datastream_id = "temperature_data_flow";
        $this->sensOper->getHistData();
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
        switch($action){
            //用于scene_set中获取场景号信息，在dosceneset()中的话，网页跳转的语句影响输出结果
            case 'getSceneId':
                $sceneData = DB::get_one("SELECT * FROM {$_M[table]['userdata_scene']} WHERE name = '{$scenename}'");
                echo($sceneData['id']);
                break;
            case 'getSensorId':
                $sensorData = DB::get_one("SELECT * FROM {$_M[table]['userdata_sensor']} WHERE sensorName = '{$_M['form']['sensorname']}'");
                echo($sensorData['id']);
                break;
            case 'saveSensorinfo':
                $sensorId = $_M['form']['sensorId'];
                $query = "INSERT INTO {$_M[table]['userdata_scene_sensor']} SET
                        sensor_id = '{$sensorId}',
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
            case 'getAllSensors':
                $sensors = DB::get_all("SELECT * FROM {$_M[table]['userdata_scene_sensor']} WHERE scene_id = '{$_M[form]['sceneId']}'");
                $obj->_data = $sensors;
                $obj->_count = count($sensors);
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
                echo($query);
                break;
            default:
                break;
        }
    }
}
?>
