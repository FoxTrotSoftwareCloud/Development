<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$client_sponsor_instance = new manage_sponsor();
$get_sponsors = $client_sponsor_instance->select_sponsor();
$instance_broker = new broker_master();
$get_brokers=$instance_broker->select_broker();
$client_account_list_instance=new client_account_list();
list($client_list,$total_rec)=$client_account_list_instance->get_client_account_list();
// print_r($client_list);die;
// echo $total_records['total_records'];
if (isset($_GET['page_no']) && $_GET['page_no']!="") {
    $page_no = (int)$_GET['page_no'];
} 
else {
    $page_no = 1;
}
$total_records_per_page = 3;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$total_records = $total_rec['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$content = "client_account_lists";
require_once(DIR_WS_TEMPLATES."main_page.tpl.php");
?>