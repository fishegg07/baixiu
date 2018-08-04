<?php
require_once '../functions.php';

//判断用户是否登录，一定最先去做的
xiu_get_current_user();

//获取界面所需要的数据
//重复的操作一定封装起来

$posts_count = xiu_fetch_one('select count(1) as num from posts;')['num'];


$categories_count = xiu_fetch_one('select count(1) as num from categories;')['num'];

$comments_count = xiu_fetch_one('select count(1) as num from comments;')['num'];

//草稿的数量
$status_drafted = xiu_fetch_one("select count(status) as num from posts where status = 'drafted';")['num'];
//草稿的数量
$status_published = xiu_fetch_one("select count(status) as num from posts where status = 'published';")['num'];

//回收站的数量
$status_trashed = xiu_fetch_one("select count(status) as num from posts where status = 'trashed';")['num'];

//准许的数量
$status_approved = xiu_fetch_one("select count(status) as num from comments where status = 'approved';")['num'];

//待审核的数量
$status_held = xiu_fetch_one("select count(status) as num from comments where status = 'held';")['num'];

//拒绝的数量
$status_rejected = xiu_fetch_one("select count(status) as num from comments where status = 'rejected';")['num'];

//评论回收的数量
$status_tra = xiu_fetch_one("select count(status) as num from comments where status = 'trashed';")['num'];

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="../static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../static/assets/css/admin.css">
  <link rel="stylesheet" href="../static/assets/css/index.css">
  <script src="../static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'; ?>

    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts_count; ?></strong>篇文章（<strong><?php echo $status_drafted; ?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $categories_count; ?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $comments_count; ?></strong>条评论（<strong><?php $status_held ?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4 text-center">
          <div><h3>文章</h3></div>
          <canvas id="chart"></canvas>
        </div>
        <div class="col-md-4 text-center">
          <div><h3>评论</h3></div>
          <canvas id="chart2"></canvas>
        </div>
      </div>
    </div>
  </div>

  <?php $current_page = 'index'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../static/assets/vendors/chart/Chart.js"></script>
  <script>
    var ctx = document.getElementById('chart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        datasets: [
          {
            data: [<?php echo $status_drafted; ?>, <?php echo $status_published; ?>, <?php echo $status_trashed; ?>],
            backgroundColor: [
              '#FF0000',
              '#019858',
              '#46A3FF',
            ]
          }
        ],
        labels: [
          '草稿',
          '已发布',
          '回收站'
        ]
      }
    });

    var ctx2 = document.getElementById('chart2').getContext('2d');
    var myChart2 = new Chart(ctx2, {
      type: 'pie',
      data: {
        datasets: [
          {
            data: [<?php echo $status_approved; ?>, <?php echo $status_held; ?>, <?php echo $status_rejected; ?>, <?php echo $status_tra; ?>],
            backgroundColor: [
              '#FF0000',
              '#019858',
              '#46A3FF',
              '#FF5809'
            ]
          }
        ],
        labels: [
          '准许',
          '待审核',
          '拒绝',
          '回收站'
        ]
      },
      options: {rotation: 10}
    });

  </script>
  <script>NProgress.done()</script>
</body>
</html>
