<?php
require_once "config.php";

function uploadFile($fieldName)
{
    unset($_SESSION['upload_result']);
    $result = [];
    $target_file = basename($_FILES[$fieldName]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$fieldName]["tmp_name"]);
        if ($check === false)
            $result['error'] = "File is not an image.";
        // echo "File is an image - " . $check["mime"] . ".";
    }

    // Check file size if its larger than 5MB stop
    if ($_FILES[$fieldName]["size"] > 5000000)
        $result['error'] = "Sorry, your file is too large.";

    // Change file name to Unique name
    $timestamp = microtime(true);
    $target_file = md5($timestamp . "_" .  $target_file) . "." . $imageFileType;

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    )
        $result['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $target_file = UPLOAD_DIR . $target_file;
    if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $target_file))
        $result["filepath"] = $target_file;
    else {
        $result['error'] = "Sorry, there was an error uploading your file.";
    }

    $_SESSION["upload_result"] = $result;
    return isset($_SESSION['upload_result']);
}

function timeAgo($timestamp) {
    $now = time();
    $difference = $now - $timestamp;

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