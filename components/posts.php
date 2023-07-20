<?php
$last_category = 0;
foreach ($posts ?? [] as $id => $post) :
    if ($last_category != $post->categoryId) :
        $last_category = $post->categoryId;
?>
        <h1 class="mt-5"><?= $post->category ?></h1>
        <hr class="mb-4" />
    <?php endif; ?>

    <a href="post.php?id=<?= $id ?>">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src=".<?= $post->thumbnail ?>" alt="thumbnail" class="img-fluid rounded-start" />
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?= $post->title ?></h5>
                        <p class="card-text">
                            <?= truncateText($post->content, 200); ?>
                        </p>
                        <p class="card-text">
                            <small class="text-muted">By: <?= $post->author ?></small>
                        </p>
                        <p class="card-text">
                            <small class="text-muted">Last updated <?= timeAgo(strtotime($post->updatedAt)); ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </a>
<?php endforeach; ?>