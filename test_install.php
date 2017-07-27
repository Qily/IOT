<?php
if(file_exists('./config/install.lock')){
	echo 'metinfoinstall';
}else{
	echo 'metinfosuc';
}
// @unlink('./test_install.php');
?>