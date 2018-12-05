<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-11-11 14:48:30
 * @version $Id$
 */
//var_dump($_FILES['avatar']);

//接收文件
//保存文件
//返回文件的访问url
if (empty($_FILES['avatar'])) {
	exit('必须上传文件');
}
$avatar =$_FILES['avatar'];

if ($avatar['error']!=UPLOAD_ERR_OK) {
	exit('上传失败');
}
//校验类型大小


//移动文件到网站范围之内
//获取文件的扩展名
$ext=pathinfo($avatar['name'],PATHINFO_EXTENSION);
$target='../../static/uploads/avatars/avatar-'.uniqid().'.'.$ext;
if (!move_uploaded_file($avatar['tmp_name'],$target)) {
	exit('上传失败');
}

//上传成功
echo substr($target,5);