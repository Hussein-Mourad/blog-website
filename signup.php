<?php

require_once __DIR__ . '/controllers/auth.php';
$pageTilte = "Sigup";
if (!isset($_SESSION))
	session_start();
Auth::preventAuth();
if (isset($_SESSION["errors"]))
	$errors = $_SESSION["errors"];
else
	$errors = [];
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include "components/head.php" ?>
	<style>
		.divider:after,
		.divider:before {
			content: "";
			flex: 1;
			height: 1px;
			background: #eee;
		}

		.h-custom {
			height: calc(100% - 72px);
		}


		@media (max-width: 1280px) {
			.h-custom {
				height: 100%;
			}

			.banner {
				width: 75%;
				height: 75%;
			}
		}
	</style>
</head>

<body>
	<section class="vh-100">
		<div class="container-fluid h-custom">
			<div class="row d-flex justify-content-center align-items-center h-100">
				<div class="col-md-9 col-lg-6 col-xl-5 d-flex justify-content-center align-items-center">
					<img src="assets/imgs/banner.webp" class="img-fluid banner" alt="Sample image">
				</div>
				<div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
					<h1 class="pb-4">Signup</h1>
					<form action="forms/auth/handleSignup.php" method="post">
						<div class="row">
							<div class="col-md-6 mb-4">
								<div class="form-group">
									<label class="form-label" for="firstNameField">First name</label>
									<input name="firstName" type="text" id="firstNameField" class="form-control" placeholder="Enter first name" />
									<?php
									if (isset($errors["firstName"])) {
									?>
										<small class="text-danger" for="firstNameField"><?= $errors["firstName"] ?></small>
									<?php
									}
									?>
								</div>
							</div>
							<div class="col-md-6 mb-4">
								<div class="form-group">
									<label class="form-label" for="lastNameField">Last name</label>
									<input name="lastName" type="text" id="lastNameField" class="form-control" placeholder="Enter last name" />
									<?php
									if (isset($errors["lastName"])) {
									?>
										<small class="text-danger" for="lastNameField"><?= $errors["lastName"] ?></small>
									<?php
									}
									?>
								</div>
							</div>
						</div>
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

						<div class="form-group mb-4">
							<label class="form-label" for="phoneField">Phone</label>
							<input name="phone" type="text" id="phoneField" class="form-control form-control-lg" placeholder="Enter phone number" />
							<?php
							if (isset($errors["phone"])) {
							?>
								<small class="text-danger" for="phoneField"><?= $errors["phone"] ?></small>
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
						<!-- <small class="small text-danger" for="passwordField">Error not password</label> -->

						<div class="text-center text-lg-start mt-4 pt-2">
							<input type="submit" value="Signup" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;" />
							<p class="small fw-bold mt-2 pt-1 mb-0">Already have an account? <a href="login.php" class="link-primary">Login</a></p>
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
</body>

</html>