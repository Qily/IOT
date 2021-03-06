<?php
defined('IN_MET') or exit('No permission');//所有文件都是已这句话开头，保证系统单入口。
header('content-type:text/html charset:utf-8');

$output = "";
$upfile = load::sys_class('upfile', 'new');//加载上传类
$upfile->set_upimg();//设置图片上传模式
$upfile->set('format','jpg|jpeg');//设置允许上传文件的后缀名
$dir_base = $_M[url][own]."img/"; 	//文件上传根目录

//显示并上传图像
//没有成功上传文件，报错并退出。
if(empty($_FILES)) {
	echo "<img src='{$dir_base}error.jpg'/>";
	exit(0);
}

$index = 0;		//$_FILES 以文件name为数组下标，不适用foreach($_FILES as $index=>$file)
foreach($_FILES as $file){
	$upload_file_name = 'upload_file' . $index;		//对应index.html FomData中的文件命名
	$filename = $_FILES[$upload_file_name]['name'];
	$gb_filename = iconv('utf-8','gb2312',$filename);	//名字转换成gb2312处理
	$filename_ = strtok($filename, ".")[0];
	$ret = $upfile->upload($dir_base.$filename_);//上传文件
	
	$imgDivWidth = $_M[form]['divImgWidth'];

	if($ret['error'] == 0){
		$imgPath = str_replace("../", $_M[url][site], $ret['path']);
		//输出图片文件<img>标签
		list($width, $height, $type, $attr) = getimagesize($imgPath);
		$height_deal = $imgDivWidth * ($height/$width);

		$output .= "<img src='{$imgPath}' style='width:{$imgDivWidth}px;height:{$height_deal}px' ' title='{$filename}' alt='{$filename}'/>";
		$output .= "<input type='hidden' id='scene-img-path' value='{$imgPath}' name='img-path'/>";
	}else {
		$output .= "<img src='{$dir_base}error.jpg' title='{$filename}' alt='{$filename}'/>";
	}
	
	$index++;
}

echo $output;