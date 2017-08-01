<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

load::sys_class('admin');

class temtool extends admin {
	public function __construct() {
		global $_M;
		parent::__construct();
		nav::set_nav(1, '模板管理', $_M['url']['own_form'].'&a=dotemlist');
		nav::set_nav(2, '模板制作教程', $_M['url']['own_form'].'&a=doword');
		nav::set_nav(3, '标签大全/技巧知识', $_M['url']['own_form'].'&a=dotools');
	}
	
	public function dotemlist() {
		global $_M;
		nav::select_nav(1);
		require $this->template('own/temlist');
	}
	public function dotools(){
		global $_M;
		nav::select_nav(3);
		require $this->template('own/tools');
	}
	public function doword(){
		global $_M;
		nav::select_nav(2);
		require $this->template('own/word');
	}
	public function dotable_temlist_json() {
		global $_M;
		$table = load::sys_class('tabledata', 'new'); //加载表格数据获取类
		$where = ""; 
		$order = ""; //排序方式
		$array = $table->getdata($_M['table']['skin_table'], '*', $where, $order); 
		foreach($array as $key => $val) {
			$list = array();
			$list[] = "<img src='{$_M[url][site]}templates/{$val['skin_file']}/view.jpg' width='150' style='padding:5px; background:#fff; border:1px solid #ddd;' />";
			$list[] = $val['skin_file'];
			$list[] = $val['devices'] ? '手机模板' : '电脑模板';
			$list[] = "
						<a href=\"{$_M['url']['own_name']}c=temset&a=doset&no={$val[skin_file]}\">自定义标签</a>
						<span class=\"line\">|</span>
						<a href=\"{$_M['url']['own_name']}c=temtool&a=dode&id={$val[id]}&no={$val[skin_file]}\" data-confirm=\"您确定要删除该信息吗？删除之后无法再恢复。\">删除</a>
			";
			$rarray[] = $list;
		}	
		$table->rdata($rarray);
	}
	
	public function dotemin() {
		global $_M;
		require $this->template('own/temin');		
	}
	
	public function doin(){
		global $_M;
		if($_M['form']['temname']&&$_M['form']['temname']!=''){
			$query = "INSERT INTO {$_M['table']['skin_table']} SET skin_name='{$_M['form']['temname']}',skin_file='{$_M['form']['temname']}',skin_info='',devices='{$_M['form']['devices']}'";
			DB::query($query);
		}else{
			turnover("{$_M[url][own_name]}c=temtool&a=dotemlist",'操作失败！请填写模板文件夹名称！');
			die();
		}
		if(file_exists(PATH_WEB."templates/{$_M['form']['temname']}/install/install.class.php")){
			copy(PATH_WEB."templates/{$_M['form']['temname']}/install/install.class.php", PATH_OWN_FILE.'tmp/install.class.php');
			$ini = load::own_class('admin/tmp/install', 'new');
			$file = $_M['form']['temname'];
			$re = $ini->dosql();		
			$query = "DELETE FROM {$_M['table']['templates']} WHERE no='{$file}'";
			DB::query($query);
			foreach ($_M['langlist']['web'] as $key=>$val) {
				foreach ($re['sql'] as $ksql=>$vsql) {
					$query = "INSERT INTO {$_M['table']['templates']} SET no='{$file}',lang='{$key}',{$vsql}";
					DB::query($query);
				}
			}	
		}else if(file_exists(PATH_WEB."templates/{$_M['form']['temname']}/lang/language_cn.ini")){
			$ini = load::own_class('admin/class/inc', 'new');
			$ini->ini($_M['form']['temname']);
		}
		turnover("{$_M[url][own_name]}c=temtool&a=dotemlist");
	}
	public function dode(){
		global $_M;		
		
		$query = "DELETE FROM {$_M['table']['skin_table']} WHERE id='{$_M['form']['id']}'";
		DB::query($query);
		$query = "DELETE FROM {$_M['table']['templates']} WHERE no='{$_M['form']['no']}'";
		DB::query($query);
		turnover("{$_M[url][own_name]}c=temtool&a=dotemlist");
	}
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>