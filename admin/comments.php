<?php 
require_once '../functions.php';


$link='sa';
   $arr=array(
    'icon' =>'$icon',
    'text' =>'$text',
    'title' =>'$title',
    'link' =>$link
  );

 $a=[];
  array_push($a,$arr);
  $a=json_encode(array_push($a,$arr));
 
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right"></ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="150">操作</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>


  <?php $current_page = 'comments'; ?>
  <?php include 'inc/sidebar.php' ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <!-- 模板引擎 -->
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script id="comments_tmpl" type="text/x-jsrender">
    {{for comments}}
       <tr {{if status == 'held'}} class="warning" {{else status == 'rejected'}} class="danger" {{/if}} data-id="{{:id}}">
            <td class="text-center"><input type="checkbox"></td>
            <td>{{:user_nickname}}</td>
            <td>{{:content}}</td>
            <td>{{:post_title}}</td>
            <td>{{:created}}</td>
            <td>{{if status == 'held'}}待审{{else status =='rejected'}}拒绝{{else}}允许{{/if}}</td>
            <td class="text-center">
              {{if status =='held'}}
              <a href="javascript:;" class="btn-allow btn btn-info btn-xs">批准</a>
              <a href="javascript:;" class="btn-reject btn btn-warning btn-xs">拒绝</a>
              {{/if}}
              <a href="javascript:;" class="btn-delete btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
    {{/for}}
  </script>
  <script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script>
    var currentPage=1;
    function loadPageData(page) {
      //发送ajax请求获取列表的所需数据
      $.get('/admin/api/comments.php',{page:page},function (res) {
        if (page > res.totalPages) {
          loadPageData(res.totalPages);
          return;
        }
        $('.pagination').twbsPagination('destroy');
        $('.pagination').twbsPagination({
          first:'首页',
          prev:'上一页',
          next:'下一页',
          last:'末页',
          startPage:page,
          totalPages:res.totalPages,
          visiablePages:5,
          initiateStartPageClick:false,
          onPageClick:function (e,page) {
          //第一次初始化时就会触发一次
            loadPageData(page);
          }
        });

      //请求执行过后自动执行
      //console.log(res['comments']);
      //准备一个给模板使用的数据
     
      //将数据渲染到页面上
        var html=$('#comments_tmpl').render({comments: res['comments']});
        $('tbody').fadeOut(function () {
          $(this).html(html).fadeIn();
        });
        currentPage=page;
      });
    }
     $('.pagination').twbsPagination({
          first:'首页',
          prev:'上一页',
          next:'下一页',
          last:'末页',
          totalPages:100,
          visiablePages:5,
          
          onPageClick:function (e,page) {
          //第一次初始化时就会触发一次
            loadPageData(page);
          }
        });


    loadPageData(currentPage);
    
    //删除功能
    //由于删除按钮是动态添加的，而且执行动态添加的代码是在此之后执行的
   /*   $('.btn-delete').on('click', function() {
        console.log(1);
      });*/
    //委托注册
    $('tbody').on('click','.btn-delete', function() {
        //删除单条数据
        //1.先拿到需要删除的数据id
        var $tr=$(this).parent().parent();
        var id=$tr.data('id');
        //2.发送一个ajax请求，告诉服务端要删除哪一条数据
        $.get('/admin/api/comments-del.php',{id:id},function (res) {
          //3.根据服务端返回的删除是否在界面上移除这个元素
          if (!res) return;
          //4.重新载入数据
          loadPageData(currentPage);
        })   
      });

    //批准功能
    //委托注册
    $('tbody').on('click','.btn-allow', function() {
       
        //1.先拿到需要删除的数据id
        var $tr=$(this).parent().parent();
        var id=$tr.data('id');
        //2.发送一个ajax请求，告诉服务端要允许哪一条数据
        $.get('/admin/api/comments-update.php',{id:id,status:'published'},function (res) {
          
          if (!res) return;
          //4.重新载入数据
          loadPageData(currentPage);
        })   
      });
    //批准功能
    //委托注册
    $('tbody').on('click','.btn-reject', function() {
       
        //1.先拿到需要删除的数据id
        var $tr=$(this).parent().parent();
        var id=$tr.data('id');
        //2.发送一个ajax请求，告诉服务端要允许哪一条数据
        $.get('/admin/api/comments-update.php',{id:id,status:'rejected'},function (res) {
          
          if (!res) return;
          //4.重新载入数据
          loadPageData(currentPage);
        })   
      });
  
  </script>
</body>
</html>
