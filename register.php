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
        <h1>欢迎注册小凶许文章网</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="form-horizontal" autocomplete="off">
		
        <div class="form-group">
          <label for="username" class="col-sm-3 control-label">登录账号</label>
          <div class="col-sm-6">
            <input id="username" class="form-control" name="username" type="text" placeholder="请输入账号" >
            <p class="help-block">限制5-8个数字或者字母组合</p>
          </div>
        </div>

        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">密码</label>
          <div class="col-sm-6">
            <input id="password" class="form-control" name="password" type="type" placeholder="请输入密码">
            <p class="help-block">限制5-16个数字或者字母组合</p>
          </div>
        </div>

		<div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认密码</label>
          <div class="col-sm-6">
            <input id="confirm" class="form-control" name="confirm" type="type" placeholder="确认密码">
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
            <textarea id="bio" class="form-control" placeholder="Bio" cols="30" rows="6">MAKE IT BETTER!</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">注册</button>
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

  		var reg=/^[a-zA-Z0-9]{5,8}$/;
  		//账号校验
  		$('#username').on('blur', function() {
  			if ($(this).val()) {
  				if(!reg.test($(this).val())){
  					$(this).parent().parent().attr('class','form-group has-error');
  				}else{
  					$(this).parent().parent().attr('class','form-group has-success has-feedback');	
  				}
  			}	
  			$.get('/index-api/username.php',{username:$(this).val()},function (res) {
          		if (!res) return;
    			$('#username').next().html('用户名已存在,请重新输入');
          		$('#username').parent().parent().attr('class','form-group has-error');
          		
        	});  
  		});

  		//密码校验
  		$('#password').on('blur', function() {
  			if ($(this).val()) {
  				if(!reg.test($(this).val())){	
  					$(this).parent().parent().attr('class','form-group has-error');
  				}else{
  					$(this).parent().parent().attr('class','form-group has-success has-feedback');
  				}
  			}	
  		});


  	})
  </script>
  <script>NProgress.done()</script>
</body>
</html>
