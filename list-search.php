<?php 
require_once 'functions.php';

if (isset($_SESSION['views'])) {
  unset($_SESSION['views']);
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (isset($_POST['keys'])) {

    $keys=$_POST['keys'];
   
    $list=xiu_fetch("SELECT posts.*,categories.name,categories.slug,users.nickname FROM posts,categories,users where title like '%{$keys}%' and posts.category_id=categories.id and posts.status='published' and users.id=posts.user_id ORDER BY posts.created DESC;");
  }
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀-发现生活，发现美!</title>
  <link rel="stylesheet" type="text/css" href="/static/assets/vendors/bootstrap/css/bootstrap.min.modify.css">  
  <link rel="stylesheet" type="text/css" href="/static/assets/vendors/login-jiaoben/css/index.css"> 
  <link href="/static/assets/vendors/login-jiaoben/css/signin.css" rel="stylesheet"> 
  <link rel="stylesheet" href="/static/assets/css/style.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  
</head>
<body>
  <div class="wrapper">
   <?php include 'index-inc/navbar.php' ?>
   <?php include 'index-inc/sidebar.php' ?>

    <div class="content">
      <div class="panel new">
        <h3>查询结果</h3>

        <?php foreach ($list as $item): ?>
          <div class="entry" style="height: 270px;overflow: hidden;">
          <div class="head">
            <a href="/detail.php?slug=<?php echo $item['slug']; ?>&id=<?php echo $item['id']; ?>"><?php echo $item['title']; ?></a>
          </div>
          <div class="main" style="height: 185px;font-size: 18px;">
            <p class="info"><?php echo $item['nickname']; ?> 发表于 <?php echo $item['created']; ?></p>
            <p class="brief"><?php echo $item['content']; ?></p>
            
            <a href="/detail.php?slug=<?php echo $_GET['slug']; ?>&id=<?php echo $item['id']; ?>" class="thumb">
              <img style="width: 174px;height: 130px;" src="<?php echo $item['feature']; ?>" alt="">
            </a>
          </div>
          <div>
             <p class="extra">
              <span class="reading">阅读(<?php echo $item['views']; ?>)</span>
              <span class="comment">评论(<?php echo xiu_fetch_one("select count(id) as num from comments where post_id = '{$item["id"]}';")['num']; ?>)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?php echo $item['likes']; ?>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span><?php echo $item['tags']; ?></span>
              </a>
            </p>
          </div>
         
        </div>
        <?php endforeach ?>
        
      
      </div>     
    </div>

   
    <div class="footer">

      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/login-jiaoben/js/modal.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/login-jiaoben/js/script.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
 

</body>
</html>
