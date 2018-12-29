<?php 
require_once 'functions.php';

if (isset($_SESSION['views'])) {
  unset($_SESSION['views']);
}

$swipe_wrapper=json_decode(xiu_fetch_one("select * from options where site='home_slides';")['val'],true);
$site_footer = xiu_fetch_one("select val from options where id = 6;");

//焦点关注
$funny=xiu_fetch("select posts.*,categories.slug from posts,categories where status='published' and posts.category_id=2 and posts.category_id=categories.id ORDER BY views DESC LIMIT 1;")[0];
$living=xiu_fetch("select posts.*,categories.slug from posts,categories where status='published' and posts.category_id=3 and posts.category_id=categories.id ORDER BY views DESC LIMIT 1;")[0];
$technology=xiu_fetch("select posts.*,categories.slug from posts,categories where status='published' and posts.category_id=4 and posts.category_id=categories.id ORDER BY views DESC LIMIT 1;")[0];
$miracle=xiu_fetch("select posts.*,categories.slug from posts,categories where status='published' and posts.category_id=5 and posts.category_id=categories.id ORDER BY views DESC LIMIT 1;")[0];


//一周热门排行数据
$rankinglist=xiu_fetch("select posts.*,categories.slug from posts,categories where status='published' and posts.category_id=categories.id ORDER BY views DESC LIMIT 5;");


//最新发布
$max_new_posts = xiu_fetch("select posts.*,categories.slug,categories.name,users.nickname from posts,categories,users where posts.status='published' and posts.category_id=categories.id and users.id=posts.user_id ORDER BY created DESC LIMIT 5;");



$n=1;

 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀，发现生活，发现美!</title>
   
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

    <!-- 轮播 -->
    <div class="content">
      <div class="swipe">
        <ul class="swipe-wrapper">
          <?php foreach ($swipe_wrapper as $item): ?>
          <li>
            <a href="#">
              <img src="<?php echo $item['image']; ?>">
              <span><?php echo $item['text']; ?></span>
            </a>
          </li>
          <?php endforeach ?>
         
          
        </ul>
        <p class="cursor"><span class="active"></span><span></span><span></span><span></span></p>
        <a href="javascript:;" class="arrow prev"><i class="fa fa-chevron-left"></i></a>
        <a href="javascript:;" class="arrow next"><i class="fa fa-chevron-right"></i></a>
      </div>
<!-- 焦点关注 -->
      <div class="panel focus">
        <h3>焦点关注</h3>
        <ul>
          <li class="large">
            <a href="javascript:;">
              <img src="/static/uploads/hots_1.jpg" alt="">
              <span>XIU主题演示</span>
            </a>
          </li>

          <li>
            <a href="/detail.php?slug=<?php echo $funny['slug']; ?>&id=<?php echo $funny['id']; ?>">
              <img src="<?php echo $funny['feature']; ?>" alt="">
              <span><?php echo $funny['title']; ?></span>
            </a>
          </li>

          <li>
            <a href="/detail.php?slug=<?php echo $living['slug']; ?>&id=<?php echo $living['id']; ?>">
              <img src="<?php echo $living['feature']; ?>" alt="">
              <span><?php echo $living['title']; ?></span>
            </a>
          </li>

          <li>
            <a href="/detail.php?slug=<?php echo $technology['slug']; ?>&id=<?php echo $technology['id']; ?>">
              <img src="<?php echo $technology['feature']; ?>" alt="">
              <span><?php echo $technology['title']; ?></span>
            </a>
          </li>

          <li>
            <a href="/detail.php?slug=<?php echo $miracle['slug']; ?>&id=<?php echo $miracle['id']; ?>">
              <img src="<?php echo $miracle['feature']; ?>" alt="">
              <span><?php echo $miracle['title']; ?></span>
            </a>
          </li>
     
        </ul>
      </div>
<!--一周热门排行-->
      <div class="panel top">
        <h3>一周热门排行</h3>
        <ol>
        	<?php foreach ($rankinglist as $item): ?>
        		<li>
          		  <i><?php echo $n;$n = $n+1 ?></i>
          		  <a href="/detail.php?slug=<?php echo $item['slug']; ?>&id=<?php echo $item['id']; ?>"><?php echo $item['title']; ?></a>
          		  <span>赞(<?php echo $item['likes']; ?>)</span>
          		  <span>阅读 (<?php echo $item['views']; ?>)</span>
          		</li>

        	<?php endforeach ?>  
        </ol>
      </div>

<!-- 热门推荐 -->
      <div class="panel hots">
        <h3>热门推荐</h3>
        <ul>

        	<?php foreach ($hot_recommend as $item): ?>
        		 <li>
          		  <a href="/detail.php?slug=<?php echo $item['slug']; ?>&id=<?php echo $item['id']; ?>">
          		    <img style="width: 208px;height:132px;" src="<?php echo $item['feature']; ?>" alt="">
          		    <span><?php echo $item['title']; ?></span>
          		  </a>
          		</li>
        	<?php endforeach ?>
    
        </ul>
      </div>
<!-- 最新发布 -->
      <div class="panel new">
        <h3>最新发布</h3>

		<?php foreach ($max_new_posts as $item): ?>
			<div class="entry">
        	   <div class="head">
        	     <span class="sort"><?php echo $item['name']; ?></span>
        	     <a href="/detail.php?slug=<?php echo $item['slug']; ?>&id=<?php echo $item['id']; ?>"><?php echo $item['title']; ?></a>
        	   </div>
        	   <div class="main" style="height: 185px;font-size: 18px;">
        	     <p class="info"><?php echo $item['nickname']; ?> 发表于 <?php echo $item['created']; ?></p>
        	     <p class="brief"><?php echo $item['content']; ?></p>

        	     <a href="/detail.php?slug=<?php echo $item['slug']; ?>&id=<?php echo $item['id']; ?>" class="thumb">
            	    <img style="width: 174px;height:130px;" src="<?php echo $item['feature']; ?>" alt="">
            	  </a>
        	    
        	   </div>
        	   <div>
          		   <p class="extra" style="margin-bottom: 20px;">
          		    <span class="reading">阅读(<?php echo $item['views']; ?>)</span>
          		    <span class="comment">评论(<?php echo xiu_fetch_one("select count(id) as id from comments where post_id = '{$item["id"]}';")['id']; ?>)</span>
          		    <a href="javascript:;" class="like">
          		      <i class="fa fa-thumbs-up"></i>
          		      <span>赞(<?php echo $item['likes']; ?>)</span>
          		    </a>
          		    <a href="javascript:;" class="tags">
          		      分类：<span>星球大战</span>
          		    </a>
          		  </p>
          		</div>
        	 </div>
		<?php endforeach ?>
       
      
      </div>
    </div>
    <div class="footer">
      <p><?php echo $site_footer['val']; ?></p>
    </div>
  </div>
  
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/swipe/swipe.js"></script>

  <script type="text/javascript" src="/static/assets/vendors/login-jiaoben/js/modal.js"></script>
  <script type="text/javascript" src="/static/assets/vendors/login-jiaoben/js/script.js"></script>
  <script>
    //
    var swiper = Swipe(document.querySelector('.swipe'), {
      auto: 3000,
      transitionEnd: function (index) {
        // index++;

        $('.cursor span').eq(index).addClass('active').siblings('.active').removeClass('active');
      }
    });

    // 上/下一张
    $('.swipe .arrow').on('click', function () {
      var _this = $(this);

      if(_this.is('.prev')) {
        swiper.prev();
      } else if(_this.is('.next')) {
        swiper.next();
      }
    })
  </script>
</body>
</html>

