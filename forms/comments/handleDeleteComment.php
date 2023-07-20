<?php

require_once __DIR__ . '/../../utils.php';
require_once __DIR__ . '/../../controllers/comments.php';
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION))
    session_start();

$id = $_POST['id'];
$postId = $_POST['postId'];
$result = Comment::delete($id, $content, $parentCommentId);
var_dump($result);
redirect("/post.php?id=" . $postId);
