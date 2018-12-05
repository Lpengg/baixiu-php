<?php 
require_once '../functions.php';
xiu_get_current_user();

function add_nav(){
  if (empty($_POST['text']) || empty($_POST['title']) || empty($_POST['link']) ) {
    $GLOBALS['message']='请完整填写表单';
    return;
  }
  $data=array();

  $data['icon']='fa fa-fire';
  $data['text']=$_POST['text'];
  $data['title']=$_POST['title'];
  $data['link']=$_POST['link'];
 
  $nav_menus=xiu_fetch_one("SELECT * FROM options where site='nav_menus';")['val'];
  $nav_menu=json_decode($nav_menus,true);
  $nav_menu[]=$data;
  //设置第二个参数 避免出现中文编码问题
  $new_nav_menu=json_encode($nav_menu,JSON_UNESCAPED_UNICODE);
  
  $affect_row=xiu_execute("update options set val='{$new_nav_menu}' where site='nav_menus';");
  if ($affect_row < 0) {
    $GLOBALS['message']='添加失败';
    return;
  }else{
    $GLOBALS['success']='true';
    $GLOBALS['message']='添加成功';
    return;
  }
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
  add_nav();
}


//----------获取数据----------
//获取的是json格式的数据
$nav_menus=xiu_fetch_one("SELECT * FROM options where site='nav_menus';")['val'];
$nav_menu=json_decode($nav_menus,true);
//接受一个 JSON 格式的字符串并且把它转换为 PHP 变量 第二个参数为true 则变为数组 否则是object
$n=1;

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Navigation menus &laquo; Admin</title>
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

   
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>导航菜单</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
        <div class="alert <?php echo isset($success) ? 'alert-success':'alert-danger'; ?>">
        <strong><?php echo $message; ?></strong>
      </div>
      <?php endif ?>
      
      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
            <h2>添加新导航链接</h2>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control" name="title" type="text" placeholder="标题">
            </div>
            <div class="form-group">
              <label for="href">链接</label>
              <input id="href" class="form-control" name="link" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="btn_del" class="btn btn-danger btn-sm" href="/admin/nav-menus-del.php" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>文本</th>
                <th>标题</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
             
             <?php foreach ($nav_menu as $item): ?>
                <tr>
                <td class="text-center"><input data-num="<?php echo $n; ?>" type="checkbox"></td>
                <td><i class="<?php echo $item['icon']; ?>"></i><?php echo $item['text']; ?></td>
                <td><?php echo $item['title']; ?></td>
                <td><?php echo $item['link']; ?></td>
                <td class="text-center">
                  <a href="/admin/nav-menus-del.php?num=<?php echo $n; $n+=1; ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
             <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
 <?php $current_page = 'nav-menus'; ?>
  <?php include 'inc/sidebar.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $(function ($) {
      var $thisbody=$('tbody input');
      var allChecked=[];
      var $btnDelete=$('#btn_del');
      $thisbody.on('change', function() {
        var num=$(this).data('num');
        if ($(this).prop('checked')) {
          allChecked.push(num);
        }else{
          allChecked.splice(allChecked.indexOf(num),1);
        }
        allChecked.length > 0 ? $btnDelete.fadeIn():$btnDelete.fadeOut();
         $btnDelete.prop('search','?num='+allChecked);
      });
    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
