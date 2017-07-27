<?php
define('M_NAME', 'userdata');//指定应用名称，即创建的应用的文件夹的名称。
define('M_MODULE', 'web');//指定模块类型
define('M_CLASS', 'userdata');//指定模块，即创建的前台模块类名称，不要加“.class.php”。
define('M_ACTION', 'doparameter');//或define('M_ACTION', $GET['action']);//指定调用的模块方法，必须是用“do”;开头的方法。
require_once '../app/app/entrance.php';//包含入口文件
?>