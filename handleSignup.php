<?php
require "users.php";
$user = User::register("hussein", "kassem", "hussein3@hussein.com", "0100054554354", "123456");
if ($user)
    $_SESSION["user"] = serialize($user);
