<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-11-10 17:53:35
 * @version $Id$
 */

require_once '../../functions.php';

if (empty($_GET['id'])) {
	exit('缺少必要参数');
}

$id = $_GET['id'];
//var_dump($id);
//删除id对应的数据
$row=xiu_execute('DELETE FROM comments WHERE id in (' . $id .');');
header('Content-Type:application/json');
echo json_encode($row > 0);