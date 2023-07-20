<?php
require_once __DIR__ . "/../../utils.php";
if (!isset($_SESSION))
    session_start();
unset($_SESSION['user']);
// session_unset();
// session_destroy();
redirect('/index.php');
