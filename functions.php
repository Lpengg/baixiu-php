<?php
session_start();
/**
 * 
 * 封装公共的函数
 * @date    2018-11-06 14:35:36
 * @version $Id$
 */
//定义函数一定要注意：函数名与内置函数冲突问题
//php判断函数是否定义的方式：function_exits('函数名');
require_once 'config.php';

/**
 * 封装大家公用的函数
 */


// 定义函数时一定要注意：函数名与内置函数冲突问题
// JS 判断方式：typeof fn === 'function'
// PHP 判断函数是否定义的方式： function_exists('get_current_user')

/**
 * 获取当前登录用户信息，如果没有获取到则自动跳转到登录页面
 * @return [type] [description]
 */
function xiu_get_current_user () {
  if (empty($_SESSION['current_login_user'])) {
    // 没有当前登录用户信息，意味着没有登录
    header('Location: /admin/login.php');
    exit(); // 没有必要再执行之后的代码
  }
  return $_SESSION['current_login_user'];
}

function xiu_get_index_user(){
	if (!empty($_SESSION['current_login_user'])) {
  	return $_SESSION['current_login_user'];
	}
}

/**
 * 通过一个数据库查询获取数据
 * @return [索引数组] $result
 */
function xiu_fetch($sql){
	$conn=mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
	if (!$conn) {
  		exit('<h1>连接数据库失败</h1>');
	}

	$query=mysqli_query($conn,$sql);
	if (!$query) {
		//查询失败
    	return false;
	}
	$result=array();
	
	while ($row = mysqli_fetch_assoc($query)) {
		$result[] = $row; 
	}
	mysqli_free_result($query);
	mysqli_close($conn);

	return $result;
}
/**
 * 获取单条数据
 * @param  [type] $sql [sql语句]
 * @return [数组]      [一条数据]
 */
function xiu_fetch_one($sql){
	$res= xiu_fetch($sql);
	return isset($res[0]) ? $res[0]:null;
}

/**
 * 执行一个增删改语句
 * @param  [type] $sql [增删改sql语句]
 * @return [type]      [受影响行数]
 */
function xiu_execute($sql){
	$conn=mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
	if (!$conn) {
  		exit('<h1>连接数据库失败</h1>');
	}
	$query=mysqli_query($conn,$sql);
	if (!$query) {
		//查询失败
    	return false;
	}
	$affected_rows = mysqli_affected_rows($conn);
	mysqli_close($conn);
	return $affected_rows;
}