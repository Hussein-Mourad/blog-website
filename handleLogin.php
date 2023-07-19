<?php
session_start();
require "users.php";
$user= User::login("hussein3@hussein.com", "123456");
if ($user)
    $_SESSION["user"] = serialize($user);
