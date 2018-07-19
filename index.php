<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    header("location:home.php");
?>
<!--<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Dashboard</title>
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_IMAGES.SITE_FAVICON; ?>" />
		<!--STYLESHEET-->
		<!--Bootstrap Stylesheet [ REQUIRED ]-->
		<!--<link href="<?php echo SITE_CSS; ?>bootstrap.min.css" rel="stylesheet" />
		<!--Nifty Stylesheet [ REQUIRED ]-->
		<!--</a><link href="<?php echo SITE_CSS; ?>nifty.min.css" rel="stylesheet" />
		<!--Nifty Premium Icon [ DEMONSTRATION ]-->
		<!--<link href="<?php echo SITE_CSS; ?>demo/nifty-demo-icons.min.css" rel="stylesheet" />
        <!--Themify Icons [ OPTIONAL ]-->
        <!--<link href="<?php echo SITE_PLUGINS; ?>themify-icons/themify-icons.min.css" rel="stylesheet" />
        <link href="<?php echo SITE_PLUGINS; ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<!--JAVASCRIPT-->
		<!--Pace - Page Load Progress Par [OPTIONAL]-->
		<!--<link href="<?php echo SITE_PLUGINS; ?>pace/pace.min.css" rel="stylesheet" />
		<script src="<?php echo SITE_PLUGINS; ?>pace/pace.min.js"></script>
		<!--jQuery [ REQUIRED ]-->
		<!--<script src="<?php echo SITE_JS; ?>jquery.min.js"></script>
		<!--BootstrapJS [ RECOMMENDED ]-->
		<!--<script src="<?php echo SITE_JS; ?>bootstrap.min.js"></script>
		<!--NiftyJS [ RECOMMENDED ]-->
		<!--<script src="<?php echo SITE_JS; ?>nifty.min.js"></script>
		<link href="<?php echo SITE_CSS; ?>theme-dust.min.css" rel="stylesheet" />
        <link href="<?php echo SITE_CSS; ?>style.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo SITE_JS; ?>validator.js"></script>
        <script src="<?php echo SITE_JS; ?>script.js"></script>
	</head>
	<body>
        <div class="overlay">
            <div class="overlay-content">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
        </div>
		<div id="container" class="cls-container">
			<!-- HEADER -->
			<!--<div class="cls-header">
				<div class="cls-brand">
                    <h1 style="margin: 0;">
    					<a class="box-inline" href="#">
    						Foxtrot
    					</a>
                    </h1>
				</div>
			</div>
			<!-- CONTENT -->
			<!--<div class="cls-content">
                <h2>Hello, <?php echo $current_user['first_name'].' '.$current_user['last_name']; ?></h2>
				<h1 class="error-code text-orange">Under Construction</h1>
				<div class="pad-btm">
					Sorry, but the page you are looking for is under construction.
				</div>
				<hr class="new-section-sm bord-no" />
				<div class="pad-top"><a class="btn btn-warning btn-lg bg-orange-active" href="<?php echo SITE_URL; ?>sign-out"><i class="ti-lock"></i> Logout</a></div>
			</div>
		</div>
		<!-- END OF CONTAINER -->
	<!--</body>
</html>-->