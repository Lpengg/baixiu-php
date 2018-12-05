<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-11-14 15:25:52
 * @version $Id$
 */
require_once '../functions.php';
if (empty($_GET['num'])) {
	exit('请正确传入参数');
}

$num=explode(',',$_GET['num']);

$slide_value=json_decode(xiu_fetch_one("select * from options where site='home_slides';")['val'],true);
foreach ($num as $i) {
	echo $i-1;
	unset($slide_value[$i-1]);
}
$slide_value=json_encode(array_values($slide_value),JSON_UNESCAPED_UNICODE);

$affect_row=xiu_execute("update options set val='{$slide_value}' where site='home_slides';");
  if ($affect_row <= 0) {
   exit('false');
  }else{
  
   header('Location:/admin/slides.php');
  }
