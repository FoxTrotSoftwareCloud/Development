<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $payroll_date = date('m/d/Y');
    $instance = new payroll();
    // Dropdown created 11/05/21 li
    $get_payroll_uploads = $instance->get_payroll_uploads(0,1);
    if(count($get_payroll_uploads)>0)
    {
        $payroll_date = $get_payroll_uploads[0]['payroll_date'];
        $is_payroll_calculated = 1;
    }
    else
    {
        $is_payroll_calculated = 0;
    }
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){
        $return = $instance->get_payroll_uploads($_POST['payroll_id']);
        $payroll_date = $return['payroll_date'];

        $return = $instance->calculate_payroll($_POST,$payroll_date);
        
        if($return===true){
           // header("location:".SITE_URL."review_payroll.php?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    
    $content = "calculate_payrolls";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>