<div class="card mb-3">
    <div class="card-body">
        <div class="mb-4 d-flex">
            <div class="me-4">
                <img src=".<?= $comment->avatar ?>" class="img-fluid shadow-1-strong rounded-circle" style="width: 50px;" alt="<?= $comment->username ?>" />
            </div>
            <div class="">
                <p class="mb-2"><strong><?= $comment->username ?></strong></p>
                <p class="card-text"><?= $comment->content ?></p>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <?php if (!empty($comment->replies)) : ?>
            <h3 class="mb-4">Replies</h3>
            <?php foreach ($comment->replies ?? [] as $reply) : ?>
                <div class="mb-4 d-flex">
                    <div class="me-4">
                        <img src=".<?= $reply->avatar ?>" class="img-fluid shadow-1-strong rounded-circle" style="width: 50px;" alt="<?= $reply->username ?>" />
                    </div>
                    <div class="">
                        <p class="mb-2"><strong><?= $reply->username ?></strong></p>
                        <p class="card-text"><?= $reply->content ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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
    </div>
</div>