<?php if(!isset($_POST['key'])){header('Location: ../home.php'); exit();} ?>
<?php include_once( '../classes/translate.class.php' ); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>InstantWL - Installation</title>

		<meta name="description" content="InstantWL - Installation">
		<meta name="author" content="Matt Gates | Jigowatt">

		<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le styles -->
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="../assets/css/jigowatt.css" rel="stylesheet">

		<link rel="shortcut icon" href="//jigowatt.co.uk/favicon.ico">
	</head>

	<body>

<!-- Navigation
================================================== -->

	<div class="navbar navbar-fixed-top">
		<div class="navbar">
			<div class="navbar-inner">
				<div class="container">

				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<div class="nav-collapse">
					<ul class="nav">
					   <li><a href="#"><?php _e('InstantWL'); ?></a></li>
					</ul>
					<ul class="nav pull-right">
						
					</ul>
				</div>
				</div>
			</div><!-- /navbar-inner -->
		</div><!-- /navbar -->
	</div><!-- /navbar-wrapper -->

<!-- Main content
================================================== -->
		<div class="container">
			<div class="row">
				<div class="span12">
