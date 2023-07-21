<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <img src=".<?= $comment->avatar ?>" class="rounded-circle" style="width: 50px;" alt="<?= $comment->username ?>" />
            </div>
            <div class="">
                <h6 class="mb-0"><strong><?= $comment->username ?></strong></h6>
            </div>
        </div>
        <p class="card-text"><?= $comment->content ?></p>

    </div>
    <div class="card-footer">
        <?php if (!empty($comment->replies)) : ?>
            <h3 class="mb-4">Replies</h3>
            <?php foreach ($comment->replies ?? [] as $reply) : ?>
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <img src=".<?= $reply->avatar ?>" class="rounded-circle" style="width: 50px;" alt="<?= $reply->username ?>" />
                        </div>
                        <div class="">
                            <h6 class="mb-0"><strong><?= $reply->username ?></strong></h6>
                        </div>
                    </div>
                    <p class="card-text"><?= $reply->content ?></p>
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