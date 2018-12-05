<?php 
require_once '../functions.php';
xiu_get_current_user();

function edit_setting(){
  if (empty($_POST['site_logo']) ||
      empty($_POST['site_name']) ||
      empty($_POST['site_description']) ||
      empty($_POST['site_keywords']) ) {
    $GLOBALS['message']='请完整填写表单';
    return;
  }
  $logo=$_POST['site_logo'];
  $name=$_POST['site_name'];
  $description=$_POST['site_description'];
  $keywords=$_POST['site_keywords'];
  $comment_status=isset($_POST['comment_status']) ? '1':'0';
  $comment_reviewed=isset($_POST['comment_reviewed']) ? '1':'0';
  
  $ar1=xiu_execute("update options set val='{$logo}' where site='site_logo'; ");
  $ar2=xiu_execute("update options set val='{$name}' where site='site_name'; ");
  $ar3=xiu_execute("update options set val='{$description}' where site='site_description'; ");
  $ar4=xiu_execute("update options set val='{$keywords}' where site='site_keywords'; ");
  $ar5=xiu_execute("update options set val='{$comment_status}' where site='comment_status'; ");
  $ar6=xiu_execute("update options set val='{$comment_reviewed}' where site='comment_reviewed'; ");
  




}

if ($_SERVER['REQUEST_METHOD']==='POST') {
  edit_setting();
}
$site_logo=xiu_fetch_one("select * from options where site='site_logo';")['val'];
$site_name=xiu_fetch_one("select * from options where site='site_name';")['val'];
$site_description=xiu_fetch_one("select * from options where site='site_description';")['val'];
$site_keywords=xiu_fetch_one("select * from options where site='site_keywords';")['val'];
$comment_status=xiu_fetch_one("select * from options where site='comment_status';")['val'];
$comment_reviewed=xiu_fetch_one("select * from options where site='comment_reviewed';")['val'];

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Settings &laquo; Admin</title>
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
        <h1>网站设置</h1>
      </div>
      <!-- 有错误信息时展示 -->
       <?php if (isset($message)): ?>
        <div class="alert <?php echo isset($success) ? 'alert-success':'alert-danger'; ?>">
        <?php echo $message; ?>
      </div>
     <?php endif ?>
      <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="form-group">
          <label for="site_logo" class="col-sm-2 control-label">网站图标</label>
          <div class="col-sm-6">
            <input id="site_logo" name="site_logo" type="hidden">
            <label class="form-image">
              <input id="logo" type="file">
              <?php if (empty($site_logo)): ?>
                <img src="/static/assets/img/logo.png">
              <?php else: ?>
                <img src="<?php echo $site_logo; ?>">
              <?php endif ?>
              
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="site_name" class="col-sm-2 control-label">站点名称</label>
          <div class="col-sm-6">
            <input id="site_name" name="site_name" class="form-control" type="type" value="<?php echo $site_name; ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="site_description" class="col-sm-2 control-label">站点描述</label>
          <div class="col-sm-6">
            <textarea id="site_description" name="site_description" class="form-control" cols="30" rows="6"><?php echo $site_description; ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="site_keywords" class="col-sm-2 control-label">站点关键词</label>
          <div class="col-sm-6">
            <input id="site_keywords" name="site_keywords" class="form-control" type="type" value="<?php echo $site_keywords; ?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">评论</label>
          <div class="col-sm-6">
            <div class="checkbox">
              <label><input id="comment_status" name="comment_status" type="checkbox" <?php echo $comment_status==1 ? 'checked':''; ?>>开启评论功能</label>
            </div>
            <div class="checkbox">
              <label><input id="comment_reviewed" name="comment_reviewed" type="checkbox" <?php echo $comment_reviewed==1 ? 'checked':''; ?>>评论必须经人工批准</label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-6">
            <button type="submit" class="btn btn-primary">保存设置</button>
          </div>
        </div>
      </form>
    </div>
  </div>
 <?php $current_page = 'settings'; ?>
  <?php include 'inc/sidebar.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $(function () {
       if (!$('#logo').parent().siblings('input').val()) {
        $('#logo').parent().siblings('input').val($('#logo').siblings('img').attr('src'));
      }


      $('#logo').on('change', function() {
        
        //文件选择状态发生改变时会执行这个事件
        //判断是否选中了文件
        var $this=$(this);
        var files=$(this).prop('files');
        if (!files.length) return;
        //拿到需要上传的文件
        var file=files[0];

        //通过ajax上传文件
        var data=new FormData();
        data.append('logo',file);

        xhr=new XMLHttpRequest();
        xhr.open('POST','/admin/api/logo.php');
        xhr.send(data);
        xhr.onload=function () {
          
          $this.siblings('img').attr('src',this.responseText);
          $this.parent().siblings('input').val(this.responseText);
        }
      });
    });
  </script>
  <script>NProgress.done()</script>
</body>
</html>
