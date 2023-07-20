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
                            <form action="forms/posts/handleDeletePost.php" method="post">
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
                <div class="thumbnail">
                    <img src=".<?= $thumbnail ?>" class="card-img-top" alt="Thumbnail" />
                </div>

                <p class="card-text mt-3">
                    <?= $content ?>
                </p>
            </div>
            <div class="card-footer mt-2">
                <div class="d-flex align-items-center">
                    <button class="me-3 mb-0 btn btn-link">
                        <i class="fa-regular fa-thumbs-up"></i> Like
                    </button>
                    <button class="mb-0 btn btn-link">
                        <i class="fa-regular fa-comment"></i> Comment
                    </button>
                </div>
            </div>
        </div>