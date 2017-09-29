<?php
header("Content-Type:text/html; charset=utf-8");

echo "<script language=\"JavaScript\">\r\n";
echo " alert(\"". $text ."\");\r\n";
echo " location.replace(\"". $sucToPage ."\");\r\n";
echo "</script>";
exit;
?>