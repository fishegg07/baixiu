<?php

require_once '../../functions.php';

$page = empty($_GET['page']) ? 1 : intval($_GET['page']);

$legth = 5;
$offset = ($page - 1) * $legth;

$sql = sprintf('select
comments.*,
posts.title as post_title
from comments
inner join posts on comments.post_id = posts.id
order by comments.created desc
limit %d, %d;', $offset, $legth);


//查询所有的评论数据
$comments = xiu_fetch_all($sql);
//先查询到所有的评论数量
$total_count = xiu_fetch_one('select count(1) as count
from comments
inner join posts on comments.post_id = posts.id')['count'];

$total_pages = ceil($total_count / $legth);
//因为网络之间传输的只能是字符串
//所有我们先将数据转换成字符串

$json = json_encode(array(
    'total_pages' => $total_pages,
    'comments' => $comments
));

//设置响应的响应体类型为：JSON
header('Content-Type: application/json');

//响应给客户端
echo $json;


 ?>