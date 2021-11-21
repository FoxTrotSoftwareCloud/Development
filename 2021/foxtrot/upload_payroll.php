<?php
    /*
    *   11/17/21 [Modified] Payroll Date validation - Allow user to upload to same Payroll Date/ID multiple times.
    */
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    $payroll_id = $id;
    if ($action == 'view') {
        unset($_SESSION['upload_payroll']);
    }
    
    $instance = new payroll();
    $payroll_date = '';
    $clearing_business_cutoff_date = '';
    $direct_business_cutoff_date = '';
    $payroll_transactions_array = $instance->select_payroll_transactions();


    if((isset($_POST['upload_payroll']) AND $_POST['upload_payroll']=='Upload Payroll') OR (isset($_POST['duplicate_payroll_proceed']) AND $_POST['duplicate_payroll_proceed']=="true")) {
        $payroll_date = isset($_POST['payroll_date'])?$instance->re_db_input($_POST['payroll_date']):'';
        $clearing_business_cutoff_date = isset($_POST['clearing_business_cutoff_date'])?$instance->re_db_input($_POST['clearing_business_cutoff_date']):'';
        $direct_business_cutoff_date = isset($_POST['direct_business_cutoff_date'])?$instance->re_db_input($_POST['direct_business_cutoff_date']):'';
                
        $return = $instance->upload_payroll($_POST);
        
        if($return===true){
            header("location:".SITE_URL."calculate_payrolls.php?action=view");
            exit;
        }
        else {
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_POST['reverse_payroll']) && $_POST['reverse_payroll']=='Reverse Payroll'){
        
        $return = $instance->reverse_payroll($_POST);
        
        if($return===true){
            header("location:".CURRENT_PAGE."?action=view");exit;
        } else {
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='payroll_close' && (isset($_POST['close_payroll']) && $_POST['close_payroll']=='Close Payroll')){
        
        $return = $instance->payroll_close($_POST['payroll_id']);
        
        if($return===true){
            unset($_POST['close_payroll']);
            header("location:".CURRENT_PAGE."?action=payroll_close");exit;
        } else {
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    } 
    
    $content = "upload_payroll";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>