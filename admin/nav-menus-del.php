<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-11-13 16:13:49
 * @version $Id$
 */
require_once '../functions.php';
if (empty($_GET['num'])) {
	exit('请传入参数');
}

//字符串
$num=$_GET['num'];

//数字组
$num=explode(',',$num);

//json格式的字符串
$nav_menus=xiu_fetch_one("SELECT * FROM options where site='nav_menus';")['val'];
//值为value的数组
$nav_menu=json_decode($nav_menus,true);
foreach ($num as $i) {
	//删除保留原先数组索引
	unset($nav_menu[$i-1]);
}

//数组重新建立索引
$nav_menu=array_values($nav_menu);



$new_nav_menu=json_encode($nav_menu,JSON_UNESCAPED_UNICODE);

$affect_row=xiu_execute("update options set val='{$new_nav_menu}' where site='nav_menus';");
  if ($affect_row <= 0) {
   exit('false');
  }else{
  
   header('Location:/admin/nav-menus.php');
  }
