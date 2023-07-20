<?php
require_once __DIR__ . '/controllers/auth.php';
require_once __DIR__ . '/controllers/posts.php';
require_once __DIR__ . "/utils.php";

$user = Auth::isAuth();
$posts = Post::getAllPosts();
// FIXME: A Lazy fix as php doesn't get summer time in Egypt
date_default_timezone_set("Asia/Riyadh");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/head.php" ?>
</head>

<body>
    <?php include "components/navbar.php" ?>
    <section class="container pt-5">
        <?php include "components/posts.php" ?>
    </section>
    <!-- MDB -->
    <script type="text/javascript" src="js/mdb.min.js"></script>
</body>

</html>