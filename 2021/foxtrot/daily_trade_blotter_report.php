<?php
// print_r($_GET);
require_once("include/config.php");
require_once(DIR_FS . "islogin.php");

$instance_company = new manage_company();
$get_company = $instance_company->select_company();

$instance_branch = new branch_maintenance();
$branch_list = $instance_branch->select();

$instance_broker = new broker_master();
$get_brokers = $instance_broker->select_broker();

$instance_manager = new user_master();
$manager_list = $instance_manager->select_managers();
// echo "<pre>"; print_r($manager_list);die;

$is_branch_manager = $instance_branch->check_if_branch_manager($_SESSION['user_id']);

$company = '';
$broker = '';
$branch = '';
$output = '';
$report_for = '';
$return_from_broker_client = array();

if (isset($_POST['submit']) && $_POST['submit'] == 'Proceed') {
    $data_array = json_encode($_POST);
    $output = isset($_POST['output']) ? $instance->re_db_input($_POST['output']) : 0;
    $company = isset($_POST['company']) ? $instance->re_db_input($_POST['company']) : 0;
    $broker = isset($_POST['broker']) ? $instance->re_db_input($_POST['broker']) : 0;
    $branch = isset($_POST['branch']) ? $instance->re_db_input($_POST['branch']) : 0;
    $report_for = isset($_POST['report_for']) ? $instance->re_db_input($_POST['report_for']) : '';


    if ($output == 1) {
        header('location:' . CURRENT_PAGE . '?filter=' . $data_array);
        exit;
    } else if ($output == 2) {
        header("location:" . SITE_URL . "report_daily_trade_blotter_pdf.php?open=output_print&filter=" . $data_array);
        exit;
    } else if ($output == 3) {

        header("location:" . SITE_URL . "report_daily_trade_blotter_excel.php?filter=" . $data_array);
        exit;
    } else if ($output == 4) {

        header("location:" . SITE_URL . "report_daily_trade_blotter_pdf.php?filter=" . $data_array);
        exit;
    }
}
if (isset($_GET['filter']) && $_GET['filter'] != '') {
    $filter_array =  json_decode($_GET['filter'], true);
    $output = isset($filter_array['output']) ? $instance->re_db_input($filter_array['output']) : '';
    $company = isset($filter_array['company']) ? $instance->re_db_input($filter_array['company']) : '';
    $broker = isset($filter_array['broker']) ? $instance->re_db_input($filter_array['broker']) : '';
    $branch = isset($filter_array['branch']) ? $instance->re_db_input($filter_array['branch']) : '';
    $report_for = isset($filter_array['report_for']) ? $instance->re_db_input($filter_array['report_for']) : '';
    $beginning_date=isset($filter_array['beginning_date'])?$instance->re_db_input($filter_array['beginning_date']):'';
    $ending_date=isset($filter_array['ending_date'])?$instance->re_db_input($filter_array['ending_date']):'';
}

$content = "daily_trade_blotter_report";
require_once(DIR_WS_TEMPLATES . "main_page.tpl.php");
