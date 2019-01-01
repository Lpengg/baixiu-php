<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2019-01-01 16:45:31
 * @version $Id$
 */

require_once '../functions.php';

$username=$_GET['username'];
$password=$_GET['password'];
$nickname=$_GET['nickname'];
$bio=$_GET['bio'];

$affect = xiu_execute("insert into users values(null,'{$username}','{$password}','author','{$nickname}','/static/uploads/avatars/avatar-5c179e4ce4458.jpeg','activated','{$bio}');");
if ($affect <= 0){
	echo '失败';
} 
	
