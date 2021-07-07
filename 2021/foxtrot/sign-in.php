<?php
    require_once("include/config.php");
    if(isset($_SESSION['user_id'])&&$_SESSION['user_id']>0){
        header("location:".SITE_URL);exit;
    }
    $title = 'Sign In';
    $username = '';
    if(isset($_POST['submit']) && $_POST['submit']=='submit'){
        $instance = new user_master();
        $username = isset($_POST['username'])&&$_POST['username']!=''?$instance->re_db_input($_POST['username']):'';
		$return = $instance->login($_POST);
        if($return!==true){
            $error = $return;
        }
        else{
			header('location:'.SITE_URL.'home.php');exit;
        }
	}
    $content = "sign-in";
    include(DIR_WS_TEMPLATES."access_page.tpl.php");
?>