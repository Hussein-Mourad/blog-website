<?php
require_once "../controllers/auth.php";
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
if (isset($_POST['role']))
    $role = $_POST['role'];
else
    $role = 'regular';
$user = Auth::register($firstName, $lastName, $email, $phone, $password, $role);
