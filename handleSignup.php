<?php
require "users.php";
$result = User::register("", "kassem", "test4@gmail.com", "010989880434", "123456");
var_dump($result);
