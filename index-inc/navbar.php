<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2018-11-15 14:53:41
 * @version $Id$
 */
require_once 'functions.php';
$categories=json_decode(xiu_fetch_one("select val from options where site='nav_menus';")['val'],true);
$categories[0]['slug']="funny";
$categories[2]['slug']="living";
$categories[1]['slug']="technology";
$categories[3]['slug']="miracle";

?>

 <div class="topnav">
      <ul>
        <li><a href="javascript:;"><i class="fa fa-glass"></i>奇趣事</a></li>
        <li><a href="javascript:;"><i class="fa fa-fire"></i>会生活</a></li>
        <li><a href="javascript:;"><i class="fa fa-phone"></i>潮科技</a></li>
        
        <li><a href="javascript:;"><i class="fa fa-gift"></i>美奇迹</a></li>
      </ul>
    </div>
    <div class="header">
      <h1 class="logo"><a href="index.php"><img src="/static/assets/img/logo.png" alt=""></a></h1>
      <ul class="nav">
        <?php foreach ($categories as $item): ?>
           <li><a href="/list.php?slug=<?php echo $item['slug']; ?>"><i class="<?php echo $item['icon']; ?>"></i><?php echo $item['text']; ?></a></li>
        <?php endforeach ?>
     

      </ul>
      <div class="search">
        <form>
          <input type="text" class="keys" placeholder="输入关键字">
          <input type="submit" class="btn" value="搜索">
        </form>
      </div>
      <div class="slink">
        <a href="javascript:;">链接01</a> | <a href="javascript:;">链接02</a>
      </div>
    </div>