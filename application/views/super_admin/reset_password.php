<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Super Admin - Reset Password</title>
	<link rel="stylesheet" href="<?= base_url('assets/css/vendors_css.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/skin_color.css') ?>">
</head>
<body class="hold-transition dark-skin theme-primary bg-img" style="background-image: url(<?= base_url('images/auth-bg/bg-16.jpg') ?>)" data-overlay="5">
	<div class="container h-p100">
		<div class="row align-items-center justify-content-md-center h-p100">
			<div class="col-12">
				<div class="row justify-content-center g-0">
					<div class="col-lg-5 col-md-6 col-12">
						<div class="bg-gray-800 rounded10 shadow-lg">
							<div class="content-top-agile p-20 pb-0">
								<h2 class="mb-10 fw-600 text-primary">Reset Password</h2>
								<p class="mb-0 text-fade">Choose a new password for your Super Admin account.</p>
							</div>
							<div class="p-40">
								<?php if ($this->session->flashdata('error')): ?>
									<div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
								<?php endif; ?>
								<form action="<?= base_url('update-password-super-admin') ?>" method="post">
									<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
									<input type="hidden" name="token" value="<?= html_escape($token) ?>">
									<div class="form-group mb-3">
										<label for="password">New Password</label>
										<input type="password" id="password" name="password" class="form-control" minlength="6" required autocomplete="new-password">
									</div>
									<div class="form-group mb-3">
										<label for="confirm_password">Confirm Password</label>
										<input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="6" required autocomplete="new-password">
									</div>
									<button type="submit" class="btn btn-primary w-p100 mt-10">Update Password</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="<?= base_url('assets/js/vendors.min.js') ?>"></script>
</body>
</html>
