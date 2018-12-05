<?php 
ob_start();

require_once '../functions.php';

/**
 * 转换状态
 * @param  string $status 英文状态
 * @return string         中文状态
 */
function xiu_convert_status($status){
  $dict=array(
    'published' => '已发布',
    'drafted' => '草稿',
    'trashed' => '回收站'
  );
  return isset($dict[$status]) ? $dict[$status]:'未知状态';
}


/**
 * 转换时间格式
 * @param  string $created 时间
 * @return string          转换后的时间
 */
function xiu_convert_date($created){
  //=>2017-07-01 08:08:00
  $timestamp=strtotime($created);
  //<br>需要中间加\转义
  return date('Y年m月d日<b\r>H:i:s',$timestamp);
}


//判断用户是否登录
xiu_get_current_user();

//获取分类数据
$categories=xiu_fetch("select * from categories;");
//接收筛选参数
//------------------------
$where ='1=1';
$where2= '1=1';
$search='';
if (isset($_GET['categories'])&&$_GET['categories']!='all') {
  $where = 'posts.category_id ='. $_GET['categories'];
  $search = '&categories=' . $_GET['categories'];
}
//状态筛选
//-------------
if (isset($_GET['status'])&&$_GET['status']!='all') {
  $where2 = "posts.status ='{$_GET['status']}'";
  $search = $search.'&status=' . $_GET['status'];
}
//var_dump($search);
//&categories=2&status=published

//$total_pages=>101

//处理分页参数
$page = empty($_GET['page']) ? 1:(int)$_GET['page'];

//显示多少条
$size = 10;
//计算出越过多少条
$skip = ($page - 1) * $size;

//最大页数$total_pages=ceil($total_count/$size)
$total_count=xiu_fetch_one("select COUNT(1) as num from users,posts,categories where {$where} and {$where2} and users.id=posts.user_id and categories.id=posts.category_id;")['num'];
if (empty($total_count)) {
  $total_count=1;
}
$total_pages=ceil($total_count/$size);

//$page超出范围
if ($page < 1) {
  header('Location:/admin/posts.php?page=1' . $search);
}
if ($page > $total_pages) {
  header('Location:/admin/posts.php?page='.$total_pages . $search);
}





//获取全部数据 （方法一）
/*$posts=xiu_fetch("select * from posts");*/
//方法二 数据库连接查询
$posts=xiu_fetch("select posts.id,title,posts.status,posts.created,nickname,name from users,posts,categories  where {$where} and {$where2} and users.id=posts.user_id and categories.id=posts.category_id ORDER BY posts.created desc LIMIT {$skip},{$size};");


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
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="/admin/post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a id="btn-delete" class="btn btn-danger btn-sm" href="/admin/posts-del.php" style="display: none">批量删除</a>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <select name="categories" class="form-control input-sm">
            <option value="all">所有分类</option>

            <?php foreach ($categories as $item): ?>
              <option value="<?php echo $item['id'] ?>" <?php echo isset($_GET['categories'])&& $_GET['categories']===$item['id'] ? 'selected':''; ?>><?php echo $item['name']; ?></option>
            <?php endforeach ?>
            
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted" <?php echo isset($_GET['status'])&& $_GET['status']==='drafted' ? 'selected':''; ?>>草稿</option>
            <option value="published" <?php echo isset($_GET['status'])&& $_GET['status']==='published' ? 'selected':''; ?>>已发布</option>
            <option value="trashed" <?php echo isset($_GET['status'])&& $_GET['status']==='trashed' ? 'selected':''; ?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">

          <li><a href="?page=1&<?php echo $search; ?>">首页</a></li>
          <li><a href="?page=<?php echo $page-1 <1 ? 1:$page-1; ?><?php echo $search; ?>">上一页</a></li>
          <?php for ($i=$begin; $i <= $end; $i++): ?>

            <li <?php echo $i==$page ? 'class="active"':''; ?>><a href="?page=<?php echo $i . $search; ?>"><?php echo $i; ?></a></li>
          <?php endfor; ?>
          <li><a href="?page=<?php echo $page+1 > $total_pages ? $total_pages:$page+1; ?><?php echo $search; ?>">下一页</a></li>
          <li><a href="?page=<?php echo $total_pages . $search; ?>">尾页</a></li>

        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
         <?php foreach ($posts as $item): ?>
            <tr>
            <td class="text-center"><input data-id="<?php echo $item['id']; ?>" type="checkbox"></td>
            <td><?php echo $item['title']; ?></td>
            <td><?php echo $item['nickname']; ?></td>
            <td><?php echo $item['name']; ?></td>
            <td class="text-center"><?php echo xiu_convert_date($item['created']); ?></td>
            <!-- 一旦当输出的判断逻辑或者转换逻辑过于复杂，不建议直接写在混编的位置 -->
            <td class="text-center"><?php echo xiu_convert_status($item['status']); ?></td>
            <td class="text-center">
              <a href="/admin/post-add.php?id=<?php echo $item['id']; ?>" class="btn btn-default btn-xs">编辑</a>
              <a href="/admin/posts-del.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
         <?php endforeach ?>
         
        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'posts'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $(function ($) {
      var $tbodyChecked=$('tbody input');
      var $btnDelete=$('#btn-delete');
      var allChecked=[];
      $tbodyChecked.on('change', function() {
        
        var id=$(this).data('id');
        if ($(this).prop('checked')) {
          allChecked.push(id);
        }else{
          allChecked.splice(allChecked.indexOf(id),1);
        }
        allChecked.length ? $btnDelete.fadeIn():$btnDelete.fadeOut();
        $btnDelete.prop('search','?id='+allChecked);
      });
    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
