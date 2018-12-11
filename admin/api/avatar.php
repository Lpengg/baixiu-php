<?php
/**
 * 
 *根据用户邮箱获取用户头像
 *输入是邮箱名称失去焦点
 *输出是该用户对应的数据库中存储的邮箱
 *
 * username=>image
 * @date    2018-11-04 16:43:45
 * @version $Id$
 */
require_once '../../config.php';

//接收传递过来的邮箱
if (empty($_GET['username'])) {
	exit('缺少必要参数');
}
$username=$_GET['username'];

//查询对应的头像地址在
$conn=mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
if (!$conn) {
	exit('连接数据库失败');
}
$res = mysqli_query($conn,"select avatar from users where username='{$username}' limit 1;");
if (!$res) {
	exit('查询失败');
}
$row=mysqli_fetch_assoc($res);

//3.echo
echo $row['avatar'];

