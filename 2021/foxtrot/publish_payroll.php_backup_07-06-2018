<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new payroll();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){
        $publish_report = isset($_POST['publish_report'])?$instance->re_db_input($_POST['publish_report']):0;
        
        if(isset($publish_report) && $publish_report == 1)
        {
            header("location:".SITE_URL."report_payroll_broker_statement.php");exit;
        }
        else if(isset($publish_report) && $publish_report == 2)
        {
            header("location:".SITE_URL."report_payroll_company_statement.php");exit;
        }
        else if(isset($publish_report) && $publish_report == 3)
        {
            header("location:".SITE_URL."report_payroll_adjustment.php");exit;
        }
        else if(isset($publish_report) && $publish_report == 4)
        {
            header("location:".SITE_URL."report_payroll_reconciliation.php");exit;
        }
    }
    
    $content = "publish_payroll";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>