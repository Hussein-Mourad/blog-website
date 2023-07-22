<?php

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../controllers/reactions.php';
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION))
    session_start();

$id = $_POST['id'];
$postId = $_POST['postId'];
$type = $_POST['type'];
$result = Reaction::update($id, $type);
var_dump($result);
redirect("/post.php?id=" . $postId);
