<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new payroll();
    $payroll_date = '';
    $clearing_business_cutoff_date = '';
    $direct_business_cutoff_date = '';
    
    if(isset($_POST['upload_payroll'])&& $_POST['upload_payroll']=='Upload Payroll'){
        
        $clearing_business_cutoff_date = isset($_POST['clearing_business_cutoff_date'])?$instance->re_db_input($_POST['clearing_business_cutoff_date']):'';
        $direct_business_cutoff_date = isset($_POST['direct_business_cutoff_date'])?$instance->re_db_input($_POST['direct_business_cutoff_date']):'';
        
        $return = $instance->upload_payroll($_POST);
        
        if($return===true){
            
            header("location:".SITE_URL."calculate_payrolls.php?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_POST['reverse_payroll'])&& $_POST['reverse_payroll']=='Reverse Payroll'){
        
        $return = $instance->reverse_payroll();
        
        if($return===true){
            
            header("location:".CURRENT_PAGE."?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($action)&& $action=='payroll_close'){
        
        $return = $instance->payroll_close();
        
        if($return===true){
            
            header("location:".CURRENT_PAGE."?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    
    $content = "upload_payroll";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>