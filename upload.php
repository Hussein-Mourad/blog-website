<?php
require_once "config.php";

function uploadFile($fieldName)
{
    $return = [];
    $target_file = basename($_FILES[$fieldName]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$fieldName]["tmp_name"]);
        if ($check === false)
            $return['error'] = "File is not an image.";
        // echo "File is an image - " . $check["mime"] . ".";
    }

    // Check file size if its larger than 5MB stop
    if ($_FILES["fileToUpload"]["size"] > 5000000)
        $return['error'] = "Sorry, your file is too large.";

    // Change file name to Unique name
    $timestamp = microtime(true);
    $target_file = md5($timestamp . "_" .  $target_file) . "." . $imageFileType;

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    )
        $return['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $target_file = UPLOAD_DIR . $target_file;
    if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $target_file))
        $return["filepath"] = $target_file;
    else
    {
        $return['error'] = "Sorry, there was an error uploading your file.";
    
    }
    return $return;
}


$fieldName = "fileToUpload";
$result = uploadFile($fieldName);
var_dump($result);
