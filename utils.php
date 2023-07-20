<?php
require_once __DIR__ . '/config.php';

function uploadFile($fieldName, $savePath)
{
    unset($_SESSION['upload_result']);
    $result = [];
    $target_file = basename($_FILES[$fieldName]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $upload = true;

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$fieldName]["tmp_name"]);
        if ($check === false) {
            $result['error'] = "File is not an image.";
            $upload = false;
        }
        // echo "File is an image - " . $check["mime"] . ".";
    }

    // Check file size if its larger than 5MB stop
    if ($_FILES[$fieldName]["size"] > 5000000) {
        $result['error'] = "Sorry, your file is too large.";
        $upload = false;
    }

    // Change file name to Unique name
    $timestamp = microtime(true);
    $target_file = md5($timestamp . "_" .  $target_file) . "." . $imageFileType;

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $result['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload = false;
    }
    $filename = $target_file;
    $target_file = $savePath . "/" . $target_file;
    if ($upload) {
        if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $target_file))
            $result["filename"] = $filename;
        else {
            $result['error'] = "Sorry, there was an error uploading your file.";
            $upload = false;
        }
    }
    $_SESSION["upload_result"] = $result;
    return $upload;
}

function timeAgo($timestamp)
{
    $now = time();
    $difference = $now - $timestamp;
    // var_dump($now, $timestamp);

    if ($difference < 60) {
        return $difference . ' seconds ago';
    } elseif ($difference < 3600) {
        $minutes = floor($difference / 60);
        return $minutes . ' minutes ago';
    } elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return $hours . ' hours ago';
    } elseif ($difference < 604800) {
        $days = floor($difference / 86400);
        return $days . ' days ago';
    } else {
        return date('Y-m-d', $timestamp); // Show the date for more than 1 week ago
    }
}


function truncateText($text, $length, $ellipsis = '...')
{
    if (mb_strlen($text) <= $length) {
        return $text;
    } else {
        return mb_substr($text, 0, $length) . $ellipsis;
    }
}

// Redirect to a page relative to server root
function redirect($page)
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $serverName = $_SERVER['SERVER_NAME'];
    $baseUrl = $protocol . $serverName;
    $url= $baseUrl . PROJECT_ROOT . $page;
    header("location: $url");
}
