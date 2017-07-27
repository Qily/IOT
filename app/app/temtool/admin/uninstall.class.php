<?php
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');
load::sys_class('nav.class.php');
load::sys_func('file');

class uninstall extends admin {
	public function __construct() {
		parent::__construct();
	}
	public function dodel() {
		global $_M;
		$query = "delete from {$_M['table']['applist']} where no='10012'";
		DB::query($query);
		echo '删除成功！';
	}
}
?>