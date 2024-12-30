<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- ===============================================-->
	<!--    Document Title-->
	<!-- ===============================================-->
	<title>PT. Adijaya Karya Makmur</title>
	<!-- ===============================================-->
	<!--    Favicons-->
	<!-- ===============================================-->
	<link rel="apple-touch-icon" sizes="180x180" href="<?=BASEURL?>assets/img/Logo/logo.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?=BASEURL?>assets/img/Logo/logo.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?=BASEURL?>assets/img/Logo/logo.png">
	<link rel="shortcut icon" type="image/png" href="<?=BASEURL?>assets/img/Logo/logo.png">
	<meta name="msapplication-TileImage" content="<?=BASEURL?>assets/img/favicons/mstile-150x150.png">
	<meta name="theme-color" content="#ffffff">
	<script type="text/javascript" src="<?=BASEURL?>assets/js/jquery-3.7.0.js"></script>
	<script src="<?=BASEURL?>assets/vendors/simplebar/simplebar.min.js"></script>
	<script src="<?=BASEURL?>assets/js/config.js"></script>
	<!-- ===============================================-->
	<!--    Stylesheets-->
	<!-- ===============================================-->
	<link href="<?=BASEURL?>assets/vendors/simplebar/simplebar.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
	<link href="<?=BASEURL?>assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
	<link href="<?=BASEURL?>assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
	<link href="<?=BASEURL?>assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
	<link href="<?=BASEURL?>assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
	<link rel="stylesheet" type="text/css" href="<?=BASEURL?>assets/css/fontawesome.6.5.1.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<link rel="stylesheet" type="text/css" href="<?=BASEURL?>assets/css/loading.css">
	<script>
		var BASEURL = "<?=BASEURL?>";
		var phoenixIsRTL = window.config.config.phoenixIsRTL;
		if (phoenixIsRTL) {
			var linkDefault = document.getElementById('style-default');
			var userLinkDefault = document.getElementById('user-style-default');
			linkDefault.setAttribute('disabled', true);
			userLinkDefault.setAttribute('disabled', true);
			document.querySelector('html').setAttribute('dir', 'rtl');
		} else {
			var linkRTL = document.getElementById('style-rtl');
			var userLinkRTL = document.getElementById('user-style-rtl');
			linkRTL.setAttribute('disabled', true);
			userLinkRTL.setAttribute('disabled', true);
		}
	</script>
</head>

<body>
	<!-- ===============================================-->
	<!--    Main Content-->
	<!-- ===============================================-->
	<div class="container_loading">
		<div class="loading">
	        <div class="loading__square"></div>
	        <div class="loading__square"></div>
	        <div class="loading__square"></div>
	        <div class="loading__square"></div>
	        <div class="loading__square"></div>
	        <div class="loading__square"></div>
	        <div class="loading__square"></div>
	    </div>
	</div>
	<main class="main" id="top">
		<div class="container">
			<form id="loginForm">
				<div class="row flex-center min-vh-100 py-5">
					<div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3">
						<a class="d-flex flex-center text-decoration-none mb-1" href="<?=BASEURL?>">
							<div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
								<img src="<?=BASEURL?>assets/img/Logo/logo.png" alt="AKM" width="110" />
							</div>
						</a>
						<div class="text-center mb-1">
							<h3 class="text-body-highlight">LOGIN</h3>
						</div>
						<div class="position-relative">
							<hr class="bg-body-secondary mt-5 mb-4" />
							<div class="divider-content-center">Get access to your account</div>
						</div>
						<div class="mb-3 text-start">
							<label class="form-label" for="email">Username</label>
							<div class="form-icon-container">
								<input name="username" class="form-control form-icon-input" id="email" type="text" placeholder="Masukan username anda" />
								<span class="fas fa-user text-body fs-9 form-icon"></span>
							</div>
						</div>
						<div class="mb-3 text-start">
							<label class="form-label" for="password">Password</label>
							<div class="form-icon-container" data-password="data-password">
								<input name="password" class="form-control form-icon-input pe-6" id="password" type="password" placeholder="Password" data-password-input="data-password-input" />
								<span class="fas fa-key text-body fs-9 form-icon"></span>
								<button class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary" data-password-toggle="data-password-toggle">
									<span class="uil uil-eye show"></span>
									<span class="uil uil-eye-slash hide"></span>
								</button>
							</div>
						</div>
						<div class="row flex-between-center mb-7">
							<div class="col-auto">
								<div class="form-check mb-0">
									<input class="form-check-input" id="basic-checkbox" type="checkbox" checked="checked" />
									<label class="form-check-label mb-0" for="basic-checkbox">Remember me</label>
								</div>
							</div>
							<div class="col-auto">
								<a class="fs-9 fw-semibold" href="#">Forgot Password?</a>
							</div>
						</div>
						<button class="btn btn-primary w-100 mb-3">Sign In</button>
					</div>
				</div>
			</form>
		</div>
	</main>
	<!-- ===============================================-->
	<!--    JavaScripts-->
	<!-- ===============================================-->
	<script type="text/javascript" type="module" src="<?=BASEURL?>assets/js/login.js"></script>
	<script src="<?=BASEURL?>assets/vendors/popper/popper.min.js"></script>
	<script src="<?=BASEURL?>assets/vendors/bootstrap/bootstrap.min.js"></script>
	<script src="<?=BASEURL?>assets/vendors/anchorjs/anchor.min.js"></script>
	<script src="<?=BASEURL?>assets/vendors/is/is.min.js"></script>
	<script src="<?=BASEURL?>assets/vendors/fontawesome/all.min.js"></script>
	<script src="<?=BASEURL?>assets/vendors/lodash/lodash.min.js"></script>
	<script src="<?=BASEURL?>assets/vendors/list.js/list.min.js"></script>
	<script src="<?=BASEURL?>assets/vendors/feather-icons/feather.min.js"></script>
	<script src="<?=BASEURL?>assets/vendors/dayjs/dayjs.min.js"></script>
	<script src="<?=BASEURL?>assets/js/phoenix.js"></script>
</body>

</html>