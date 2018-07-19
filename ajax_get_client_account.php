<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new transaction();  
if(isset($_GET['client_id']) && $_GET['client_id'] > 0)
{
    $client_id = isset($_GET['client_id'])?$instance->re_db_input($_GET['client_id']):'';
    $get_client_account = $instance->select_client_account_no($client_id);
    echo $get_client_account;
}
if(isset($_GET['batch_id']) && $_GET['batch_id'] > 0)
{
    $batch_id = isset($_GET['batch_id'])?$instance->re_db_input($_GET['batch_id']):'';
    $get_batch_date = $instance->select_batch_date($batch_id);
    echo date('m/d/Y',strtotime($get_batch_date));
}
    
?>
