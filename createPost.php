<?php
require_once "controllers/categories.php";
$categories = Category::getAllCategories();
if (isset($_SESSION['upload_result']['error']))
    $upload_error = $_SESSION['upload_result']['error'];
else
    $upload_error = false;
if (isset($_SESSION['errors']))
    $errors = $_SESSION['errors'];
unset($_SESSION['upload_result']);
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="assets/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container m-5 p-5">
        <form action="handleCreatePost.php" method="post" enctype="multipart/form-data">
            <h2 class="mb-3">Add New Post</h2>
            <div class="form-group">
                <label for="title">Title</label>
                <input name="title" type="text" class="form-control" id="title" placeholder="Enter Post Title" required>
                <?php
                if (isset($errors["title"])) {
                ?>
                    <small class="text-danger" for="title"><?= $errors["title"] ?></small>
                <?php
                }
                ?>
                <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" class="form-control" id="content" rows="3" placeholder="Enter Post Content" required></textarea>
                <?php
                if (isset($errors["content"])) {
                ?>
                    <small class="text-danger" for="content"><?= $errors["content"] ?></small>
                <?php
                }
                ?>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" class="form-control" id="category">
                    <?php
                    foreach ($categories as $key => $category) {
                        $id = $category[0];
                        $name = $category[1];
                    ?>
                        <option value="<?= $id ?>"><?= $name ?> </option>
                    <?php
                    }
                    ?>
                </select>
                <?php
                if (isset($errors["category"])) {
                ?>
                    <small class="text-danger" for="category"><?= $errors["category"] ?></small>
                <?php
                }
                ?>
            </div>
            <div class="form-group my-4">
                <label for="thumbnail">Upload Thumbnail</label>
                <input name="thumbnail" type="file" class="form-control-file" id="thumbnail">
                <?php
                if ($upload_error) {
                ?>
                    <small class="text-danger" for="thumbnail"><?= $upload_error ?></small>
                <?php
                }
                ?>
            </div>
            <input name="submit" type="submit" class="btn btn-primary mt-3" value="Add Post" />
        </form>
    </div>
</body>

</html>


