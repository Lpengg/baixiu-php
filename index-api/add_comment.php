<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-12-18 09:00:44
 * @version $Id$
 */
require_once '../functions.php';

$post_id = $_GET['post_id'];
$user_nickname= $_GET['user_nickname'];
$user_name= $_GET['user_name'];
$created= $_GET['created'];
$content= $_GET['content'];
$affect=xiu_execute("insert into comments values(null,'{$user_nickname}','{$user_name}','{$created}','{$content}','published','{$post_id}',null);");
if ($affect<=0){
	echo '错误';
	return;
}
