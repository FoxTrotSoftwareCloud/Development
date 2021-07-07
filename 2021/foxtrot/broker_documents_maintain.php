<?php

    require_once("include/config.php");
	require_once(DIR_FS."islogin.php");
 
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new broker_document();
   
    if(isset($_POST['submit'])&& $_POST['submit']=='Save'){ 
        //echo '<pre>';print_r($_POST);exit();
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $desc = isset($_POST['desc'])?$instance->re_db_input($_POST['desc']):'';
        $required = isset($_POST['required'])?$instance->re_db_input($_POST['required']):'';
        
        $return = $instance->insert_update($_POST);
        
        if($return===true){
            header("location:".CURRENT_PAGE."?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
 
    }
    else if($action=='edit' && $id>0){
        $return = $instance->edit($id);
        $id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $desc = isset($return['desc'])?$instance->re_db_output($return['desc']):'';
        $required = isset($return['required'])?$instance->re_db_output($return['required']):'';
    }
    else if(isset($_GET['action'])&&$_GET['action']=='status'&&isset($_GET['id'])&&$_GET['id']>0&&isset($_GET['status'])&&($_GET['status']==0 || $_GET['status']==1))
    {
        $id = $instance->re_db_input($_GET['id']);
        $status = $instance->re_db_input($_GET['status']);
        $return = $instance->status($id,$status);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?action=view');exit;
        }
    }
    else if($action=='view'){
        
        $return = $instance->select();
        
    }
    $content = "broker_documents_maintain";
	require_once(DIR_WS_TEMPLATES."main_page.tpl.php");

?>