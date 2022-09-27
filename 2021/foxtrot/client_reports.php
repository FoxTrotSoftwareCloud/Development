<?php
// print_r($_GET);
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$client_maintenance_instance = new client_maintenance();
$get_states = $client_maintenance_instance->select_state();
$instance_broker = new broker_master();
$get_brokers=$instance_broker->select_broker();
$client_sponsor_instance = new manage_sponsor();
$get_sponsors = $client_sponsor_instance->select_sponsor();
$state = '';
$broker = '';
$sponser = '';
$output = '';
$report_for = '';
$return_from_broker_client = array();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){
        $data_array = json_encode($_POST);
        $output = isset($_POST['output'])?$instance->re_db_input($_POST['output']):0;
        $state = isset($_POST['state'])?$instance->re_db_input($_POST['state']):0;
        $broker = isset($_POST['broker'])?$instance->re_db_input($_POST['broker']):0;
        $sponser = isset($_POST['sponser'])?$instance->re_db_input($_POST['sponser']):'';
        $report_for = isset($_POST['report_for'])?$instance->re_db_input($_POST['report_for']):'';
        
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
                header("location:".SITE_URL."report_client_pdf.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."report_client_review_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){
                
                header("location:".SITE_URL."report_client_pdf.php?filter=".$data_array);exit;
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
                header("location:".SITE_URL."report_batch.php?open=output_print&filter=".$data_array);exit;
            }
            else if($output == 3){
                
                header("location:".SITE_URL."report_batch_excel.php?filter=".$data_array);exit;
            }
            else if($output == 4){
                
                header("location:".SITE_URL."report_client_review_pdf.php?filter=".$data_array);exit;
            }
        }
    }
    if(isset($_GET['filter']) && $_GET['filter'] != '')
    {
        $filter_array =  json_decode($_GET['filter'],true);
        $output = isset($filter_array['output'])?$instance->re_db_input($filter_array['output']):'';
        $state = isset($filter_array['state'])?$instance->re_db_input($filter_array['state']):'';
        $broker = isset($filter_array['broker'])?$instance->re_db_input($filter_array['broker']):'';
        $sponser = isset($filter_array['sponser'])?$instance->re_db_input($filter_array['sponser']):'';
        $report_for = isset($filter_array['report_for'])?$instance->re_db_input($filter_array['report_for']):'';
    }
    
$content = "clientreport";
require_once(DIR_WS_TEMPLATES."main_page.tpl.php");