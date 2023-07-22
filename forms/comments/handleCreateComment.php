<?php

require_once __DIR__ . '/../../utils.php';
require_once __DIR__ . '/../../controllers/comments.php';
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION))
    session_start();

var_dump($_POST);

$postId = $_POST['postId'];
$content = $_POST['content'];
$parentCommentId = null;
if (isset($_POST['parentCommentId'])) {
    $parentCommentId = $_POST['parentCommentId'];
}
$result = Comment::create($postId, $content, $parentCommentId);
redirect("/post.php?id=" . $postId);
