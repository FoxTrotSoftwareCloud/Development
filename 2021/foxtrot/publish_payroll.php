<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    $output = 0;
    $instance = new payroll();
    $payroll_id = $id;
    if ($payroll_id > 0){
        $_SESSION['publish_payroll']['payroll_id'] = $payroll_id;
    }
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){
        $data_array = json_encode($_POST);
        $publish_report = isset($_POST['publish_report'])?$instance->re_db_input($_POST['publish_report']):0;
        $output = isset($_POST['output'])?$instance->re_db_input($_POST['output']):0;
        // 11/11/21 Not sure if this is the best way to default the prior report selections, but i am pressed for time!   
        $_SESSION['publish_payroll']=$_POST;

        if(isset($publish_report) && $publish_report == 1)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array); 
                exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_payroll_broker_statement.php?open=output_print&filter=".$data_array);
                exit;
            }
            else if($output == 3)
            {
                header("location:".SITE_URL."report_payroll_broker_statement_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4)
            {
                header("location:".SITE_URL."report_payroll_broker_statement.php?filter=".$data_array);exit;
            }
        }
        else if(isset($publish_report) && $publish_report == 2)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_payroll_company_statement.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3)
            {
                header("location:".SITE_URL."report_payroll_company_statement_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4)
            {
                header("location:".SITE_URL."report_payroll_company_statement.php?filter=".$data_array);exit;
            }
        }
        else if(isset($publish_report) && $publish_report == 3)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_payroll_adjustment.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3)
            {
                header("location:".SITE_URL."report_payroll_adjustment_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4)
            {
                header("location:".SITE_URL."report_payroll_adjustment.php?filter=".$data_array);exit;
            }
        }
        else if(isset($publish_report) && $publish_report == 4)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_payroll_reconciliation.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3)
            {
                header("location:".SITE_URL."report_payroll_reconciliation_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4)
            {
                header("location:".SITE_URL."report_payroll_reconciliation.php?filter=".$data_array);exit;
            }
        }
    }
    if(isset($_GET['filter']) && $_GET['filter'] != '')
    {
        $filter_array =  json_decode($_GET['filter'],true);
        $output = isset($filter_array['output'])?$instance->re_db_input($filter_array['output']):'';
        $product_category = isset($filter_array['product_category'])?$instance->re_db_input($filter_array['product_category']):'';
        $company = isset($filter_array['company'])?$instance->re_db_input($filter_array['company']):'';
        $batch = isset($filter_array['batch'])?$instance->re_db_input($filter_array['batch']):'';
        $beginning_date = isset($filter_array['beginning_date'])?$instance->re_db_input($filter_array['beginning_date']):'';
        $ending_date = isset($filter_array['ending_date'])?$instance->re_db_input($filter_array['ending_date']):'';
        $sort_by = isset($filter_array['sort_by'])?$instance->re_db_input($filter_array['sort_by']):'';
        $report_for = isset($filter_array['report_for'])?$instance->re_db_input($filter_array['report_for']):'';
        $payroll_id = isset($filter_array['payroll_id'])?$instance->re_db_input($filter_array['payroll_id']):'';
    }
    $content = "publish_payroll";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>