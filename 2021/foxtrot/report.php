<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");

    $instance = new batches();
    $instance_product_type = new product_master();
    $get_product_category =$instance_product_type->select_product_type();
    $get_batches =$instance->select();
    $instance_multi_company = new manage_company();
    $get_multi_company = $instance_multi_company->select_company();

    $product_category = '';
    $company = '';
    $batch = '';
    $beginning_date = '';
    $ending_date = '';
    $sort_by = '';
    $output = '';
    $report_for = '';

    $return_from_broker_client = array();


    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){
        $_POST['sort_by'] =  isset($_POST['payable_sort_by']) ? $_POST['payable_sort_by'] :  $_POST['sort_by'];
        // $_POST['output'] =  isset($_POST['payable_output']) ? $_POST['payable_output'] :  $_POST['output'];
        $_POST['output'] =  $_POST['report_for'] == 4 ? $_POST['payable_output'] :  $_POST['output'];
        $_POST['company'] =  isset($_POST['payable_company']) ? $_POST['payable_company'] :  $_POST['company'];

        $data_array = json_encode($_POST);
        $output = isset($_POST['output'])?$instance->re_db_input($_POST['output']):0;
        $product_category = isset($_POST['product_category'])?$instance->re_db_input($_POST['product_category']):0;
        $company = isset($_POST['company'])?$instance->re_db_input($_POST['company']):0;
        $batch = isset($_POST['batch'])?$instance->re_db_input($_POST['batch']):0;
        $beginning_date = isset($_POST['beginning_date'])?$instance->re_db_input($_POST['beginning_date']):'';
        $ending_date = isset($_POST['ending_date'])?$instance->re_db_input($_POST['ending_date']):'';
        $sort_by = isset($_POST['sort_by'])?$instance->re_db_input($_POST['sort_by']):0;
        $report_for = isset($_POST['report_for'])?$instance->re_db_input($_POST['report_for']):'';
        $cuttoff_date = isset($_POST['cuttoff_date'])?$instance->re_db_input($_POST['cuttoff_date']):'';
        $payable_type = isset($_POST['payable_type'])?$instance->re_db_input($_POST['payable_type']):'';

        if($report_for == 1)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_transaction_by_batch.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){

                header("location:".SITE_URL."report_transaction_by_postinglog_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){

                header("location:".SITE_URL."report_transaction_by_batch.php?filter=".$data_array);exit;
            }
        }
        else if($report_for == 2)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_batch.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){

                header("location:".SITE_URL."report_batch_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){

                header("location:".SITE_URL."report_batch.php?filter=".$data_array);exit;
            }
        }
        else if($report_for == 3)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_transaction_by_hold.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){

                header("location:".SITE_URL."report_transaction_by_hold_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){

                header("location:".SITE_URL."report_transaction_by_hold.php?filter=".$data_array);exit;
            }
        }
        else if($report_for == 4)
        {
           if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_transaction_by_payable.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){

                header("location:".SITE_URL."report_transaction_by_payable_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){

                header("location:".SITE_URL."report_transaction_by_payable.php?filter=".$data_array);exit;
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
        $payable_type = isset($filter_array['payable_type'])?$instance->re_db_input($filter_array['payable_type']):'';
        $cuttoff_date = isset($filter_array['cuttoff_date'])?$instance->re_db_input($filter_array['cuttoff_date']):'';
    }

    $content = "report";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>