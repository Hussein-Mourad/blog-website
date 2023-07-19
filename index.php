<?php
require_once "auth.php";
$user = Auth::isAuth();
if ($user)
    $user = $_SESSION["user"];
var_dump ($user);
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

    <?php
    $filename = "test.jpg";
    $timestampWithMicroseconds = microtime(true);
    // echo md5($timestampWithMicroseconds . "_".  $filename);    
    ?>


    <form action="" method="post"></form>
</body>

</html>