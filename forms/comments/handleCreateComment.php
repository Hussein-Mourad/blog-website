<?php

require_once __DIR__ . '/../../utils.php';
require_once __DIR__ . '/../../controllers/comments.php';
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION))
    session_start();

$postId = $_POST['postId'];
$content = $_POST['content'];
$parentCommentId = $_POST['parentCommentId'];
$result = Comment::create($postId, $content, $parentCommentId);
var_dump($result);

redirect("/post.php?id=" . $postId);
