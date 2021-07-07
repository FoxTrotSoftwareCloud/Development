<?php
	require_once("include/config.php");
	if(!isset($_SESSION['user_id']) || (isset($_SESSION['user_id']) && !$_SESSION['user_id']>0)){
		header("location:".SITE_URL."sign-in");exit;
	}
?>