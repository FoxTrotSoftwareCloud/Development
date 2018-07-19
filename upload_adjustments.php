<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new payroll();
    $payroll_date = '';
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){
        
        $upload_type = isset($_POST['upload_type'])?$instance->re_db_input($_POST['upload_type']):'';
        $upload_adjustments = isset($_POST['upload_adjustments'])?$_POST['upload_adjustments']:array();
        
        $return = $instance->upload_adjustments($_POST);
        
        if($return===true){
            
            header("location:".CURRENT_PAGE."?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    
    $content = "upload_adjustments";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>