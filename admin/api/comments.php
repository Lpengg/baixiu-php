<?php
/**
 * 接收客户端的ajax请求，返回评论数据
 * @authors Your Name (you@example.org)
 * @date    2018-11-10 14:38:50
 * @version $Id$
 */
require_once '../../functions.php';


//得到客户端传递过来的分页页码 
$page = empty($_GET['page']) ? 1:intval($_GET['page']);

$length=10;
$skip=($page-1) * $length;


$login_user = xiu_get_current_user();

if ($login_user['role'] === 'author') {
  	$user_id = $login_user['id'];

	//查询所有的评论数据
	$sql=sprintf("select 
		comments.*,
		posts.title as post_title 
		from comments 
	inner join posts on comments.post_id=posts.id and posts.user_id={$user_id}
	ORDER BY comments.created DESC
	LIMIT %d,%d;",$skip,$length);
	$comments=xiu_fetch($sql);
	
	$total_count=xiu_fetch_one("select count(1) as num from comments inner join posts on comments.post_id=posts.id and posts.user_id={$user_id};")['num'];
	$totalPages = ceil($total_count/$length);

}else{

	//查询所有的评论数据
	$sql=sprintf("select 
		comments.*,
		posts.title as post_title 
		from comments 
	inner join posts on comments.post_id=posts.id
	ORDER BY comments.created DESC
	LIMIT %d,%d;",$skip,$length);
	$comments=xiu_fetch($sql);
	
	$total_count=xiu_fetch_one("select count(1) as num from comments inner join posts on comments.post_id=posts.id;")['num'];
	$totalPages = ceil($total_count/$length);
	
}


//因为网络之间传输的只能是字符串
//所有我们先将数据转换成字符串(序列化)
$json = json_encode(array(
	'totalPages' => $totalPages,
	'comments' => $comments
));

//设置响应的响应体是json
header('Content-Type:application/json');
//响应给客户端
echo $json;