<?php
require_once '../functions.php';

xiu_get_current_user();

$categories = xiu_fetch_all('select * from categories;');

function add_post(){

  if(empty($_POST['slug']) || empty($_POST['title']) || empty($_POST['created'])
    || empty($_POST['content']) || empty($_POST['category'])){
    $message = '请完整填写所有内容！';
  }else if(xiu_fetch_one(sprintf("select count(1) as num from posts where slug = '%s'",$_POST['slug']))['num'] > 0){
    $message = '别名已经存在，清修改别名！';
  }else{
    if(empty($_FILES['feature']['error'])){

      $temp_file = $_FILES['feature']['tmp_name'];

      $target_file = '../static/uploads/' . $_FILES['feature']['name'];
      if( move_uploaded_file($temp_file,$target_file)){
        $image_file = $target_file;
      }
    }
  }

  //接收数据
  //==================================
  $slug = $_POST['slug'];
  $title = $_POST['title'];
  $feature = isset($image_file) ? $image_file : '';
  $created = $_POST['created'];
  $content = $_POST['content'];
  $status = $_POST['status'];
  $user_id = $_SESSION['current_login_user']['id'];
  $category = $_POST['category'];

  var_dump($slug);
  var_dump($title);
  var_dump($feature);
  var_dump($created);
  var_dump($content);
  var_dump($status);
  var_dump($user_id);
  var_dump($category);

  $rows = xiu_execute("insert into posts values(null,'{$slug}','{$title}','{$feature}','{$created}','{$content}',0,0,'{$status}',{$user_id},{$category});");

  if($rows > 0){
    header('Location: /admin/posts.php');
  }else{
    $message = "上传失败！";
  }
}

//处理提交请求
//==============================
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  add_post();
}

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
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
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(isset($message)): ?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $message; ?>
      </div>
      <?php endif ?>
      <form class="row" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">标题</label>
            <!-- <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea> -->
            <script id="content" name="content" type="text/plain">这是初始值</script>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <?php foreach($categories as $item): ?>
              <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php $current_page = 'post-add'; ?>
  <?php include 'inc/sidebar.php'; ?>

  <script src="../static/assets/vendors/jquery/jquery.js"></script>
  <script src="../static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../static/assets/vendors/ueditor/ueditor.config.js"></script>
  <script src="../static/assets/vendors/ueditor/ueditor.all.js"></script>
  <script>
    UE.getEditor('content',{
      initialFrameHeight: 500,
      autoHeight: false
    })
  </script>
  <script>NProgress.done()</script>

</body>
</html>
