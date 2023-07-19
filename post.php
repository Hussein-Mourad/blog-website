<?php
require_once __DIR__ . '/controllers/auth.php';
require_once __DIR__ . '/controllers/posts.php';
require_once __DIR__ . "/utils.php";
$user = Auth::isAuth();
if (!isset($_GET["id"]))
    header("location: index.php");
$id = $_GET['id'];
$post = Post::getPost($id);
if (!$post)
    header("location: index.php");
$postId = $post["id"];
$title = $post['title'];
$content = $post['content'];
$thumbnail = $post['thumbnail'];
if (empty($thumbnail))
    $thumbnail = "/assets/imgs/default_image.png";
$author = $post['author'];
$updatedAt = timeAgo(strtotime($post['updatedAt']));
$category = $post['category'];

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
    <style>
        .thumbnail {
            /* max-width: 300px; */
            width: 100%;
            max-height: 1000px;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">

        <!-- Container wrapper -->
        <div class="container">
            <!-- Navbar brand -->
            <a class="navbar-brand" href="index.php">Blogs</a>
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
                        <a class="nav-link" href="index.php">Home</a>
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
            <div class="row">
                <div class="col-md-4 position-relative overflow-hidden d-flex justify-content-center align-items-center">
                    <img src=".<?= $thumbnail ?>" alt="thumbnail" class="img-fluid rounded-start position-absolute w-100, h-100 object-cover" />
                </div>
            </div>
            <div class="thumbnail">
                <img src=".<?= $thumbnail ?>" class="card-img-top" alt="Thumbnail" />
            </div>
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
    </section>
</body>

</html>