<?php
require_once __DIR__ . '/../../controllers/auth.php';
require_once __DIR__ . '/../../controllers/posts.php';
require_once __DIR__ . "/../../utils.php";
$user = Auth::AuthOnly();
$id = $_POST['id'];
$result = Post::delete($id);
// var_dump ($result);
redirect("/index.php");
