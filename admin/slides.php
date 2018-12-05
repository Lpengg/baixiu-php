<?php 
require_once '../functions.php';

xiu_get_current_user();

function add_slide(){
  if (empty($_FILES['image'])) {
    $GLOBALS['message']='请上传文件';
    return;
  }
  if (empty($_POST['text']) || empty($_POST['link'])) {
    $GLOBALS['message']='请完整填写数据';
    return;
  }
  

  $image=$_FILES['image'];
  if (!empty($image['error'])) {
    $GLOBALS['message']='上传文件失败，请重新上传';
    return;
  }
  $temp_file = $image['tmp_name'];
  // 我们只需要把文件保存到我们指定上传目录
  $target_file = '../static/uploads/slideshow/'.uniqid().'-'. $image['name'];
  if (!move_uploaded_file($temp_file, $target_file)) {
      $GLOBALS['message']='移动文件失败，请重新上传';
      return;
    } 

  $image=substr($target_file,2);
  $text=$_POST['text'];
  $link=$_POST['link'];

  $data=array();
  $data['image']=$image;
  $data['text']=$text;
  $data['link']=$link;

  $slide_value=json_decode(xiu_fetch_one("select * from options where site='home_slides';")['val'],true);
  $slide_value[]=$data;
  //最终结果
  $slide_value=json_encode($slide_value,JSON_UNESCAPED_UNICODE);


  $affect_row=xiu_execute("update options set val='{$slide_value}' where site='home_slides';");
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
  add_slide();
}


$slide_value=json_decode(xiu_fetch_one("select * from options where site='home_slides';")['val'],true);
$num=1;
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Slides &laquo; Admin</title>
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
        <h1>图片轮播</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($message)): ?>
        <div class="alert <?php echo isset($success) ? 'alert-success':'alert-danger'; ?>">
        <strong><?php echo $message; ?></strong>
      </div>
        
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
            <h2>添加新轮播内容</h2>
            <div class="form-group">
              <label for="image">图片</label>
              <!-- show when image chose -->
              <img class="help-block thumbnail" style="display: none">
              <input id="image" class="form-control" name="image" type="file" accept="image/*">
            </div>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="link">链接</label>
              <input id="link" class="form-control" name="link" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="btn-delete" class="btn btn-danger btn-sm" href="/admin/slides-del.php" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center">图片</th>
                <th>文本</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($slide_value as $item): ?>
              <tr>
                <td class="text-center"><input type="checkbox" data-num="<?php echo $num; ?>"></td>
                <td class="text-center"><img class="slide" src="<?php echo $item['image']; ?>"></td>
                <td><?php echo $item['text']; ?></td>
                <td><?php echo $item['link']; ?></td>
                <td class="text-center">
                  <a href="/admin/slides-del.php?num=<?php echo $num;$num+=1; ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
            <?php endforeach ?>
             
            
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
 <?php $current_page = 'slides'; ?>
  <?php include 'inc/sidebar.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $(function () {
       // 当文件域文件选择发生改变过后，本地预览选择的图片
      $('#image').on('change', function () {
        var file = $(this).prop('files')[0]
        // 为这个文件对象创建一个 Object URL
        var url = URL.createObjectURL(file)
        // url => blob:http://zce.me/65a03a19-3e3a-446a-9956-e91cb2b76e1f
        // 不用奇怪 BLOB: binary large object block
        // 将图片元素显示到界面上（预览）

        $(this).siblings('.thumbnail').attr('src', url).fadeIn()
      })



      //多选删除
      var $tbodyinput=$('tbody input');
      var allChecked=[];
      var $btnDelete=$('#btn-delete');
      $tbodyinput.on('change', function() {
        var $num=$(this).data('num');
        if ($(this).prop('checked')) {
          allChecked.push($num);
        }else{
          allChecked.splice(allChecked.indexOf($num),1);
        }
        allChecked.length > 0 ? $btnDelete.fadeIn():$btnDelete.fadeOut();
        $btnDelete.prop('search','?num='+allChecked);
      });
    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
