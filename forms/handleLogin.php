<?php
require_once "../controllers/auth.php";
$email = $_POST["email"];
$password = $_POST["password"];
$user = Auth::login($email, $password);
