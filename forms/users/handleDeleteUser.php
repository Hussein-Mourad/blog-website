<?php
require_once __DIR__ . '/../../controllers/auth.php';
require_once __DIR__ . "/../../utils.php";

$user = Auth::AdminOnly();
$id = $_POST['id'];
$result = User::delete($id);
// var_dump ($result);
redirect("/dashboard.php?tab=users");
