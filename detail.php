<?php 
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD']==='GET') {
  if (isset($_GET['slug'])) {
    if (isset($_GET['id'])) {
      $slug=$_GET['slug'];

      $id=$_GET['id'];
      $post=xiu_fetch_one("SELECT * FROM posts,categories,users where posts.category_id=categories.id and posts.status='published' and categories.slug='{$slug}' and users.id=posts.user_id and posts.id={$id};");  
       
    }
  }

}


?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀-发现生活，发现美!</title>
  
  <link rel="stylesheet" href="/static/assets/css/style.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
</head>
<body>
  <div class="wrapper">
   <?php include 'index-inc/navbar.php' ?>
   <?php include 'index-inc/sidebar.php' ?>

    <div class="content">
      <div class="article">
        <div class="breadcrumb">
          <dl>
            <dt>当前位置：</dt>
            <dd><a href="/list.php?slug=<?php echo $_GET['slug']; ?>"><?php echo $post['name']; ?></a></dd>
            <dd><?php echo $post['title']; ?></dd>
          </dl>
        </div>
        <h2 class="title">
          <a href="javascript:;"><?php echo $post['title']; ?></a>
        </h2>
        <div class="meta">
          <span><?php echo $post['email']; ?> 发布于 <?php echo $post['created']; ?></span>
          <span>分类: <a href="/list.php?slug=<?php echo $_GET['slug']; ?>"><?php echo $post['name']; ?></a></span>
          <span>阅读: (2421)</span>
          <span>评论: (143)</span>
        </div>
      </div>
    <!--   <div class="panel post-content"><?php echo $post['content']; ?></div> -->
       <div class="panel post-content">
            <textarea id="content" class="" name="content" cols="30" rows="10"><?php echo $post['content']; ?></textarea>
          </div>
      <div class="panel hots">
        <h3>热门推荐</h3>
        <ul>
          <li>
            <a href="javascript:;">
              <img src="/static/uploads/hots_2.jpg" alt="">
              <span>星球大战:原力觉醒视频演示 电影票68</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="/static/uploads/hots_3.jpg" alt="">
              <span>你敢骑吗？全球第一辆全功能3D打印摩托车亮相</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="/static/uploads/hots_4.jpg" alt="">
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="/static/uploads/hots_5.jpg" alt="">
              <span>实在太邪恶！照亮妹纸绝对领域与私处</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
 
  <script src="/static/assets/vendors/ueditor/ueditor.config.js"></script>
  <script src="/static/assets/vendors/ueditor/ueditor.all.js"></script>
  <script src="/static/assets/vendors/ueditor/ueditor.parse.js"></script>
  <script>
     
     var editor=UE.getEditor('content', {
        initialFrameHeight: 500,
        autoHeight: false,
        toolbars: [],
        elementPathEnabled : false,
        wordCount:false
       
      });
     
      editor.ready(function() {
      //不可编辑

        editor.setDisabled();
      });
  </script>
</body>
</html>
