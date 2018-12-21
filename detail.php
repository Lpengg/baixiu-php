<?php 
require_once 'functions.php';


if ($_SERVER['REQUEST_METHOD']==='GET') {
  if (isset($_GET['slug'])) {
    if (isset($_GET['id'])) {
      $slug=$_GET['slug'];

      $id=$_GET['id'];
      $post=xiu_fetch_one("SELECT * FROM posts,categories,users where posts.category_id=categories.id and posts.status='published' and categories.slug='{$slug}' and users.id=posts.user_id and posts.id={$id};");  
      $comments = xiu_fetch("select comments.*,users.avatar from comments,users where comments.status='published' and post_id={$id} and users.username=comments.user_name ORDER BY comments.created DESC;");
      
    }
  }

}

//设置阅读数
if (!isset($_SESSION['views'])){
   $_SESSION['views']=1;
   $views=$post['views']+1;
   xiu_execute("update posts set views={$views} where id ={$post['id']};");
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
  <link type="text/css" rel="stylesheet" href="/static/assets/vendors/dianzan/Css/demo.css">
  <script type="text/javascript" src="/static/assets/vendors/dianzan/Js/jquery-1.8.3.min.js"></script>
  <link rel="stylesheet" href="/static/assets/vendors/comments/css/comment.css">
  <link rel="stylesheet" href="/static/assets/vendors/comments/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="/static/assets/vendors/comments/css/bootstrap.css">
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
          <span><?php echo $post['username']; ?> 发布于 <?php echo $post['created']; ?></span>
          <span>分类: <a href="/list.php?slug=<?php echo $_GET['slug']; ?>"><?php echo $post['name']; ?></a></span>
          <span>阅读: (<?php echo $post['views']; ?>)</span>
          <span>评论: (143)</span>
        </div>
      </div>
    <!--   <div class="panel post-content"><?php echo $post['content']; ?></div> -->
       <div class="panel post-content">
            <textarea id="content" class="" name="content" cols="30" rows="10"><?php echo $post['content']; ?></textarea>
          </div>

      <!-- 点赞开始 -->
      <div class="praise">
        <span id="praise"><img src="/static/assets/vendors/dianzan/Images/zan.png" id="praise-img" /></span>
        <span id="praise-txt"><?php echo $post['likes']; ?> </span>
        <span id="add-num"><em>+1</em></span>
      </div>
      <!--动态点赞结束-->



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

      <div class="container" style="width: 900px;">
          <div class="commentbox">
            <textarea cols="80" rows="50" placeholder="来说几句吧......" class="mytextarea"></textarea>
            <div class="btn btn-info pull-right" id="btn_comment">评论</div>
          </div>

          <div class="comment-list">
          <?php foreach ($comments as $item): ?>

             <div class="comment-info">
              <header><img src="<?php echo $item['avatar']; ?>"></header>
              <div class="comment-right">
                <h3><?php echo $item['user_nickname']; ?></h3>
                <div class="comment-content-header"><span><i class="glyphicon glyphicon-time"></i> <?php echo $item['created']; ?></span></div>
                <p class="comment-content"><?php echo $item['content']; ?></p>
                <div class="reply-list"></div>
              </div>
            </div> 
            
          <?php endforeach ?>   
          </div>

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
  <script type="text/javascript" src="/static/assets/vendors/comments/js/jquery.comment.js" ></script>
  <script type="text/javascript" src="/static/assets/vendors/comments/js/bootstrap.min.js"></script>
  <script>
    $(function(){
       function getDate() {
        var dt=new Date();
        var year=dt.getFullYear();
        var month=dt.getMonth()+1;
        var day=dt.getDate();
        var hour=dt.getHours();
        var minute=dt.getMinutes();
        var second=dt.getSeconds();
        month=month < 10? "0" + month:month;
        day=day < 10? "0" + day:day;
        hour=hour < 10? "0" + hour:hour;
        minute=minute < 10? "0" + minute:minute;
        second=second < 10? "0" + second:second;
        return year+"-"+month+"-"+day+"-"+hour+":"+minute+":"+second;
    }

    
      $('#btn_comment').on('click', function() {
        //1.是否登录
        <?php if (isset($current_user)): ?>
        //2.文本是否为空
       var nickname="<?php echo $current_user['nickname']; ?>";

        if($(this).prev().val()){
           var created=getDate();
          var comment_content=$(this).prev().val();
           d={post_id:<?php echo $post['id']; ?>,
            user_nickname:nickname,
            user_name:"<?php echo $current_user['username']; ?>",
            created:created,
            content:comment_content
          }
          console.log(d);
          $.get('/index-api/add_comment.php',d)
          location.reload();
        }else{
          alert('请输入内容哦！！');
        }
        <?php else: ?>
         alert('登录后才能评论哦！！');
        <?php endif ?>
     
      });
    });
  </script>
  <script>
    $(function(){

      //点赞特效
    $("#praise").click(function(){
      var praise_img = $("#praise-img");
      var text_box = $("#add-num");
      var praise_txt = $("#praise-txt");
      var num=parseInt(praise_txt.text());
      if(praise_img.attr("src") == ("/static/assets/vendors/dianzan/Images/yizan.png")){
        $(this).html("<img src='/static/assets/vendors/dianzan/Images/zan.png' id='praise-img' class='animation' />");
        praise_txt.removeClass("hover");
        text_box.show().html("<em class='add-animation'>-1</em>");
        $(".add-animation").removeClass("hover");
        num -=1;
        $.get('/index-api/dianzan.php',{id:<?php echo $post['id']; ?>,number:num});
        praise_txt.text(num)
      }else{
        $(this).html("<img src='/static/assets/vendors/dianzan/Images/yizan.png' id='praise-img' class='animation' />");
        praise_txt.addClass("hover");
        text_box.show().html("<em class='add-animation'>+1</em>");
        $(".add-animation").addClass("hover");
        num +=1;
        $.get('/index-api/dianzan.php',{id:<?php echo $post['id']; ?>,number:num});

        praise_txt.text(num)
        }
      });
    })
     
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
