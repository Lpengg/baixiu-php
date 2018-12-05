<?php 
require_once '../functions.php';
//验证用户是否登录
xiu_get_current_user();

//编辑文章
function edit_post($post){
  if (empty($_POST['slug'])
    || empty($_POST['title'])
    || empty($_POST['created'])
    || empty($_POST['content'])
    || empty($_POST['status'])
    || empty($_POST['category'])) {
    // 缺少必要数据
    $GLOBALS['message'] = '请完整填写所有内容';
  } 
   //获取当前登录的用户信息
  $user_id=(int)$_SESSION['current_login_user']['id'];
  //获取数据
  $slug=$_POST['slug'];
  $title=$_POST['title'];
  $content=$_POST['content'];
  $category_id = $_POST['category'];
  $created=$_POST['created'];
  $status=$_POST['status'];
  $id=$_GET['id'];
  $feature=$post['feature'];
 

  if (!empty($_FILES['feature']['name'])) {
    //更改了文件域
     if (empty($_FILES['feature']['error'])) {
      // PHP 在会自动接收客户端上传的文件到一个临时的目录
      $temp_file = $_FILES['feature']['tmp_name'];
      // 我们只需要把文件保存到我们指定上传目录
      $target_file = '../static/uploads/images/'.uniqid().'-'. $_FILES['feature']['name'];
      if (!move_uploaded_file($temp_file, $target_file)) {
         $GLOBALS['message']='移动文件失败，请重新上传';
         return;
      }
      $feature =substr($target_file,2);
     
     } 
  }

  $affect_rows=xiu_execute("update posts set slug='{$slug}',title='{$title}',feature='{$feature}',created='{$created}',content='{$content}',status='{$status}',user_id={$user_id},category_id={$category_id} where id={$id};");

  if ($affect_rows >0) {
    header('Location:/admin/posts.php');
  }else{
    $GLOBALS['message']='编辑失败';
  }
}


//添加文章
function add_post(){
  if (empty($_POST['slug'])
    || empty($_POST['title'])
    || empty($_POST['created'])
    || empty($_POST['content'])
    || empty($_POST['status'])
    || empty($_POST['category'])) {
    // 缺少必要数据
    $GLOBALS['message'] = '请完整填写所有内容';
  } 

  //获取当前登录的用户信息
  $user_id=(int)$_SESSION['current_login_user']['id'];
  //获取数据
  $slug=$_POST['slug'];
  $title=$_POST['title'];
  $content=htmlspecialchars_decode($_POST['content']);

  $category_id = $_POST['category'];
  $created=$_POST['created'];
  $status=$_POST['status'];

  if ((int)xiu_fetch_one("select count(1) as num from posts where slug='{$slug}';")['num'] > 0) {
    // slug 重复
    $GLOBALS['message'] = '别名已经存在，请修改别名';
    return;
  } 
  if ((int)xiu_fetch_one("select count(1) as num from posts where slug='{$title}';")['num'] > 0) {
    //标题重复
    $GLOBALS['message'] = '标题已经存在，请修改标题';
    return;
  } 

    // 接收文件并保存
    // ------------------------------
    // 如果选择了文件 $_FILES['feature']['error'] => 0
  if (empty($_FILES['feature']['error'])) {
      // PHP 在会自动接收客户端上传的文件到一个临时的目录
      $temp_file = $_FILES['feature']['tmp_name'];
      // 我们只需要把文件保存到我们指定上传目录
      $target_file = '../static/uploads/images/'.uniqid().'-'. $_FILES['feature']['name'];
      if (!move_uploaded_file($temp_file, $target_file)) {
         $GLOBALS['message']='移动文件失败，请重新上传';
         return;
      }
       $feature =substr($target_file,2);
    }

  
 
  $sql = sprintf(
      "insert into posts values (null, '%s', '%s', '%s', '%s', '%s', 0, 0, '%s', %d, %d)",
      $slug,
      $title,
      $feature,
      $created,
      $content,
      $status,
      $user_id,
      $category_id
    );
    // 执行 SQL 保存数据
    if (xiu_execute($sql) > 0) {
      $GLOBALS['success']='true';
      $GLOBALS['message'] = '添加成功';
      
    } else {
      // 保存失败
      $GLOBALS['message'] = '保存失败，请重试';
    }

}

if (isset($_GET['id'])) {
  //修改文章
  $post=xiu_fetch_one("select * from posts where id=" . $_GET['id']);
  
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    edit_post($post);
  }
}

if ($_SERVER['REQUEST_METHOD']==='POST' && empty($_GET['id'])) {
  add_post();
}


//查询所有的分类
$categories=xiu_fetch("select * from categories;");


 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <link rel="stylesheet" href="/static/assets/vendors/simplemde/simplemde.min.css">
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
  <?php include 'inc/navbar.php'; ?>

  
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
          <div class="alert <?php echo !isset($success) ? 'alert-danger':'alert-success'; ?>">
        <strong><?php echo $message; ?>！</strong>
      </div>
      <?php endif ?>
      <!-- 编辑 -->
    <?php if (isset($_GET['id'])): ?>
       <form class="row" action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" value="<?php echo $post['title']; ?>">
          </div>
          <div class="form-group">
            <label for="content">文章内容</label>
            <textarea id="content" class="" name="content" cols="30" rows="10"><?php echo isset($post['content']) ? $post['content'] : ''; ?></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" value="<?php echo $post['slug']; ?>">
            <!-- <p class="help-block">https://zce.me/post/<strong>slug</strong></p> -->
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <img class="help-block thumbnail" style="display: none" >
            <input id="feature" class="form-control" name="feature" type="file" accept="image/*" <?php echo 'src="'.$post['feature'].'"'; ?>>
          </div>
          <div class="form-group">
            <!-- 分类 -->
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <?php foreach ($categories as $item): ?>
                <option <?php echo $item['id']==$post['category_id'] ? 'selected ':''; ?>value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option <?php echo $post['status']==='drafted' ? 'selected':''; ?> value="drafted">草稿</option>
              <option <?php echo $post['status']==='published' ? 'selected':''; ?> value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    <?php else: ?>
 <!-- 添加 -->
      <form class="row" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">文本内容</label>
            <textarea id="content" class="" name="content" cols="30" rows="10" placeholder="内容"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <!-- <p class="help-block">https://zce.me/post/<strong>slug</strong></p> -->
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file" accept="image/*">
          </div>
          <div class="form-group">
            <!-- 分类 -->
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <?php foreach ($categories as $item): ?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    <?php endif ?>
   
    </div>
  </div>
 <?php $current_page = 'post-add'; ?>
 <?php include 'inc/sidebar.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>

  <script src="/static/assets/vendors/ueditor/ueditor.config.js"></script>
  <script src="/static/assets/vendors/ueditor/ueditor.all.js"></script>
  <script src="/static/assets/vendors/moment/moment.js"></script>
 <script>
  
    $(function () {

       if ($('#feature').attr('src')) {
          $url=$('#feature').attr('src');
           $('#feature').siblings('.thumbnail').attr('src', $url).fadeIn()
        }

     // 当文件域文件选择发生改变过后，本地预览选择的图片
      $('#feature').on('change', function () {
        var file = $(this).prop('files')[0]
        // 为这个文件对象创建一个 Object URL
        var url = URL.createObjectURL(file)
        // url => blob:http://zce.me/65a03a19-3e3a-446a-9956-e91cb2b76e1f
        // 不用奇怪 BLOB: binary large object block
        // 将图片元素显示到界面上（预览）

        $(this).siblings('.thumbnail').attr('src', url).fadeIn()
      })

    
      UE.getEditor('content', {
        initialFrameHeight: 500,
        autoHeight: false
       
      });
     
      // 发布时间初始值
      $('#created').val(moment().format('YYYY-MM-DDTHH:mm'))
    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
