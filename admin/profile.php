<?php 
require_once '../functions.php';
//判断当前用户是否登录
xiu_get_current_user();
//获取session中当前用户信息
$current_user=$_SESSION['current_login_user'];

function edit_user($current_id){
  if (empty($_POST['avatar']) ||
      empty($_POST['slug']) ||
      empty($_POST['nickname']) ||
      empty($_POST['bio'])) {
    $GLOBALS['message']='请完整填写信息';
    return;
  }

  $avatar=$_POST['avatar'];
  $slug=$_POST['slug'];
  $nickname=$_POST['nickname'];
  $bio=$_POST['bio'];
  $id=$current_id;
  $affect_rows=xiu_execute("update users set avatar='{$avatar}',slug='{$slug}',nickname='{$nickname}',bio='{$bio}' where id={$id};");
  if ($affect_rows <= 0) {
    $GLOBALS['message']='修改失败';
    return;
  }else{
    $GLOBALS['message']='更新成功！！';
    $GLOBALS['success']='true';
  }

}

if ($_SERVER['REQUEST_METHOD']==='POST') {
  edit_user($current_user['id']);
}



//获取数据库中当前用户信息
$user=xiu_fetch_one("select * from users where id=".$current_user['id']);
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
     <?php if (isset($message)): ?>
        <div class="alert <?php echo isset($success) ? 'alert-success':'alert-danger'; ?>">
        <?php echo $message; ?>
      </div>
     <?php endif ?>

      <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <?php if (empty($user['avatar'])): ?>
                 <img src="/static/assets/img/default.png">
              <?php else: ?>
                 <img src="<?php echo $user['avatar']; ?>">
              <?php endif ?>
             
              <input type="hidden" name="avatar">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="<?php echo $user['email']; ?>" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="<?php echo $user['slug']; ?>" placeholder="slug">
            <!-- <p class="help-block">https://zce.me/author/<strong>zce</strong></p> -->
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="<?php echo $user['nickname']; ?>" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" name="bio" class="form-control" placeholder="Bio" cols="30" rows="6"><?php echo $user['bio']; ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">更新</button>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>
 <?php $current_page = 'profile'; ?>
  <?php include 'inc/sidebar.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>

  <script>
    $(function ($) {

      if (!$('#avatar').siblings('input').val()) {
        $('#avatar').siblings('input').val($('#avatar').siblings('img').attr('src'));
      }
      $('#avatar').on('change', function() {
        
        //文件选择状态发生改变时会执行这个事件
        //判断是否选中了文件
        var $this=$(this);
        var files=$(this).prop('files');
        if (!files.length) return;
        //拿到需要上传的文件
        var file=files[0];

        //通过ajax上传文件
        
        var data=new FormData();
        data.append('avatar',file);

        var xhr = new XMLHttpRequest();
        xhr.open('POST','/admin/api/upload.php');
        xhr.send(data);
        xhr.onload=function () {
          
          $this.siblings('img').attr('src',this.responseText);
          $this.siblings('input').val(this.responseText);
        }
      });
    });

  </script>
  <script>NProgress.done()</script>
</body>
</html>
