<?php 
require_once '../functions.php';
//判断当前用户是否登录
xiu_get_current_user();

function edit_password(){
  if (empty($_POST['old']) || empty($_POST['password']) || empty($_POST['confirm'])) {
    $GLOBALS['message']='请完整填写表单';
    return;
  }
  //获取session中当前用户信息
  $id=$_SESSION['current_login_user']['id'];


  $old=$_POST['old'];
  $password=$_POST['password'];
  $confirm=$_POST['confirm'];

  if ($old!=xiu_fetch_one("select password from users where id={$id};")['password']) {
    $GLOBALS['message']='请输入正确的密码';
    return;
  }
  if ($password!=$confirm) {
    $GLOBALS['message']='新密码两次输入不一致，请重试';
    return;
  }

  $affect_row=xiu_execute("update users set password='{$password}' where id={$id};");
  if ($affect_row <= 0) {
    $GLOBALS['message']='修改密码失败';
    return;
  }else{
    $GLOBALS['success']='true';
    $GLOBALS['message']='修改密码成功';
    return;
  }
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
  edit_password();
}

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Password reset &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
   <?php include 'inc/navbar.php'; ?>

   
    <div class="container-fluid">
      <div class="page-title">
        <h1>修改密码</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
        <div class="alert <?php echo isset($success) ? 'alert-success':'alert-danger'; ?>">
        <strong><?php echo $message; ?></strong>
      </div>
      <?php endif ?>
      
      <form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" name="old" class="form-control"  autocomplete="new-password" type="password" placeholder="旧密码">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" name="password" class="form-control"  autocomplete="new-password" type="password" placeholder="新密码">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" name="confirm" class="form-control"  autocomplete="new-password" type="password" placeholder="确认新密码">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <button type="submit" class="btn btn-primary">修改密码</button>
          </div>
        </div>
      </form>
    </div>
  </div>
 <?php $current_page = 'password-reset'; ?>
 <?php include 'inc/sidebar.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
