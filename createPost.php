<?php

require_once __DIR__ . '/controllers/auth.php';
require_once __DIR__ . '/controllers/categories.php';
require_once __DIR__ . '/utils.php';

$user = Auth::isAuth();
$categories = Category::getAllCategories();
if (isset($_SESSION['upload_result']['error']))
    $upload_error = $_SESSION['upload_result']['error'];
else
    $upload_error = false;
if (isset($_SESSION['errors']))
    $errors = $_SESSION['errors'];
if (isset($_SESSION['post-success-msg']))
    $success_msg = $_SESSION['post-success-msg'];
else
    $success_msg = null;
unset($_SESSION['upload_result']);
unset($_SESSION['errors']);
unset($_SESSION['post-success-msg']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "components/head.php" ?>
    <script>
        function removeAlert() {
            let alert = document.querySelector("#alertSuccess");
            alert.remove()
        }
    </script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">

        <!-- Container wrapper -->
        <div class="container">
            <!-- Navbar brand -->
            <a class="navbar-brand" href="index.php">Blogs</a>

            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarButtonsExample" aria-controls="navbarButtonsExample" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                    if ($user && $user->getRole() === 'admin') {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                    <?php
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="createPost.php">Add Post</a>
                    </li>
                </ul>
                <!-- Left links -->

                <div class="d-flex align-items-center">
                    <?php
                    if ($user) {
                    ?>
                        <a href="forms/auth/handleLogout.php">
                            <button type="button" class="btn btn-primary px-3 me-2">
                                Logout
                            </button>
                        </a>
                    <?php
                    } else {
                    ?>
                        <a href="login.php">
                            <button type="button" class="btn btn-link px-3 me-2">
                                Login
                            </button>
                        </a>
                        <a href="signup.php">
                            <button type="button" class="btn btn-primary me-3">
                                Signup
                            </button>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <section class="container p-5">
        <?php
        if ($success_msg) {
        ?>
            <div class="alert alert-success mb-4 alert-dismissible fade show" id="alertSuccess" role="alert" data-mdb-color="primary">
                <i class="fas fa-check me-2"></i>
                <?= $success_msg ?>
                <button type="button" class="btn-close ms-2" data-mdb-dismiss="alert" aria-label="Close" onclick="removeAlert()"></button>
            </div>
        <?php
        }
        ?>
        <div>
            <form action="forms/posts/handleCreatePost.php" method="post" enctype="multipart/form-data">
                <h2 class="mb-4">Add New Post</h2>
                <div class="form-group mb-3">
                    <label for="title">Title</label>
                    <input name="title" type="text" class="form-control" id="title" placeholder="Enter Post Title" required>
                    <?php
                    if (isset($errors["title"])) {
                    ?>
                        <small class="text-danger" for="title"><?= $errors["title"] ?></small>
                    <?php
                    }
                    ?>
                </div>
                <div class="form-group mb-3">
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
                <div class="form-group mb-3">
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
                <div class="form-group mb-4">
                    <label for="thumbnail" class="form-label">Upload Post Thumbnail</label>
                    <input name="thumbnail" type="file" class="form-control" id="thumbnail">
                    <?php
                    if ($upload_error) {
                    ?>
                        <small class="text-danger" for="thumbnail"><?= $upload_error ?></small>
                    <?php
                    }
                    ?>
                </div>
                <input name="submit" type="submit" class="btn btn-primary" value="Add Post" />
            </form>
        </div>
    </section>
    <!-- MDB -->
    <script type="text/javascript" src="assets/mdb5/js/mdb.min.js"></script>
</body>


</html>