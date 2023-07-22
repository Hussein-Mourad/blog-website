<?php

require_once __DIR__ . '/../../utils.php';
require_once __DIR__ . '/../../controllers/posts.php';
require_once __DIR__ . '/../../config.php';

if (!isset($_SESSION))
    session_start();

$title = $_POST['title'];
$content = $_POST['content'];
$categoryId = $_POST['category'];
$thumbnail = null;
$isThumbnail = !empty($_FILES["thumbnail"]["name"]);
$filepath = null;

# Upload Image if exists
if ($isThumbnail) {
    $thumbnail = $_FILES["thumbnail"]["name"];
    $result = uploadFile("thumbnail", __DIR__ . "/../.." . UPLOAD_DIR);
    if ($result) {
        $filename =  $_SESSION["upload_result"]["filename"];
        $filepath = "/" . UPLOAD_DIR . "/" . $filename;
    } else {
        redirect("/createPost.php");
        die("Invaild Upload");
    }
}

$result = Post::create($title, $content, $categoryId, $filepath);
var_dump($result);
