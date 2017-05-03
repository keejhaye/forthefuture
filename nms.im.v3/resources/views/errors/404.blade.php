<!DOCTYPE html>
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
	<!--<![endif]-->
	<!-- start: HEAD -->
<head>
	<title>Page Not Found</title>
	<!-- start: META -->
	<meta charset="utf-8" />
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta content="" name="description" />
	<meta content="" name="author" />

	<!-- PLUGIN CSS -->
	<link rel="stylesheet" href="<?= secure_asset('css/bootstrap.min.css') ?>">

	<!-- CORE CSS -->
	<link rel="stylesheet" href="<?= secure_asset('css/styles.css') ?>">
	<link rel="stylesheet" href="<?= secure_asset('css/sticky-footer.css') ?>">

	<link rel="shortcut icon" href="<?= secure_asset('favicon.ico') ?>" />
</head>

	<body class="platform-name">
	<div class="_404page"></div>
	<div class="clearfix"></div>
	<div class="wrapper-page">
		<div class="ex-page-content text-center">
			<div class="text-error">404</div>
			<h3 class="text-uppercase font-600">Page not Found</h3>
			<p class="text-muted">
					It's looking like you may have taken a wrong turn. Don't worry... it happens to
					the best of us. You might want to check your internet connection. Here's a little tip that might
					help you get back on track.
			</p>
			<br>
			<a class="btn btn-success waves-effect waves-light" href="<?= secure_asset('/') ?>"> Return Home</a>
		</div>
	</div>
	</body>
</html>