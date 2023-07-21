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
        <!-- <form action="">
            <div class="mb-3">
                <label for="reply" class="form-label">Reply</label>
                <input type="hidden" name="parentId" value="">
                <textarea class="form-control" name="reply" id="reply" rows="3" placeholder="Enter your reply"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Reply</button>
        </form> -->
    </div>
    <?php if (isset($comment->replies)) : ?>
        <div class="card-footer">
            <h3 class="mb-3">Replies</h3>
            <?php foreach ($comment->replies ?? [] as $reply) : ?>
                <div class="mb-3">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img src=".<?= $reply->avatar ?>" class="rounded-circle" style="width: 50px;" alt="<?= $reply->username ?>" />
                        </div>
                        <div class="">
                            <h6 class="mb-0"><strong><?= $reply->username ?></strong></h6>
                        </div>
                    </div>
                    <hr>
                    <p class="card-text"><?= $reply->content ?></p>
                </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>