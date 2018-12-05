<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-11-12 16:22:15
 * @version $Id$
 */
require_once '../../functions.php';

if (empty($_GET['id']) || empty($_GET['status'])) {
	exit('缺少必要参数');
}

$id = $_GET['id'];
$status=$_GET['status'];


$row=xiu_execute("update comments set status='{$status}' WHERE id={$id};");
header('Content-Type:application/json');
echo json_encode($row > 0);