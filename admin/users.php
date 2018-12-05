<?php 
ob_start();
require_once '../functions.php';
//判断用户是否登录
xiu_get_current_user();


function add_user(){
  //数据校验
  if (empty($_POST['email']) ||empty($_POST['slug']) ||empty($_POST['nickname']) ||empty($_POST['password'])) {
     $GLOBALS['success']=false;
    $GLOBALS['message']='请完整填写表单';
    return;
  }

  $email = $_POST['email'];
  $slug = $_POST['slug'];
  $nickname = $_POST['nickname'];
  $password = $_POST['password'];
  
  $rows=xiu_execute("insert into users values(null,'{$slug}','{$email}','{$password}','{$nickname}',null,null,'activated')");
  $rows <= 0 ? $GLOBALS['success']=false:'';
  $GLOBALS['message']= $rows <= 0 ? '添加失败':'添加成功';
}

function edit_user(){
  global $current_edit_user;
  $id=$current_edit_user['id'];
  $email=empty($_POST['email']) ? $current_edit_user['email']:$_POST['email'];
  $slug=empty($_POST['slug']) ? $current_edit_user['slug']:$_POST['slug'];
  $nickname=empty($_POST['nickname']) ? $current_edit_user['nickname']:$_POST['nickname'];
  $password=empty($_POST['password']) ? $current_edit_user['password']:$_POST['password'];

  $affect_row=xiu_execute("update users set email='{$email}',slug='{$slug}',nickname='{$nickname}',password='{$password}' where id={$id};");
  $affect_row <= 0 ? $GLOBALS['success']=false:'';
  $GLOBALS['message']= $affect_row <= 0 ? '编辑失败':'编辑成功';

}


if (empty($_GET['id'])) {
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    add_user();
  }
}else{
  $current_edit_user=xiu_fetch_one('select * from users where id='. $_GET['id']);
   if ($_SERVER['REQUEST_METHOD']==='POST') {
    edit_user();
  }
}

  



//查询所有的数据
$users=xiu_fetch("select * from users");
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
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
        <h1>用户</h1>
      </div>
<!-- 展示错误信息 -->
       <?php if (isset($message)): ?>
         <div class="alert <?php echo isset($success) ? 'alert-danger':'alert-success'; ?> ">
        <?php echo $message; ?>
      </div>
      <?php endif ?>


      <div class="row">
        <div class="col-md-4">
<!-- 编辑和添加用户 -->
          <?php if (isset($current_edit_user)): ?>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $current_edit_user['id']; ?>" method="post" autocomplete="off">
            <h2>编辑《<?php echo $current_edit_user['email']; ?>》</h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <input id="email" class="form-control" name="email" type="email" placeholder="<?php echo $current_edit_user['email']; ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="<?php echo $current_edit_user['slug']; ?>">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="<?php echo $current_edit_user['nickname']; ?>">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" placeholder="<?php echo $current_edit_user['password']; ?>">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">保存</button>
            </div>
          </form>
         <?php else: ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
            <h2>添加新用户</h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <input id="email" class="form-control" name="email" type="email" placeholder="邮箱">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" placeholder="密码">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
          <?php endif ?>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="btn_delete" class="btn btn-danger btn-sm" href="/admin/users-del.php" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
<!-- 遍历所有的数据 -->
             <?php foreach ($users as $item): ?>
                <tr>
                <td class="text-center"><input type="checkbox"  data-id="<?php echo $item['id']; ?>"></td>
                <td class="text-center"><img class="avatar" src="<?php echo $item['avatar']; ?>"></td>
                <td><?php echo $item['email']; ?></td>
                <td><?php echo $item['slug']; ?></td>
                <td><?php echo $item['nickname']; ?></td>
                <td><?php echo $item['status']==='activated' ? '激活':'未激活'; ?></td>
                <td class="text-center">
                  <a href="/admin/users.php?id=<?php echo $item['id']; ?>" class="btn btn-default btn-xs">编辑</a>
                  <a href="/admin/users-del.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
             
             <?php endforeach ?>


            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

 <?php $current_page = 'users'; ?>
 <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script>
    $(function ($) {
       //在表格中的任意一个check选中状态变化时
      var $tbdoyCheckboxs = $('tbody input');
      var $btnDelete=$('#btn_delete');
      //定义一个数组记录被选中的id
      var allChecked=[];
      $tbdoyCheckboxs.on('change', function() {
         var id=$(this).data('id');
         if ($(this).prop('checked')) {
          allChecked.push(id);
         }else{
          allChecked.splice(allChecked.indexOf(id),1);
        }
        allChecked.length ? $btnDelete.fadeIn():$btnDelete.fadeOut();
        $btnDelete.prop('search','?id='+allChecked);
      });
    });
  </script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>

</body>
</html>
