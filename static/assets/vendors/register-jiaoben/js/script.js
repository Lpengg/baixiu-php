// user
var user_Boolean = false;
var password_Boolean = false;
var varconfirm_Boolean = false;
var emaile_Boolean = false;
var Mobile_Boolean = false;
$('.reg_user').blur(function(){
  if ((/^[a-z][a-zA-Z0-9]{4,7}$/).test($(".reg_user").val())){
    $.get('/index-api/username.php',{username:$(this).val()},function (res) {
      if (!res){ 
        $('.user_hint').html("✔").css("color","green");
        user_Boolean = true; 
    }else{
      $('.user_hint').html("用户存在<br />请重新输入").css("color","red");
      user_Boolean = false;
      }    
    });  
  }else {
    $('.user_hint').html("x").css("color","red");
    user_Boolean = false;
  }
});
// password
$('.reg_password').blur(function(){
  if ((/^[a-zA-Z0-9_-]{6,16}$/).test($(".reg_password").val())){
    $('.password_hint').html("✔").css("color","green");
    password_Boolean = true;
  }else {
    $('.password_hint').html("×").css("color","red");
    password_Boolean = false;
  }
});


// password_confirm
$('.reg_confirm').blur(function(){
  if (($(".reg_password").val())==($(".reg_confirm").val())){
    $('.confirm_hint').html("✔").css("color","green");
    varconfirm_Boolean = true;
  }else {
    $('.confirm_hint').html("×").css("color","red");
    varconfirm_Boolean = false;
  }
});

// Email
$('.reg_nickname').blur(function(){
  
  if (($(this).val().length >=2 && $(this).val().length <=5)){
    $.get('/index-api/nickname.php',{nickname:$(this).val()},function (res) {
      if (!res) {
        $('.email_hint').html("✔").css("color","green");
        emaile_Boolean = true;
      }else{
        $('.email_hint').html("昵称已存在<br />请重新输入").css("color","red");
        emaile_Boolean = false;
      }
    });  

    
  }else {
    $('.email_hint').html("×").css("color","red");
    emaile_Boolean = false;
  }
});


// Mobile
$('.reg_bio').blur(function(){
  if ($('.reg_bio').val()){
    $('.mobile_hint').html("✔").css("color","green");
    Mobile_Boolean = true;
  }else {
    $('.mobile_hint').html("×").css("color","red");
    Mobile_Boolean = false;
  }
});


// click
$('.red_button').click(function(){
  if(user_Boolean && password_Boolean && varconfirm_Boolean && emaile_Boolean && Mobile_Boolean == true){
    $.get('/index-api/register.php',{username:$('.reg_user').val(),password:$('.reg_password').val(),nickname:$('.reg_nickname').val(),bio:$('.reg_bio').val()},function (res) {
      if (!res) return;
      $('.reg_user').val('');
      $('.reg_password').val('');
      $('.reg_nickname').val('');
      $('.reg_confirm').val('');
      $('.reg_bio').val('');
      $('.alert').html('注册失败').css("display","block").css("color","red");
    }); 
    $('.reg_user').val('');
    $('.reg_password').val('');
    $('.reg_nickname').val('');
    $('.reg_confirm').val('');
    $('.reg_bio').val('');
    $('.alert').html('注册成功').css("display","block").css("color","green");
  }else {
    $('.alert').html('请完善信息').css("display","block").css("color","red");
  }
});


$('#register_close').click(function(){
  $('.reg_user').val('');
  $('.reg_password').val('');
  $('.reg_nickname').val('');
  $('.reg_confirm').val('');
  $('.reg_bio').val('');
  
});