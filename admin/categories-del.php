<?php
/**
 * 根据客户端传递过来的id删除对应的数据
 * 
 * @date    2018-11-06 17:45:22
 * @version $Id$
 */
require_once '../functions.php';

if (empty($_GET['id'])) {
	exit('缺少必要参数');
}

$id = $_GET['id'];
//var_dump($id);
//删除id对应的数据
$row=xiu_execute('DELETE FROM categories WHERE id in (' . $id .');');

if ($row <= 0) {
	exit('删除失败');
}

header('Location:/admin/categories.php');