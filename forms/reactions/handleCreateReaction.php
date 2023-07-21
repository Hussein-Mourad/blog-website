<?php

require_once __DIR__ . '/../../utils.php';
require_once __DIR__ . '/../../controllers/reactions.php';
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION))
    session_start();
$postId = $_POST['postId'];
$type = $_POST['type'];
$result = Reaction::create($postId, $type);
var_dump($result);
redirect("/post.php?id=" . $postId);