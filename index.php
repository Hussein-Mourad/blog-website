<?php
require_once "auth.php";
$user = Auth::isAuth();
if ($user)
    $user = $_SESSION["user"];
var_dump($user);
// var_dump($errors);
// unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Website</title>
</head>

<body>
    <a href="login.php">Login</a>
    <a href="signup.php">Signup</a>
    <a href="handleLogout.php">Logout</a>


    <form action="handleCreatePost.php" method="post" enctype="multipart/form-data">

        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>

    <?php
    $filename = "test.jpg";
    $pathinfo = pathinfo($filename);
    $fileName = $pathinfo['filename'];
    $fileExtension = $pathinfo['extension'];
    $timestamp = microtime(true);
    $target_file = md5($timestamp . "_" .  $fileName) . "." . $fileExtension;
    var_dump($target_file);
    ?>


    <form action="" method="post"></form>
</body>

</html>