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
            <h1 class="mb-0 h4"><?= $post->getTitle() ?></h1>
        </div>
        <!-- Jumbotron -->
    </header>
    <main>
        <div class="container md-8 mb-4">
            <div class="reactions btn-group">
                <button class="reaction-btn btn btn-secondary" data-post-id="<?= $post->getId() ?>" data-reaction-type="like" onclick="handleAddReaction(this)">&#128077;Like</button>
                <button class="reaction-btn btn btn-secondary" data-post-id="<?= $post->getId() ?>" data-reaction-type="love" onclick="handleAddReaction(this)">Love</button>
                <button class="reaction-btn btn btn-secondary" data-post-id="<?= $post->getId() ?>" data-reaction-type="haha" onclick="handleAddReaction(this)">Haha</button>
                <button class="reaction-btn btn btn-secondary" data-post-id="<?= $post->getId() ?>" data-reaction-type="sad" onclick="handleAddReaction(this)">Sad</button>
                <button class="reaction-btn btn btn-secondary" data-post-id="<?= $post->getId() ?>" data-reaction-type="angry" onclick="handleAddReaction(this)">Angry</button>
            </div>
            <div class="reactions-summary mt-2">
                <span class="reaction-count" data-post-id="<?= $post->getId() ?>" data-reaction-type="like">0</span> Likes
                <span class="reaction-count" data-post-id="<?= $post->getId() ?>" data-reaction-type="love">0</span> Loves
                <span class="reaction-count" data-post-id="<?= $post->getId() ?>" data-reaction-type="haha">0</span> Hahas
                <span class="reaction-count" data-post-id="<?= $post->getId() ?>" data-reaction-type="sad">0</span> Sads
                <span class="reaction-count" data-post-id="<?= $post->getId() ?>" data-reaction-type="angry">0</span> Angrys
            </div>
            <!--Section: Post data-mdb-->
            <section class="border-bottom mb-4">
                <div class="d-flex justify-content-center">
                    <img src=".<?= $post->getThumbnail() ?>" class="img-fluid shadow-2-strong rounded-5 mb-4" alt="" />
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-lg-6 text-center text-lg-start mb-3 m-lg-0">
                        <img src=".<?= $post->getAuthorAvatar() ?>" class="rounded-5 shadow-1-strong me-2" height="35" alt="" loading="lazy" />
                        <span> Published <u><?= date("d-m-Y", strtotime($post->getCreatedAt()));  ?></u> by
                            <span class="text-dark"><?= $post->getAuthor() ?></span>
                        </span>
                    </div>

                    <!-- <div class="col-lg-6 text-center text-lg-end">
                        <button type="button" class="btn btn-primary px-3 me-1" style="background-color: #3b5998;">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button type="button" class="btn btn-primary px-3 me-1" style="background-color: #55acee;">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button type="button" class="btn btn-primary px-3 me-1" style="background-color: #0082ca;">
                            <i class="fab fa-linkedin"></i>
                        </button>
                    </div> -->
                </div>
            </section>
            <!--Section: Post data-mdb-->

            <section class="border-bottom mb-4">
                <?php if (isset($user) && ($user->getId() == $post->getAuthorId() || $user->getRole() == 'admin')) : ?>
                    <div class="d-flex justify-content-between mb-4">
                        <div class="">
                            <div class="d-flex align-items-center">
                                <form action="forms/reactions/handleCreateReaction.php">
                                    <button class="me-3 mb-0 btn btn-link">
                                        <i class="fa-regular fa-thumbs-up me-2"></i>Like
                                    </button>
                                </form>
                                <button class="mb-0 btn btn-link">
                                    <i class="fa-regular fa-comment me-2"></i>Comment
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <form method="post" action="editPost.php" class="me-2">
                                <input name="id" value="<?= $post->getId() ?>" type="hidden" />
                                <textarea name="title" class="d-none" id="text"><?= $post->getTitle() ?></textarea>
                                <textarea name="content" class="d-none" id="content"><?= $post->getContent() ?></textarea>
                                <button class="btn btn-primary px-4">Edit</button>
                            </form>
                            <form action="forms/posts/handleDeletePost.php" method="post">
                                <input name="id" value="<?= $postId ?>" type="hidden" />
                                <button class=" btn btn-danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <!--Section: Text-->
            <section class="border-bottom mb-3">
                <p>
                    <?= $post->getContent() ?>
                </p>
            </section>



            <!--Section: Text-->

            <!--Section: Comments-->
            <section class="border-bottom mb-3">
                <p class="text-center"><strong>Comments: <?= count($comments); ?></strong></p>
                <?php foreach ($comments ?? [] as $id => $comment) : ?>
                    <!-- Comment -->
                    <div class="d-flex mb-4">
                        <div class="me-4">
                            <img src=".<?= $comment->avatar ?>" class="img-fluid shadow-1-strong rounded-5" alt="" style="width: 80px;" />
                        </div>

                        <div class="">
                            <p class="mb-2"><strong><?= $comment->username ?></strong></p>
                            <p>
                                <?= $comment->content ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
            <!--Section: Comments-->


            <?php if ($user) : ?>
                <!--Section: Reply-->
                <section>
                    <p class="text-center"><strong>Leave a comment</strong></p>

                    <form action="forms/comments/handleCreateComment.php">
                        <input type="hidden" name="postId" value="<?= $postId ?>">
                        <div class="form-outline mb-4">
                            <textarea class="form-control" id="form4Example3" rows="4" name="content" required></textarea>
                            <label class="form-label" for="form4Example3">Text</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mb-4">
                            Comment
                        </button>
                    </form>
                </section>
                <!--Section: Reply-->
            <?php endif; ?>
        </div>
    </main>

    <!-- MDB -->
    <script type="text/javascript" src="assets/mdb5/js/mdb.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>