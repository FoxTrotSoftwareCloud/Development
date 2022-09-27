<?php
// print_r($_GET);

require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new transaction();
$get_sponsors = $instance->select_sponsor();
$get_client= $instance->select_client();
$branch_instance = new branch_maintenance();
$get_branch = $branch_instance->select();
$get_brokers = $instance->select_broker();
$product_instance = new product_maintenance();
$products= $product_instance->product_list_by_name();
$get_batches = $instance->select_batch();
$product_category = $instance->select_category();
$state = '';
$broker = '';
$sponser = '';
$output = '';
$report_for = '';
$date_by=1;
$filter_by=1;
$is_trail=1;
$return_from_broker_client = array();
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){
        $data_array = json_encode($_POST);
        $output = isset($_POST['output'])?$instance->re_db_input($_POST['output']):0;
        $state = isset($_POST['state'])?$instance->re_db_input($_POST['state']):0;
        $broker = isset($_POST['broker'])?$instance->re_db_input($_POST['broker']):0;
        $sponser = isset($_POST['sponser'])?$instance->re_db_input($_POST['sponser']):'';
        $report_for = isset($_POST['report_for'])?$instance->re_db_input($_POST['report_for']):'';
        $batch = isset($_POST['batch'])?$instance->re_db_input($_POST['batch']):'';
        $batch_cate= isset($_POST['batch_cate'])?$instance->re_db_input($_POST['batch_cate']):'';
        $product_cate= isset($_POST['product_cate'])?$instance->re_db_input($_POST['product_cate']):'';
        $date_by= isset($_POST['date_by'])?$instance->re_db_input($_POST['date_by']):1;
        $filter_by= isset($_POST['filter_by'])?$instance->re_db_input($_POST['filter_by']):1;
        $is_trail= isset($_POST['is_trail'])?$instance->re_db_input($_POST['is_trail']):0;
        
        
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {  
                if($report_for == 'broker'){
                  header("location:".SITE_URL."transaction_broker_report_print.php?open=output_print&filter=".$data_array);exit;
                }
                else{

                 header("location:".SITE_URL."transaction_report_print.php?open=output_print&filter=".$data_array);exit;
                }
            }
            else if($output == 3){
                 if($report_for == 'broker'){
                     header("location:".SITE_URL."transaction_broker_report_excel.php?filter=".$data_array);exit;
                 }
                 else{
                     header("location:".SITE_URL."transaction_report_excel.php?filter=".$data_array);exit;
                 }
               
            }
            else if($output == 4){
                 if($report_for == 'broker'){
                  header("location:".SITE_URL."transaction_broker_report_print.php?filter=".$data_array);exit;
                 }
                 else{

                        header("location:".SITE_URL."transaction_report_print.php?filter=".$data_array);exit;
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
        $beginning_date=isset($filter_array['beginning_date'])?$instance->re_db_input($filter_array['beginning_date']):'';
        $ending_date=isset($filter_array['ending_date'])?$instance->re_db_input($filter_array['ending_date']):'';
        $client = isset($filter_array['client'])?$filter_array['client']:0;
        $product = isset($filter_array['product'])?$filter_array['product']:0;
        $batch = isset($filter_array['batch'])?$instance->re_db_input($filter_array['batch']):0;
        $rep_no = isset($filter_array['rep_no'])?$instance->re_db_input($filter_array['rep_no']):'';
        $batch_cate= isset($filter_array['batch_cate'])?$instance->re_db_input($filter_array['batch_cate']):'';
        $product_cate= isset($filter_array['product_cate'])?$instance->re_db_input($filter_array['product_cate']):'';
        $date_by= isset($filter_array['date_by'])?$instance->re_db_input($filter_array['date_by']):1;
        $filter_by= isset($filter_array['filter_by'])?$instance->re_db_input($filter_array['filter_by']):1;
        $is_trail= isset($filter_array['is_trail'])?$instance->re_db_input($filter_array['is_trail']):0;
        

    }

   
    
$content = "transactionreports";
require_once(DIR_WS_TEMPLATES."main_page.tpl.php");