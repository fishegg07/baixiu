
<?php
//根据客户端传递过来的ID删除对应数据
require_once '../functions.php';


if(empty($_GET['id'])){
    exit('缺少必要参数');
}

$id = $_GET['id'];

$rows = xiu_execute('delete from categories where id in ('. $id.');');

header('Location: /admin/categories.php');