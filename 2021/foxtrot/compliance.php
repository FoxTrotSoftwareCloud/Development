<?php
// print_r($_GET);
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$client_maintenance_instance = new client_maintenance();
$get_states = $client_maintenance_instance->select_state();
$instance_broker = new broker_master();
$get_brokers=$instance_broker->select_broker();
$instance_client = new client_maintenance();
$get_clients=$instance_client->select();
$client_sponsor_instance = new manage_sponsor();
$get_sponsors = $client_sponsor_instance->select_sponsor();
$branch_instance = new branch_maintenance();
$get_branches= $branch_instance->select();
$state = '';
$broker = '';
$sponser = '';
$client = '';
$branch = '';
$output = '';
$report_for = '';
$do_not_contact = '';
$allias_groupby = '';
$cip_client = '';
$ending_date = $beginning_date = '';
$return_from_broker_client = array();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){
        $data_array = json_encode($_POST);
        $output = isset($_POST['output'])?$instance->re_db_input($_POST['output']):0;
        $state = isset($_POST['state'])?$instance->re_db_input($_POST['state']):0;
        $broker = isset($_POST['broker'])?$instance->re_db_input($_POST['broker']):0;
        $branch = isset($_POST['branch'])?$instance->re_db_input($_POST['branch']):0;
        $sponser = isset($_POST['sponser'])?$instance->re_db_input($_POST['sponser']):'';
        $report_for = isset($_POST['report_for'])?$instance->re_db_input($_POST['report_for']):'';
        $dont_contact_client = isset($_POST['dont_contact_client'])?$instance->re_db_input($_POST['dont_contact_client']):0;
        $beginning_date = isset($_POST['beginning_date'])?$instance->re_db_input($_POST['beginning_date']):'';
        $ending_date = isset($_POST['ending_date'])?$instance->re_db_input($_POST['ending_date']):'';
        if($report_for == 1)
        {//print_r($report_for);exit;
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_client_ac_pdf.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."report_client_review_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){
                
                header("location:".SITE_URL."report_client_ac_pdf.php?filter=".$data_array);exit;
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
                header("location:".SITE_URL."report_client_review_pdf.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."report_batch_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){
                
                header("location:".SITE_URL."report_client_review_pdf.php?filter=".$data_array);exit;
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
                header("location:".SITE_URL."report_client_review_pdf.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."report_client_review_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){
                
                header("location:".SITE_URL."report_client_review_pdf.php?filter=".$data_array);exit;
            }
        }
        else if($report_for == 5)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_broker_state_license_pdf.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."report_broker_state_license_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){
                
                header("location:".SITE_URL."report_broker_state_license_pdf.php?filter=".$data_array);exit;
            }
        }
        else if($report_for == 7)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_complience_exception_pdf.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."report_complience_exception_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){
                
                header("location:".SITE_URL."report_complience_exception_pdf.php?filter=".$data_array);exit;
            }
        }
        else if($report_for == 9)
        {
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_broker_sponsor_appointment_pdf.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."report_broker_sponsor_appointment_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){
                
                header("location:".SITE_URL."report_broker_sponsor_appointment_pdf.php?filter=".$data_array);exit;
            }
        }
        else if($report_for == 10)
        {
            if($output == 1)
            {
                // header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
                header("location:".SITE_URL."ajax_client_cip_report.php?filter=".$data_array);exit;
            }
            else if($output == 2)
            {
                header("location:".SITE_URL."report_client_cip_pdf.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."report_client_cip_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){
                
                header("location:".SITE_URL."report_client_cip_pdf.php?filter=".$data_array);exit;
            }
        }
    }
    if(isset($_GET['filter']) && $_GET['filter'] != '')
    {
        $filter_array =  json_decode($_GET['filter'],true);
        $output = isset($filter_array['output'])?$instance->re_db_input($filter_array['output']):'';
        $state = isset($filter_array['state'])?$instance->re_db_input($filter_array['state']):'';
        $broker = isset($filter_array['broker'])?$instance->re_db_input($filter_array['broker']):'';
        $branch = isset($filter_array['branch'])?$instance->re_db_input($filter_array['branch']):'';
        $sponser = isset($filter_array['sponsor'])?$instance->re_db_input($filter_array['sponsor']):'';
        $report_for = isset($filter_array['report_for'])?$instance->re_db_input($filter_array['report_for']):'';
        $dont_contact_client = isset($filter_array['dont_contact_client'])?$instance->re_db_input($filter_array['dont_contact_client']):0;
        $beginning_date = isset($filter_array['beginning_date'])?$instance->re_db_input($filter_array['beginning_date']):'';
        $ending_date = isset($filter_array['ending_date'])?$instance->re_db_input($filter_array['ending_date']):'';
        $allias_groupby = isset($filter_array['allias_groupby'])?$instance->re_db_input($filter_array['allias_groupby']):'';
        $cip_client = isset($filter_array['cip_client'])?$instance->re_db_input($filter_array['cip_client']):'';
        $client = isset($filter_array['client'])?$instance->re_db_input($filter_array['client']):'';
    }
    
$content = "compliance";
require_once(DIR_WS_TEMPLATES."main_page.tpl.php");