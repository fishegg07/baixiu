<?php
require_once '../functions.php';

xiu_get_current_user();

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
  <link rel="stylesheet" href="../static/assets/css/loading.css">
  <script src="../static/assets/vendors/nprogress/nprogress.js"></script>
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
        <ul class="pagination pagination-sm pull-right">
        </ul>
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
        <tbody>

        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'comments'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <div id="loading" style="display: none;">
    <div class="flip-txt-loading">
      <span>L</span><span>o</span><span>a</span><span>d</span><span>i</span><span>n</span><span>g</span>
    </div>
  </div>


  <script id="comments_tmp" type="text/x-jsrender">
  {{for comments}}
  <tr{{if status == 'held'}} class="warning"{{else status == 'rejected'}} class="danger"{{/if}} data-id={{:id}}>
    <td class="text-center"><input type="checkbox"></td>
    <td>{{:author}}</td>
    <td>{{:content}}</td>
    <td>{{:post_title}}</td>
    <td>{{:created}}</td>
    <td>{{:status}}</td>
    <td class="text-center">
      {{if status == 'held'}}
      <a href="post-add.php" class="btn btn-info btn-xs">批准</a>
      <a href="post-add.php" class="btn btn-warning btn-xs">拒绝</a>
      {{/if}}
      <a href="javascript:;" class="btn btn-danger btn-xs btn-delete">删除</a>
    </td>
  </tr>
  {{/for}}
  </script>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../static/assets/vendors/jsrender/jsrender.js"></script>
  <script src="../static/assets/vendors/twbs-pagination/jquery.twbsPagination.js"></script>
  <script>
    //nprogress
    $(document)
       .ajaxStart(function () {
         NProgress.start();
         //显示loading
         $('#loading').fadeIn();
       })

       .ajaxStop(function () {
         NProgress.done();
         //隐藏loading
         $('#loading').fadeOut();
       })

    var currentPage = 1;

    function loadPageData (page){
      $('tbody').fadeOut();
      $.getJSON('../admin/api/comments.php', {page:page}, function(data){
        if(page > data.total_pages){
          loadPageData(data.total_pages);
          return false;
        }
        //第一次回调时没有初始化分页组件
        //第二次调用这个组件不会重新渲染分页组件
        $('.pagination').twbsPagination('destroy')
        $('.pagination').twbsPagination({
          first: '首页',
          last: '尾页',
          prev: '上一页',
          next: '下一页',
          startPage: page,
          totalPages: data.total_pages,
          visiablePages: 5,
          initiateStartPageClick: false,
          onPageClick: function(e, page){
            //点击分页页码会执行这里
            loadPageData(page);
          }
        })

        //渲染数据
        var html = $('#comments_tmp').render({comments: data.comments});
        $('tbody').html(html).fadeIn();
        currentPage = page;
      });
    }

    $('.pagination').twbsPagination({
      first: '首页',
      last: '尾页',
      prev: '上一页',
      next: '下一页',
      totalPages: 100,
      visiablePages: 5,
      onPageClick: function(e, page) {
        //点击分页页码会执行这里
        loadPageData(page);
      }
    })

    //删除功能
    //==================================
    //由于删除按钮是动态添加的，而且执行动态添加的代码是在此之后执行的，过早注册不上
    //委托事件
    $('tbody').on('click','.btn-delete',function(){
      //删除单挑数据的时机
      //1.拿到需要删除的数据ID
      var $tr = $(this).parent().parent();
      var id = $tr.data('id');
      //2.发送一个 AJAX 请求，告诉服务端要删除哪一条具体的数据
      $.get('../admin/api/comments-delete.php', {id:id}, function(res){
        if(!res) return;
      //3.根据服务端返回的删除是否成功决定是否在界面上移除这个元素
      //4.重新再载入当前这一页的数据
        loadPageData(currentPage);
      })
    })

  </script>
  <script>NProgress.done()</script>
</body>
</html>
