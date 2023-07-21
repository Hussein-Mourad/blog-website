<?php
require_once __DIR__ . '/controllers/auth.php';
require_once __DIR__ . '/controllers/posts.php';
require_once __DIR__ . '/controllers/comments.php';
require_once __DIR__ . '/controllers/reactions.php';
require_once __DIR__ . "/utils.php";

$user = Auth::isAuth();
if (!isset($_GET["id"]))
    redirect("/index.php");
$id = $_GET['id'];
$post = Post::getPost($id);
if (!$post)
    redirect("/index.php");
$comments = Comment::getAllPostComments($id);
$reactions = Reaction::getAllPostReactions($id);
$userReaction = Reaction::getUserPostReaction($id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/head.php" ?>

    <style>
        .dropdown:hover>.dropdown-menu {
            display: block;
        }

        .dropdown>.dropdown-toggle:active {
            /*Without this, clicking will make it sticky*/
            pointer-events: none;
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
            <!--Section: Post data-mdb-->
            <section class="border-bottom mb-3">
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

            <section class="border-bottom mb-5">
                <?php if (isset($user) && ($user->getId() == $post->getAuthorId() || $user->getRole() == 'admin')) : ?>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <!-- Default dropup button -->
                            <div class="dropdown">
                                <button class="btn btn-link text-muted">
                                    <i class="fa-xl fa-regular fa-thumbs-up me-2"></i><strong>Like</strong>
                                </button>
                                <div class="dropdown-menu">
                                    <div class="btn-group">
                                        <?php foreach ($reactionTypes as $key => $reaction) : ?>
                                            <button class="btn btn-link text-muted"><?= $reaction ?></button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="me-3">
                                <?php if (empty($userReaction)) : ?>
                                    <form action="forms/reactions/handleCreateReaction.php" method="post">
                                        <input name="postId" value="<?= $post->getId() ?>" type="hidden" />
                                        <input type="hidden" name="type" value="like" />
                                        <button class="btn btn-link text-muted">
                                            <i class="fa-xl fa-regular fa-thumbs-up me-2"></i><strong>Like</strong>
                                        </button>
                                    </form>
                                <?php else : ?>
                                    <form action="forms/reactions/handleDeleteReaction.php" method="post">
                                        <input name="id" value="<?= $userReaction->getId() ?>" type="hidden" />
                                        <input name="postId" value="<?= $post->getId() ?>" type="hidden" />
                                        <button class="btn btn-link">
                                            <i class="fa-xl fa-solid fa-thumbs-up me-2"></i><strong>Like</strong>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <div>
                                <form>
                                    <button class="btn btn-link text-muted ps-0">
                                        <i class="fa-xl fa-regular fa-comment me-2"></i><strong>Comment</strong>
                                    </button>
                                </form>
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
                                <input name="id" value="<?= $post->getId() ?>" type="hidden" />
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

                    <form action="forms/comments/handleCreateComment.php" method="post">
                        <input name="postId" value="<?= $post->getId() ?>" type="hidden" />
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
</body>

</html>