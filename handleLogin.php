<?php
require_once "auth.php";
$email = $_POST["email"];
$password = $_POST["password"];
$user = Auth::login($email, $password);
