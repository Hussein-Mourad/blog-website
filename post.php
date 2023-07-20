<?php
require_once __DIR__ . '/controllers/auth.php';
require_once __DIR__ . '/controllers/posts.php';
require_once __DIR__ . '/controllers/comments.php';
require_once __DIR__ . "/utils.php";
$user = Auth::isAuth();
if (!isset($_GET["id"]))
    header("location: index.php");
$id = $_GET['id'];
$post = Post::getPost($id);
if (!$post)
    header("location: index.php");
$comments = Comment::getAllPostComments($id);
$postId = $post["id"];
$title = $post['title'];
$content = $post['content'];
$thumbnail = $post['thumbnail'];
$avatar = $post['picture'];
if (empty($avatar))
    $avatar = "/assets/imgs/default-avatar.jpg";
if (empty($thumbnail))
    $thumbnail = "/assets/imgs/default_image.png";
$authorId = $post['authorId'];
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
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h1 class="card-title mb-3"><?= $title ?></h1>
                    <?php
                    if ($user->getId() == $authorId || $user->getRole() == 'admin') {
                    ?>
                        <div class="d-flex">
                            <form method="post" action="editPost.php" class="me-2">
                                <input name="id" value="<?= $postId ?>" type="hidden" />
                                <textarea name="title" class="d-none" id="text"><?= addslashes($title) ?></textarea>
                                <textarea name="content" class="d-none" id="content"><?= $content ?></textarea>
                                <button class="btn btn-lg btn-primary px-4">Edit</button>
                            </form>
                            <form action="forms/handleDeletePost.php" method="post">
                                <input name="id" value="<?= $postId ?>" type="hidden" />
                                <button class=" btn btn-lg btn-danger" type="submit">Delete</button>
                            </form>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img src=".<?= $avatar ?>" class="rounded-circle" style="width: 50px;" alt="Avatar" />
                        </div>
                        <div class="mt-1">
                            <h6 class="mb-0"><strong><?= $author ?></strong></h6>
                            <p class="text-muted mb-0">Author</p>
                        </div>
                    </div>

                    <p class="card-text">
                        <small class="text-muted">Last updated <?= $updatedAt ?></small>
                    </p>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <div>
                        <div>React Comment</div>
                        <!-- <div>Comment</div> -->
                    </div>


                </div>
                <hr>

                <div class="thumbnail">
                    <img src=".<?= $thumbnail ?>" class="card-img-top" alt="Thumbnail" />
                </div>

                <p class="card-text mt-3">
                    <?= $content ?>
                </p>

            </div>
        </div>
    </section>

    <section class="container pt-5">
        <div class="mt-4">
            <h2 class="">Comments</h2>
            <hr class="mb-3">
            <?php
            foreach ($comments ?? [] as $id => $comment) {
            ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <img src=".<?= $comment->avatar ?>" class="rounded-circle" style="width: 50px;" alt="<?= $comment->username ?>" />
                            </div>
                            <div class="">
                                <h6 class="mb-0"><strong><?= $comment->username ?></strong></h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?= $comment->content ?></p>
                        <button class="btn btn-secondary">Reply</button>
                        <form action="">
                            <div class="mb-3">
                                <label for="reply" class="form-label">Reply</label>
                                <input type="hidden" name="parentId" value="">
                                <textarea class="form-control" name="reply" id="reply" rows="3" placeholder="Enter your reply"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Reply</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <h3>Replies</h3>
                        <?php
                        foreach ($comment->replies ?? [] as $reply) {
                        ?>
                            <div class="card mb-3">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <img src=".<?= $reply->avatar ?>" class="rounded-circle" style="width: 50px;" alt="<?= $reply->username ?>" />
                                        </div>
                                        <div class="">
                                            <h6 class="mb-0"><strong><?= $reply->username ?></strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?= $reply->content ?></p>
                                </div>
                            </div>
                    </div>
                </div>
        <?php
                        }
                    }
        ?>
        </div>
    </section>
</body>

</html>