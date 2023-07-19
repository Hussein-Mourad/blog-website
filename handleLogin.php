<?php
require "auth.php";
$user = Auth::login("hussein3@hjjussein.com", "123456");
if ($user)
    $_SESSION["user"] = serialize($user);
