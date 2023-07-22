<?php
require_once __DIR__ . '/controllers/auth.php';
require_once __DIR__ . '/controllers/posts.php';
require_once __DIR__ . "/utils.php";

$user = Auth::isAuth();
$posts = Post::getAll();
// FIXME: A Lazy fix as php doesn't get summer time in Egypt
// TODO: Make Template page
// TODO: Remove Redirect from Controllers and add them to froms handlers
date_default_timezone_set("Asia/Riyadh");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/head.php" ?>
</head>

<body>
    <header>
        <?php include "components/navbar.php" ?>
    </header>
    <main>
        <section class="container pt-5">
            <?php include "components/posts.php" ?>
        </section>
    </main>
    <!-- MDB -->
    <script type="text/javascript" src="assets/mdb5/js/mdb.min.js"></script>
</body>

</html>