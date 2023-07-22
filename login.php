<?php

require_once __DIR__ . '/controllers/auth.php';

if (!isset($_SESSION))
	session_start();
Auth::preventAuth();
if (isset($_SESSION["errors"]))
	$errors = $_SESSION["errors"];
else
	$errors = [];
unset($_SESSION['errors']);
$pageTitle="Login Page"
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<?php include "./components/head.php" ?>
	<style>
		.divider:after,
		.divider:before {
			content: "";
			flex: 1;
			height: 1px;
			background: #eee;
		}

		.h-custom {
			height: calc(100% - 74px);
		}

		@media (max-width: 450px) {
			.h-custom {
				height: 100%;
			}
		}
	</style>
</head>

<body>
	<section class="vh-100">
		<div class="container-fluid h-custom">
			<div class="row d-flex justify-content-center align-items-center h-100">
				<div class="col-md-9 col-lg-6 col-xl-5">
					<img src="assets/imgs/banner.webp" class="img-fluid" alt="Sample image">
				</div>
				<div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
					<h1 class="pb-4">Login</h1>
					<form action="forms/auth/handleLogin.php" method="post">
						<!-- Email input -->
						<div class="form-group mb-4">
							<label class="form-label" for="emailField">Email address</label>
							<input name="email" type="email" id="emailField" class="form-control form-control-lg" placeholder="Enter email address" />
							<?php
							if (isset($errors["email"])) {
							?>
								<small class="text-danger" for="emailField"><?= $errors["email"] ?></small>
							<?php
							}
							?>

						</div>

						<!-- Password input -->
						<div class="form-group mb-3">
							<label class="form-label" for="passwordField">Password</label>
							<input name="password" id="passwordField" type="password" class="form-control form-control-lg" placeholder="Enter password" />
							<?php
							if (isset($errors["password"])) {
							?>
								<small class="text-danger" for="passwordField"><?= $errors["password"] ?></small>
							<?php
							}
							?>
						</div>
						<?php
						if (isset($errors["email_password"])) {
						?>
							<small class="text-danger"><?= $errors["email_password"] ?></small>
						<?php
						}
						?>
						<!-- <small class="small text-danger" for="passwordField">Error not password</label> -->

						<div class="text-center text-lg-start mt-4 pt-2">
							<input type="submit" value="Login" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;" />
							<p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="signup.php" class="link-primary">Register</a></p>
						</div>

					</form>
				</div>
			</div>
		</div>
		<div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5 bg-primary">
			<!-- Copyright -->
			<div class="text-white mb-3 mb-md-0">
				Copyright © 2023. All rights reserved.
			</div>
			<!-- Copyright -->

			<!-- Right -->
			<div>
				<a href="#!" class="text-white me-4">
					<i class="fab fa-facebook-f"></i>
				</a>
				<a href="#!" class="text-white me-4">
					<i class="fab fa-twitter"></i>
				</a>
				<a href="#!" class="text-white me-4">
					<i class="fab fa-google"></i>
				</a>
				<a href="#!" class="text-white">
					<i class="fab fa-linkedin-in"></i>
				</a>
			</div>
			<!-- Right -->
		</div>
	</section>
	<!-- MDB -->
    <script type="text/javascript" src="assets/mdb5/js/mdb.min.js"></script>
</body>

</html>