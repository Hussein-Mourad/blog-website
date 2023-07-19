<?php
require_once __DIR__. '/../controllers/auth.php';

$email = $_POST["email"];
$password = $_POST["password"];
$user = Auth::login($email, $password);
