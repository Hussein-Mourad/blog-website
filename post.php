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
    <?php include "./components/navbar.php"; ?>

    <section class="container pt-5">
    <?php include "./components/single-post.php"; ?>
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