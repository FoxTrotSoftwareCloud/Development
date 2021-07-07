<?php
    require_once("include/config.php");
    if(isset($_SESSION['user_id'])&&$_SESSION['user_id']>0){
        header("location:".SITE_URL);exit;
    }
    $title = 'Reset Password';
    $email = '';
    if(isset($_POST['submit']) && $_POST['submit']=='submit'){
        $instance = new user_master();
        $email = isset($_POST['email'])&&$_POST['email']!=''?$instance->re_db_input($_POST['email']):'';
		$return = $instance->forgot_password($_POST);
        if($return!==true){
            $error = $return;
        }
        else{
			header('location:'.SITE_URL.'sign-in');exit;
        }
	}
    $content = "forgot-password";
    include(DIR_WS_TEMPLATES."access_page.tpl.php");
?>