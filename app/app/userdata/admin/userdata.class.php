<?php
defined('IN_MET') or exit('No permission');//所有文件都是已这句话开头，保证系统单入口。

load::sys_class('admin');//包含后台基类，“.class.php”可以省略。


class userdata extends admin {//继承后台基类。类名称要与文件名一致


    public function __construct() {
		global $_M;
        parent::__construct();//如果重写了初始化方法,一定要调用父类的初始化函数。
		
		nav::set_nav(1, '组织管理', $_M['url']['own_form'].'&a=doindex');
		nav::set_nav(2, '组用户管理', $_M['url']['own_form'].'&a=dogroupuser');
		nav::set_nav(3, '设备管理', $_M['url']['own_form'].'&a=dosensor');
		nav::set_nav(4, '应用设置', $_M['url']['own_form'].'&a=doappset');
		nav::set_nav(5, '用户申请', $_M['url']['own_form'].'&a=dogetapply');
    }
    public function doindex(){//定义自己的方法
        global $_M;//引入全局数组
        //自己的代码
        nav::select_nav(1);
		$action = $_M['form']['action'];
		$id = $_M['form']['modify_id'];
		$status = $_M['form']['group_status'];
		$table_group = $_M['table']['userdata_group'];
		
		switch($action){
			
			case 'save':
				if($id){
					$query = "UPDATE {$table_group} SET
							group_id = '{$_POST['groupId']}',
							group_manager_id = '{$_POST['groupManagerId']}',
							group_status = '{$_POST['groupStatus']}'
							WHERE id = {$id}";
				} else{
					$query = "INSERT INTO {$table_group} SET
							group_id = '{$_POST['groupId']}',
							group_manager_id = '{$_POST['groupManagerId']}',
							group_status = '{$_POST['groupStatus']}'";
				}
				DB::query($query);
				break;
			case 'delete':
				$query = "DELETE FROM {$table_group} WHERE id = {$_M['form']['id']}";
				DB::query($query);
				break;
			case 'changeStatus':
				$status1 = 0;
				if($status == 0){
					$status1 = 1;
				}
				$query = "UPDATE {$table_group} SET
						group_status = '{$status1}' 
						WHERE id = '{$_M['form']['id']}'";
				DB::query($query);
				break;
			default:
				break;
		}
		require $this->template('own/group');
	}

	public function dogroupadd(){//定义自己的方法
        global $_M;//引入全局数组
		nav::select_nav(1);		
		$action = $_M[form]['action'];
		$table_group = $_M[table]['userdata_group'];
		
		
		switch($action){
			case 'add':
				//各个位置的值是空值
				break;
			case 'modify':
				//各个位置的值为当前的id中对应的值
				$group_array = DB::get_one("SELECT * FROM {$table_group} WHERE id = {$_M[form]['id']}");
				break;
			default:
				break;
		}
		
		require $this->template('own/group_add');
    }
	
	
	
	public function dogroupuser(){
		global $_M;
		nav::select_nav(2);
		$action = $_M[form]['action'];
		$table_group_user = $_M[table]['userdata_group_user'];
		$table_group = $_M[table]['userdata_group'];
		echo $_M[form]['groupId'];

		switch($action){
			case 'save':
				//根据设备组唯一ID确定met_userdata_group的id
				//根据用户名确定met_user的ID
				//联合表
				
				$group_array = DB::get_one("SELECT * FROM {$table_group} WHERE group_id = '{$_M[form]['groupId']}'");
				$user_array = DB::get_one("SELECT * FROM {$_M[table]['user']} where username = '{$_M[form]['username']}'");

				if($_M[form]['modify_id']){
					$query = "UPDATE {$table_group_user} SET
						group_id = '{$group_array['id']}',
						user_id = '{$user_array['id']}',
						group_user_status = '{$_M[form]['groupUserStatus']}'
						WHERE id = {$_M[form]['modify_id']}";
				
				} else {
					$query = "INSERT INTO {$table_group_user} SET
						group_id = '{$group_array['id']}',
						user_id = '{$user_array['id']}',
						group_user_status = '{$_M[form]['groupUserStatus']}'";
				}
				DB::query($query);
				break;
			case 'modify':
				break;
			
			case 'changeStatus':
				$group_user_status1 = 0;
				if($_M[form]['group_user_status'] == 0){
					$group_user_status1 = 1;
				}
				$query = "UPDATE {$table_group_user} SET
						group_user_status = '{$group_user_status1}' 
						WHERE id = {$_M[form]['id']}";
				DB::query($query);
				break;
			case 'delete':
				$query = "DELETE FROM {$table_group_user} WHERE id = {$_M['form']['id']}";
				DB::query($query);
				break;
			default:
				break;
			
		}
		require $this->template('own/group_user');
	}

	public function dogroupuseradd(){
		global $_M;
		nav::select_nav(2);

		$action = $_M[form]['action'];
		$table_group_user = $_M[table]['userdata_group_user'];

		switch($action){
			case 'add':
				break;
			case 'modify':
				$group_user_array = DB::get_one("select * from {$table_group_user} where id = {$_M[form]['id']}");
				break;
		}

		require $this->template('own/group_user_add');
	}








	public function dosensor(){
		global $_M;
		nav::select_nav(3);
		
		$action =$_M[form]['action'];
		
		$sensor_table = $_M[table]['userdata_sensor'];

		

		switch($action){
			case 'save':
				//save需要处理的内容有以下几点
				// 1. 将表格中的内容获取到（包括title description tags）用来创建设备
				// 2、通过在sensor_add页面中创建应用，得到相关的解析串，并解析出有用的内容
				// 3. 将所有的信息集中到一起，再存储到数据库中
				/*********************************************************************************************************************
				*  1 后面要做到加锁，防止多个人一起添加，引起数据错误
				*  2 添加设备失败的时候，无效数据不能写到数据库中
				*  3 不能出现设备添加成功但是数据却因为某些原因不能写到数据库中（比如关联的公司id号没有写正确，这种情况是无法添加的）
				*/
				$id  = $_M[form]['modify_id'];

				$sensor_title = $_M[form]['sensorName'];
				$sensor_desc = $_M[form]['sensorDescrip'];
				$sensor_tag = $_M[form]['tag'];
				$auth_info = $_M[form]['authInfo'];
				$parse_chunk = $_M[form]['parseChunk'];
				
				$app_info = $this->getAppPara($parse_chunk, '\"');
				$data_view = $app_info[5];
				$data_appid = $app_info[9];
				$sensor_status = $_M[form]['sensorStatus'];
				$group_id = $_M[form]['groupId'];
				$sensor_loca = $_M[form]['sensorLoca'];

				if($id){
					//***************************TODO*****************************************
					//更新设备号有了

					$device_id = $_M[form]['device_id'];
					echo $device_id;
					require_once $this->template('own/sensor_update');
					
					
					
					$query = "UPDATE {$sensor_table} SET
								sensorName = '{$sensor_title}',
								sensorLoca = '{$sensor_loca}',
								sensorStatus = '{$sensor_status}',
								tag = '{$sensor_tag}',
								deviceId = '{$device_id}',
								dataView = '{$data_view}',
								appid = '{$data_appid}',
								groupId = '{$group_id}',
								authInfo = '{$auth_info}',
								parseChunk = '{$parse_chunk}'
								WHERE id = {$_M[form]['modify_id']}";

				} else{
					require_once $this->template('own/sensor_create');
					//将oneNet上的设备号获取，然后存到数据库中
					$device_id = $datastream[device_id];
					$query = "INSERT INTO {$sensor_table} SET
								sensorName = '{$sensor_title}',
								sensorLoca = '{$sensor_loca}',
								sensorStatus = '{$sensor_status}',
								tag = '{$sensor_tag}',
								deviceId = '{$device_id}',
								dataView = '{$data_view}',
								appid = '{$data_appid}',
								groupId = '{$group_id}',
								authInfo = '{$auth_info}',
								parseChunk = '{$parse_chunk}'";
				}
				DB::query($query);
				break;
			case 'delete':
				//删除设备要做一下几件事
				//1. 在oneNet上删除设备
				//2. 在oneNet上删除设备对应的应用
				//3. 将数据库有关该设备的信息都删除
				$device_id = $_M[form]['deviceId'];

				require_once $this->template('own/sensor_del');

				$query = "DELETE FROM {$sensor_table} WHERE id = {$_M['form']['id']}";

				DB::query($query);
				break;
			case 'changeStatus':
				$sensor_status = $_M[form]['sensorStatus'];
				$sensor_status1 = 0;
				if($sensor_status == 0){
					$sensor_status1 = 1;
				}
				$query = "UPDATE {$sensor_table} SET
						sensorStatus = '{$sensor_status1}' 
						WHERE id = {$_M[form]['sensor_id']}";
				DB::query($query);
				break;
			default:
				break;
		
		}
		require $this->template('own/sensor');
	}
	
	public function dosensoradd(){//定义自己的方法
        global $_M;//引入全局数组
		nav::select_nav(3);		
		$action = $_M[form]['action'];
		$sensor_table = $_M[table]['userdata_sensor'];
		
		switch($action){
			case 'add':
				
				
				break;
			case 'modify':
				//各个位置的值为当前的id中对应的值
				$sensor_array = DB::get_one("SELECT * FROM {$sensor_table} WHERE id = {$_M['form']['id']}");
				break;
			default:
				break;
		}
		
		require $this->template('own/sensor_add');
    }

	public function doappset(){
		global $_M;
		nav::select_nav(4);
		require $this->template('own/appset');
	}

	public function getAppPara($str, $splitStr){
		global $_M;
		//将字符串的引号前面加上“\”转义符
		$string = addslashes($str);
		$token = strtok($string, $splitStr);
		$i = 0;
		$arr = array();
		while ($token !== false)
		{
			$arr[$i] = $token;
			$token = strtok($splitStr);
			$i++;
		}
		return $arr;
	}

	public function dogetapply(){
		global $_M;
		nav::select_nav(5);
		require $this->template('own/get_apply');
	}
}
?>