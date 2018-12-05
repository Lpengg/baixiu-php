<?php
/**
 * 
 * 删除用户
 * @authors Your Name (you@example.org)
 * @date    2018-11-09 19:56:20
 * @version $Id$
 */
require_once '../functions.php';
if (empty($_GET['id'])) {
	exit('缺少必要参数');
}

$rows=xiu_execute("delete from users where id in (".$_GET['id'].");");
if ($rows<0) {
	exit('删除失败');
}

header('Location:/admin/users.php');