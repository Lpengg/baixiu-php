<?php

require_once '../functions.php';

$username=$_GET['username'];
$password=$_GET['password'];


$user = xiu_fetch_one("select * from users where username = '{$username}' limit 1;");
  if (!$user) {
    echo '该用户不存在';
    return;
  }

  if ($user['password']!=$password) {
    echo '用户名与密码不匹配';
    return;
  }


  $_SESSION['current_login_user'] = $user;
  
 
 