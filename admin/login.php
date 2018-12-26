<?php 

//引入配置文件
require_once '../config.php';
require_once '../functions.php';
//开始session （给用户找一个箱子，如果之前有就用之前的，没有给个新的）


function login(){
  //1.接收数据并校验
  //2.持久化
  //3.响应
  if (empty($_POST['username'])) {
    $GLOBALS['message']='用户名错误';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['message']='密码错误';
    return;
  }
  $username=$_POST['username'];
  $password=$_POST['password'];

  //客户端提交过来完整的表单信息就应该开始对其进行数据校验
  //1.建立连接
 /* $conn = mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
  if(!$conn){
    exit('<h1>连接数据库失败</h1>');
  }
  $query=mysqli_query($conn,"select * from users where username = '{$username}' limit 1;");
  if (!$query) {
    $GLOBALS['message']='登录失败请重试';
    return;
  }
  
  $user=mysqli_fetch_assoc($query);
  var_dump($user);*/
$user = xiu_fetch("select * from users where username = '{$username}' limit 1;")[0];

if ($user==='NULL') {
    $GLOBALS['message']='登录失败请重试';
    return;
  }

  if (!$user) {
    $GLOBALS['message']='用户名与密码不匹配';
    return;
  }

  if ($user['password']!=$password) {
    $GLOBALS['message']='用户名与密码不匹配';
    return;
  }

  //存一个登录标识
  //$_SESSION['is_logind_in']=true;
  //$_SESSION['current_login_user'] = $user;
  $_SESSION['current_login_user'] = $user;

  //一切ok 可以跳转
  header('Location:/admin/');
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
  login();
 
}

//退出功能
if ($_SERVER['REQUEST_METHOD']==='GET'&&isset($_GET['action'])&&$_GET['action']==='loginout') {
  unset($_SESSION['current_login_user']);
}

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
</head>
<body>
  <div class="login">
    <!-- 可以通过在form上添加novalidate取消浏览器自带的校验功能 -->
    <!-- autocomplete="off"关闭客户端的自动完成功能 -->
    <form class="login-wrap<?php echo isset($message)? ' shake animated':''; ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate autocomplete="off"> 
      <img class="avatar" src="/static/assets/img/default.png">
      
      <?php if (isset($message)): ?>
         <div class="alert alert-danger">
        <?php echo $message; ?>
      </div>
      <?php endif ?>
     
      <div class="form-group">
        <label for="username" class="sr-only">用户名</label>
        <input id="username" name="username" type="username" class="form-control" placeholder="邮箱" autofocus value="<?php echo empty($_POST['username'])? '':$_POST['username']; ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
      <a href="../index.php"><div class="btn btn-primary btn-block">返 回</div></a>
    </form>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script>
    $(function ($) {
      //1.单独作用域
      //2.确保页面加载过后执行
      
      //目标：用户输入自己的邮箱过后，拿到页面上展示这个邮箱对应的头像
      //实现：
      //-时机：邮箱文本框失去焦点
      //-事情：获取文本框中填写的邮箱对应的头像地址，展示代上面的img元素上
      
      //失去焦点事件，并且能够拿到文本框中填写的邮箱时
      var usernameFormat=/^[a-z][a-zA-Z0-9]{4,7}$/;
      $('#username').on('blur',function () {
        var value=$(this).val();
        //忽略掉文本框为空或者不是一个邮箱
        if (!value || !usernameFormat.test(value)) return;

        //用户输入了一个合理的邮箱地址
        //获取这个邮箱对应的头像地址
        //因为客户端的js无法直接操作数据库，应该通过js发送ajax请求告诉服务端的某个接口
        //让这个接口帮助客户端获取头像
        
        $.get('/admin/api/avatar.php',{username:value},function (res) {
          //希望res=>这个邮箱对应的头像地址
          if (!res) return;
          //展示到img元素上
          //$('.avatar').fadeOut().attr('src',res).fadeIn();
          $('.avatar').fadeOut(function () {
            $(this).on('load', function() {
              $(this).fadeIn();
            }).attr('src',res)
          })
        })
      })
    })
  </script>
</body>
</html>
