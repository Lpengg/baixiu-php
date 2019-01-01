<?php 
require_once 'functions.php';

if (isset($_SESSION['views'])) {
  unset($_SESSION['views']);
}

if ($_SERVER['REQUEST_METHOD']==='GET') {
  if (isset($_GET['slug'])) {

    $slug=$_GET['slug'];

    //处理分页参数
    $page = empty($_GET['page']) ? 1:(int)$_GET['page'];
    
    //显示多少条
    $size = 10;
    //计算出越过多少条
    $skip = ($page - 1) * $size;

    //最大页数$total_pages=ceil($total_count/$size)
    $total_count=xiu_fetch_one("SELECT COUNT(1) as num FROM posts,categories,users where posts.category_id=categories.id and posts.status='published' and categories.slug='{$slug}' and users.id=posts.user_id;")['num'];

    if (empty($total_count)) {
      $total_count=1;
    }

    $total_pages=ceil($total_count/$size);
    
    //$page超出范围
    if ($page < 1) {
      header('Location:/list.php?slug={$slug}&page=1');
    }
    if ($page > $total_pages) {
      header('Location:/list.php?slug={$slug}&page='.$total_pages);
    }

    $list=xiu_fetch("SELECT posts.*,categories.name,users.nickname FROM posts,categories,users where posts.category_id=categories.id and posts.status='published' and categories.slug='{$slug}' and users.id=posts.user_id ORDER BY posts.created DESC LIMIT {$skip},{$size};");



  }
}


//处理分页页码------------------

//计算页码开始
$visiables=5;
$region=($visiables-1)/2;
$begin=$page - $region;
$end=$page + $region;
//可能出现$begin和$end的不合理情况
//$begin>=1
if ($begin < 1) {
    $begin=1;
    $end=$visiables;
  }  
//$end<=最大页数

if ($end>$total_pages) {
  $end=$total_pages;
  $begin=$end-$visiables+1;
  if ($begin<1) {
    $begin=1;
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
        <h3><?php echo $list[0]['name']; ?></h3>

        <?php foreach ($list as $item): ?>
          <div class="entry" style="height: 270px;overflow: hidden;">
          <div class="head">
            <a href="/detail.php?slug=<?php echo $_GET['slug']; ?>&id=<?php echo $item['id']; ?>"><?php echo $item['title']; ?></a>
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
                标签：<span><?php echo $item['tags']; ?></span>
              </a>
            </p>
          </div>
         
        </div>
        <?php endforeach ?>
        
      
      </div>
      <div>
        <ul class="pagination pagination-sm pull-right" style="margin-bottom: 60px;margin-right: 282px;">

          <li><a href="?slug=<?php echo $slug; ?>&page=1">首页</a></li>
          <li><a href="?slug=<?php echo $slug; ?>&page=<?php echo $page-1 <1 ? 1:$page-1; ?>">上一页</a></li>
          <?php for ($i=$begin; $i <= $end; $i++): ?>

            <li <?php echo $i==$page ? 'class="active"':''; ?>><a href="?slug=<?php echo $slug; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
          <?php endfor; ?>
          <li><a href="?slug=<?php echo $slug; ?>&page=<?php echo $page+1 > $total_pages ? $total_pages:$page+1; ?>">下一页</a></li>
          <li><a href="?slug=<?php echo $slug; ?>&page=<?php echo $total_pages; ?>">尾页</a></li>
          <li><a>共<?php echo $total_pages; ?>页</a></li>
        </ul>
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
