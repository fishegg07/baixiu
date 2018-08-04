<?php
require_once '../functions.php';

xiu_get_current_user();

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>doupan</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
  <script src="../static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
      <table class="table table-striped table-bordered table-hover">
        <thead><h3>电影榜单</h3></thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>

  <?php $current_page = 'doupan'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../static/assets/vendors/jsrender/jsrender.js"></script>
  <script id="movies_tmp" type="text/x-jsrender">

  <tr><td>电影名称</td><td>题材类型</td><td>主演</td></tr>
  {{for moviesdata}}
  <tr>
    <td>{{:title}}</td>
    <td>{{:genres}}</td>
    <td>{{for casts}}{{:name}}{{/for}}</td>
  </tr>
  {{/for}}
  </script>
  <script>
    $.ajax({
      url:'https://api.douban.com/v2/movie/in_theaters',
      dataType:'jsonp',
      success: function(res){
        var html = $('#movies_tmp').render({moviesdata:res.subjects});
        $('tbody').html(html).fadeIn();
      }
    });
  </script>

  <script>NProgress.done()</script>
</body>
</html>
