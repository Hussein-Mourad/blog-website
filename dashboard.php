<?php
require_once __DIR__ . '/controllers/auth.php';

$pageTile = "Dashboard";
$user = Auth::AdminOnly();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/head.php" ?>
</head>

<body>
    <?php include "components/navbar.php" ?>
</body>

</html>