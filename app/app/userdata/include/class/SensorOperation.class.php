<?php
header("Content-Type: text/html; charset=utf-8");
require 'OneNetApi.php';

Class SensorOperation{
	public function __construct() {
        global $_M;
		
    }

	private function getApi(){
		$apikey = 'Pj3ho=07dPOUkQuHVunpJoa5QnA=';
		$apiurl = 'http://api.heclouds.com';

		//创建api对象
		$sm = new OneNetApi($apikey, $apiurl);
		return $sm;
	
	}

	public function createSensor(){
		
	}
	public function delSensor($device_id){
		$datastream = $this->getApi()->device_delete($device_id);
		$error_code = 0;
		$error = '';
		if (empty($datastream)) {
			//处理错误信息
			$error_code = $sm->error_no();
			$error = $sm->error();
		}
		//展现设备
		var_dump($datastream);
	}
	public function getHistData($device_id, $datastream_id, $start_time,$end_time, $limit){     
        // $device_id = "10828595";
        // $datastream_id = "humidity_data_flow";
        // $start_time = "2017-08-03 10:40:00";

        $datastream = $this->getApi()->datapoint_get($device_id, $datastream_id, $start_time,$end_time, $limit, null);


        $error_code = 0;
        $error = '';
        if(empty($datastream)){
            $error_code = $sm->error_no();
			$error = $sm->error();
			return "error";
        } else {
			return $datastream;
		}
	}


	public function getLastDatapoint($device_id, $datastream_id){
		// $device_id = "10828595";
		// $datastream_id = "humidity_data_flow";
		date_default_timezone_set("Asia/Shanghai");
		$end_time = date("y-m-d H:i:s");
		$datastream = $this->getApi()->datapoint_get($device_id, $datastream_id, null, $end_time, 1);

		$error_code = 0;
        $error = '';
        if(empty($datastream)){
            $error_code = $sm->error_no();
			$error = $sm->error();
			return "error";
        } else {
			return $datastream;
		}
	}

}
