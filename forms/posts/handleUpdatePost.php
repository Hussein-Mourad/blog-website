<?php

require_once __DIR__ . '/../../utils.php';
require_once __DIR__ . '/../../controllers/posts.php';
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION))
    session_start();

$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];
// var_dump($id, $title, $content);
$result = Post::update($id, $title, $content);
var_dump($result);
if ($result)
    redirect("/post.php?=" . $id);
