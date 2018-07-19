<?php 
	require_once("include/config.php");
	require_once(DIR_FS."islogin.php");
	
    $error = '';
    $fname = '';
    $lname = '';
    $email = '';
    $uname = '';
    $password = '';
    $user_image = array();
    $menu = array();
    $menu_rights = array();
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new user_master();
    $menu11 = $instance->menu_select();
    
    if(isset($_POST['submit'])&&$_POST['submit']=='Save'){
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $fname = isset($_POST['fname'])?$instance->re_db_input($_POST['fname']):'';
        $lname = isset($_POST['lname'])?$instance->re_db_input($_POST['lname']):'';
        $email = isset($_POST['email'])?$instance->re_db_input($_POST['email']):'';
        $uname = isset($_POST['uname'])?$instance->re_db_input($_POST['uname']):'';
        $password = isset($_POST['password'])?$instance->re_db_input($_POST['password']):'';
        $user_image = isset($_POST['file_image'])?$_POST['file_image']:array();
        $menu_rights = isset($_POST['check_sub'])?$_POST['check_sub']:array();
        
        $return = $instance->insert_update($_POST);
        
        if($return===true){
            header("location:".CURRENT_PAGE);exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
   
    else if($action=='edit' && $id>0){
        $return = $instance->edit($id);
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $fname = isset($return['first_name'])?$instance->re_db_output($return['first_name']):'';
        $lname = isset($return['last_name'])?$instance->re_db_output($return['last_name']):'';
        $email = isset($return['email'])?$instance->re_db_output($return['email']):'';
        $uname = isset($return['user_name'])?$instance->re_db_output($return['user_name']):'';
        $password = isset($return['password'])?$instance->re_db_input($return['password']):'';
        $user_image = isset($return['image'])?$instance->re_db_output($return['image']):'';
        $menu_link_id = $instance->edit_menu_rights($id);
        foreach($menu_link_id as $key=>$data)
        {
            $menu_id[] = $data['link_id'];
             
        }
        $menu_rights = isset($menu_id)?$menu_id:array();
    }
    else if(isset($_GET['action'])&&$_GET['action']=='status'&&isset($_GET['id'])&&$_GET['id']>0&&isset($_GET['status'])&&($_GET['status']==0 || $_GET['status']==1))
    {
        $id = $instance->re_db_input($_GET['id']);
        $status = $instance->re_db_input($_GET['status']);
        $return = $instance->status($id,$status);
        if($return==true){
            header('location:'.CURRENT_PAGE);exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }  
    else if(isset($_GET['action'])&&$_GET['action']=='delete'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete($id);
        if($return==true){
            header('location:'.CURRENT_PAGE);exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view'){
        
        $return = $instance->select();//echo '<pre>';print_r($return);exit;
        
    }
    
	$content = "user_profile";
	require_once(DIR_WS_TEMPLATES."main_page.tpl.php");
?>