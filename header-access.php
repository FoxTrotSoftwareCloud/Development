<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    	<title><?php echo $title; ?></title>
        <!-- <link rel="shortcut icon" type="image/x-icon" href="<?php //echo SITE_IMAGES.SITE_FAVICON; ?>" /> -->
    	<!--STYLESHEET-->
    	<!--Bootstrap Stylesheet [ REQUIRED ]-->
    	<link href="<?php echo SITE_CSS; ?>bootstrap.min.css" rel="stylesheet" />
    	<!--Nifty Stylesheet [ REQUIRED ]-->
    	<link href="<?php echo SITE_CSS; ?>nifty.min.css" rel="stylesheet" />
    	<!--Nifty Premium Icon [ DEMONSTRATION ]-->
    	<link href="<?php echo SITE_CSS; ?>demo/nifty-demo-icons.min.css" rel="stylesheet" />
    	<!--Demo [ DEMONSTRATION ]-->
    	<link href="<?php echo SITE_CSS; ?>demo/nifty-demo.min.css" rel="stylesheet" />
        <!--Themify Icons [ OPTIONAL ]-->
        <link href="<?php echo SITE_PLUGINS; ?>themify-icons/themify-icons.min.css" rel="stylesheet" />
        <link href="<?php echo SITE_PLUGINS; ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    	<!--JAVASCRIPT-->
    	<!--Pace - Page Load Progress Par [OPTIONAL]-->
    	<link href="<?php echo SITE_PLUGINS; ?>pace/pace.min.css" rel="stylesheet" />
    	<script src="<?php echo SITE_PLUGINS; ?>pace/pace.min.js"></script>
    	<!--jQuery [ REQUIRED ]-->
    	<script src="<?php echo SITE_JS; ?>jquery.min.js"></script>
    	<!--BootstrapJS [ RECOMMENDED ]-->
    	<script src="<?php echo SITE_JS; ?>bootstrap.min.js"></script>
    	<!--NiftyJS [ RECOMMENDED ]-->
    	<script src="<?php echo SITE_JS; ?>nifty.min.js"></script>
        <script src="<?php echo SITE_JS; ?>validator.js"></script>
        <script src="<?php echo SITE_JS; ?>script.js"></script>
        <link href="<?php echo SITE_CSS; ?>theme-dust.min.css" rel="stylesheet" />
        <link href="<?php echo SITE_CSS; ?>style.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            body{
                background-image: url('assets/images/login-bg.jpg');
                background-size: cover;
            }
			.cls-content .cls-content-sm, .cls-content .cls-content-lg{
                background-color: rgba(255, 255, 255, 0.60);
			}
        </style>

    </head>
    <body>
        <div class="overlay">
            <div class="overlay-content">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
        </div>
    	<div id="container" class="">
        	<!-- BACKGROUND IMAGE -->
    		<div id="bg-overlay"></div>