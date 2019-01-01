<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- 重置文件 -->
  <link rel="stylesheet" href="/static/assets/vendors/register-jiaoben/css/normalize.css">
  <link rel="stylesheet" href="/static/assets/vendors/register-jiaoben/css/style.css">
  <title>jQuery实用的注册表单验证代码 - 站长素材</title>
  <style>
  	#register_close{
    	float: right;
    	font-size: 21px;
    	font-weight: bold;
    	opacity: .2;
    	background: transparent;
    	cursor: pointer;
    	overflow: hidden;
    	margin-right: 8px;
    	width: 17px;
    	margin-top: 11px;
  	}
  </style>
</head>
<body>
  <div class="reg_div">
  	 <a type="button" id="register_close" class="close" href="/index.php" ><span>×</span><span class="sr-only">Close</span></a>
    <p>注册</p>

        <span class="alert" style="display: none;"><strong>123</strong></span>
     	
    <ul class="reg_ul">
    	<form autocomplete="off">
      <li>
          <span>账号：</span>
          <input type="text" name="username" value="" placeholder="首个字符为小写字母，5-8位" class="reg_user">
          <span class="tip user_hint"></span>
      </li>
      <li>
          <span>密码：</span>
          <input type="password" name="password" value="" placeholder="6-16位密码" class="reg_password">
          <span class="tip password_hint"></span>
      </li>
      <li>
          <span>确认密码：</span>
          <input type="password" name="confirm" value="" placeholder="确认密码" class="reg_confirm">
          <span class="tip confirm_hint"></span>
      </li>
      <li>
          <span>昵称：</span>
          <input type="text" name="nickname" value="" placeholder="2-5个字符" class="reg_nickname">
          <span class="tip email_hint"></span>
      </li>
      <li>
          <span>简介：</span>
          <input type="text" name="bio" value="" placeholder="一句话" class="reg_bio">
          <span class="tip mobile_hint"></span>
      </li>
      <li>
        <button type="button" name="button" class="red_button">注册</button>
      </li>
	</form>
    </ul>
  </div>
  
  <script type="text/javascript" src="/static/assets/vendors/register-jiaoben/js/jquery.min.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/register-jiaoben/js/script.js"></script>
 

</div>
</body>
</html>

