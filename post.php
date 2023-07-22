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
$post = Post::get($id);
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
date_default_timezone_set("Asia/Riyadh");
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
        <div id="intro" class="mt-5 p-5 text-center bg-light">
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
                <?php if (!empty($reactions)) : ?>
                    <section class="">
                        <div class="d-flex">
                            <?php $viewedReactionTypes = [];
                            foreach ($reactions as $reaction) : ?>
                                <div class="me-1">
                                    <?php
                                    $type = $reactionEmojis[$reaction->getType()];
                                    if (!in_array($type, $viewedReactionTypes))
                                        echo $reactionEmojis[$reaction->getType()];
                                    $viewedReactionTypes[] = $type;
                                    ?>
                                </div>
                            <?php endforeach; ?>
                            <div class="me-1"><?= count($reactions); ?></div>
                        </div>
                    </section>
                <?php endif; ?>
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
                                    <button class=" btn btn-link btn-floating text-muted" type="submit"><i class="fas fa-xl fa-pen"></i></button>
                                </form>
                                <form action="forms/posts/handleDeletePost.php" method="post">
                                    <input name="id" value="<?= $post->getId() ?>" type="hidden" />
                                    <button class=" btn btn-link btn-floating text-muted" type="submit"><i class="fas fa-xl fa-trash-can"></i></button>
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
            <?php if (!empty($comments)) : ?>
                <section class="border-bottom mb-3">
                    <p class="text-center"><strong>Comments: <?= count($comments); ?></strong></p>
                    <?php foreach ($comments ?? [] as $id => $comment) : ?>
                        <div class="d-flex flex-start mb-3">
                            <img class="rounded-circle shadow-1-strong me-3" src=".<?= $comment->avatar ?>" alt="avatar" width="65" height="65" />
                            <div class="flex-grow-1 flex-shrink-1">
                                <div class="">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1">
                                            <?= $comment->username ?> <span class="small ms-1"><?= timeAgo(strtotime($comment->updatedAt)) ?></span>
                                        </p>
                                        <div class="d-flex">
                                            <?php if (isset($user)) : ?>
                                                <button class="btn btn-link" onclick="showReplyForm(<?= 'replyForm' . $comment->getId() ?>)"><i class="fas fa-reply fa-xs me-2"></i><span class="small"> reply</span></button>
                                            <?php endif; ?>
                                            <?php if (isset($user) && ($user->getId() == $post->getAuthorId() || $user->getRole() == 'admin')) : ?>
                                                <form action="forms/comments/handleDeleteComment.php" method="post">
                                                    <input name="id" value="<?= $comment->getId() ?>" type="hidden" />
                                                    <input type="hidden" name="postId" value="<?= $post->getId() ?>">
                                                    <button class=" btn btn-link btn-floating text-muted" type="submit"><i class="fas fa-xl fa-trash-can"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="small mb-0">
                                        <?= $comment->content ?>
                                    </p>
                                </div>
                                <?php if (!empty($comment->replies)) : ?>
                                    <?php foreach ($comment->replies ?? [] as $reply) : ?>
                                        <div class="d-flex flex-start mt-4">
                                            <div class="me-3">
                                                <img class="rounded-circle shadow-1-strong" src=".<?= $reply->avatar ?>" alt="avatar" width="65" height="65" />
                                            </div>
                                            <div class="flex-grow-1 flex-shrink-1">
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <p class="mb-1">
                                                            <?= $reply->username ?> <span class="small ms-1"> <?= timeAgo(strtotime($reply->updatedAt)) ?></span>
                                                        </p>
                                                        <?php if (isset($user) && ($user->getId() == $post->getAuthorId() || $user->getRole() == 'admin')) : ?>
                                                            <form action="forms/comments/handleDeleteComment.php" method="post">
                                                                <input name="id" value="<?= $reply->getId() ?>" type="hidden" />
                                                                <input type="hidden" name="postId" value="<?= $post->getId() ?>">
                                                                <button class=" btn btn-link btn-floating text-muted" type="submit"><i class="fas fa-xl fa-trash-can"></i></button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                    <p class="small mb-0">
                                                        <?= $reply->content ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (isset($user)) : ?>
                            <div class="mt-4 mb-5">
                                <form action="./forms/comments/handleCreateComment.php" method='post' id="<?= "replyForm" . $comment->getId() ?>" style="display:none;">
                                    <div class="form-outline mb-3">
                                        <textarea class="form-control" name="content" id="reply" rows="2" placeholder="Enter your reply" required></textarea>
                                        <label for="reply" class="form-label">Leave a Reply</label>
                                    </div>
                                    <input type="hidden" name="postId" value="<?= $post->getId() ?>">
                                    <input type="hidden" name="parentCommentId" value="<?= $comment->getId() ?>">
                                    <button type="submit" class="btn btn-primary">Reply</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </section>
                <!--Section: Comments-->
            <?php endif; ?>
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
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>