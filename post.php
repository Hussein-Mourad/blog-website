<?php
require_once __DIR__ . '/controllers/auth.php';
require_once __DIR__ . '/controllers/posts.php';
require_once __DIR__ . '/controllers/comments.php';
require_once __DIR__ . "/utils.php";

$user = Auth::isAuth();
if (!isset($_GET["id"]))
    redirect("/index.php");

$id = $_GET['id'];
$post = Post::getPost($id);
if (!$post)
    redirect("/index.php");

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
    <?php include "components/head.php" ?>

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
    <header>
        <?php include "./components/navbar.php"; ?>
        <!-- Jumbotron -->
        <div id="intro" class="p-5 text-center bg-light">
            <h1 class="mb-0 h4"><?= ?></h1>
        </div>
    </header>

    <section class="container pt-5">
        <?php include "./components/post.php"; ?>
    </section>

    <?php if (!empty($comments)) : ?>
        <section class="container pt-5">
            <div class="mt-4">
                <h2 class="">Comments</h2>
                <hr class="mb-3">
                <?php foreach ($comments ?? [] as $id => $comment) {
                    include "components/comment.php";
                }
                ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($user) : ?>
        <section class="container pt-3 mb-5">
            <div class="card">
                <form action="forms/comments/handleCreateComment.php" method="post" class="card-body">
                    <h3 class="mb-3">Leave a Comment</h3>
                    <input type="hidden" name="postId" value="<?= $postId ?>">
                    <div class="mb-3">
                        <!-- <label for="commentContent" class="form-label">Comment</label> -->
                        <textarea class="form-control" name="content" id="commentContent" rows="3" placeholder="Enter your Comment" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </section>
    <?php endif; ?>
    <!-- MDB -->
    <script type="text/javascript" src="assets/mdb5/js/mdb.min.js"></script>
</body>

</html>