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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Website</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/fontawesome-free-6.0.0-web/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="assets/mdb5/css/mdb.min.css" />
</head>

<body>
    <?php  include "navbar.php" ?>
    <section class="container pt-5">
        <?php 
        $last_category = 0;
        foreach ($posts ?? [] as $post) {
            $postId = $post[0];
            $title = $post[1];
            $content = truncateText($post[2], 200);
            $thumbnail = $post[3];
            $author = $post[5];
            $updatedAt = timeAgo(strtotime($post[6]));
            $categoryId = $post[7];
            $category = $post[8];
            if (empty($thumbnail))
                $thumbnail = "/assets/imgs/default_image.png";
            if ($last_category != $categoryId) {
                $last_category = $categoryId;
        ?>
                <h1 class="mt-5"><?= $category ?></h1>
                <hr class="mb-4" />

            <?php
            }
            ?>
            <a href="post.php?id=<?= $postId ?>">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src=".<?= $thumbnail ?>" alt="thumbnail" class="img-fluid rounded-start" />
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?= $title ?></h5>
                                <p class="card-text">
                                    <?= $content ?>
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">By: <?= $author ?></small>
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">Last updated <?= $updatedAt ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php
        }
        ?>
    </section>
</body>

</html>