<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-12-04 22:44:41
 * @version $Id$
 */
require_once '../functions.php';

//得到传过来的参数
$username = $_GET['username'];
$row = xiu_fetch_one("select * from users where username='{$username}';");
if ($row) {
	echo 'existence';
}
