<?php
session_start();
var_dump ($_SESSION);
if (isset($_SESSION["user"]))
	$user = $_SESSION["user"];
else
    $user = null;
// var_dump($errors);
// unset($_SESSION['errors']);
?>
