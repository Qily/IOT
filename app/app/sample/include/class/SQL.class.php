<?php
header("Content-Type: text/html; charset=utf-8");

Class SQL{
	public function __construct() {
        global $_M;
    }

    public function getLoginUserId(){
        return get_met_cookie(metinfo_member_id);
    }

    public function getLoginUsername(){
        return get_met_cookie(metinfo_member_name);
    }

    public function getSensorsByUserId($action, $scenename){
        
    }


}
