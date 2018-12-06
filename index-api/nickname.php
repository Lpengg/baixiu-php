<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-12-06 19:13:49
 * @version $Id$
 */
require_once '../functions.php';

//得到传过来的参数
$nickname = $_GET['nickname'];
$row = xiu_fetch_one("select * from users where nickname='{$nickname}';");
if ($row) {
	echo 'existence';
}
