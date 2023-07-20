<?php

require_once __DIR__ . '/../../utils.php';
require_once __DIR__ . '/../../controllers/comments.php';
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION))
    session_start();

$id = $_POST['id'];
$postId = $_POST['postId'];
$content = $_POST['content'];
$result = Comment::update($id, $content);
var_dump($result);
redirect("/post.php?id=" . $postId);
