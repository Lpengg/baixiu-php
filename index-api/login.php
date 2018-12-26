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

  //存一个登录标识
  //$_SESSION['is_logind_in']=true;
  //$_SESSION['current_login_user'] = $user;
  $_SESSION['current_login_user'] = $user;

  //一切ok 可以跳转
 