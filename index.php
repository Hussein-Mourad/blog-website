<?php

require_once __DIR__ . '/controllers/auth.php';
require_once __DIR__ . '/controllers/posts.php';
require_once __DIR__ . "/utils.php";
$user = Auth::isAuth();
$posts = Post::getAllPosts();
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">

        <!-- Container wrapper -->
        <div class="container">
            <!-- Navbar brand -->
            <a class="navbar-brand me-2" href="https://mdbgo.com/">
                <img src="assets/imgs/mdb-transaprent-noshadows.webp" height="16" alt="MDB Logo" loading="lazy" style="margin-top: -1px;" />
            </a>

            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarButtonsExample" aria-controls="navbarButtonsExample" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                    if ($user && $user->getRole() === 'admin') {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                    <?php
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>

                    <?php
                    if ($user) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="createPost.php">Add Post</a>
                        </li>
                    <?php
                    }
                    ?>

                </ul>
                <!-- Left links -->

                <div class="d-flex align-items-center">
                    <?php
                    if ($user) {
                    ?>
                        <a href="forms/handleLogout.php">
                            <button type="button" class="btn btn-primary px-3 me-2">
                                Logout
                            </button>
                        </a>
                    <?php
                    } else {
                    ?>
                        <a href="login.php">
                            <button type="button" class="btn btn-link px-3 me-2">
                                Login
                            </button>
                        </a>
                        <a href="signup.php">
                            <button type="button" class="btn btn-primary me-3">
                                Signup
                            </button>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <section class="container pt-5">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="https://mdbcdn.b-cdn.net/wp-content/uploads/2020/06/vertical.webp" alt="thumbnail" class="img-fluid rounded-start" />
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">
                            This is a wider card with supporting text below as a natural lead-in to
                            additional content. This content is a little bit longer.
                        </p>
                        <p class="card-text">
                            <small class="text-muted">Last updated 3 mins ago</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        foreach ($posts as $key => $post) {
            $postId = $post[0];
            $title = $post[1];
            $content = $post[2];
            $thumbnail = $post[3];
            $author = $post[4];
            $updatedAt = timeAgo(strtotime($post[5]));
            $category = $post[7];
            var_dump($thumbnail);

        ?>
            <a href="">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src=" <?= $thumbnail ?>" alt="thumbnail" class="img-fluid rounded-start" />
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?= $title ?></h5>
                                <p class="card-text">
                                    <?= $content ?>
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