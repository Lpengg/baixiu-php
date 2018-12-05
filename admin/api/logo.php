<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-11-14 16:00:30
 * @version $Id$
 */

//接收文件
//保存文件
//返回文件的访问url
if (empty($_FILES['logo'])) {
	exit('必须上传文件');
}
$logo =$_FILES['logo'];
if ($logo['error']!=UPLOAD_ERR_OK) {
	exit('上传失败');
}
$target='../../static/uploads/logo/logo-' . $logo['name'];
if (!move_uploaded_file($logo['tmp_name'],$target)) {
	exit('文件上传失败');
}
echo substr($target, 5);