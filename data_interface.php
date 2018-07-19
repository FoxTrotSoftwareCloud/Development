<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
	
    $error = '';
    $return = array();
    $uname = '';
    $password = '';
    $trade_activity = 0;
    $add_client = 0;
    $update_client = 0;
    $local_folder = '';
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    $dim_id = isset($_GET['dim'])&&$_GET['dim']!=''?$dbins->re_db_input($_GET['dim']):1;
    $instance = new date_interfaces_master();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $uname = isset($_POST['uname'])?$instance->re_db_input($_POST['uname']):'';
    	$password = isset($_POST['password'])?$instance->re_db_input($_POST['password']):'';
    	$trade_activity = isset($_POST['trade_activity'])?$instance->re_db_input($_POST['trade_activity']):0;
    	$add_client = isset($_POST['add_client'])?$instance->re_db_input($_POST['add_client']):0;
    	$update_client = isset($_POST['update_client'])?$instance->re_db_input($_POST['update_client']):0;
    	$local_folder = isset($_POST['local_folder'])?$instance->re_db_input($_POST['local_folder']):'';//echo '<pre>';print_r($_POST);exit;
    	$return = $instance->insert_update($_POST);
        
        if($return===true){
            header('location:'.CURRENT_PAGE);exit;
            
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='view' && $dim_id>0){
        $user_id = $_SESSION['user_id'];
        $return = $instance->edit($dim_id,$user_id);
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $uname = isset($return['user_name'])?$instance->re_db_output($return['user_name']):'';
    	$password = isset($return['password'])?$instance->re_db_output($return['password']):'';
    	$trade_activity = isset($return['exclude_non_comm_trade_activity'])?$instance->re_db_output($return['exclude_non_comm_trade_activity']):0;
    	$add_client = isset($return['add_client'])?$instance->re_db_output($return['add_client']):0;
    	$update_client = isset($return['update_client'])?$instance->re_db_output($return['update_client']):0;
    	$local_folder = isset($return['local_folder'])?$instance->re_db_output($return['local_folder']):'';//echo '<pre>';print_r($_POST);exit;
        
    }
    
    
	$content = "data_interface";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>