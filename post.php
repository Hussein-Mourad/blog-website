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
if ($user) {
    $reactions = Reaction::getAllPostReactions($id);
    $userReaction = Reaction::getUserPostReaction($id);
    $reactionEmojis = [
        "like" => "&#128077;",
        "haha" => "&#128514;",
        "love" => "&#10084;&#65039;",
        "sad" => "&#128549",
        "angry" => "&#128544"
    ];
}
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

        html {
            scroll-behavior: smooth;
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
                </div>
            </section>
            <!--Section: Post data-mdb-->

            <?php if ($user) : ?>
                <section class="border-bottom mb-5">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex align-items-center">

                            <div class="me-3">
                                <?php if (empty($userReaction)) : ?>
                                    <div class="dropdown">
                                        <form action="forms/reactions/handleCreateReaction.php" method="post">
                                            <input name="postId" value="<?= $post->getId() ?>" type="hidden" />
                                            <input type="hidden" name="type" value="like" />
                                            <button class="btn btn-link text-muted">
                                                <i class="fa-xl fa-regular fa-thumbs-up me-2"></i><strong>Like</strong>
                                            </button>
                                        </form>
                                        <div class="dropdown-menu">
                                            <div class="btn-group">
                                                <?php foreach ($reactionTypes as $key => $reaction) : ?>
                                                    <form action="forms/reactions/handleCreateReaction.php" method="post">
                                                        <input name="postId" value="<?= $post->getId() ?>" type="hidden" />
                                                        <input type="hidden" name="type" value="<?= $reaction ?>" />
                                                        <button class="btn btn-link text-muted d-flex px-3"><i class="me-2"><?= $reactionEmojis[$reaction] ?></i><?= $reaction ?></button>
                                                    </form>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <form action="forms/reactions/handleDeleteReaction.php" method="post">
                                        <input name="id" value="<?= $userReaction->getId() ?>" type="hidden" />
                                        <input name="postId" value="<?= $post->getId() ?>" type="hidden" />
                                        <button class="btn btn-link">
                                            <?php if ($userReaction->getType() == 'like') : ?>
                                                <i class="fa-xl fa-solid fa-thumbs-up me-2"></i>
                                            <?php else : ?>
                                                <i class="me-2"><?= $reactionEmojis[$userReaction->getType()] ?></i>
                                            <?php endif; ?>
                                            <strong><?= $userReaction->getType() ?></strong>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <div>
                                <form>
                                    <a href="#leaveComment" class="btn btn-link text-muted ps-0">
                                        <i class="fa-xl fa-regular fa-comment me-2"></i><strong>Comment</strong>
                                    </a>
                                </form>
                            </div>
                        </div>
                        <?php if (isset($user) && ($user->getId() == $post->getAuthorId() || $user->getRole() == 'admin')) : ?>
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
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

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
                    <div class="d-flex flex-start mb-5">
                        <img class="rounded-circle shadow-1-strong me-3" src=".<?= $comment->avatar ?>" alt="avatar" width="65" height="65" />
                        <div class="flex-grow-1 flex-shrink-1">
                            <div class="">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">
                                        <?= $comment->username ?> <span class="small">- <?= timeAgo(strtotime($comment->updatedAt)) ?></span>
                                    </p>
                                    <a href="#!"><i class="fas fa-reply fa-xs"></i><span class="small"> reply</span></a>
                                </div>
                                <p class="small mb-0">
                                    <?= $comment->content ?>
                                </p>
                            </div>
                            <?php if (!empty($comment->replies)) : ?>
                                <?php foreach ($comment->replies ?? [] as $reply) : ?>
                                    <div class="d-flex flex-start mt-5">
                                        <div class="me-3">
                                            <img class="rounded-circle shadow-1-strong" src=".<?= $reply->avatar ?>" alt="avatar" width="65" height="65" />
                                        </div>
                                        <div class="flex-grow-1 flex-shrink-1">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-1">
                                                        <?= $comment->username ?> <span class="small">- <?= timeAgo(strtotime($comment->updatedAt)) ?></span>
                                                    </p>
                                                </div>
                                                <p class="small mb-0">
                                                    <?= $comment->content ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (isset($user)) : ?>
                        <div class="mt-4 mb-1">
                            <form action="./forms/comments/handleCreateComment.php" method='post'>
                                <div class="mb-3">
                                    <label for="reply" class="form-label">Leave a Reply</label>
                                    <textarea class="form-control" name="content" id="reply" rows="3" placeholder="Enter your reply" required></textarea>
                                </div>
                                <input type="hidden" name="postId" value="<?= $postId ?>">
                                <input type="hidden" name="parentCommentId" value="<?= $comment->getId() ?>">
                                <button type="submit" class="btn btn-primary">Reply</button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Comment -->
                    <div class="mb-4">
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


                    </div>
                <?php endforeach; ?>
            </section>
            <!--Section: Comments-->




            <?php if ($user) : ?>
                <!--Section: Reply-->
                <section>
                    <p class="text-center"><strong>Leave a comment</strong></p>

                    <form action="forms/comments/handleCreateComment.php" method="post" id="leaveComment">
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