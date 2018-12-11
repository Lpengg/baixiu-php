<?php 

function add_title(){
	if (empty($_POST['section']) || empty($_POST['knowledge']) || empty($_POST['title']) || empty($_POST['optionA']) ||  empty($_POST['radio'])|| empty($_POST['parsing'])) {
		$GLOBALS['message']='请完整填写表单';
		return;
	}
	
	$section=$_POST['section'];
	$knowledge=$_POST['knowledge'];
	$title=$_POST['title'];
	
	$answer=$_POST['radio'];
	$parsing=$_POST['parsing'];

	$data=array();
	
	$data['optionA']=$_POST['optionA'];

	if (isset($_POST['optionB'])) {
		$data['optionB']=$_POST['optionB'];
	}
	if (isset($_POST['optionC'])) {
		$data['optionC']=$_POST['optionC'];
	}
	if (isset($_POST['optionD'])) {
		$data['optionB']=$_POST['optionD'];
	}
	if (isset($_POST['optionE'])) {
		$data['optionB']=$_POST['optionE'];
	}
	if (isset($_POST['optionF'])) {
		$data['optionB']=$_POST['optionF'];
	}

	$options[]=$data;
	$options=json_encode($options,JSON_UNESCAPED_UNICODE);

	$conn=mysqli_connect('127.0.0.1','root','123456','question_bank');
	if (!$conn) {
  		exit('<h1>连接数据库失败</h1>');
	}
	$query=mysqli_query($conn,"insert into topic values(null,'{$section}','{$knowledge}','{$title}','{$options}','{$answer}','{$parsing}');");
	if (!$query) {
		//查询失败
    	return false;
	}
	$affect = mysqli_affected_rows($conn);
	if ($affect<=0) {
	$GLOBALS['message']='添加失败';
		return;
	}else{
		$GLOBALS['message']='添加成功';
		$GLOBALS['success']='true';
		return;
	}








}
if ($_SERVER['REQUEST_METHOD']==='POST') {
	add_title();
}

$conn=mysqli_connect('127.0.0.1','root','123456','question_bank');
if (!$conn) {
  	exit('<h1>连接数据库失败</h1>');
}
$query=mysqli_query($conn,"select * from sections");
if (!$query) {
	//查询失败
   	return false;
}
$sections=array();

while ($row = mysqli_fetch_assoc($query)) {
	$sections[] = $row; 
}
mysqli_free_result($query);

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
        <h1>添加题目</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
      <div class="alert <?php echo isset($success) ? 'alert-success':'alert-danger'; ?>" style="width:900px;margin-left: 360px;margin-top: 60px;">
        <strong><?php echo $message; ?></strong>
      </div>
      <?php endif ?>

      <form class="form-horizontal" autocomplete="off" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

        <div class="form-group">
          <label for="section" class="col-sm-3 control-label">课程阶段</label>
          <div class="col-sm-6">
            <select id="section" class="form-control" name="section">
            	<?php foreach ($sections as $item): ?>
            		<option><?php echo $item['name']; ?></option>
            	<?php endforeach ?>
      		</select>
           
          </div>
        </div>

        <div class="form-group">
          <label for="knowledge" class="col-sm-3 control-label">课程知识点</label>
          <div class="col-sm-6">
            <select id="knowledge" class="form-control" name="knowledge">
        		<option>全部阶段</option>
        		<option>linux</option>
      		</select>
 
          </div>
        </div>

		<div class="form-group">
          <label for="title" class="col-sm-3 control-label">题目</label>
          <div class="col-sm-6">
           <textarea class="form-control" rows="3" name="title"></textarea>
           
          </div>
        </div>
 
        <div class="form-group">
          <label for="option" class="col-sm-3 control-label">选项</label>
          <div class="col-sm-6">
            <input id="optionA" class="form-control" name="optionA" type="type" placeholder="请输入一条选项">
            
          </div>
         <button type="button" class="btn btn-default" id="btn_add">+</button>
        </div>
		
		 <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">答案</label>
          <div class="col-sm-6">
 			 	<input type="radio" name="radio" id="radioA" value="A"> A
          </div>
        </div>

        <div class="form-group">
          <label for="parsing" class="col-sm-3 control-label">答案解析</label>
          <div class="col-sm-6">
            <textarea id="parsing" class="form-control" name="parsing" cols="30" rows="6">解析：</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button  id="btn_submit" type="submit" class="btn btn-primary">添加</button>
            <a class="btn btn-link btn-default" href="#">返回</a>
          </div>
        </div>
      </form>
    </div>
  </div>

 

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
  	$(function () {
  		//添加按钮事件
  		var a=new Array('A','B','C','D','E','F');
  		var n=1;
  		$('#btn_add').on('click', function() {
  			//创建一个选项框
  			var input=$("<input>");
  			//创建一个答案选项
  			
  			input.attr({name:"option"+a[n]+"",class:"form-control",type:"type",placeholder:"请输入一条选项"});
  			input.css("margin-top","10px");
  			$('#optionA').parent().append(input);

  			var radio=$("<input>");
  			radio.attr({name:"radio",type:"radio",value:""+a[n]+""});
  			radio.css("margin-left","10px");
  			$('#radioA').parent().append(radio).append('<span>'+a[n]+'</span>');
  			
 			n=n+1;
  		});
  	});
  </script>
 
  <script>NProgress.done()</script>
</body>
</html>
