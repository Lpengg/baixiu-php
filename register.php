<?php 
require_once 'functions.php';

function add_user(){
  if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['nickname']) || empty($_POST['bio'])) {
    $GLOBALS['message']='请完整填写表单';
    return;
  }
  $uname=$_POST['username'];
  $pword=$_POST['password'];
  $nname=$_POST['nickname'];
  $bio=$_POST['bio'];

  $affect_row=xiu_execute("insert into users VALUES(null,'{$uname}','{$pword}','author','{$nname}','/static/uploads/avatars/avatar.jpg','activated','{$bio}');");
  if ($affect_row <= 0) {
    $GLOBALS['message']='注册失败';
    return;
  }else{
    $GLOBALS['message']='注册成功,请返回登陆';
    $GLOBALS['success'] ='true';
    return;
  }

}

if ($_SERVER['REQUEST_METHOD']==='POST') {
  add_user();
}
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
    
    <div class="container-fluid">
      <div style="text-align: center;">
        <h1>欢迎注册百秀</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
      <div class="alert <?php echo isset($success) ? 'alert-success':'alert-danger'; ?>" style="width:900px;margin-left: 360px;margin-top: 60px;">
        <strong><?php echo $message; ?></strong>
      </div>
      <?php endif ?>
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="form-horizontal" autocomplete="off" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <div class="form-group">
          <label for="username" class="col-sm-3 control-label">登录账号</label>
          <div class="col-sm-6">
            <input id="username" class="form-control" name="username" type="text" placeholder="请输入账号" >
            <p class="help-block">限制5-8个数字和字母组合,首个字符必须为小写字母</p>
          </div>
        </div>

        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">密码</label>
          <div class="col-sm-6">
            <input id="password" class="form-control" name="password" type="type" placeholder="请输入密码">
            <p class="help-block">限制5-16个字符</p>
          </div>
        </div>

		<div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认密码</label>
          <div class="col-sm-6">
            <input id="confirm" class="form-control" name="confirm" type="type" placeholder="确认密码">
            <p class="help-block">请再次输入密码</p>
          </div>
        </div>

        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" placeholder="昵称">
            <p class="help-block">限制在 2-5 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" name="bio" placeholder="Bio" cols="30" rows="6">MAKE IT BETTER!</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button  id="btn_submit" type="submit" class="btn btn-primary">注册</button>
            <a class="btn btn-link btn-default" href="/index.php">返回</a>
          </div>
        </div>
      </form>
    </div>
  </div>

 

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
  	$(function () {
      var $username =$('#username');
      var $password =$('#password');
      var $confirm =$('#confirm');
      var $nickname =$('#nickname');
  		var reg=/^[a-z][a-zA-Z0-9]{4,7}$/;
  		//账号校验
      //
  		$username.on('blur', function() {
        $username.next().html('限制5-8个数字和字母组合,首个字符必须为小写字母');
  			if ($(this).val()) {
  				if(!reg.test($(this).val())){
  					$(this).parent().parent().attr('class','form-group has-error');
            $("#btn_submit").attr('disabled',true);
  				}else{
  					$(this).parent().parent().attr('class','form-group has-success has-feedback');
            $("#btn_submit").attr('disabled',false);
  				}

            $.get('/index-api/username.php',{username:$(this).val()},function (res) {
              if (!res) return;
              $username.next().html('用户名已存在,请重新输入');
              $username.parent().parent().attr('class','form-group has-error');
              $("#btn_submit").attr('disabled',true);
          });  
  			}	
  			$.get('/index-api/username.php',{username:$(this).val()},function (res) {
          		if (!res) return;
    			    $username.next().html('用户名已存在,请重新输入');
          		$username.parent().parent().attr('class','form-group has-error');
          		
        	});  
  		});
      //密码校验
      $password.on('blur', function() {
        if ($(this).val()) {
          //console.log($(this).val().length);
          if (!($(this).val().length >=5 && $(this).val().length <=16) ) {
            $(this).parent().parent().attr('class','form-group has-error');
            $("#btn_submit").attr('disabled',true);
          }else{
            $(this).parent().parent().attr('class','form-group has-success has-feedback');
            $("#btn_submit").attr('disabled',false);
          }
        }
      });
  		//确认密码校验
  		$confirm.on('blur', function() {
        if($(this).val()){
  			   if ($(this).val() != $('#password').val()) {
  			      $(this).next().html('两次输入密码不一致');
  					  $(this).parent().parent().attr('class','form-group has-error');
              $("#btn_submit").attr('disabled',true);
  			    }else{
  					  $(this).parent().parent().attr('class','form-group has-success has-feedback');
  				    $("#btn_submit").attr('disabled',false);
  			    }	
          }
  		  });
      //用户昵称校验
      $nickname.on('blur', function() {
        $nickname.next().html('限制在 2-5 个字符');
        if ($(this).val()) {
          //校验长度
          if (!($(this).val().length >=2 && $(this).val().length <=5) ) {
            $(this).parent().parent().attr('class','form-group has-error');
            $("#btn_submit").attr('disabled',true);
          }else{
            $(this).parent().parent().attr('class','form-group has-success has-feedback');
            $("#btn_submit").attr('disabled',false);
          }
          //校验是否重复
            $.get('/index-api/nickname.php',{nickname:$(this).val()},function (res) {
              if (!res) return;
              $nickname.next().html('昵称已存在,请重新输入');
              $nickname.parent().parent().attr('class','form-group has-error');
              $("#btn_submit").attr('disabled',true);
            });  
        }
      });

  	})
  </script>
  <script>NProgress.done()</script>
</body>
</html>
