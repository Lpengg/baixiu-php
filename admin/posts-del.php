<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-11-10 13:55:21
 * @version $Id$
 */

require_once '../functions.php';

if (empty($_GET['id'])) {
	exit('缺少必要参数');
}

$id = $_GET['id'];
//var_dump($id);
//删除id对应的数据
$row=xiu_execute('DELETE FROM posts WHERE id in (' . $id .');');

if ($row <= 0) {
	exit('删除失败');
}
//http中referer用来标识当前页面请求的来源
header('Location:'.$_SERVER['HTTP_REFERER']);