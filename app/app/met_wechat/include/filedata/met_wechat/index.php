<?php
if (@!$_GET['n'] || @!$_GET['c'] || @!$_GET['a']) {
	echo "<pre>";
	echo "{'errorcode':'403','errormsg':'Forbidden'}";
	die;
}
define('M_NAME', @$_GET['n']);
define('M_MODULE', 'web');
define('M_CLASS', @$_GET['c']);
define('M_ACTION', @$_GET['a']);
require_once '../app/app/entrance.php';
?>