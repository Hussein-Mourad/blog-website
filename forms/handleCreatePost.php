<?php

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../controllers/posts.php';
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION))
    session_start();

$title = $_POST['title'];
$content = $_POST['content'];
$categoryId = $_POST['category'];
$thumbnail = null;
$isThumbnail = !empty($_FILES["thumbnail"]["name"]);

# Upload Image if exists
if ($isThumbnail) {
    $thumbnail = $_FILES["thumbnail"]["name"];
    $result = uploadFile("thumbnail", __DIR__ . "/../uploads");
    if ($result) {
        $filename =  $_SESSION["upload_result"]["filename"];
    } else {
        header("location: ../" . ADD_POST_PAGE);
        die("Invaild Upload");
    }
}

$filepath = "/" . UPLOAD_DIR . "/" . $filename;

$result = Post::create($title, $content, $categoryId, $filepath);
var_dump($result);