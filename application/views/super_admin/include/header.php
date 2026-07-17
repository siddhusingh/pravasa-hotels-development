<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<?php
	$CI = &get_instance();
	$CI->load->database();

	$branding = $CI->db
		->select('logo, login_bg_image, site_title,favicon')
		->from('software_settings')
		->limit(1)
		->get()
		->row();

	$logoimg = !empty($branding->logo)
		? base_url($branding->logo)
		: base_url('assets/defaul_logo.png');

	$favicon = !empty($branding->favicon)
		? base_url($branding->favicon)
		: base_url('assets/default-favicon.ico');

	?>

	<title><?php echo $branding->site_title ?> Super Admin - Dashboard</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?= $favicon ?>">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css">
	<!-- Vendors Style-->
	<link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/vendors_css.css">
	<script src="https://use.fontawesome.com/76ecd250b9.js"></script>

	<!-- Style-->
	<link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/skin_color.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/custom.css">
	<!-- <link href="../../../../../cdn.jquery.app/jqueryscripttop.css" rel="stylesheet" type="text/css">
	 -->
<link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/resposnive.css">
<link rel="stylesheet" href="<?php echo base_url('assets/') ?>/css/lead.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
	<style type="text/css">
		#loader {
			position: fixed;
			width: 100%;
			height: 100vh;
			z-index: 999999;
			overflow: visible;
			background: #fff url("https://www.icegif.com/wp-content/uploads/2023/07/icegif-1262.gif") no-repeat center center
		}

		.sidebar .fa {
			font-size: 16px
		}


		.select2-container {
			display: block !important;
		}
	</style>

	<script src="https://use.fontawesome.com/76ecd250b9.js"></script>
	<!-- ✅ jQuery - Use FULL version instead of slim for Flot -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- ✅ DataTables CSS & Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    
    <!-- ✅ DataTables Core -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <!-- ✅ Buttons Extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    
    
    <!-- ✅ Export dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    
	<script>
        window.CSRF = {
            name: "<?php echo $this->security->get_csrf_token_name();?>",
            hash: "<?php echo $this->security->get_csrf_hash();?>"
        };
    </script>

</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

	<div class="wrapper">
		<div class="d-none" id="loader"></div>

		<header class="main-header">
			<div class="d-flex align-items-center logo-box justify-content-start mobile_hide">
				<!-- Logo -->
				<a href="<?php echo base_url('super-admin-dashbaord') ?>" class="logo">
					<!-- logo-->
					<div class="logo-mini w-40">
						<span class="light-logo"><img src="<?= !empty($branding->logo)
																? base_url($branding->logo)
																: base_url('assets/images/default-logo.png') ?>" style="     height:50%; "></span>
						<span class="dark-logo"><img src="<?= !empty($branding->logo)
																? base_url($branding->logo)
																: base_url('assets/images/default-logo.png') ?>" style="     height:50%; "></span>
					</div>

					<div class="logo-lg">
						<span class="light-logo" style="height: 90px;"><img src="<?= !empty($branding->logo)
																						? base_url($branding->logo)
																						: base_url('assets/images/default-logo.png') ?>" style="     height:50%; "></span>
						<span class="dark-logo" style="height: 90px;"><img src="<?= !empty($branding->logo)
																					? base_url($branding->logo)
																					: base_url('assets/images/default-logo.png') ?>" style="     height:50%; "></span>
					</div>
				</a>
			</div>
			<!-- Header Navbar -->
			<nav class="navbar navbar-static-top">
				<!-- Sidebar toggle button-->
				<div class="app-menu">
					<ul class="header-megamenu nav">
						<li class="btn-group nav-item">
							<a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light" data-toggle="push-menu" role="button">
								<i data-feather="menu"></i>
							</a>
						</li>
						<li class="btn-group d-lg-inline-flex d-none">
							<div class="app-menu">

							</div>
						</li>
					</ul>
				</div>

				<div class="responsive_logo">
					<a href="<?php echo base_url('super-admin-dashbaord') ?>" class="logo">
					<!-- logo-->
					<div class="logo-mini w-40">
						<span class="light-logo"><img src="<?= !empty($branding->logo)
																? base_url($branding->logo)
																: base_url('assets/images/default-logo.png') ?>" style="     height:50%; "></span>
						<span class="dark-logo"><img src="<?= !empty($branding->logo)
																? base_url($branding->logo)
																: base_url('assets/images/default-logo.png') ?>" style="     height:50%; "></span>
					</div>

					<div class="logo-lg">
						<span class="light-logo" style="height: 90px;"><img src="<?= !empty($branding->logo)
																						? base_url($branding->logo)
																						: base_url('assets/images/default-logo.png') ?>" style="     height:50%; "></span>
						<span class="dark-logo" style="height: 90px;"><img src="<?= !empty($branding->logo)
																					? base_url($branding->logo)
																					: base_url('assets/images/default-logo.png') ?>" style="     height:50%; "></span>
					</div>
				</a>
				</div>

				<div class="navbar-custom-menu r-side">
					<ul class="nav navbar-nav">
						<li class="dropdown notifications-menu btn-group">
							<label class="switch">
								<a class="waves-effect waves-light btn-primary-light svg-bt-icon">
									<input type="checkbox" data-mainsidebarskin="toggle" id="toggle_left_sidebar_skin">
									<span class="switch-on"><i data-feather="moon"></i></span>
									<span class="switch-off"><i data-feather="sun"></i></span>
								</a>
							</label>
						</li>





						<li class="btn-group nav-item d-xl-inline-flex d-none">
							<a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link btn-primary-light svg-bt-icon" title="Full Screen">
								<i data-feather="maximize"></i>
							</a>
						</li>

						<!-- User Account-->
						<li class="dropdown user user-menu">
							<a href="#" class="waves-effect waves-light dropdown-toggle w-auto l-h-12 bg-transparent p-0 no-shadow" title="User" data-bs-toggle="modal" data-bs-target="#quick_user_toggle">
								<img src="<?php echo base_url('images/') ?>userIcon.png" class="avatar rounded-circle bg-primary-light h-40 w-40" alt="" />
							</a>
						</li>

					</ul>
				</div>
			</nav>
		</header>