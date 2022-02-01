<?php
// print_r($_GET);
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new transaction();
$get_sponsors = $instance->select_sponsor();
$get_client= $instance->select_client();
$branch_instance = new branch_maintenance();
$instance_multi_company = new manage_company();
$get_multi_company = $instance_multi_company->select_company();
$get_branch = $branch_instance->select();
$get_brokers = $instance->select_broker();
$product_instance = new product_maintenance();
$products= $product_instance->product_list_by_name();
$get_batches = $instance->select_batch();
$product_category = $instance->select_category();
$prod_cat=array();
$state = '';
$broker = '';
$sponser = '';
$output = '';
$report_for = '';
 $sort_by=1;
 $date_by=1;
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
        $sort_by= isset($_POST['sort_by'])?$instance->re_db_input($_POST['sort_by']):1;
        $date_by= isset($_POST['date_by'])?$instance->re_db_input($_POST['date_by']):1;
        $prod_cat = isset($_POST['prod_cat'])?$_POST['prod_cat']:array();
            if($output == 1)
            {
                header('location:'.CURRENT_PAGE.'?filter='.$data_array);exit;
            }
            else if($output == 2)
            {  
                if($report_for == 'broker'){
                  header("location:".SITE_URL."sales_broker_report_print.php?open=output_print&filter=".$data_array);exit;
                }
                else{

                 header("location:".SITE_URL."sales_report_print.php?open=output_print&filter=".$data_array);exit;
                }
            }
            else if($output == 3){
                 if($report_for == 'broker'){
                     header("location:".SITE_URL."sales_broker_report_excel.php?filter=".$data_array);exit;
                 }
                 else{
                     header("location:".SITE_URL."sales_report_excel.php?filter=".$data_array);exit;
                 }
               
            }
            else if($output == 4){
                 if($report_for == 'broker'){
                  header("location:".SITE_URL."sales_broker_report_print.php?filter=".$data_array);exit;
                 }
                 else{

                        header("location:".SITE_URL."sales_report_print.php?filter=".$data_array);exit;
                 }
            }
        
       
    }
    if(isset($_GET['filter']) && $_GET['filter'] != '')
    {
        $filter_array =  json_decode($_GET['filter'],true);

        $output = isset($filter_array['output'])?$instance->re_db_input($filter_array['output']):'';
        $state = isset($filter_array['state'])?$instance->re_db_input($filter_array['state']):'';
        $broker = isset($filter_array['broker'])?$instance->re_db_input($filter_array['broker']):'';
        $company = isset($filter_array['company'])?$instance->re_db_input($filter_array['company']):'';
        $sponser = isset($filter_array['sponsor'])?$instance->re_db_input($filter_array['sponsor']):'';
        $report_for = isset($filter_array['report_for'])?$instance->re_db_input($filter_array['report_for']):'';
        $beginning_date=isset($filter_array['beginning_date'])?$instance->re_db_input($filter_array['beginning_date']):'';
        $ending_date=isset($filter_array['ending_date'])?$instance->re_db_input($filter_array['ending_date']):'';
        $client = isset($filter_array['client'])?$filter_array['client']:0;
        $product = isset($filter_array['product'])?$filter_array['product']:0;
        $batch = isset($filter_array['batch'])?$instance->re_db_input($filter_array['batch']):0;
        $rep_no = isset($filter_array['rep_no'])?$instance->re_db_input($filter_array['rep_no']):'';
        $batch_cate= isset($filter_array['batch_cate'])?$instance->re_db_input($filter_array['batch_cate']):'';
        $sort_by= isset($filter_array['sort_by'])?$instance->re_db_input($filter_array['sort_by']):1;
        $date_by= isset($filter_array['date_by'])?$instance->re_db_input($filter_array['date_by']):1;
    }

   
    
$content = "sales_reports";
require_once(DIR_WS_TEMPLATES."main_page.tpl.php");